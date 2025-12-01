<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'nombre',
        'anio',
        'periodo',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'commission_student')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function professors()
    {
        return $this->belongsToMany(Professor::class, 'professor_commission')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
