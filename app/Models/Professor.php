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

    /**
     * Casts
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: profesor ↔ comisiones
     */
    public function commissions()
    {
        return $this->belongsToMany(Commission::class, 'professor_commission')
            ->withTimestamps();
    }

    /**
     * Relación: profesor ↔ cátedras (materias)
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'professor_subject')
            ->withTimestamps();
    }

    /**
     * Scope opcional para traer solo activos
     * (NO rompe nada si no lo usás en otros lados).
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
