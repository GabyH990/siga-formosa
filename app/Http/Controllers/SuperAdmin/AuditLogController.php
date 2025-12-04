<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        // 👈 NOMBRE DE LA VISTA CORRECTO
        return view('superadmin.audit.index', compact('logs'));
    }
}
