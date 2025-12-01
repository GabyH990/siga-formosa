<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_id',
        'student_id',
        'fecha',
        'estado',
    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
