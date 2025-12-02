<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Subject;
use App\Models\Commission;
use App\Models\Professor;
use App\Models\Student;
use App\Models\AcademicState;

class AsistenciaController extends Controller
{
    /**
     * Menú principal del módulo Asistencia
     */
    public function index()
    {
        return view('asistencias.index');
    }

    /* =========================================================
     *  Helper: carrera activa (TUP / LPB)
     * ========================================================= */
    private function getActiveCareerId(): ?int
    {
        if (session()->has('active_career_id')) {
            return (int) session('active_career_id');
        }

        $user = Auth::user();
        if ($user && $user->career_id) {
            return (int) $user->career_id;
        }

        return null;
    }

    /* =========================================================
     *  ARMAR CURSADA
     * ========================================================= */

    /**
     * GET: Página "Armar Cursada" (sin Livewire)
     */
    public function armarCursada(Request $request)
    {
        $careerId    = $this->getActiveCareerId();
        $subjectId   = $request->query('subject_id');
        $commission  = $request->query('commission_nombre');
        $professorId = $request->query('professor_id');
        $filter      = trim((string) $request->query('filter', ''));

        // Materias de la carrera activa
        $subjects = Subject::query()
            ->when($careerId, fn ($q) => $q->where('career_id', $careerId))
            ->with('career')
            ->orderBy('nombre')
            ->get();

        // Opciones de comisión
        $commissionOptions = ['1.1', '1.2', '1.3', '2.1', '2.2', '2.3'];

        // Profesores (por materia / carrera)
        $professors = $this->loadProfessors($careerId, $subjectId);

        $elegibles = collect();
        $cursantes = collect();

        if ($subjectId && $commission) {
            $subject = Subject::with('career')->find($subjectId);

            if ($subject && $subject->career_id) {
                // Comisión (reutiliza o crea)
                $commissionModel = Commission::firstOrCreate(
                    [
                        'subject_id' => $subjectId,
                        'nombre'     => $commission,
                    ],
                    [
                        'anio'    => now()->year,
                        'periodo' => str_starts_with($commission, '1.') ? '1C' : '2C',
                    ]
                );

                // Vincular profesor a la comisión
                if ($professorId) {
                    $prof = Professor::find($professorId);
                    if ($prof) {
                        $prof->commissions()->syncWithoutDetaching([$commissionModel->id]);
                    }
                }

                // Carrera de la materia (para alumnos)
                $careerIdMateria = $subject->career_id;

                /* -------- Cursantes -------- */
                $cursantesQuery = Student::where('career_id', $careerIdMateria)
                    ->whereHas('academicStates', function ($q) use ($subjectId, $commissionModel) {
                        $q->where('subject_id', $subjectId)
                          ->where('commission_id', $commissionModel->id)
                          ->where('estado', 'Cursando');
                    });

                if ($filter !== '') {
                    $cursantesQuery->where(function ($q) use ($filter) {
                        $q->where('apellido', 'like', "%{$filter}%")
                          ->orWhere('nombre', 'like', "%{$filter}%")
                          ->orWhere('legajo', 'like', "%{$filter}%");
                    });
                }

                $cursantes = $cursantesQuery
                    ->orderBy('apellido')
                    ->orderBy('nombre')
                    ->get();

                /* -------- Elegibles -------- */
                $elegiblesQuery = Student::where('career_id', $careerIdMateria)
                    // NO tener la materia en Cursando / Regular / Aprobada
                    ->whereDoesntHave('academicStates', function ($q) use ($subjectId) {
                        $q->where('subject_id', $subjectId)
                          ->whereIn('estado', ['Cursando', 'Regular', 'Aprobada']);
                    })
                    // NO estar ya en alguna comisión de la misma cátedra
                    ->whereDoesntHave('commissions', function ($q) use ($subjectId) {
                        $q->where('subject_id', $subjectId);
                    });

                if ($filter !== '') {
                    $elegiblesQuery->where(function ($q) use ($filter) {
                        $q->where('apellido', 'like', "%{$filter}%")
                          ->orWhere('nombre', 'like', "%{$filter}%")
                          ->orWhere('legajo', 'like', "%{$filter}%");
                    });
                }

                $elegibles = $elegiblesQuery
                    ->orderBy('apellido')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        return view('asistencias.armar-cursada', [
            'subjects'          => $subjects,
            'commissionOptions' => $commissionOptions,
            'professors'        => $professors,
            'elegibles'         => $elegibles,
            'cursantes'         => $cursantes,
            'subjectId'         => $subjectId,
            'commissionNombre'  => $commission,
            'professorId'       => $professorId,
            'filter'            => $filter,
        ]);
    }

    /**
     * POST: agregar elegibles como cursantes
     */
    public function armarCursadaAdd(Request $request)
    {
        $request->validate([
            'subject_id'        => ['required', 'exists:subjects,id'],
            'commission_nombre' => ['required', 'in:1.1,1.2,1.3,2.1,2.2,2.3'],
            'students'          => ['required', 'array'],
            'students.*'        => ['integer', 'exists:students,id'],
            'professor_id'      => ['nullable', 'exists:professors,id'],
        ]);

        $subjectId        = (int) $request->subject_id;
        $commissionNombre = $request->commission_nombre;
        $professorId      = $request->professor_id;
        $studentIds       = $request->students;

        DB::transaction(function () use ($subjectId, $commissionNombre, $professorId, $studentIds) {
            $periodo = str_starts_with($commissionNombre, '1.') ? '1C' : '2C';

            // 1) Comisión destino (Cátedra + Comisión)
            $commission = Commission::firstOrCreate(
                [
                    'subject_id' => $subjectId,
                    'nombre'     => $commissionNombre,
                ],
                [
                    'anio'    => now()->year,
                    'periodo' => $periodo,
                ]
            );

            // 2) Enganchar profesor a la comisión (si viene)
            if ($professorId) {
                $prof = Professor::find($professorId);
                if ($prof) {
                    $prof->commissions()->syncWithoutDetaching([$commission->id]);
                }
            }

            // 3) Regla por alumno
            foreach ($studentIds as $sid) {

                // 3.a) TRASLADO EN COMMISSION_STUDENT:
                //     sacar al alumno de otras comisiones de ESTA materia
                $otrasComisionesIds = Commission::where('subject_id', $subjectId)
                    ->where('id', '!=', $commission->id)
                    ->pluck('id');

                if ($otrasComisionesIds->isNotEmpty()) {
                    DB::table('commission_student')
                        ->whereIn('commission_id', $otrasComisionesIds)
                        ->where('student_id', $sid)
                        ->delete();
                }

                // 3.b) Agregarlo a la comisión destino (o mantenerlo si ya está)
                $commission->students()->syncWithoutDetaching([
                    $sid => ['activo' => true],
                ]);

                // 3.c) ESTADO ACADÉMICO
                $estado = AcademicState::where('student_id', $sid)
                    ->where('subject_id', $subjectId)
                    ->first();

                if ($estado) {
                    $estado->update([
                        'commission_id' => $commission->id,
                        'estado'        => 'Cursando',
                    ]);
                } else {
                    AcademicState::create([
                        'student_id'    => $sid,
                        'subject_id'    => $subjectId,
                        'commission_id' => $commission->id,
                        'estado'        => 'Cursando',
                    ]);
                }
            }
        });

        return back()->with('success', 'Alumnos agregados como cursantes (con traslado si correspondía).');
    }

    /**
     * POST: quitar cursantes
     */
    public function armarCursadaRemove(Request $request)
    {
        $data = $request->validate([
            'subject_id'          => 'required|exists:subjects,id',
            'commission_nombre'   => 'required',
            'selectedCursantes'   => 'required|array',
            'selectedCursantes.*' => 'exists:students,id',
        ]);

        $subjectId        = $data['subject_id'];
        $commissionNombre = $data['commission_nombre'];
        $ids              = $data['selectedCursantes'];

        $commission = Commission::where('subject_id', $subjectId)
            ->where('nombre', $commissionNombre)
            ->first();

        if ($commission) {
            $commission->students()->detach($ids);
        }

        return redirect()
            ->route('asistencias.armar-cursada', [
                'subject_id'        => $subjectId,
                'commission_nombre' => $commissionNombre,
                'professor_id'      => $request->input('professor_id'),
                'filter'            => $request->input('filter'),
            ])
            ->with('success', 'Alumnos quitados de la cursada.');
    }

    /**
     * Helper: profesores por materia / carrera
     */
    protected function loadProfessors(?int $careerId, ?string $subjectId)
    {
        // 1) Por materia (professor_subject)
        if ($subjectId) {
            $professorIds = DB::table('professor_subject')
                ->where('subject_id', (int) $subjectId)
                ->pluck('professor_id');

            if ($professorIds->count() > 0) {
                return Professor::whereIn('id', $professorIds)
                    ->orderBy('apellido')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        // 2) Por carrera
        if ($careerId) {
            $professorIds = DB::table('professor_subject')
                ->join('subjects', 'subjects.id', '=', 'professor_subject.subject_id')
                ->where('subjects.career_id', $careerId)
                ->pluck('professor_id')
                ->unique();

            if ($professorIds->count() > 0) {
                return Professor::whereIn('id', $professorIds)
                    ->orderBy('apellido')
                    ->orderBy('nombre')
                    ->get();
            }
        }

        return collect();
    }

    /* =========================================================
     *  Registros / Reportes
     * ========================================================= */

    /**
     * Página "Registros" (Tomar asistencia)
     */
    public function registros()
    {
        return view('asistencias.registros');
    }

    /**
     * Página "Reportes"
     */
    public function reportes()
    {
        return view('asistencias.reportes');
    }
}
