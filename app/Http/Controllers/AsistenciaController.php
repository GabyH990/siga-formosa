<?php

namespace App\Http\Controllers;

class AsistenciaController extends Controller
{
    /**
     * Menú principal del módulo Asistencia
     */
    public function index()
    {
        return view('asistencias.index');
    }

    /**
     * Página "Armar Cursada" (envuelve al componente Livewire)
     */
    public function armarCursada()
    {
        return view('asistencias.armar-cursada');
    }

    /**
     * Página "Registros" (Tomar asistencia)
     */
    public function registros()
    {
        return view('asistencias.registros');
    }

    /**
     * Página "Reportes"
     */
    public function reportes()
    {
        return view('asistencias.reportes');
    }
}
