<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicState extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'commission_id',
        'estado',
        'anio_regularizacion',
        'tipo_aprobacion',
        'libro',
        'acta',
        'tomo',
        'nota_final',
        'observaciones',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }
}
