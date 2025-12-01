<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Career;
use App\Models\AcademicState;
use App\Models\Subject;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    /**
     * Devuelve la carrera activa (TUP o LPB) según:
     *  - sesión (superadmin que eligió carrera)
     *  - career_id del usuario (bedel normal)
     */
    private function getActiveCareerId(): ?int
    {
        $user = auth()->user();

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

        $students = $query->orderBy('apellido')->paginate(15);

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
            'legajo'           => 'required|unique:students',
            'nombre'           => 'required',
            'apellido'         => 'required',
            'dni'              => 'required|unique:students',
            'fecha_nacimiento' => 'nullable|date',
            'correo'           => 'nullable|email',
            'telefono'         => 'nullable',
            'direccion'        => 'nullable',
            'cohorte'          => 'required|integer',
            'career_id'        => 'required|exists:careers,id',
        ]);

        Student::create($validated);

        return redirect()->route('alumnos.index')->with('success', 'Alumno creado correctamente.');
    }

    public function edit($id)
    {
        $student   = Student::findOrFail($id);
        $careerId  = $this->getActiveCareerId();

        $careers = $careerId
            ? Career::where('id', $careerId)->get()
            : Career::all();

        return view('alumnos.edit', compact('student', 'careers'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'legajo'           => 'required|unique:students,legajo,' . $student->id,
            'nombre'           => 'required',
            'apellido'         => 'required',
            'dni'              => 'required|unique:students,dni,' . $student->id,
            'fecha_nacimiento' => 'nullable|date',
            'correo'           => 'nullable|email',
            'telefono'         => 'nullable',
            'direccion'        => 'nullable',
            'cohorte'          => 'required|integer',
            'career_id'        => 'required|exists:careers,id',
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

        $cursando  = $estados->where('estado', 'Cursando');
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
            'student'         => $student,
            'subjects'        => $subjects,
            'commissionNames' => $commissionNames,
            'estados'         => $estados,
        ]);
    }

    /**
     * Guarda / actualiza un estado académico de ese alumno.
     */
    public function guardarEstado(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'subject_id'          => ['required', 'exists:subjects,id'],
            'commission_nombre'   => ['nullable', 'in:1.1,1.2,1.3,2.1,2.2,2.3'],
            'estado'              => ['required', 'in:Cursando,Regular,Aprobada'],
            'anio_regularizacion' => ['nullable', 'integer'],
            'tipo_aprobacion'     => ['nullable', 'in:directa,final'],
            'libro'               => ['nullable', 'string', 'max:50'],
            'acta'                => ['nullable', 'string', 'max:50'],
            'tomo'                => ['nullable', 'string', 'max:50'],
            'nota_final'          => ['nullable', 'numeric'],
            'observaciones'       => ['nullable', 'string'],
        ]);

        // Resolver / crear la comisión para esa materia
        $commissionId = null;

        if (!empty($validated['commission_nombre'])) {
            $commissionNombre = $validated['commission_nombre'];

            // Primera parte (1.x o 2.x) para inferir periodo
            $periodo = str_starts_with($commissionNombre, '1.')
                ? '1C'
                : '2C';

            $commission = Commission::firstOrCreate(
                [
                    'subject_id' => $validated['subject_id'],
                    'nombre'     => $commissionNombre,
                ],
                [
                    'anio'    => $validated['anio_regularizacion'] ?? now()->year,
                    'periodo' => $periodo,
                ]
            );

            $commissionId = $commission->id;

            // Si está Cursando, lo enganchamos a esa comisión (para Armar Cursada)
            if ($validated['estado'] === 'Cursando') {
                $commission->students()->syncWithoutDetaching([
                    $student->id => ['activo' => true],
                ]);
            }
        }

        // Guardar / actualizar estado académico
        AcademicState::updateOrCreate(
            [
                'student_id'    => $student->id,
                'subject_id'    => $validated['subject_id'],
                'commission_id' => $commissionId,
            ],
            [
                'estado'              => $validated['estado'],
                'anio_regularizacion' => $validated['anio_regularizacion'] ?? null,
                'tipo_aprobacion'     => $validated['tipo_aprobacion'] ?? null,
                'libro'               => $validated['libro'] ?? null,
                'acta'                => $validated['acta'] ?? null,
                'tomo'                => $validated['tomo'] ?? null,
                'nota_final'          => $validated['nota_final'] ?? null,
                'observaciones'       => $validated['observaciones'] ?? null,
            ]
        );

        return redirect()
            ->route('alumnos.estado', $student->id)
            ->with('success', 'Estado académico actualizado correctamente.');
    }
}
