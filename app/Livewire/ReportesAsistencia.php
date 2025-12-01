<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;
use App\Models\Commission;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\AcademicState;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesAsistencia extends Component
{
    use WithPagination;

    public $subject_id;
    public $commission_id;
    public $dateFrom;
    public $dateTo;
    public $search = '';

    protected $queryString = ['subject_id', 'commission_id', 'dateFrom', 'dateTo'];

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSubjectId()
    {
        $this->commission_id = null;
        $this->resetPage();
    }

    public function getSubjectsProperty()
    {
        return Subject::orderBy('nombre')->get();
    }

    public function getCommissionsProperty()
    {
        if (!$this->subject_id)
            return [];
        return Commission::where('subject_id', $this->subject_id)->get();
    }

    public function getAttendancesProperty()
    {
        if (!$this->commission_id)
            return [];

        return Attendance::with('student')
            ->where('commission_id', $this->commission_id)
            ->whereBetween('fecha', [$this->dateFrom, $this->dateTo])
            ->whereHas('student', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%')
                    ->orWhere('legajo', 'like', '%' . $this->search . '%');
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('student_id')
            ->paginate(20);
    }

    public function updateState($attendanceId, $newState)
    {
        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $attendance->update(['estado' => $newState]);
            $this->dispatch('saved');
        }
    }

    public function closeCursada()
    {
        if (!$this->commission_id)
            return;

        if (!confirm('¿Está seguro de cerrar la cursada? Esto regularizará a los alumnos con >= 75% de asistencia.')) {
            return;
        }

        $commission = Commission::find($this->commission_id);
        $totalClasses = Attendance::where('commission_id', $this->commission_id)
            ->distinct('fecha')
            ->count('fecha');

        if ($totalClasses == 0) {
            session()->flash('error', 'No hay clases registradas para calcular porcentajes.');
            return;
        }

        $students = $commission->students;

        DB::transaction(function () use ($students, $totalClasses, $commission) {
            foreach ($students as $student) {
                $presentes = Attendance::where('commission_id', $this->commission_id)
                    ->where('student_id', $student->id)
                    ->whereIn('estado', ['P', 'AJ'])
                    ->count();

                $percentage = ($presentes / $totalClasses) * 100;

                if ($percentage >= 75) {
                    AcademicState::updateOrCreate(
                        ['student_id' => $student->id, 'subject_id' => $this->subject_id],
                        [
                            'commission_id' => $this->commission_id,
                            'estado' => 'Regular',
                            'anio_regularizacion' => Carbon::now()->year
                        ]
                    );
                } else {
                    // Remove "Cursando" state
                    AcademicState::where('student_id', $student->id)
                        ->where('subject_id', $this->subject_id)
                        ->where('estado', 'Cursando')
                        ->delete();

                    // Detach from commission so they return to 'Eligibles' list
                    $commission->students()->detach($student->id);
                }
            }
        });

        session()->flash('success', 'Cursada cerrada correctamente. Alumnos regularizados según asistencia.');
    }

    public function export()
    {
        if (!$this->commission_id)
            return;

        $attendances = Attendance::with('student')
            ->where('commission_id', $this->commission_id)
            ->whereBetween('fecha', [$this->dateFrom, $this->dateTo])
            ->orderBy('fecha', 'desc')
            ->orderBy('student_id')
            ->get();

        $csv = "Fecha,Legajo,Alumno,Estado\n";

        foreach ($attendances as $attendance) {
            $estado = match ($attendance->estado) {
                'P' => 'Presente',
                'A' => 'Ausente',
                'AJ' => 'Ausente Justificado',
                default => $attendance->estado
            };

            $csv .= "{$attendance->fecha},{$attendance->student->legajo},\"{$attendance->student->apellido}, {$attendance->student->nombre}\",{$estado}\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'asistencias-' . date('Y-m-d') . '.csv');
    }

    public function render()
    {
        return view('livewire.reportes-asistencia', [
            'subjects' => $this->getSubjectsProperty(),
            'commissions' => $this->getCommissionsProperty(),
            'attendances' => $this->getAttendancesProperty(),
        ])->layout('layouts.app-interno', ['header' => 'Reportes de Asistencia']);
    }
}
