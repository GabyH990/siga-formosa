<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model)
    {
        $this->log($model, 'crear');
    }

    public function updated(Model $model)
    {
        $this->log($model, 'editar');
    }

    public function deleted(Model $model)
    {
        $this->log($model, 'eliminar');
    }

    protected function log(Model $model, string $action)
    {
        if (!Auth::check())
            return;

        AuditLog::create([
            'user_id' => Auth::id(),
            'accion' => $action,
            'entidad' => class_basename($model),
            'entidad_id' => $model->id,
            'descripcion' => "Usuario " . (Auth::user()->name ?? 'Sistema') . " realizó acción $action en " . class_basename($model) . " ID " . $model->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
