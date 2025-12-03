<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Si la tabla se llama "attendances" no hace falta $table.
    // Si la llamaste distinto, descomenta y ajusta:
    // protected $table = 'attendances';

    protected $fillable = [
        'commission_id',
        'student_id',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /* ==========================
     *  RELACIONES
     * ========================== */

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
