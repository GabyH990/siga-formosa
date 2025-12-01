<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;
use App\Models\Subject;
use App\Models\Commission;

class CarrerasMateriasComisionesSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run(): void
    {
        // ==========================
        // 1) CARRERAS
        // ==========================

        $tup = Career::firstOrCreate(
            ['codigo' => 'TUP'],
            ['nombre' => 'Tecnicatura Universitaria en Programación']
        );

        $lpb = Career::firstOrCreate(
            ['codigo' => 'LPB'],
            ['nombre' => 'Licenciatura en Producción de Bioimágenes']
        );

        // ==========================
        // 2) MATERIAS TUP
        // ==========================

        $tupSubjects = [
            ['codigo' => 'PRG1',   'nombre' => 'Programación I'],
            ['codigo' => 'ARQSO',  'nombre' => 'Arquitectura y Sistemas Operativos'],
            ['codigo' => 'MAT',    'nombre' => 'Matemática'],
            ['codigo' => 'ORGEMP', 'nombre' => 'Organización empresarial'],
            ['codigo' => 'PRG2',   'nombre' => 'Programación II'],
            ['codigo' => 'PROBEST','nombre' => 'Probabilidad y Estadística'],
            ['codigo' => 'BD1',    'nombre' => 'Base de Datos I'],
            ['codigo' => 'ING1',   'nombre' => 'Inglés I'],
            ['codigo' => 'PRG3',   'nombre' => 'Programación III'],
            ['codigo' => 'BD2',    'nombre' => 'Base de Datos II'],
            ['codigo' => 'MS1',    'nombre' => 'Metodología de Sistemas I'],
            ['codigo' => 'ING2',   'nombre' => 'Inglés II'],
            ['codigo' => 'PRG4',   'nombre' => 'Programación IV'],
            ['codigo' => 'MS2',    'nombre' => 'Metodología de Sistemas II'],
            ['codigo' => 'IAD',    'nombre' => 'Introducción al análisis de datos'],
            ['codigo' => 'LEG',    'nombre' => 'Legislación'],
            ['codigo' => 'GDS',    'nombre' => 'Gestión de desarrollo de software'],
            ['codigo' => 'TFI',    'nombre' => 'Trabajo Final Integrador'],
        ];

        foreach ($tupSubjects as $data) {
            $subject = Subject::firstOrCreate(
                ['codigo' => $data['codigo']],
                [
                    'career_id' => $tup->id,
                    'nombre'    => $data['nombre'],
                    'anio_plan' => 2024,
                ]
            );

            $this->crearComisionesPorDefecto($subject);
        }

        // ==========================
        // 3) MATERIAS LPB
        // ==========================

        $lpbSubjects = [
            ['codigo' => 'APATI',   'nombre' => 'Adquisición, procesamiento, almacenamiento y transmisión de imágenes'],
            ['codigo' => 'TCT',     'nombre' => 'Tecnología médica en tomografía computada'],
            ['codigo' => 'DIO',     'nombre' => 'Diagnóstico por imágenes osteoarticular'],
            ['codigo' => 'DIT',     'nombre' => 'Diagnóstico por imágenes de tórax'],
            ['codigo' => 'METINV',  'nombre' => 'Metodología de la investigación'],
            ['codigo' => 'EST',     'nombre' => 'Estadística'],
            ['codigo' => 'SALPUB',  'nombre' => 'Salud pública'],
            ['codigo' => 'TRMN',    'nombre' => 'Tecnología médica en resonancia magnética nuclear'],
            ['codigo' => 'ULTRA',   'nombre' => 'Tecnología médica en ultrasonido'],
            ['codigo' => 'RADIOIS', 'nombre' => 'Tecnología médica en radioisótopos'],
            ['codigo' => 'DIAABD',  'nombre' => 'Diagnóstico por imágenes de abdomen'],
            ['codigo' => 'DIACUC',  'nombre' => 'Diagnóstico por imágenes de cabeza y cuello'],
            ['codigo' => 'GEST',    'nombre' => 'Gestión'],
            ['codigo' => 'TES',     'nombre' => 'Tesina'],
        ];

        foreach ($lpbSubjects as $data) {
            $subject = Subject::firstOrCreate(
                ['codigo' => $data['codigo']],
                [
                    'career_id' => $lpb->id,
                    'nombre'    => $data['nombre'],
                    'anio_plan' => 2024,
                ]
            );

            $this->crearComisionesPorDefecto($subject);
        }
    }

    /**
     * Crea las comisiones 1.1, 1.2, 1.3 y 2.1, 2.2, 2.3 para una materia dada.
     */
    private function crearComisionesPorDefecto(Subject $subject): void
    {
        $comisiones = [
            ['nombre' => '1.1', 'anio' => 2024, 'periodo' => '1C'],
            ['nombre' => '1.2', 'anio' => 2024, 'periodo' => '1C'],
            ['nombre' => '1.3', 'anio' => 2024, 'periodo' => '1C'],
            ['nombre' => '2.1', 'anio' => 2024, 'periodo' => '2C'],
            ['nombre' => '2.2', 'anio' => 2024, 'periodo' => '2C'],
            ['nombre' => '2.3', 'anio' => 2024, 'periodo' => '2C'],
        ];

        foreach ($comisiones as $data) {
            Commission::firstOrCreate(
                [
                    'subject_id' => $subject->id,
                    'nombre'     => $data['nombre'],
                ],
                [
                    'anio'    => $data['anio'],
                    'periodo' => $data['periodo'],
                ]
            );
        }
    }
}
