<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use App\Models\Subject;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfesorController extends Controller
{
    /**
     * Carrera activa (TUP / LPB).
     */
    private function getActiveCareerId(): ?int
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (session()->has('active_career_id')) {
            return (int) session('active_career_id');
        }

        if ($user && $user->career_id) {
            return (int) $user->career_id;
        }

        return null;
    }

    /**
     * Entrada al módulo Profesores:
     * redirige directamente al listado (como pasa con Alumnos).
     */
    public function index()
    {
        return redirect()->route('profesores.listado');
    }

    /**
     * Listado principal (similar a alumnos.index)
     */
    public function listado(Request $request)
    {
        $careerId     = $this->getActiveCareerId();
        $search       = $request->input('search');
        $verInactivos = $request->boolean('ver_inactivos');

        // Traemos profesores con sus relaciones
        $query = Professor::with(['commissions.subject', 'subjects.career']);

        // Si NO marcó "ver inactivos", solo activos
        if (! $verInactivos) {
            $query->where('activo', true);
        }

        // Solo profesores que tengan al menos una comisión
        // de una materia de la carrera activa
        if ($careerId) {
            $query->whereHas('commissions.subject', function ($q) use ($careerId) {
                $q->where('career_id', $careerId);
            });
        }

        // Filtro de búsqueda
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('legajo', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        $professors = $query
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(15)
            ->appends([
                'search'       => $search,
                'ver_inactivos'=> $verInactivos ? '1' : '0',
            ]);

        return view('profesores.listado', [
            'professors'   => $professors,
            'search'       => $search,
            'verInactivos' => $verInactivos,
        ]);
    }

    /**
     * Form nuevo profesor
     */
    public function create()
    {
        $careerId = $this->getActiveCareerId();

        // Solo materias de la carrera activa
        $subjects = Subject::with('career')
            ->when($careerId, function ($q) use ($careerId) {
                $q->where('career_id', $careerId);
            })
            ->orderBy('nombre')
            ->get();

        return view('profesores.create', compact('subjects'));
    }

    /**
     * Guarda nuevo profesor + sus cátedras
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'legajo'   => 'required|integer|min:1|regex:/^[0-9]+$/|unique:professors',
            'nombre'   => 'required',
            'apellido' => 'required',
             'correo' => 'nullable|email|unique:professors,correo',
            'telefono' => 'nullable',
            'titulo'   => 'nullable',

            // array de IDs de materias (cátedras)
            'subjects'   => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        DB::transaction(function () use ($validated) {
            // Datos básicos del profesor
            $professorData = collect($validated)
                ->except('subjects')
                ->toArray();

            // Siempre crear como ACTIVO
            $professorData['activo'] = true;

            /** @var \App\Models\Professor $professor */
            $professor = Professor::create($professorData);

            // Materias seleccionadas
            $subjectIds = $validated['subjects'] ?? [];

            if (!empty($subjectIds)) {
                // 1) Guardar qué cátedras dicta (pivot professor_subject)
                $professor->subjects()->sync($subjectIds);

                // 2) Vincularlo a TODAS las comisiones de esas cátedras
                $commissionIds = Commission::whereIn('subject_id', $subjectIds)
                    ->pluck('id')
                    ->toArray();

                $professor->commissions()->sync($commissionIds);
            }
        });

        return redirect()
            ->route('profesores.listado')
            ->with('success', 'Profesor creado correctamente.');
    }

    public function buscar(Request $request)
    {
        $professors = [];
        if ($request->filled('search')) {
            $search = $request->search;
            $professors = Professor::where('nombre', 'like', "%{$search}%")
                ->orWhere('apellido', 'like', "%{$search}%")
                ->orWhere('legajo', 'like', "%{$search}%")
                ->limit(10)
                ->get();
        }

        return view('profesores.buscar', compact('professors'));
    }

    /**
     * Form editar profesor
     */
    public function edit($id)
    {
        $careerId  = $this->getActiveCareerId();
        $professor = Professor::with('commissions', 'subjects')->findOrFail($id);

        // Solo materias de la carrera activa
        $subjects = Subject::with('career')
            ->when($careerId, function ($q) use ($careerId) {
                $q->where('career_id', $careerId);
            })
            ->orderBy('nombre')
            ->get();

        // materias que YA tiene (por pivot professor_subject)
        $selectedSubjects = $professor->subjects
            ->pluck('id')
            ->unique()
            ->toArray();

        return view('profesores.edit', compact('professor', 'subjects', 'selectedSubjects'));
    }

    /**
     * Actualiza datos del profesor + cátedras
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\Professor $professor */
        $professor = Professor::findOrFail($id);

        $validated = $request->validate([
            'legajo'   => 'required|integer|min:1|regex:/^[0-9]+$/|unique:professors,legajo,' . $professor->id,
            'nombre'   => 'required',
            'apellido' => 'required',
            'correo' => 'nullable|email|unique:professors,correo,' . $id,
            'telefono' => 'nullable',
            'titulo'   => 'nullable',

            'subjects'   => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        DB::transaction(function () use ($professor, $validated) {
            // Datos del profesor (no tocamos "activo" acá)
            $professorData = collect($validated)
                ->except('subjects')
                ->toArray();

            $professor->update($professorData);

            // Materias seleccionadas
            $subjectIds = $validated['subjects'] ?? [];

            if (!empty($subjectIds)) {
                // 1) Actualizar cátedras que dicta (professor_subject)
                $professor->subjects()->sync($subjectIds);

                // 2) Actualizar comisiones asociadas a esas cátedras (professor_commission)
                $commissionIds = Commission::whereIn('subject_id', $subjectIds)
                    ->pluck('id')
                    ->toArray();

                $professor->commissions()->sync($commissionIds);
            } else {
                // Si no eligió materias, limpiamos ambas relaciones
                $professor->subjects()->detach();
                $professor->commissions()->detach();
            }
        });

        return redirect()
            ->route('profesores.listado')
            ->with('success', 'Profesor actualizado correctamente.');
    }

    /**
     * "Eliminar": marcar como INACTIVO (no se borra de la BD).
     */
    public function destroy($id)
    {
        /** @var \App\Models\Professor $professor */
        $professor = Professor::findOrFail($id);

        $professor->update([
            'activo' => false,
        ]);

        // No tocamos relaciones, para mantener historial
        return redirect()
            ->route('profesores.listado')
            ->with('success', 'Profesor desactivado correctamente (se mantiene en el historial).');
    }

    /**
     * Activar de nuevo un profesor inactivo.
     */
    public function activar($id)
    {
        /** @var \App\Models\Professor $professor */
        $professor = Professor::findOrFail($id);

        $professor->update([
            'activo' => true,
        ]);

        return redirect()
            ->route('profesores.listado')
            ->with('success', 'Profesor activado nuevamente.');
    }
}
