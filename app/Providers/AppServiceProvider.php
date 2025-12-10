<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. IMPORTANTE: Importamos el Facade URL
use App\Models\User;
use App\Models\Student;
use App\Models\Professor;
use App\Models\Subject;
use App\Models\Commission;
use App\Models\AcademicState;
use App\Observers\AuditObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ----------------------------------------------------------------
        // CORRECCIÓN PARA RENDER (HTTPS)
        // ----------------------------------------------------------------
        // Si la aplicación está en producción, forzamos que todos los enlaces
        // (incluyendo los de Vite/CSS/JS) se generen con HTTPS.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /**
         * Registramos el observer de auditoría para los modelos clave.
         */
        User::observe(AuditObserver::class);
        Student::observe(AuditObserver::class);
        Professor::observe(AuditObserver::class);
        Subject::observe(AuditObserver::class);
        Commission::observe(AuditObserver::class);
        AcademicState::observe(AuditObserver::class);
    }
}