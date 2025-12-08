<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Career;
use App\Models\AcademicState;
use App\Models\Subject;
use App\Models\Commission;
use App\Models\Attendance;   // 👈 AGREGADO
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    /**
     * Devuelve la carrera activa (TUP o LPB) según:
     *  - sesión (superadmin que eligió carrera)
     *  - career_id del usuario (bedel normal)
     */
    private function getActiveCareerId(): ?int
    {
        $user = Auth::user();

        if (session()->has('active_career_id')) {
            return (int) session('active_career_id');
        }

        if ($user && $user->career_id) {
            return (int) $user->career_id;
        }

        return null;
    }

    public function index(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        $query = Student::query();

        // Solo alumnos de la carrera activa
        if ($careerId) {
            $query->where('career_id', $careerId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('legajo', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('apellido')
            ->paginate(15)
            ->appends($request->query());

        return view('alumnos.index', compact('students'));
    }

    public function create()
    {
        $careerId = $this->getActiveCareerId();

        // Solo la carrera activa (TUP o LPB)
        $careers = $careerId
            ? Career::where('id', $careerId)->get()
            : Career::all();

        return view('alumnos.create', compact('careers'));
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
    'legajo' => [
        'required',
        'integer',
        'min:1',
        'regex:/^[0-9]+$/',
        'unique:students,legajo',
    ],

    'nombre' => 'required|string',
    'apellido' => 'required|string',

    'dni' => [
        'required',
        'integer',
        'min:1',
        'regex:/^[0-9]+$/',
        'unique:students,dni',
    ],

    'fecha_nacimiento' => [
        'required',
        'date',
        'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
    ],

    'correo' => 'nullable|email|unique:students,correo',

    'telefono' => 'nullable|string',
    'direccion' => 'nullable|string',

    'cohorte' => 'required|integer',
    'career_id' => 'required|exists:careers,id',
]);


        Student::create($validated);

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $careerId = $this->getActiveCareerId();

        $careers = $careerId
            ? Career::where('id', $careerId)->get()
            : Career::all();

        return view('alumnos.edit', compact('student', 'careers'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

    $validated = $request->validate([
    'legajo' => [
        'required',
        'integer',
        'min:1',
        'regex:/^[0-9]+$/',
        'unique:students,legajo,' . $student->id,
    ],

    'nombre' => 'required|string',
    'apellido' => 'required|string',

    'dni' => [
        'required',
        'integer',
        'min:1',
        'regex:/^[0-9]+$/',
        'unique:students,dni,' . $student->id,
    ],

    'fecha_nacimiento' => [
        'required',
        'date',
        'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
    ],

    'correo' => 'nullable|email|unique:students,correo,' . $student->id,

    'telefono' => 'nullable|string',
    'direccion' => 'nullable|string',

    'cohorte' => 'required|integer',
    'career_id' => 'required|exists:careers,id',
]);

        $student->update($validated);

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado correctamente.');
    }

    public function estadoAcademico($id)
    {
        // Alumno con su carrera
        $student = Student::with('career')->findOrFail($id);

        // Estados académicos del alumno
        $estados = AcademicState::with(['subject', 'commission'])
            ->where('student_id', $id)
            ->orderBy('subject_id')
            ->get();

        $cursando = $estados->where('estado', 'Cursando');
        $regulares = $estados->where('estado', 'Regular');
        $aprobadas = $estados->where('estado', 'Aprobada');

        return view('alumnos.estado', compact('student', 'cursando', 'regulares', 'aprobadas'));
    }

    /**
     * Formulario para cargar / editar el estado académico de un alumno.
     */
    public function editarEstado($id)
    {
        $student = Student::with('career')->findOrFail($id);

        // Materias SOLO de la carrera del alumno
        $subjects = Subject::query()
            ->when($student->career_id, function ($q) use ($student) {
                $q->where('career_id', $student->career_id);
            })
            ->orderBy('nombre')
            ->get();

        // Opciones fijas de comisión (para ambas carreras)
        $commissionNames = ['1.1', '1.2', '1.3', '2.1', '2.2', '2.3'];

        // Estados ya cargados para mostrar abajo en la tabla
        $estados = AcademicState::with(['subject', 'commission'])
            ->where('student_id', $id)
            ->orderBy('subject_id')
            ->get();

        return view('alumnos.estado-editar', [
            'student' => $student,
            'subjects' => $subjects,
            'commissionNames' => $commissionNames,
            'estados' => $estados,
        ]);
    }

    /**
     * Guarda / actualiza un estado académico de ese alumno.
     */
    public function guardarEstado(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'commission_nombre' => ['nullable', 'in:1.1,1.2,1.3,2.1,2.2,2.3'],
            'estado' => ['required', 'in:Cursando,Regular,Aprobada'],
            'anio_regularizacion' => ['nullable', 'integer'],
            'tipo_aprobacion' => ['nullable', 'in:directa,final'],
            'libro' => ['nullable', 'string', 'max:50'],
            'acta' => ['nullable', 'string', 'max:50'],
            'tomo' => ['nullable', 'string', 'max:50'],
            'nota_final' => ['nullable', 'numeric'],
            'observaciones' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($student, $validated) {

            // --------------------------------------------------
            // 1) Resolver / crear comisión (si se indicó)
            // --------------------------------------------------
            $commissionId = null;

            if (!empty($validated['commission_nombre'])) {
                $commissionNombre = $validated['commission_nombre'];

                // Primera parte (1.x o 2.x) para inferir período
                $periodo = str_starts_with($commissionNombre, '1.')
                    ? '1C'
                    : '2C';

                $commission = Commission::firstOrCreate(
                    [
                        'subject_id' => $validated['subject_id'],
                        'nombre' => $commissionNombre,
                    ],
                    [
                        'anio' => $validated['anio_regularizacion'] ?? now()->year,
                        'periodo' => $periodo,
                    ]
                );

                $commissionId = $commission->id;

                // Si está Cursando, lo enganchamos a esa comisión (para Asistencias / Armar cursada)
                if ($validated['estado'] === 'Cursando') {
                    $commission->students()->syncWithoutDetaching([
                        $student->id => ['activo' => true],
                    ]);
                }
            }

            // --------------------------------------------------
            // 2) Buscar ÚNICO estado por (alumno, materia)
            //    y actualizarlo en lugar de crear filas nuevas
            // --------------------------------------------------
            $estadoExistente = AcademicState::where('student_id', $student->id)
                ->where('subject_id', $validated['subject_id'])
                ->first();

            if ($estadoExistente) {
                // Actualizo la misma fila (Cursando -> Regular -> Aprobada)
                $estadoExistente->update([
                    'commission_id' => $commissionId,
                    'estado' => $validated['estado'],
                    'anio_regularizacion' => $validated['anio_regularizacion'] ?? null,
                    'tipo_aprobacion' => $validated['tipo_aprobacion'] ?? null,
                    'libro' => $validated['libro'] ?? null,
                    'acta' => $validated['acta'] ?? null,
                    'tomo' => $validated['tomo'] ?? null,
                    'nota_final' => $validated['nota_final'] ?? null,
                    'observaciones' => $validated['observaciones'] ?? null,
                ]);

                $estadoId = $estadoExistente->id;
            } else {
                // Primera vez que se carga esa materia para este alumno
                $nuevo = AcademicState::create([
                    'student_id' => $student->id,
                    'subject_id' => $validated['subject_id'],
                    'commission_id' => $commissionId,
                    'estado' => $validated['estado'],
                    'anio_regularizacion' => $validated['anio_regularizacion'] ?? null,
                    'tipo_aprobacion' => $validated['tipo_aprobacion'] ?? null,
                    'libro' => $validated['libro'] ?? null,
                    'acta' => $validated['acta'] ?? null,
                    'tomo' => $validated['tomo'] ?? null,
                    'nota_final' => $validated['nota_final'] ?? null,
                    'observaciones' => $validated['observaciones'] ?? null,
                ]);

                $estadoId = $nuevo->id;
            }

            // --------------------------------------------------
            // 3) Limpieza de basura histórica:
            //    por si ya existían duplicados, dejamos SOLO uno
            // --------------------------------------------------
            AcademicState::where('student_id', $student->id)
                ->where('subject_id', $validated['subject_id'])
                ->where('id', '!=', $estadoId)
                ->delete();
        });

        return redirect()
            ->route('alumnos.estado', $student->id)
            ->with('success', 'Estado académico actualizado correctamente.');
    }

    /**
     * ELIMINAR alumno + datos relacionados
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        DB::transaction(function () use ($student) {

            // 1) Asistencias del alumno
            Attendance::where('student_id', $student->id)->delete();

            // 2) Estados académicos del alumno
            AcademicState::where('student_id', $student->id)->delete();

            // 3) Pivot commission_student (por si está en comisiones)
            DB::table('commission_student')
                ->where('student_id', $student->id)
                ->delete();

            // 4) Finalmente, el alumno
            $student->delete();
        });

        return redirect()
            ->route('alumnos.index')
            ->with('success', 'Alumno eliminado correctamente (incluyendo estados, comisiones y asistencias).');
    }
}
