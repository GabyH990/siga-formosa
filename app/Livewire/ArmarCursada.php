<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subject;
use App\Models\Commission;
use App\Models\Professor;
use App\Models\Student;
use App\Models\AcademicState;
use Illuminate\Support\Facades\Auth;

class ArmarCursada extends Component
{
    // Filtros / selección
    public $subjectId = '';
    public $commissionNombre = '';
    public $professorId = '';
    public $filter = '';

    // Checkboxes
    public array $selectedElegibles = [];
    public array $selectedCursantes = [];

    // Comisiones permitidas
    public array $commissionOptions = ['1.1', '1.2', '1.3', '2.1', '2.2', '2.3'];

    // Datos para la vista
    public $subjects = [];
    public $professors = [];
    public $elegibles = [];
    public $cursantes = [];

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

    public function mount(): void
    {
        $careerId = $this->getActiveCareerId();

        $this->subjects = Subject::query()
            ->when($careerId, fn ($q) => $q->where('career_id', $careerId))
            ->with('career')
            ->orderBy('nombre')
            ->get()
            ->all();

        $this->loadProfessors();
        $this->refreshLists();
    }

    public function render()
    {
        // usa: resources/views/livewire/armar-cursada.blade.php
        return view('livewire.armar-cursada');
    }

    /* ====================== REACCIONES ====================== */

    public function updatedSubjectId(): void
    {
        $this->commissionNombre   = '';
        $this->professorId        = '';
        $this->selectedElegibles  = [];
        $this->selectedCursantes  = [];

        $this->loadProfessors();
        $this->refreshLists();
    }

    public function updatedCommissionNombre(): void
    {
        $this->selectedElegibles  = [];
        $this->selectedCursantes  = [];

        $this->refreshLists();
    }

    public function updatedFilter(): void
    {
        $this->refreshLists();
    }

    /* ====================== PROFESORES ====================== */

    protected function loadProfessors(): void
    {
        $this->professors = [];

        // si no hay materia seleccionada, no mostramos nada
        if (!$this->subjectId) {
            return;
        }

        // 1) intentamos traer SOLO los profes que dictan esa materia
        $profes = Professor::whereHas('subjects', function ($q) {
                $q->where('subjects.id', $this->subjectId);
            })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        // 2) si no hay ninguno asociado todavía, mostramos TODOS como fallback
        if ($profes->isEmpty()) {
            $profes = Professor::orderBy('apellido')
                ->orderBy('nombre')
                ->get();
        }

        $this->professors = $profes;
    }

    /* ================== COMISION / LISTAS =================== */

    protected function resolveCommission(bool $createIfNotExists = false): ?Commission
    {
        if (!$this->subjectId || !$this->commissionNombre) {
            return null;
        }

        $commission = Commission::where('subject_id', $this->subjectId)
            ->where('nombre', $this->commissionNombre)
            ->first();

        if (!$commission && $createIfNotExists) {
            $periodo = str_starts_with($this->commissionNombre, '1.')
                ? '1C'
                : '2C';

            $commission = Commission::create([
                'subject_id' => $this->subjectId,
                'nombre'     => $this->commissionNombre,
                'anio'       => now()->year,
                'periodo'    => $periodo,
            ]);
        }

        return $commission;
    }

    protected function refreshLists(): void
    {
        $this->elegibles = [];
        $this->cursantes = [];

        if (!$this->subjectId || !$this->commissionNombre) {
            return;
        }

        $subject = Subject::with('career')->find($this->subjectId);
        if (!$subject || !$subject->career_id) {
            return;
        }

        $commission = $this->resolveCommission(true);
        if (!$commission) {
            return;
        }

        $careerId = $subject->career_id;

        // Cursantes
        $cursantesQuery = Student::where('career_id', $careerId)
            ->whereHas('academicStates', function ($q) use ($commission) {
                $q->where('subject_id', $this->subjectId)
                    ->where('commission_id', $commission->id)
                    ->where('estado', 'Cursando');
            });

        if ($this->filter !== '') {
            $f = $this->filter;
            $cursantesQuery->where(function ($q) use ($f) {
                $q->where('apellido', 'like', "%{$f}%")
                    ->orWhere('nombre', 'like', "%{$f}%")
                    ->orWhere('legajo', 'like', "%{$f}%");
            });
        }

        $this->cursantes = $cursantesQuery
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get()
            ->all();

        // Elegibles
        $query = Student::where('career_id', $careerId)
            ->whereDoesntHave('academicStates', function ($q) {
                $q->where('subject_id', $this->subjectId)
                    ->whereIn('estado', ['Regular', 'Aprobada', 'Cursando']);
            })
            ->whereDoesntHave('commissions', function ($q) {
                $q->where('subject_id', $this->subjectId);
            });

        if ($this->filter !== '') {
            $f = $this->filter;
            $query->where(function ($q) use ($f) {
                $q->where('apellido', 'like', "%{$f}%")
                    ->orWhere('nombre', 'like', "%{$f}%")
                    ->orWhere('legajo', 'like', "%{$f}%");
            });
        }

        $this->elegibles = $query
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get()
            ->all();
    }

    /* ==================== BOTONES ==================== */

    public function addSelected(): void
    {
        if (!$this->subjectId || !$this->commissionNombre || empty($this->selectedElegibles)) {
            return;
        }

        $commission = $this->resolveCommission(true);
        if (!$commission) {
            return;
        }

        $ids = $this->selectedElegibles;

        $attachData = [];
        foreach ($ids as $id) {
            $attachData[$id] = ['activo' => true];
        }
        $commission->students()->syncWithoutDetaching($attachData);

        foreach ($ids as $studentId) {
            AcademicState::updateOrCreate(
                [
                    'student_id'    => $studentId,
                    'subject_id'    => $this->subjectId,
                    'commission_id' => $commission->id,
                ],
                [
                    'estado' => 'Cursando',
                ]
            );
        }

        if ($this->professorId) {
            $prof = Professor::find($this->professorId);
            if ($prof) {
                $prof->commissions()->syncWithoutDetaching([$commission->id]);
            }
        }

        $this->selectedElegibles = [];
        $this->refreshLists();
    }

    public function removeSelected(): void
    {
        $commission = $this->resolveCommission(false);
        if (!$commission || empty($this->selectedCursantes)) {
            return;
        }

        $commission->students()->detach($this->selectedCursantes);

        $this->selectedCursantes = [];
        $this->refreshLists();
    }
}
