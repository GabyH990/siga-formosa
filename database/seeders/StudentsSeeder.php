<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Career;
use Illuminate\Support\Str;

class StudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar carrera TUP (ya viene de tu CareersSeeder / CarrerasMateriasComisionesSeeder)
        $tup = Career::where('codigo', 'TUP')->first();

        if (!$tup) {
            throw new \Exception("No existe la carrera TUP. Corré primero CareersSeeder.");
        }

        // Crear 50 de cohorte 2024 y 50 de cohorte 2025
        $this->crearAlumnos($tup->id, 2024, 50);
        $this->crearAlumnos($tup->id, 2025, 50);
    }

    private function crearAlumnos(int $careerId, int $cohorte, int $cantidad): void
    {
        // Legajo: si ya tenés alumnos, continúa desde el último
        $ultimoLegajo = (int) (Student::max('legajo') ?? 0);
        $baseLegajo = max($ultimoLegajo, 0);

        // Para evitar DNI repetidos si ya hay data
        $ultimoDni = (int) (Student::max('dni') ?? 0);
        $dniBase = max($ultimoDni, 20_000_000); // arranca desde un número razonable

        for ($i = 1; $i <= $cantidad; $i++) {
            $nro = $i + (($cohorte === 2025) ? $cantidad : 0); // solo para diferenciar emails
            $legajo = $baseLegajo + $nro;

            // DNI incremental único
            $dni = $dniBase + $nro;

            // Mayor de 21: entre 22 y 35 años aprox
            $fechaNacimiento = $this->randomFechaMayorDe21();

            $nombre = $this->randomNombre();
            $apellido = $this->randomApellido();

            // Email único y @gmail.com
            $email = Str::lower(
                Str::ascii($nombre . '.' . $apellido) . '.' . $cohorte . '.' . str_pad((string)$nro, 3, '0', STR_PAD_LEFT) . '@gmail.com'
            );

            // Evitar duplicar por correo (si volvés a correr seeder)
            Student::firstOrCreate(
                ['correo' => $email],
                [
                    'legajo'           => (string) $legajo,
                    'nombre'           => $nombre,
                    'apellido'         => $apellido,
                    'dni'              => (string) $dni,
                    'fecha_nacimiento' => $fechaNacimiento,
                    'correo'           => $email,
                    'telefono'         => $this->randomTelefonoAR(),
                    'direccion'        => 'Formosa, Argentina',
                    'cohorte'          => $cohorte,
                    'career_id'        => $careerId,
                    'activo'           => true,
                ]
            );
        }
    }

    private function randomFechaMayorDe21(): string
    {
        // edad entre 22 y 35
        $edad = random_int(22, 35);
        $anio = (int) date('Y') - $edad;
        $mes = random_int(1, 12);
        $dia = random_int(1, 28);

        return sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
    }

    private function randomTelefonoAR(): string
    {
        // Formato simple: 3704 + 6 dígitos
        return '3704' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function randomNombre(): string
    {
        $nombres = [
            'Ana','María','Lucía','Sofía','Valentina','Camila','Martina','Julieta',
            'Juan','Lucas','Mateo','Thiago','Nicolás','Tomás','Franco','Agustín'
        ];

        return $nombres[array_rand($nombres)];
    }

    private function randomApellido(): string
    {
        $apellidos = [
            'Gómez','González','Rodríguez','Fernández','López','Martínez','Pérez','Sánchez',
            'Romero','Díaz','Alvarez','Torres','Ruiz','Flores','Acosta','Benítez'
        ];

        return $apellidos[array_rand($apellidos)];
    }
}
