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

    public function index()
    {
        return view('profesores.index');
    }

    public function listado(Request $request)
    {
        $careerId = $this->getActiveCareerId();

        $query = Professor::with('commissions.subject');

        // Solo profesores que tengan al menos una comisión
        // de una materia de la carrera activa
        if ($careerId) {
            $query->whereHas('commissions.subject', function ($q) use ($careerId) {
                $q->where('career_id', $careerId);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('legajo', 'like', "%{$search}%")
                    ->orWhere('correo', 'like', "%{$search}%");
            });
        }

        $professors = $query->orderBy('apellido')->paginate(15);

        return view('profesores.listado', compact('professors'));
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
            'legajo'   => 'required|unique:professors',
            'nombre'   => 'required',
            'apellido' => 'required',
            'correo'   => 'nullable|email',
            'telefono' => 'nullable',
            'titulo'   => 'nullable',

            // array de IDs de materias (cátedras)
            'subjects'   => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        DB::transaction(function () use ($validated) {
            // Datos básicos del profesor
            $professorData = collect($validated)->except('subjects')->toArray();
            /** @var \App\Models\Professor $professor */
            $professor = Professor::create($professorData);

            // Materias seleccionadas
            $subjectIds = $validated['subjects'] ?? [];

            if (!empty($subjectIds)) {
                // 1) Guardar qué cátedras dicta (pivot professor_subject)
                $professor->subjects()->sync($subjectIds);

                // 2) (opcional) vincularlo a TODAS las comisiones de esas cátedras
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
            'legajo'   => 'required|unique:professors,legajo,' . $professor->id,
            'nombre'   => 'required',
            'apellido' => 'required',
            'correo'   => 'nullable|email',
            'telefono' => 'nullable',
            'titulo'   => 'nullable',

            'subjects'   => ['nullable', 'array'],
            'subjects.*' => ['exists:subjects,id'],
        ]);

        DB::transaction(function () use ($professor, $validated) {
            // Datos del profesor
            $professorData = collect($validated)->except('subjects')->toArray();
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

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->delete();

        return redirect()
            ->route('profesores.listado')
            ->with('success', 'Profesor eliminado correctamente.');
    }
}
