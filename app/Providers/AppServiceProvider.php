<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        /**
         * Registramos el observer de auditoría para los modelos clave.
         *
         * Cada vez que se haga create/update/delete sobre estos modelos,
         * AuditObserver se va a ejecutar y guardará el registro en la tabla
         * de auditorías (según cómo lo tengas implementado).
         */
        User::observe(AuditObserver::class);
        Student::observe(AuditObserver::class);
        Professor::observe(AuditObserver::class);
        Subject::observe(AuditObserver::class);
        Commission::observe(AuditObserver::class);
        AcademicState::observe(AuditObserver::class);
    }
}
