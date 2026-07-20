<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Blog;
use App\Models\Configuration;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSuperAdmin();

        // Create all pages with empty blocks first (routes must exist before blocks reference them)
        $home = $this->seedOrCreatePage('Home', 'home', [], 'home');
        $sobreMi = $this->seedOrCreatePage('Sobre mi', 'sobre-mi', [], 'default');
        $contacto = $this->seedOrCreatePage('Contacto', 'contacto', [], 'default');
        $blogIndex = $this->seedOrCreatePage('Novedades', 'novedades', [], 'default');
        $errorPage = $this->seedOrCreatePage('Error 404', 'error-404', [], 'default');
        $this->seedOrCreatePage('Publicaciones', 'publicaciones', [], 'default');
        $this->seedOrCreatePage('Prensa', 'prensa', [], 'default');
        $this->seedOrCreatePage('Agenda', 'agenda', [], 'default');
        $this->seedOrCreatePage('Programas', 'programas', [], 'default');
        $this->seedOrCreatePage('Docencia', 'docencia', [], 'default');
        $this->seedOrCreatePage('Trayectoria', 'trayectoria', [], 'default');

        // New pages for menu hierarchy
        $this->seedOrCreatePage('Actividad Academica', 'actividad-academica', [], 'default');
        $this->seedOrCreatePage('Jornadas y Congresos', 'jornadas-y-congresos', [], 'default');
        $this->seedOrCreatePage('Actualidad y Medios', 'actualidad-y-medios', [], 'default');
        $this->seedOrCreatePage('Exposicion Publica', 'exposicion-publica', [], 'default');
        $this->seedOrCreatePage('Dossier de Prensa', 'dossier-de-prensa', [], 'default');

        // Now all routes exist, set blocks with resolved route references
        $home->update(['blocks' => $this->homeBlocks()]);
        $sobreMi->update(['blocks' => $this->sobreMiBlocks()]);
        $contacto->update(['blocks' => $this->contactoBlocks()]);
        $blogIndex->update(['blocks' => $this->newsIndexBlocks()]);
        $errorPage->update(['blocks' => $this->errorBlocks()]);
        $this->updateParentPageBlocks();

        // Refresh for menu and config
        $home = $home->fresh('route');
        $blogIndex = $blogIndex->fresh('route');
        $errorPage = $errorPage->fresh('route');

        $posts = $this->seedPosts($blogIndex->route);
        $this->seedMainMenu();
        $this->seedConfig();
        $this->seedHomeConfig($home);
        $this->seedErrorConfig($errorPage);
    }

    // ─── Super Admin ───────────────────────────────────────────

    private function seedSuperAdmin(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@socies.agency'],
            [
                'name' => 'Socies Admin',
                'password' => Hash::make('123456'),
            ]
        );

        $role = Role::firstOrCreate([
            'name' => config('filament-shield.super_admin.name', 'super_admin'),
            'guard_name' => 'web',
        ]);

        $permissionNames = collect([
            'view_any_page', 'view_page', 'create_page', 'update_page', 'delete_page', 'delete_any_page', 'force_delete_page', 'force_delete_any_page', 'restore_page', 'restore_any_page', 'replicate_page', 'reorder_page',
            'view_any_blog', 'view_blog', 'create_blog', 'update_blog', 'delete_blog', 'delete_any_blog', 'force_delete_blog', 'force_delete_any_blog', 'restore_blog', 'restore_any_blog', 'replicate_blog', 'reorder_blog',
            'view_any_menu', 'view_menu', 'create_menu', 'update_menu', 'delete_menu', 'delete_any_menu', 'force_delete_menu', 'force_delete_any_menu', 'restore_menu', 'restore_any_menu', 'replicate_menu', 'reorder_menu',
            'view_any_banner', 'view_banner', 'create_banner', 'update_banner', 'delete_banner', 'delete_any_banner', 'force_delete_banner', 'force_delete_any_banner', 'restore_banner', 'restore_any_banner', 'replicate_banner', 'reorder_banner',
            'view_any_configuration', 'view_configuration', 'create_configuration', 'update_configuration', 'delete_configuration', 'delete_any_configuration', 'force_delete_configuration', 'force_delete_any_configuration', 'restore_configuration', 'restore_any_configuration', 'replicate_configuration', 'reorder_configuration',
            'view_any_redirection', 'view_redirection', 'create_redirection', 'update_redirection', 'delete_redirection', 'delete_any_redirection', 'force_delete_redirection', 'force_delete_any_redirection', 'restore_redirection', 'restore_any_redirection', 'replicate_redirection', 'reorder_redirection',
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user', 'delete_any_user', 'force_delete_user', 'force_delete_any_user', 'restore_user', 'restore_any_user', 'replicate_user', 'reorder_user',
            'view_role', 'view_any_role', 'create_role', 'update_role', 'delete_role',
        ])->unique();

        $permissions = $permissionNames->map(fn (string $name) => Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
        ]));

        $role->syncPermissions($permissions);
        $user->assignRole($role);
    }

    // ─── Page helpers ──────────────────────────────────────────

    private function seedOrCreatePage(string $title, string $slug, array $blocks, string $layout = 'default'): Page
    {
        $existingRoute = Route::where('slug', $slug)
            ->where('routable_type', Page::class)
            ->first();

        if ($existingRoute) {
            $page = $existingRoute->routable;
            $page->update(['blocks' => $blocks]);

            return $page->fresh('route');
        }

        $page = Page::create(['name' => $title, 'blocks' => $blocks]);
        $page->route()->create([
            'title' => $title,
            'slug' => $slug,
            'layout' => $layout,
            'status' => Status::Published,
            'full_slug' => $slug,
            'parent_id' => null,
            'description' => "Pagina: {$title}",
        ]);

        return $page->fresh('route');
    }

    private function block(string $type, array $data): array
    {
        return [
            'type' => $type,
            'data' => array_merge([
                'blockTitle' => null,
                'blockAnchor' => null,
                'mb' => 'mb-12',
                'mdMb' => 'md:mb-0',
                'clases' => [],
                'styles' => [],
                'stylesMd' => [],
                'hidden' => false,
            ], $data),
        ];
    }

    private function routeAttrs(?Route $route, ?string $label = null): array
    {
        return [
            'btn_label' => $label,
            'route_id' => $route?->id ? (string) $route->id : null,
            'external_url' => null,
            'file' => null,
            'download_name' => null,
            'anchor' => null,
            'new_window' => false,
        ];
    }

    private function routeRef(string $slug): array
    {
        $route = Route::where('slug', $slug)->first();

        return $this->routeAttrs($route);
    }

    // ─── Blog posts ────────────────────────────────────────────

    private function seedPosts(Route $parentRoute): array
    {
        $posts = [
            [
                'title' => 'Como empezar con el CMS base',
                'slug' => 'como-empezar-con-el-cms-base',
                'description' => 'Una guia rapida para cargar paginas, bloques y enlaces internos.',
            ],
            [
                'title' => 'Buenas practicas para armar paginas modulares',
                'slug' => 'buenas-practicas-paginas-modulares',
                'description' => 'Criterios simples para combinar bloques y crear paginas.',
            ],
            [
                'title' => 'Checklist antes de publicar contenido',
                'slug' => 'checklist-antes-de-publicar-contenido',
                'description' => 'Que revisar antes de pasar una pagina o novedad a publicado.',
            ],
            [
                'title' => 'Nuevos bloques disponibles',
                'slug' => 'nuevos-bloques-disponibles',
                'description' => 'Un repaso de los componentes genericos incluidos en el builder.',
            ],
        ];

        return collect($posts)->map(function (array $post, int $index) use ($parentRoute): Blog {
            $route = Route::updateOrCreate(
                [
                    'routable_type' => Blog::class,
                    'slug' => $post['slug'],
                    'parent_id' => $parentRoute->id,
                ],
                [
                    'title' => $post['title'],
                    'layout' => 'default',
                    'status' => Status::Published,
<<<<<<< HEAD
                    'full_slug' => $parentRoute->full_slug . '/' . $post['slug'],
=======
                    'parent_id' => $parentRoute->id,
                    'full_slug' => $parentRoute->full_slug.'/'.$post['slug'],
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c
                    'description' => $post['description'],
                ]
            );

            if ($route->routable && $route->routable instanceof Blog) {
                $blog = $route->routable;
                $blog->update([
                    'description' => '<p>'.$post['description'].'</p>',
                    'content' => '<p>'.$post['description'].' Este contenido fue creado por el seeder para validar el flujo de crear, editar, publicar y borrar novedades desde Filament.</p>',
                    'image' => null,
                ]);
            } else {
                $blog = Blog::create([
                    'description' => '<p>'.$post['description'].'</p>',
                    'content' => '<p>'.$post['description'].' Este contenido fue creado por el seeder para validar el flujo de crear, editar, publicar y borrar novedades desde Filament.</p>',
                    'image' => null,
                ]);
                $route->routable()->associate($blog)->save();
            }

            return $blog->fresh('route');
        })->all();
    }

    // ─── Menu ──────────────────────────────────────────────────

    private function seedMainMenu(): void
    {
        $items = [
            [
                '_token' => 'menu-home',
                'label' => 'Home',
                'order' => 0,
                'route' => $this->routeRef('home'),
                'children' => [],
            ],
            [
                '_token' => 'menu-sobre-mi',
                'label' => 'Sobre mi',
                'order' => 1,
                'route' => $this->routeRef('sobre-mi'),
                'children' => [
                    [
                        '_token' => 'menu-biografia',
                        'label' => 'Biografia',
                        'route' => $this->anchorRef('sobre-mi', 'biografia'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-trayectoria',
                        'label' => 'Trayectoria',
                        'route' => $this->routeRef('trayectoria'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-cargos',
                        'label' => 'Cargos institucionales',
                        'route' => $this->anchorRef('sobre-mi', 'cargos'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-cv',
                        'label' => 'CV',
                        'route' => $this->anchorRef('sobre-mi', 'cv'),
                        'children' => [],
                    ],
                ],
            ],
            [
                '_token' => 'menu-actividad',
                'label' => 'Actividad Academica',
                'order' => 2,
                'route' => $this->routeRef('actividad-academica'),
                'children' => [
                    [
                        '_token' => 'menu-docencia',
                        'label' => 'Docencia',
                        'route' => $this->routeRef('docencia'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-programas',
                        'label' => 'Programas',
                        'route' => $this->routeRef('programas'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-jornadas',
                        'label' => 'Jornadas y Congresos',
                        'route' => $this->routeRef('jornadas-y-congresos'),
                        'children' => [],
                    ],
                ],
            ],
            [
                '_token' => 'menu-publicaciones',
                'label' => 'Publicaciones',
                'order' => 3,
                'route' => $this->routeRef('publicaciones'),
                'children' => [
                    [
                        '_token' => 'menu-libros',
                        'label' => 'Libros',
                        'route' => $this->anchorRef('publicaciones', 'libros'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-articulos',
                        'label' => 'Articulos Academicos',
                        'route' => $this->anchorRef('publicaciones', 'articulos'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-actualidad',
                        'label' => 'Actualidad y Medios',
                        'route' => $this->routeRef('actualidad-y-medios'),
                        'children' => [],
                    ],
                ],
            ],
            [
                '_token' => 'menu-prensa',
                'label' => 'Prensa',
                'order' => 4,
                'route' => $this->routeRef('prensa'),
                'children' => [
                    [
                        '_token' => 'menu-entrevistas',
                        'label' => 'Entrevistas',
                        'route' => $this->anchorRef('prensa', 'entrevistas'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-exposicion',
                        'label' => 'Exposicion Publica',
                        'route' => $this->routeRef('exposicion-publica'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-agenda',
                        'label' => 'Agenda',
                        'route' => $this->routeRef('agenda'),
                        'children' => [],
                    ],
                    [
                        '_token' => 'menu-dossier',
                        'label' => 'Dossier de Prensa',
                        'route' => $this->routeRef('dossier-de-prensa'),
                        'children' => [],
                    ],
                ],
            ],
            [
                '_token' => 'menu-contacto',
                'label' => 'Contacto',
                'order' => 5,
                'route' => $this->routeRef('contacto'),
                'children' => [],
            ],
        ];

        Menu::updateOrCreate(
            ['slug' => 'header'],
            [
                'name' => 'Header',
                'items' => $items,
            ]
        );
    }

    private function anchorRef(string $slug, string $anchor): array
    {
        $route = Route::where('slug', $slug)->first();
        $attrs = $this->routeAttrs($route);
        $attrs['anchor'] = $anchor;

        return $attrs;
    }

    // ─── Config ────────────────────────────────────────────────

    private function seedConfig(): void
    {
        Configuration::updateOrCreate(
            ['key' => 'site-name'],
            [
                'type' => 'text',
                'value' => ['text' => 'Marcela Basterra'],
            ]
        );
    }

    private function seedHomeConfig(Page $home): void
    {
        Configuration::updateOrCreate(
            ['key' => 'home_route_id'],
            [
                'type' => 'url',
                'value' => [
                    'route' => $this->routeAttrs($home->route),
                ],
            ]
        );
    }

    private function seedErrorConfig(Page $errorPage): void
    {
        Configuration::updateOrCreate(
            ['key' => 'error_404_route_id'],
            [
                'type' => 'url',
                'value' => [
                    'route' => $this->routeAttrs($errorPage->route),
                ],
            ]
        );
    }

    // ═══════════════════════════════════════════════════════════
    //  PAGE BLOCKS
    // ═══════════════════════════════════════════════════════════

    // ─── Home ──────────────────────────────────────────────────

    private function homeBlocks(): array
    {
        return [
            // 1. Hero
            $this->block('Hero', [
                'title' => 'Marcela Basterra',
                'subtitle' => 'Abogada | Academica | Investigadora',
                'image' => null,
                'cta_label' => 'Conoceme',
                'cta_route' => $this->routeRef('sobre-mi'),
            ]),
            // 2. Publicaciones destacadas
            $this->block('PublicationsHighlight', [
                'title' => 'Publicaciones destacadas',
                'description' => 'Libros y articulos academicos',
                'libros' => [],
                'articulos' => [],
                'max_items' => 6,
                'show_type_label' => true,
            ]),
            // 3. Resumen biografico
            $this->block('BiographySummary', [
                'title' => 'Trayectoria',
                'summary' => '<p>Doctora en Derecho por la Universidad de Buenos Aires. Investigadora del CONICET. Especialista en derecho constitucional y derechos humanos.</p>',
                'photo' => null,
                'cta_label' => 'Ver mas',
                'cta_route' => $this->routeRef('sobre-mi'),
            ]),
            // 4. Prensa / Medios destacados
            $this->block('InterviewsHighlight', [
                'title' => 'Prensa y medios',
                'description' => 'Entrevistas y apariciones en medios',
                'entrevistas' => [],
                'max_items' => 6,
            ]),
            // 5. Agenda destacada
            $this->block('EventsHighlight', [
                'title' => 'Agenda',
                'description' => 'Proximos eventos y actividades',
                'eventos' => [],
                'max_items' => 6,
                'show_past' => false,
            ]),
            // 6. CTA Contacto
            $this->block('CTA', [
                'title' => 'Contacto',
                'text' => 'Escribime para consultas, conferencias o colaboraciones.',
                'button_label' => 'Contactar',
                'button_route' => $this->routeRef('contacto'),
            ]),
            // 7. CTA CV
            $this->block('CTA', [
                'title' => 'Curriculum Vitae',
                'text' => 'Descarga mi CV completo con trayectoria academica y profesional.',
                'button_label' => 'Descargar CV',
                'button_route' => $this->routeAttrs(null),
            ]),
        ];
    }

    // ─── Sobre mi ──────────────────────────────────────────────

    private function sobreMiBlocks(): array
    {
        return [
            // 1. Bio / Presentacion
            $this->block('BiographySummary', [
                'blockAnchor' => 'biografia',
                'title' => 'Marcela Basterra',
                'summary' => '<p>Doctora en Derecho por la Universidad de Buenos Aires. Investigadora del CONICET. Profesora titular de Derecho Constitucional en la UBA y en la Universidad de Palermo. Autora de numerosos libros y articulos academicos sobre derecho constitucional, derechos humanos y genero.</p>',
                'photo' => null,
                'cta_label' => null,
                'cta_route' => $this->routeAttrs(null),
            ]),
            // 2. Trayectoria (Timeline)
            $this->block('Timeline', [
                'title' => 'Trayectoria profesional',
                'items' => [
                    ['year' => '2020', 'title' => 'Investigadora Principal CONICET', 'description' => ''],
                    ['year' => '2015', 'title' => 'Profesora Titular UBA', 'description' => ''],
                    ['year' => '2010', 'title' => 'Doctorado en Derecho', 'description' => 'Universidad de Buenos Aires'],
                ],
            ]),
            // 3. Cargos institucionales (Cards)
            $this->block('Cards', [
                'blockAnchor' => 'cargos',
                'title' => 'Cargos institucionales',
                'description' => '',
                'items' => [
                    ['title' => 'CONICET', 'description' => 'Investigadora Principal', 'image' => null, 'route' => []],
                    ['title' => 'UBA', 'description' => 'Profesora Titular de Derecho Constitucional', 'image' => null, 'route' => []],
                    ['title' => 'Universidad de Palermo', 'description' => 'Profesora Titular', 'image' => null, 'route' => []],
                ],
            ]),
            // 4. CTA CV
            $this->block('CTA', [
                'blockAnchor' => 'cv',
                'title' => 'Curriculum Vitae',
                'text' => 'Descarga mi CV completo con trayectoria academica y profesional.',
                'button_label' => 'Descargar CV',
                'button_route' => $this->routeAttrs(null),
            ]),
        ];
    }

    // ─── Contacto ──────────────────────────────────────────────

    private function contactoBlocks(): array
    {
        return [
            // 1. Formulario de contacto
            $this->block('ContactForm', [
                'title' => 'Contacto',
                'description' => 'Completa el formulario y te respondere a la brevedad.',
                'recipient_email' => 'contacto@marcelabasterra.com',
                'success_message' => 'Mensaje enviado correctamente',
                'show_phone' => true,
                'show_subject' => true,
            ]),
            // 2. Informacion de contacto (Cards)
            $this->block('Cards', [
                'title' => 'Informacion de contacto',
                'description' => '',
                'items' => [
                    ['title' => 'Email', 'description' => 'contacto@marcelabasterra.com', 'image' => null, 'route' => []],
                    ['title' => 'LinkedIn', 'description' => 'Perfil profesional', 'image' => null, 'route' => []],
                    ['title' => 'Twitter', 'description' => '@marcelabasterra', 'image' => null, 'route' => []],
                ],
            ]),
            // 3. CTA invitaciones
            $this->block('CTA', [
                'title' => 'Invitaciones y conferencias',
                'text' => 'Si queres invitarme a participar en un evento, congreso o conferencia, no dudes en contactarme.',
                'button_label' => 'Solicitar participacion',
                'button_route' => $this->routeAttrs(null),
            ]),
        ];
    }

    // ─── Novedades ─────────────────────────────────────────────

    private function newsIndexBlocks(): array
    {
        return [
            $this->block('Text', [
                'blockTitle' => 'Novedades',
                'eyebrow' => 'Blog',
                'title' => 'Novedades',
                'content' => '<p>Ultimas novedades y actualizaciones.</p>',
                'width' => 'container',
            ]),
        ];
    }

    // ─── Error 404 ─────────────────────────────────────────────

    private function errorBlocks(): array
    {
        return [
            $this->block('Text', [
                'blockTitle' => 'Error 404',
                'title' => 'Pagina no encontrada',
                'content' => '<p>La pagina que buscas no existe o fue movida.</p>',
                'width' => 'narrow',
            ]),
            $this->block('Search', [
                'blockTitle' => 'Busqueda',
                'title' => 'Busca en el sitio',
                'description' => 'Usa el buscador para encontrar lo que necesitas.',
            ]),
        ];
    }

    // ─── Parent pages ──────────────────────────────────────────

    private function updateParentPageBlocks(): void
    {
        $this->updatePageBlocks('publicaciones', [
            $this->block('PublicationsHighlight', [
                'blockAnchor' => 'libros',
                'title' => 'Publicaciones',
                'description' => 'Libros y articulos academicos',
                'libros' => [],
                'articulos' => [],
                'max_items' => 12,
                'show_type_label' => true,
            ]),
            $this->block('FeaturedResources', [
                'blockAnchor' => 'articulos',
                'title' => 'Recursos destacados',
                'description' => '',
                'items' => [
                    ['resource_type' => 'libro', 'resource_id' => null],
                    ['resource_type' => 'articulo', 'resource_id' => null],
                    ['resource_type' => 'libro', 'resource_id' => null],
                ],
            ]),
        ]);

        $this->updatePageBlocks('prensa', [
            $this->block('InterviewsHighlight', [
                'blockAnchor' => 'entrevistas',
                'title' => 'Prensa',
                'description' => 'Entrevistas y apariciones en medios',
                'entrevistas' => [],
                'max_items' => 12,
            ]),
            $this->block('FeaturedResources', [
                'title' => 'Dossiers de prensa',
                'description' => '',
                'items' => [
                    ['resource_type' => 'entrevista', 'resource_id' => null],
                    ['resource_type' => 'entrevista', 'resource_id' => null],
                    ['resource_type' => 'entrevista', 'resource_id' => null],
                ],
            ]),
        ]);

        $this->updatePageBlocks('agenda', [
            $this->block('EventsHighlight', [
                'title' => 'Agenda',
                'description' => 'Proximos eventos y actividades academicas',
                'eventos' => [],
                'max_items' => 12,
                'show_past' => true,
            ]),
        ]);

        $this->updatePageBlocks('programas', [
            $this->block('FeaturedResources', [
                'title' => 'Programas academicos',
                'description' => '',
                'items' => [
                    ['resource_type' => 'libro', 'resource_id' => null],
                    ['resource_type' => 'libro', 'resource_id' => null],
                    ['resource_type' => 'libro', 'resource_id' => null],
                ],
            ]),
        ]);

        $this->updatePageBlocks('docencia', [
            $this->block('FeaturedResources', [
                'title' => 'Docencia',
                'description' => '',
                'items' => [
                    ['resource_type' => 'libro', 'resource_id' => null],
                    ['resource_type' => 'libro', 'resource_id' => null],
                    ['resource_type' => 'libro', 'resource_id' => null],
                ],
            ]),
        ]);

        $this->updatePageBlocks('trayectoria', [
            $this->block('Cards', [
                'title' => 'Cargos institucionales',
                'description' => '',
                'items' => [
                    ['title' => 'Cargo institucional', 'description' => 'Los datos se completan desde el panel de administracion.', 'image' => null, 'route' => []],
                    ['title' => 'Cargo institucional', 'description' => 'Los datos se completan desde el panel de administracion.', 'image' => null, 'route' => []],
                    ['title' => 'Cargo institucional', 'description' => 'Los datos se completan desde el panel de administracion.', 'image' => null, 'route' => []],
                ],
            ]),
        ]);

        // New pages
        $this->updatePageBlocks('actividad-academica', [
            $this->block('Text', [
                'title' => 'Actividad Academica',
                'content' => '<p>Docencia, programas academicos y participacion en jornadas y congresos.</p>',
                'width' => 'container',
            ]),
        ]);

        $this->updatePageBlocks('jornadas-y-congresos', [
            $this->block('Text', [
                'title' => 'Jornadas y Congresos',
                'content' => '<p>Participacion en jornadas, congresos y conferencias academicas.</p>',
                'width' => 'container',
            ]),
        ]);

        $this->updatePageBlocks('actualidad-y-medios', [
            $this->block('Text', [
                'title' => 'Actualidad y Medios',
                'content' => '<p>Articulos de actualidad y participacion en medios de comunicacion.</p>',
                'width' => 'container',
            ]),
        ]);

        $this->updatePageBlocks('exposicion-publica', [
            $this->block('Text', [
                'title' => 'Exposicion Publica',
                'content' => '<p>Exposicion publica y participacion en medios.</p>',
                'width' => 'container',
            ]),
        ]);

        $this->updatePageBlocks('dossier-de-prensa', [
            $this->block('Text', [
                'title' => 'Dossier de Prensa',
                'content' => '<p>Dossier de prensa y materiales para medios.</p>',
                'width' => 'container',
            ]),
        ]);
    }

    private function updatePageBlocks(string $slug, array $blocks): void
    {
        $route = Route::where('slug', $slug)
            ->where('routable_type', Page::class)
            ->first();

        if ($route) {
            $route->routable->update(['blocks' => $blocks]);
        }
    }
}
