<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'nombre',
        'codigo',
        'anio_plan',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function professors()
{
    return $this->belongsToMany(Professor::class, 'professor_subject')
        ->withTimestamps();
}

}
