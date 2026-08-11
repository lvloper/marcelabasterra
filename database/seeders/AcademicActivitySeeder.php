<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Docencia;
use App\Models\InstitucionAcademica;
use App\Models\Page;
use App\Models\Route;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AcademicActivitySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $academicRoute = Route::whereFullSlug('actividad-academica')->firstOrFail();
            $institutions = $this->seedInstitutions($academicRoute);
            $teachingIds = $this->seedTeaching($academicRoute, $institutions);
            $this->composePage($academicRoute, $teachingIds);
        });
    }

    /** @return array<string, InstitucionAcademica> */
    private function seedInstitutions(Route $parent): array
    {
        $definitions = [
            'uba' => ['Universidad de Buenos Aires', 'UBA', 'Argentina', 'nacional', 'https://www.uba.ar/', 'https://www.uba.ar/imgs/logoheader40anios.png'],
            'up' => ['Universidad de Palermo', 'UP', 'Argentina', 'nacional', 'https://www.palermo.edu/', 'https://www.palermo.edu/images/header/logo.png'],
            'uca' => ['Pontificia Universidad Católica Argentina', 'UCA', 'Argentina', 'nacional', 'https://uca.edu.ar/', 'https://uca.edu.ar/assets/img/UCA-Logo-1.png'],
            'austral' => ['Universidad Austral', 'UA', 'Argentina', 'nacional', 'https://www.austral.edu.ar/', 'https://www.austral.edu.ar/wp-content/uploads/2022/09/logo-md-austral-1.png'],
            'puc-chile' => ['Pontificia Universidad Católica de Chile', 'UC Chile', 'Chile', 'internacional', 'https://www.uc.cl/', null],
            'idc-bologna' => ['Instituto para el Desarrollo Constitucional y Università di Bologna', 'IDC · UNIBO', 'Argentina · Italia', 'internacional', 'https://www.unibo.it/', null],
            'tepjf-uba' => ['Tribunal Electoral del Poder Judicial de la Federación y Universidad de Buenos Aires', 'TEPJF · UBA', 'México · Argentina', 'internacional', 'https://www.te.gob.mx/', 'https://www.te.gob.mx/media/files/waf/logo-tepjf-header.jpg'],
        ];

        $institutions = [];
        foreach ($definitions as $order => $definition) {
            [$name, $acronym, $country, $scope, $website, $logo] = $definition;
            $slug = 'institucion-'.Str::slug((string) $order);

            $institutionRoute = Route::query()
                ->where('routable_type', InstitucionAcademica::class)
                ->where('slug', $slug)
                ->first();
            $institution = $institutionRoute?->routable instanceof InstitucionAcademica
                ? $institutionRoute->routable
                : new InstitucionAcademica();
            $institution->fill([
                'sigla' => $acronym,
                'pais' => $country,
                'alcance' => $scope,
                'sitio_web' => $website,
                'logo' => $logo,
                'destacada' => true,
                'orden' => array_search($order, array_keys($definitions), true) + 1,
                'blocks' => [$this->block('Text', [
                    'eyebrow' => 'Institución académica',
                    'title' => $name,
                    'content' => '<p>'.e($country).'. Institución vinculada con la actividad docente y académica de la Dra. Marcela I. Basterra.</p>',
                    'width' => 'narrow',
                ])],
            ])->save();

            $institutionRoute ??= new Route();
            $institutionRoute->fill([
                'title' => $name,
                'slug' => $slug,
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => $parent->id,
                'full_slug' => "{$parent->full_slug}/{$slug}",
                'description' => "Actividad académica en {$name}.",
            ]);
            $institutionRoute->routable()->associate($institution);
            $institutionRoute->save();
            $institutions[(string) $order] = $institution->fresh('route');
        }

        return $institutions;
    }

    /** @param array<string, InstitucionAcademica> $institutions
     *  @return array<int>
     */
    private function seedTeaching(Route $parent, array $institutions): array
    {
        $items = [
            ['uba', 'Facultad de Derecho', 'Maestría en Derecho Constitucional', 'maestria', 'El Estado y sus elementos', null, 'presencial'],
            ['uba', 'Facultad de Derecho', 'Carrera de Especialización en Derecho Constitucional', 'posgrado', 'Los Derechos Fundamentales', null, 'presencial'],
            ['uba', 'Facultad de Derecho', 'Programa de Doctorado y de Actualización en Teoría del Derecho y Argumentación Jurídica Aplicada', 'doctorado', 'Derechos y democracia en el Estado constitucional; interrelación entre Derecho Constitucional y Derechos Humanos; fundamentos y modelos del control de constitucionalidad', null, 'presencial'],
            ['uba', 'Facultad de Derecho', 'Programa de Doctorado en Teoría del Derecho, Argumentación Jurídica y Derecho Constitucional', 'doctorado', 'Populismo, libertad de expresión, derecho a la intimidad y derechos de género', null, 'presencial'],
            ['uba', 'Facultad de Derecho', 'Curso Intensivo sobre Derecho a la Igualdad, No Discriminación y Género', 'posgrado', 'Derecho a la igualdad, no discriminación y género', null, 'presencial'],
            ['up', 'Facultad de Derecho', 'Doctorado en Derecho', 'doctorado', 'Política, Ciudadanía y República: populismo; libertad de expresión e internet', null, 'presencial'],
            ['uca', null, 'Maestría en Administración de Justicia y Litigación Oral', 'maestria', 'Control e interpretación constitucional y convencional', null, 'presencial'],
            ['austral', 'Facultad de Derecho', 'Diplomatura en Derecho Constitucional Latinoamericano', 'posgrado', 'La exigibilidad y la justiciabilidad de los derechos económicos, sociales, culturales y ambientales', null, 'hibrida'],
            ['austral', 'Facultad de Derecho', 'Diplomatura en Derecho Procesal Constitucional', 'posgrado', 'Habeas Data', null, 'hibrida'],
            ['puc-chile', null, 'Maestría en Derecho', 'maestria', 'Protección de datos personales en América Latina y el Sistema Europeo', null, 'distancia'],
            ['puc-chile', null, 'Maestría en Derecho', 'maestria', 'El amparo de derechos en el Derecho Latinoamericano', null, 'distancia'],
            ['idc-bologna', null, 'Especialización en Justicia Constitucional y Derechos Humanos', 'posgrado', 'Libertad, igualdad, derechos civiles y políticos', null, 'distancia'],
            ['tepjf-uba', null, 'Maestría en Derecho y TIC', 'maestria', 'Big Data, algoritmos y nuevas tecnologías de la información: sistema de protección de datos personales', null, 'distancia'],
        ];

        $ids = [];
        foreach ($items as $index => $data) {
            [$institutionKey, $faculty, $program, $level, $subject, $description, $modality] = $data;
            $institution = $institutions[$institutionKey];
            $teaching = Docencia::query()
                ->where('institucion_academica_id', $institution->id)
                ->where('programa', $program)
                ->where('materia', $subject)
                ->first() ?: new Docencia();
            $teaching->fill([
                'institucion_academica_id' => $institution->id,
                'institucion' => $institution->title,
                'facultad' => $faculty,
                'programa' => $program,
                'materia' => $subject,
                'catedra' => null,
                'rol' => 'Docente de posgrado',
                'nivel' => $level,
                'modalidad' => $modality,
                'periodo' => '2025/2026',
                'enlace' => $institution->sitio_web,
                'vigente' => true,
                'orden' => $index + 1,
                'descripcion' => $description,
                'blocks' => [],
            ])->save();

            $slug = 'docencia-'.Str::slug($institution->sigla.'-'.$program.'-'.$subject);
            $teachingRoute = $teaching->route ?: new Route();
            $teachingRoute->fill([
                'title' => $program.' · '.$institution->sigla,
                'slug' => Str::limit($slug, 190, ''),
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => $parent->id,
                'full_slug' => $parent->full_slug.'/'.Str::limit($slug, 190, ''),
                'description' => Str::limit($subject, 250),
            ]);
            $teachingRoute->routable()->associate($teaching);
            $teachingRoute->save();
            $ids[] = $teaching->id;
        }

        return $ids;
    }

    /** @param array<int> $teachingIds */
    private function composePage(Route $route, array $teachingIds): void
    {
        $page = $route->routable;
        if (! $page instanceof Page) {
            return;
        }

        $route->update([
            'title' => 'Docencia y Actividad Académica',
            'description' => 'Posgrados, maestrías, doctorados, artículos especializados y recursos académicos de la Dra. Marcela I. Basterra.',
        ]);
        $page->update([
            'name' => 'Docencia y Actividad Académica',
            'blocks' => [
                $this->block('Hero', [
                    'variant' => 'editorial',
                    'profile_photo' => null,
                    'image_alt' => '',
                    'badge' => 'Trayectoria académica · 2025/2026',
                    'name' => 'Docencia y Actividad Académica',
                    'subtitle' => 'Formación de posgrado en instituciones nacionales e internacionales.',
                    'description' => 'Una trayectoria dedicada a la enseñanza del Derecho Constitucional, los Derechos Humanos y la protección de datos, junto con un archivo abierto de producción académica.',
                    'indicators' => [
                        ['label' => 'Posgrados'],
                        ['label' => 'Maestrías'],
                        ['label' => 'Doctorados'],
                    ],
                    'featured_positions' => [],
                    'cta_primary' => [],
                    'cta_secondary' => [],
                    'cta_tertiary' => [],
                ]),
                $this->block('TeachingListing', [
                    'blockAnchor' => 'docencia',
                    'title' => 'Posgrados, maestrías y doctorados',
                    'description' => 'Actividad docente organizada por alcance institucional y nivel de formación, con información administrada desde el CMS.',
                    'levels' => ['posgrado', 'maestria', 'doctorado'],
                    'scopes' => ['nacional', 'internacional'],
                    'institutions' => [],
                    'selected_items' => $teachingIds,
                    'current_only' => true,
                    'max_items' => 30,
                    'show_description' => true,
                    'show_institutions' => true,
                    'student_resources' => [
                        ['label' => 'Infoleg', 'url' => 'https://servicios.infoleg.gob.ar/'],
                        ['label' => 'Universidad de Buenos Aires / Facultad de Derecho', 'url' => 'https://www.derecho.uba.ar/'],
                        ['label' => 'Corte Interamericana de Derechos Humanos', 'url' => 'https://www.corteidh.or.cr/'],
                        ['label' => 'AADC – renovación de autoridades', 'url' => 'https://aadconst.org.ar/la-asociacion-argentina-de-derecho-constitucional-renovo-sus-autoridades/'],
                        ['label' => 'Autoridades Facultad de Derecho UBA', 'url' => 'https://www.derecho.uba.ar/institucional/autoridades-derecho.php'],
                    ],
                ]),
                $this->block('ContentList', [
                    'blockAnchor' => 'articulos-especializados',
                    'title' => 'Artículos especializados',
                    'description' => 'Publicaciones ordenadas del año más reciente al más antiguo, con acceso directo a cada documento en PDF.',
                    'variant' => 'chronological',
                    'source_mode' => 'academic_articles',
                    'institutional_positions' => [],
                    'items_per_page' => 12,
                    'items' => [],
                ]),
            ],
        ]);
    }

    /** @param array<string, mixed> $data */
    private function block(string $type, array $data): array
    {
        return [
            'type' => $type,
            'data' => array_merge([
                'blockTitle' => null,
                'blockAnchor' => null,
                'mb' => 'mb-0',
                'mdMb' => 'md:mb-0',
                'clases' => [],
                'styles' => [],
                'stylesMd' => [],
                'hidden' => false,
            ], $data),
        ];
    }
}
