<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // 👈 IMPORTANTE
use App\Models\Career;

class PanelController extends Controller
{
    public function index(Request $request)
    {
        // Obtenemos al usuario autenticado
        $user = Auth::user();   // 👈 así el IDE no se queja

        // --- LÓGICA DE RESET (Opción Minimalista) ---
        if ($request->has('reset_career')) {
            session()->forget('active_career_id');
            return redirect()->route('panel');
        }

        // ¿Es Super Admin?
        $esSuperAdmin = $user->role && $user->role->nombre === 'superadmin';

        $activeCareer = null;

        if ($esSuperAdmin) {
            // --- NUEVO: permitir elegir carrera con ?career=TUP o ?career=LPB ---
            if ($request->filled('career')) {
                $career = Career::where('codigo', $request->career)->first();

                if ($career) {
                    // Guardamos la carrera seleccionada en sesión
                    session(['active_career_id' => $career->id]);
                }
            }

            // Leemos la carrera activa de sesión (si existe)
            if (session()->has('active_career_id')) {
                $activeCareer = Career::find(session('active_career_id'));
            }
        } else {
            // Para Bedel común, la carrera es la que tiene asignada
            $activeCareer = $user->career;
        }

        return view('panel', [
            'esSuperAdmin' => $esSuperAdmin,
            'activeCareer' => $activeCareer,
        ]);
    }
}
