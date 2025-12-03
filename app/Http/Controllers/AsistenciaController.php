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
use App\Models\Attendance;

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

                $careerIdMateria = $subject->career_id;

                // -------- Cursantes --------
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

                // -------- Elegibles --------
                $elegiblesQuery = Student::where('career_id', $careerIdMateria)
                    // NO tener la materia en Cursando / Regular / Aprobada
                    ->whereDoesntHave('academicStates', function ($q) use ($subjectId) {
                        $q->where('subject_id', $subjectId)
                          ->whereIn('estado', ['Cursando', 'Regular', 'Aprobada']);
                    })
                    // NO estar en ninguna comisión de esa materia
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
     * POST: guardar alta/baja de cursantes (botón general Guardar)
     */
    public function armarCursadaGuardar(Request $request)
    {
        $data = $request->validate([
            'subject_id'        => ['required', 'exists:subjects,id'],
            'commission_nombre' => ['required', 'in:1.1,1.2,1.3,2.1,2.2,2.3'],
            'professor_id'      => ['nullable', 'exists:professors,id'],
            'add_students'      => ['array'],
            'add_students.*'    => ['integer', 'exists:students,id'],
            'remove_students'   => ['array'],
            'remove_students.*' => ['integer', 'exists:students,id'],
        ]);

        $subjectId        = (int) $data['subject_id'];
        $commissionNombre = $data['commission_nombre'];
        $professorId      = $data['professor_id'] ?? null;
        $addStudents      = $data['add_students'] ?? [];
        $removeStudents   = $data['remove_students'] ?? [];

        DB::transaction(function () use ($subjectId, $commissionNombre, $professorId, $addStudents, $removeStudents) {
            $periodo = str_starts_with($commissionNombre, '1.') ? '1C' : '2C';

            // Comisión destino (Cátedra + Comisión)
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

            // Enganchar profesor
            if ($professorId) {
                $prof = Professor::find($professorId);
                if ($prof) {
                    $prof->commissions()->syncWithoutDetaching([$commission->id]);
                }
            }

            // Quitar seleccionados de cursantes
            if (!empty($removeStudents)) {
                $commission->students()->detach($removeStudents);

                AcademicState::whereIn('student_id', $removeStudents)
                    ->where('subject_id', $subjectId)
                    ->where('commission_id', $commission->id)
                    ->where('estado', 'Cursando')
                    ->delete();
            }

            // Agregar seleccionados desde elegibles
            foreach ($addStudents as $sid) {
                // Traslado desde otras comisiones de esa materia
                $otrasComisionesIds = Commission::where('subject_id', $subjectId)
                    ->where('id', '!=', $commission->id)
                    ->pluck('id');

                if ($otrasComisionesIds->isNotEmpty()) {
                    DB::table('commission_student')
                        ->whereIn('commission_id', $otrasComisionesIds)
                        ->where('student_id', $sid)
                        ->delete();
                }

                // Agregar a comisión destino
                $commission->students()->syncWithoutDetaching([
                    $sid => ['activo' => true],
                ]);

                // Estado académico
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

        return back()->with('success', 'Cursada guardada correctamente.');
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
     *  REGISTROS (tomar asistencia)
     * ========================================================= */

    public function registros(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        // Cátedras de la carrera activa
        $subjects = Subject::query()
            ->when($careerId, fn ($q) => $q->where('career_id', $careerId))
            ->orderBy('nombre')
            ->get();

        $subjectId    = $request->query('subject_id');
        $commissionId = $request->query('commission_id');
        $fecha        = $request->query('fecha');

        $commissions = collect();
        $students    = collect();
        $attendances = collect();

        if ($subjectId) {
            $commissions = Commission::where('subject_id', $subjectId)
                ->orderBy('nombre')
                ->get();
        }

        if ($subjectId && $commissionId && $fecha) {
            $commission = Commission::with(['students' => function ($q) {
                $q->orderBy('apellido')
                  ->orderBy('nombre');
            }])->find($commissionId);

            if ($commission) {
                $students = $commission->students;

                $attendances = Attendance::where('commission_id', $commissionId)
                    ->whereDate('fecha', $fecha)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('asistencias.registros', [
            'subjects'     => $subjects,
            'subjectId'    => $subjectId,
            'commissions'  => $commissions,
            'commissionId' => $commissionId,
            'fecha'        => $fecha,
            'students'     => $students,
            'attendances'  => $attendances,
        ]);
    }

    public function registrosGuardar(Request $request)
    {
        $data = $request->validate([
            'subject_id'    => ['required', 'exists:subjects,id'],
            'commission_id' => ['required', 'exists:commissions,id'],
            'fecha'         => ['required', 'date'],
            'estados'       => ['required', 'array'],
            'estados.*'     => ['required', 'in:P,A,AJ'],
        ]);

        $subjectId    = (int) $data['subject_id'];
        $commissionId = (int) $data['commission_id'];
        $fecha        = $data['fecha'];
        $estados      = $data['estados'];

        DB::transaction(function () use ($commissionId, $fecha, $estados) {
            foreach ($estados as $studentId => $estado) {
                Attendance::updateOrCreate(
                    [
                        'student_id'    => $studentId,
                        'commission_id' => $commissionId,
                        'fecha'         => $fecha,
                    ],
                    [
                        'estado' => $estado,
                    ]
                );
            }
        });

        return redirect()
            ->route('asistencias.registros', [
                'subject_id'    => $subjectId,
                'commission_id' => $commissionId,
                'fecha'         => $fecha,
            ])
            ->with('success', 'Asistencia guardada correctamente.');
    }

    /* =========================================================
     *  REPORTES
     * ========================================================= */

    /**
     * Vista principal de reportes:
     * - Seleccionar cátedra
     * - Mostrar comisiones y fechas con planillas de asistencia
     */
    public function reportes(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        // Cátedras de la carrera activa
        $subjects = Subject::query()
            ->when($careerId, fn ($q) => $q->where('career_id', $careerId))
            ->orderBy('nombre')
            ->get();

        $subjectId = $request->query('subject_id');

        $fechasPorComision = collect();
        $commissions       = collect();

        if ($subjectId) {
            // Buscar todas las (comisión, fecha) que tienen asistencia para esa cátedra
            $rows = Attendance::query()
                ->join('commissions as c', 'c.id', '=', 'attendances.commission_id')
                ->where('c.subject_id', $subjectId)
                ->select('attendances.commission_id', 'c.nombre as commission_nombre', 'attendances.fecha')
                ->groupBy('attendances.commission_id', 'c.nombre', 'attendances.fecha')
                ->orderBy('c.nombre')
                ->orderBy('attendances.fecha')
                ->get();

            $commissions = $rows->groupBy('commission_id');
        }

        return view('asistencias.reportes', [
            'subjects'          => $subjects,
            'subjectId'         => $subjectId,
            'commissionsGroups' => $commissions, // colección agrupada por commission_id
        ]);
    }

    /**
     * Detalle de una planilla (cátedra+comisión+fecha) para editar estados
     */
    public function reportesDetalle(Request $request)
    {
        $data = $request->validate([
            'commission_id' => ['required', 'exists:commissions,id'],
            'fecha'         => ['required', 'date'],
        ]);

        $commissionId = (int) $data['commission_id'];
        $fecha        = $data['fecha'];

        $commission = Commission::with('subject')->findOrFail($commissionId);

        // Traer asistencias + alumno
        $attendances = Attendance::where('commission_id', $commissionId)
            ->whereDate('fecha', $fecha)
            ->with('student')
            ->orderBy(Student::select('apellido')->whereColumn('students.id', 'attendances.student_id'))
            ->get();

        return view('asistencias.reportes-detalle', [
            'commission'  => $commission,
            'fecha'       => $fecha,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Guardar cambios de estados desde Reportes
     */
    public function reportesDetalleGuardar(Request $request)
    {
        $data = $request->validate([
            'commission_id' => ['required', 'exists:commissions,id'],
            'fecha'         => ['required', 'date'],
            'estados'       => ['required', 'array'],
            'estados.*'     => ['required', 'in:P,A,AJ'],
        ]);

        $commissionId = (int) $data['commission_id'];
        $fecha        = $data['fecha'];
        $estados      = $data['estados'];

        DB::transaction(function () use ($commissionId, $fecha, $estados) {
            foreach ($estados as $studentId => $estado) {
                Attendance::updateOrCreate(
                    [
                        'student_id'    => $studentId,
                        'commission_id' => $commissionId,
                        'fecha'         => $fecha,
                    ],
                    [
                        'estado' => $estado,
                    ]
                );
            }
        });

        return redirect()
            ->route('asistencias.reportes.detalle', [
                'commission_id' => $commissionId,
                'fecha'         => $fecha,
            ])
            ->with('success', 'Asistencia actualizada correctamente.');
    }
public function reportesPorcentajes(Request $request)
  {
    // subject_id y commission_id vienen desde Reportes
    $data = $request->validate([
        'subject_id'    => ['required', 'exists:subjects,id'],
        'commission_id' => ['required', 'exists:commissions,id'],
        'desde'         => ['nullable', 'date'],
        'hasta'         => ['nullable', 'date'],
    ]);

    $subjectId    = (int) $data['subject_id'];
    $commissionId = (int) $data['commission_id'];
    $desde        = $data['desde'] ?? null;
    $hasta        = $data['hasta'] ?? null;

    $subject    = Subject::findOrFail($subjectId);
    $commission = Commission::with('students')->findOrFail($commissionId);

    // Traemos todas las asistencias de esa comisión (y rango de fechas opcional)
    $attQuery = Attendance::where('commission_id', $commissionId);

    if ($desde) {
        $attQuery->whereDate('fecha', '>=', $desde);
    }

    if ($hasta) {
        $attQuery->whereDate('fecha', '<=', $hasta);
    }

    $attendances = $attQuery->get();

    // Cantidad de clases (fechas distintas)
    $totalClases = $attendances->pluck('fecha')->unique()->count();

    $resumen = [];

    foreach ($commission->students as $student) {
        $delAlumno = $attendances->where('student_id', $student->id);

        // Clases asistidas = P o AJ
        $asistidas = $delAlumno->whereIn('estado', ['P', 'AJ'])->count();

        $porcentaje = $totalClases > 0
            ? round(($asistidas / $totalClases) * 100, 2)
            : 0;

        // Incumplidor si P+AJ / total <= 75%
        $incumple = $totalClases > 0 && $porcentaje <= 75;

        $resumen[] = [
            'student'     => $student,
            'asistidas'   => $asistidas,
            'totalClases' => $totalClases,
            'porcentaje'  => $porcentaje,
            'incumple'    => $incumple,
        ];
    }

    $incumplidores = collect($resumen)
        ->filter(fn ($r) => $r['incumple'])
        ->values();

    return view('asistencias.reportes-porcentajes', [
        'subject'        => $subject,
        'commission'     => $commission,
        'subjectId'      => $subjectId,
        'commissionId'   => $commissionId,
        'desde'          => $desde,
        'hasta'          => $hasta,
        'totalClases'    => $totalClases,
        'resumen'        => $resumen,
        'incumplidores'  => $incumplidores,
    ]);
   }

    /**
     * Exportar planilla a Excel (CSV) respetando filtro de estado
     */
    public function reportesExportarExcel(Request $request)
    {
        $data = $request->validate([
            'commission_id' => ['required', 'exists:commissions,id'],
            'fecha'         => ['required', 'date'],
            'estado'        => ['nullable', 'in:P,A,AJ'],
        ]);

        $commissionId = (int) $data['commission_id'];
        $fecha        = $data['fecha'];
        $estado       = $data['estado'] ?? null;

        $commission = Commission::with('subject')->findOrFail($commissionId);

        $query = Attendance::where('commission_id', $commissionId)
            ->whereDate('fecha', $fecha)
            ->with('student');

        if ($estado) {
            $query->where('estado', $estado);
        }

        $rows = $query->get();

        $estadoSlug = $estado ? $estado : 'todos';
        $fileName   = sprintf(
            'asistencia_%s_%s_%s.csv',
            $commission->nombre,
            $fecha,
            $estadoSlug
        );

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');

            // Encabezados
            fputcsv($handle, ['Legajo', 'Apellido', 'Nombre', 'Estado']);

            foreach ($rows as $att) {
                $student = $att->student;
                fputcsv($handle, [
                    $student->legajo,
                    $student->apellido,
                    $student->nombre,
                    $att->estado,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
