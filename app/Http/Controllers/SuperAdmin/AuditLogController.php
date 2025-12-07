<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = AuditLog::with('user');

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Buscador de texto (Usuario, Acción, Descripción)
        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('accion', 'like', "%{$term}%")
                    ->orWhere('descripcion', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($u) use ($term) {
                        $u->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    });
            });
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate(25)
            ->appends($request->query());

        // 👈 NOMBRE DE LA VISTA CORRECTO
        return view('superadmin.audit.index', compact('logs'));
    }
}
