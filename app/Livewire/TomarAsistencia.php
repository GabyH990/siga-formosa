<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subject;
use App\Models\Commission;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TomarAsistencia extends Component
{
    public $subject_id;
    public $commission_id;
    public $fecha;

    public $attendanceData = []; // [student_id => 'P'|'A'|'AJ']

    protected $queryString = ['subject_id', 'commission_id', 'fecha'];

    public function mount()
    {
        $this->fecha = Carbon::today()->format('Y-m-d');
    }

    public function updatedSubjectId()
    {
        $this->commission_id = null;
        $this->loadStudents();
    }

    public function updatedCommissionId()
    {
        $this->loadStudents();
    }

    public function updatedFecha()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->attendanceData = [];

        if (!$this->commission_id)
            return;

        $students = Student::whereHas('commissions', function ($q) {
            $q->where('commissions.id', $this->commission_id)
                ->where('commission_student.activo', true);
        })
            ->orderBy('apellido')
            ->get();

        // Load existing attendances
        $existing = Attendance::where('commission_id', $this->commission_id)
            ->where('fecha', $this->fecha)
            ->get()
            ->keyBy('student_id');

        foreach ($students as $student) {
            if (isset($existing[$student->id])) {
                $this->attendanceData[$student->id] = $existing[$student->id]->estado;
            } else {
                $this->attendanceData[$student->id] = null;
            }
        }
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

    public function getStudentsProperty()
    {
        if (!$this->commission_id)
            return [];

        return Student::whereHas('commissions', function ($q) {
            $q->where('commissions.id', $this->commission_id)
                ->where('commission_student.activo', true);
        })
            ->orderBy('apellido')
            ->get();
    }

    public function save()
    {
        if (!$this->commission_id || !$this->fecha)
            return;

        DB::transaction(function () {
            foreach ($this->attendanceData as $studentId => $estado) {
                if ($estado) {
                    Attendance::updateOrCreate(
                        [
                            'commission_id' => $this->commission_id,
                            'student_id' => $studentId,
                            'fecha' => $this->fecha,
                        ],
                        ['estado' => $estado]
                    );
                }
            }
        });

        session()->flash('success', 'Asistencia guardada correctamente');
    }

    public function render()
    {
        return view('livewire.tomar-asistencia', [
            'subjects' => $this->getSubjectsProperty(),
            'commissions' => $this->getCommissionsProperty(),
            'students' => $this->getStudentsProperty(),
        ])->layout('layouts.app-interno', ['header' => 'Tomar Asistencia']);
    }
}
