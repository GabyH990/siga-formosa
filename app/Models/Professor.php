<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'legajo',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'titulo',
        'activo',
    ];

    public function commissions()
    {
        return $this->belongsToMany(Commission::class, 'professor_commission')
            ->withTimestamps();
    }

    public function subjects()
{
    return $this->belongsToMany(Subject::class, 'professor_subject')
        ->withTimestamps();
}

}
