<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'legajo',
        'nombre',
        'apellido',
        'dni',
        'fecha_nacimiento',
        'correo',
        'telefono',
        'direccion',
        'cohorte',
        'career_id',
        'activo',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function commissions()
    {
        return $this->belongsToMany(Commission::class, 'commission_student')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function academicStates()
    {
        return $this->hasMany(AcademicState::class);
    }
}
