<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EstadoAcademicoController extends Controller
{
    /**
     * Carrera activa (TUP / LPB).
     */
    private function getActiveCareerId(): ?int
{
    /** @var \App\Models\User|null $user */
    $user = Auth::user();  // 👈 en vez de auth()->user()

    if (session()->has('active_career_id')) {
        return (int) session('active_career_id');
    }

    if ($user && $user->career_id) {
        return (int) $user->career_id;
    }

    return null;
}


    /**
     * Estado Académico GENERAL:
     * lista de alumnos con resumen de Cursando / Regulares / Aprobadas / Observaciones.
     */
    public function index(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        // Base: alumnos
        $query = Student::query()
            ->with('career')
            ->leftJoinSub(
                AcademicState::select(
                    'student_id',
                    DB::raw("COUNT(CASE WHEN estado = 'Cursando' THEN 1 END)  as cant_cursando"),
                    DB::raw("COUNT(CASE WHEN estado = 'Regular'  THEN 1 END)  as cant_regulares"),
                    DB::raw("COUNT(CASE WHEN estado = 'Aprobada' THEN 1 END)  as cant_aprobadas"),
                    DB::raw("COUNT(CASE WHEN observaciones IS NOT NULL AND observaciones <> '' THEN 1 END)
                             as cant_observaciones")
                )->groupBy('student_id'),
                'estados',
                'estados.student_id',
                '=',
                'students.id'
            )
            ->select(
                'students.*',
                'estados.cant_cursando',
                'estados.cant_regulares',
                'estados.cant_aprobadas',
                'estados.cant_observaciones'
            );

        // Solo alumnos de la carrera activa
        if ($careerId) {
            $query->where('students.career_id', $careerId);
        }

        // Filtro por búsqueda (nombre, apellido, legajo, dni)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('legajo', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $students = $query
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(20)
            ->withQueryString();

        return view('estado-academico.index', compact('students'));
    }
    /**
     * Exportar Estado Académico a Excel (CSV)
     */
    public function exportarExcel(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        // Reutilizamos la misma lógica de filtrado que en index()
        $query = Student::query()
            ->with('career')
            ->leftJoinSub(
                AcademicState::select(
                    'student_id',
                    DB::raw("COUNT(CASE WHEN estado = 'Cursando' THEN 1 END)  as cant_cursando"),
                    DB::raw("COUNT(CASE WHEN estado = 'Regular'  THEN 1 END)  as cant_regulares"),
                    DB::raw("COUNT(CASE WHEN estado = 'Aprobada' THEN 1 END)  as cant_aprobadas"),
                    DB::raw("COUNT(CASE WHEN observaciones IS NOT NULL AND observaciones <> '' THEN 1 END)
                             as cant_observaciones")
                )->groupBy('student_id'),
                'estados',
                'estados.student_id',
                '=',
                'students.id'
            )
            ->select(
                'students.*',
                'estados.cant_cursando',
                'estados.cant_regulares',
                'estados.cant_aprobadas',
                'estados.cant_observaciones'
            );

        if ($careerId) {
            $query->where('students.career_id', $careerId);
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

        $students = $query
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $fileName = 'estado_academico_' . date('Y-m-d_H-i') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($students) {
            $handle = fopen('php://output', 'w');

            // BOM para Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($handle, [
                'Apellido',
                'Nombre',
                'Legajo',
                'DNI',
                'Carrera',
                'Cohorte',
                'Cursando',
                'Regulares',
                'Aprobadas',
                'Obs. Materias'
            ]);

            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->apellido,
                    $student->nombre,
                    $student->legajo,
                    $student->dni,
                    $student->career ? $student->career->codigo : '-',
                    $student->cohorte ?? '-',
                    $student->cant_cursando ?? 0,
                    $student->cant_regulares ?? 0,
                    $student->cant_aprobadas ?? 0,
                    $student->cant_observaciones ?? 0,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
