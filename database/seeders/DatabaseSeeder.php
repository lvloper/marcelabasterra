<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Blog;
use App\Models\CargoInstitucional;
use App\Models\Conferencia;
use App\Models\Configuration;
use App\Models\Menu;
use App\Models\Libro;
use App\Models\Page;
use App\Models\PublicacionMedio;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
        $this->seedOrCreatePage('Muestra de bloques', 'muestra-bloques', $this->sampleBlocks(), 'default');

        $this->seedEditorialContent();

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
        $this->call(AcademicActivitySeeder::class);
        $this->call(JornadasCongresosSeeder::class);
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

    private function seedEditorialContent(): void
    {
        $this->seedInstitutionalPositions();

        $bookRoute = Route::firstOrNew([
            'routable_type' => Libro::class,
            'slug' => 'teoria-general-derechos-economicos-sociales-culturales-ambientales',
        ]);
        $book = $bookRoute->routable instanceof Libro ? $bookRoute->routable : new Libro();
        $book->fill([
            'autoria' => 'Marcela I. Basterra',
            'descripcion' => '<p>Obra integral dedicada a la teoría general y al desarrollo particular de los Derechos Económicos, Sociales, Culturales y Ambientales.</p>',
            'fecha_publicacion' => '2025-01-01',
            'editorial' => 'Rubinzal Culzoni – Santa Fe, Argentina',
            'area_tematica' => 'Derecho Constitucional – Derechos Humanos – Derecho Público',
            'isbn' => '978-987-30-5231-6',
            'tomos' => [
                ['nombre' => 'Tomo I – Parte General', 'paginas' => 411, 'isbn' => '978-987-30-5232-3'],
                ['nombre' => 'Tomo II – Parte Especial', 'paginas' => 581, 'isbn' => '978-987-30-5233-0'],
            ],
            'destacado' => true,
        ])->save();
        $booksParent = Route::whereFullSlug('publicaciones/libros')->first();
        $bookRoute->fill([
            'title' => 'Teoría general de los Derechos Económicos, Sociales, Culturales y Ambientales',
            'layout' => 'default',
            'status' => Status::Published,
            'parent_id' => $booksParent?->id,
            'description' => 'Obra de Marcela I. Basterra publicada por Rubinzal Culzoni en 2025.',
        ]);
        $bookRoute->routable()->associate($book);
        $bookRoute->save();

        $importPath = storage_path('app/imports/wordpress-posts.json');
        $posts = is_file($importPath)
            ? (json_decode((string) file_get_contents($importPath), true)['posts'] ?? [])
            : [];
        foreach ($posts as $post) {
            if (! in_array('Medios', $post['categories'] ?? [], true)) continue;
            $titleParts = array_map('trim', preg_split('~/~u', (string) ($post['title'] ?? ''), 2));
            $title = $titleParts[0] ?: 'Publicación en medios';
            $medium = $titleParts[1] ?? 'Medios';
            $externalUrl = collect($post['external_links'] ?? [])->first() ?: ($post['url'] ?? null);
            $type = preg_match('/entrevista|conversaciones|radio|programa/i', $title) ? 'entrevista' : 'articulo';

            $press = PublicacionMedio::query()->where('enlace_externo', $externalUrl)->first() ?: new PublicacionMedio();
            $press->fill([
                'tipo' => $type,
                'medio' => $medium,
                'fecha' => substr((string) ($post['date'] ?? ''), 0, 10) ?: null,
                'resumen' => '<p>'.e(\Illuminate\Support\Str::limit((string) ($post['plain_text'] ?? ''), 420)).'</p>',
                'enlace_externo' => $externalUrl,
                'autoria' => 'Marcela I. Basterra',
                'destacado' => false,
            ])->save();
            $slug = 'prensa-'.($post['slug'] ?? \Illuminate\Support\Str::slug($title));
            $route = $press->route ?: new Route();
            $route->fill([
                'title' => $title,
                'slug' => $slug,
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => Route::where('slug', 'prensa')->value('id'),
                'description' => \Illuminate\Support\Str::limit((string) ($post['plain_text'] ?? ''), 155),
            ]);
            $route->routable()->associate($press);
            $route->save();
        }

        foreach ($this->homeConferenceItems() as $position => $item) {
            $conference = Conferencia::query()->where('external_url', $item['url'])->first() ?: new Conferencia();
            $conferenceImage = $conference->imagen ?: $this->downloadYoutubeThumbnail($item['url']);
            $conference->fill([
                'tipo' => $item['type'],
                'institucion' => $item['institution'],
                'fecha' => $item['date'],
                'descripcion' => '<p>'.e($item['description']).'</p>',
                'imagen' => $conferenceImage ?: $item['image'],
                'external_url' => $item['url'],
                'link_label' => $item['link_label'],
                'destacado' => true,
            ])->save();
            $route = $conference->route ?: new Route();
            $route->fill([
                'title' => $item['title'],
                'slug' => \Illuminate\Support\Str::slug($item['title']),
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => config('cms-routes.agenda_parent_id'),
                'description' => $item['description'],
            ]);
            $route->routable()->associate($conference);
            $route->save();
        }
    }

    private function seedInstitutionalPositions(): void
    {
        $positions = [
            [
                'cargo' => 'Presidenta',
                'institucion' => 'Asociación Argentina de Derecho Constitucional',
                'institutional_url' => 'https://aadconst.org.ar/la-asociacion-argentina-de-derecho-constitucional-renovo-sus-autoridades/',
                'legacy_url' => 'https://aadconst.org.ar/autoridades/',
                'featured' => true,
                'fecha_inicio' => '2025-01-01',
                'fecha_fin' => '2027-12-31',
                'descripcion' => '<p>Reelecta para el período 2025–2027.</p>',
            ],
            [
                'cargo' => 'Miembro del Consejo Directivo',
                'institucion' => 'Facultad de Derecho · Universidad de Buenos Aires',
                'institutional_url' => 'https://www.derecho.uba.ar/institucional/autoridades-derecho.php',
                'legacy_url' => 'https://www.derecho.uba.ar/institucional/consejo-directivo/autoridades-consejo-directivo.php',
                'featured' => true,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'descripcion' => '<p>Integrante del Consejo Directivo de la Facultad de Derecho de la Universidad de Buenos Aires.</p>',
            ],
        ];

        foreach ($positions as $data) {
            $legacyUrl = $data['legacy_url'];
            unset($data['legacy_url']);

            $position = CargoInstitucional::query()
                ->whereIn('institutional_url', [$data['institutional_url'], $legacyUrl])
                ->orWhere('institucion', $data['institucion'])
                ->first() ?: new CargoInstitucional();
            $position->fill($data)->save();

            $route = $position->route ?: new Route();
            $route->fill([
                'title' => $position->cargo.' · '.$position->institucion,
                'slug' => \Illuminate\Support\Str::slug($position->cargo.'-'.$position->institucion),
                'layout' => 'default',
                'status' => Status::Published,
                'parent_id' => config('cms-routes.trayectoria_parent_id'),
                'description' => trim(strip_tags((string) $position->descripcion)),
            ]);
            $route->routable()->associate($position);
            $route->save();
        }
    }

    private function downloadYoutubeThumbnail(string $url): ?string
    {
        if (! preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([^&?/]+)~', $url, $matches)) {
            return null;
        }

        $videoId = $matches[1];
        $path = "images/conferencias/youtube-{$videoId}.jpg";
        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        foreach (["https://i.ytimg.com/vi/{$videoId}/maxresdefault.jpg", "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg"] as $thumbnailUrl) {
            $response = Http::timeout(15)->retry(2, 250)->get($thumbnailUrl);
            if ($response->successful() && strlen($response->body()) > 10_000) {
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        }

        return null;
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
            $route = Route::query()
                ->where('routable_type', Blog::class)
                ->where('slug', $post['slug'])
                ->where('parent_id', $parentRoute->id)
                ->first();

            if (! $route) {
                $blog = Blog::create([
                    'description' => '<p>'.$post['description'].'</p>',
                    'content' => '<p>'.$post['description'].'</p>',
                    'image' => null,
                ]);
                $route = new Route();
                $route->routable()->associate($blog);
            }

            $route->fill([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'layout' => 'default',
                'status' => Status::Published,
                'full_slug' => $parentRoute->full_slug.'/'.$post['slug'],
                'parent_id' => $parentRoute->id,
                'description' => $post['description'],
            ])->save();

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
        $featuredPositions = \App\Models\CargoInstitucional::query()->where('featured', true)->orderBy('id')->pluck('id')->all();

        return [
            // 1. Hero editorial
            $this->block('Hero', [
                'blockTitle' => 'Hero · Apertura editorial',
                'variant' => 'editorial',
                'profile_photo' => null,
                'image_alt' => '',
                'badge' => 'Derecho constitucional · Universidad de Buenos Aires',
                'name' => 'Marcela I. Basterra',
                'subtitle' => 'Profesora Titular de Derecho Constitucional de la Facultad de Derecho, Universidad de Buenos Aires.',
                'description' => 'Doctora en Derecho, referente en derecho constitucional y procesal constitucional, con una trayectoria académica e institucional de alcance internacional.',
                'indicators' => [
                    ['label' => 'Derecho constitucional'],
                    ['label' => 'Actividad académica'],
                ],
                'featured_positions' => $featuredPositions,
                'cta_primary' => array_merge($this->routeRef('sobre-mi'), ['btn_label' => 'CV']),
                'cta_secondary' => array_merge($this->routeRef('publicaciones'), ['btn_label' => 'Ver publicaciones']),
                'cta_tertiary' => array_merge($this->routeRef('actualidad-y-medios'), ['btn_label' => 'Actualidad']),
            ]),
            // 2. Presentación
            $this->block('Intro', [
                'title' => 'Presentación',
                'summary' => '<p>Doctora en Derecho (UBA), Profesora Titular de Derecho Constitucional de la Universidad de Buenos Aires y referente en derecho constitucional y procesal constitucional. Ha ocupado destacados cargos institucionales, dictado conferencias en numerosos países y es autora de más de 50 libros y un centenar de publicaciones especializadas. Su labor ha sido distinguida por la Legislatura de la Ciudad Autónoma de Buenos Aires por su aporte a las Ciencias Jurídicas.</p>',
                'photo' => null,
                'cta_label' => 'Conocer trayectoria completa',
                'cta_route' => $this->routeRef('sobre-mi'),
            ]),
            // 3. Libro más reciente
            $this->block('PublicationsHighlight', [
                'title' => 'Último libro',
                'description' => 'La publicación más reciente de Marcela I. Basterra.',
                'source_mode' => 'latest',
                'libros' => [], 'articulos' => [],
                'max_items' => 1,
                'show_type_label' => true,
                'cta_label' => 'Ver todas las publicaciones',
                'cta_route' => $this->routeRef('publicaciones'),
            ]),
            // 4. Prensa y actualidad
            $this->block('PressFeed', [
                'title' => 'Actualidad y<br>publicaciones recientes',
                'description' => 'Artículos, entrevistas y noticias en medios.',
                'content_types' => ['articulo', 'entrevista', 'noticia'],
                'media' => '', 'selected_items' => [],
                'max_items' => 6,
                'show_filters' => false, 'show_image' => true,
                'empty_message' => 'Próximamente se incorporarán nuevas publicaciones.',
            ]),
            // 5. Conferencias y exposiciones editoriales
            $this->block('EventsListing', [
                'title' => 'Conferencias y exposiciones',
                'description' => 'Intervenciones públicas disponibles en YouTube y en la Facultad de Derecho de la UBA.',
                'conferencias' => Conferencia::query()->where('destacado', true)->orderBy('id')->pluck('id')->all(),
                'manual_items' => [],
            ]),
            // 6. CTA
            $this->block('CTA', [
                'title' => 'Invitaciones académicas, institucionales y de prensa',
                'text' => 'Para conferencias, entrevistas, actividades académicas y colaboraciones institucionales.',
                'button_label' => 'Ponerse en contacto',
                'button_route' => $this->routeRef('contacto'),
            ]),
        ];
    }

    private function homeConferenceItems(): array
    {
        return [
            ['title' => 'Ciclo “Diálogos y argumentación jurídica”: Fallo “Levinas”', 'type' => 'conferencia', 'institution' => 'Derecho UBA', 'date' => null, 'description' => 'Análisis y debate jurídico en la Facultad de Derecho de la Universidad de Buenos Aires.', 'image' => null, 'url' => 'https://www.youtube.com/watch?v=1pJwGJJjtV4&t=150s', 'link_label' => 'Ver conferencia'],
            ['title' => 'La Emergencia y el Derecho Constitucional', 'type' => 'conferencia', 'institution' => 'Facultad de Derecho UNMdP', 'date' => null, 'description' => 'Conversatorio sobre emergencia y derecho constitucional.', 'image' => null, 'url' => 'https://www.youtube.com/watch?v=Dwmrhu54AG0&t=6474s', 'link_label' => 'Ver conversatorio'],
            ['title' => 'Nuevos límites al derecho de acceso a la información pública', 'type' => 'exposicion', 'institution' => 'Derecho UBA', 'date' => null, 'description' => 'Intervención en el ciclo Derecho en Debate.', 'image' => null, 'url' => 'https://www.youtube.com/watch?v=_HYQrDw6F_E', 'link_label' => 'Ver exposición'],
            ['title' => 'Entrevista a Marcela Basterra sobre Derecho Constitucional', 'type' => 'entrevista', 'institution' => 'Vorterix Litoral', 'date' => null, 'description' => 'Entrevista en el programa La Cúpula.', 'image' => null, 'url' => 'https://www.youtube.com/watch?v=tKX1ku8boLw', 'link_label' => 'Ver entrevista'],
            ['title' => 'Comisión de Justicia', 'type' => 'exposicion', 'institution' => 'Senado Argentina', 'date' => '2020-11-10', 'description' => 'Exposición de Marcela Basterra ante la Comisión de Justicia.', 'image' => null, 'url' => 'https://www.youtube.com/watch?v=LnfLLvaGMRg', 'link_label' => 'Ver exposición'],
            ['title' => 'Segundo encuentro internacional de mujeres constitucionalistas', 'type' => 'conferencia', 'institution' => 'Facultad de Derecho UBA', 'date' => '2020-09-10', 'description' => 'Encuentro organizado por Marcela I. Basterra y el Departamento de Derecho Público I.', 'image' => null, 'url' => 'https://www.derecho.uba.ar/noticias/2020/segundo-encuentro-internacional-de-mujeres-constitucionalistas', 'link_label' => 'Ver nota en UBA'],
        ];
    }

    // ─── Sobre mi ──────────────────────────────────────────────

    private function sobreMiBlocks(): array
    {
        $institutionalPositions = CargoInstitucional::query()
            ->whereIn('institutional_url', [
                'https://aadconst.org.ar/la-asociacion-argentina-de-derecho-constitucional-renovo-sus-autoridades/',
                'https://www.derecho.uba.ar/institucional/autoridades-derecho.php',
            ])
            ->orderBy('id')
            ->pluck('id')
            ->all();

        return [
            // 1. Bio / Presentacion
            $this->block('Intro', [
                'blockAnchor' => 'biografia',
                'title' => 'Sobre mí',
                'heading_level' => 'h1',
                'summary' => '<p>Doctora en Derecho (UBA) y Magíster en Derecho Constitucional y Derechos Humanos (UP). Presidenta de la Asociación Argentina de Derecho Constitucional y Vicepresidenta de la Asociación Argentina de Derecho Procesal Constitucional. Ex Presidenta del Consejo de la Magistratura de la Ciudad Autónoma de Buenos Aires. Miembro del Instituto de Política Constitucional de la Academia Nacional de Ciencias Morales y Políticas y del Instituto de Derecho Constitucional de la Academia Nacional de Derecho y Ciencias Sociales.</p><p>Profesora Titular de Derecho Constitucional de la Universidad de Buenos Aires y profesora de grado, posgrado y doctorado en diversas universidades nacionales y extranjeras. Ha dictado clases y conferencias en España, Italia, Chile, Uruguay, Paraguay, Bolivia, Perú, Colombia, Guatemala, Estados Unidos y México.</p><p>Co-Directora Académica del Posgrado de Actualización en Derecho Constitucional y Procesal Constitucional (UBA). Es autora, coautora, directora y participante en 56 libros; ha publicado más de un centenar de artículos especializados y dictado más de un centenar de conferencias.</p>',
                'photo' => 'images/biography/marcela-basterra-1x1-2.png',
                'quote' => 'Comprometida con el fortalecimiento del Estado de Derecho y la defensa de los derechos fundamentales.',
                'quote_author' => 'Marcela I. Basterra',
                'quote_role' => 'Abogada · Académica · Investigadora',
                'cta_label' => null,
                'cta_route' => $this->routeAttrs(null),
            ]),
            // 2. Indicadores de trayectoria
            $this->block('ContentList', [
                'blockAnchor' => 'trayectoria-en-cifras',
                'title' => 'Trayectoria en cifras',
                'description' => 'Una síntesis de la actividad académica, editorial e institucional desarrollada a lo largo de su carrera.',
                'variant' => 'metrics',
                'source_mode' => 'manual',
                'institutional_positions' => [],
                'items' => [
                    ['meta' => '56', 'title' => 'Libros publicados', 'text' => 'Como autora, coautora o directora.', 'url' => null, 'link_label' => null],
                    ['meta' => '+100', 'title' => 'Artículos especializados', 'text' => null, 'url' => null, 'link_label' => null],
                    ['meta' => '+100', 'title' => 'Conferencias', 'text' => 'Nacionales e internacionales.', 'url' => null, 'link_label' => null],
                    ['meta' => '+10', 'title' => 'Universidades', 'text' => 'Nacionales e internacionales donde dicta clases.', 'url' => null, 'link_label' => null],
                    ['meta' => 'UBA', 'title' => 'Profesora Titular de Derecho Constitucional', 'text' => null, 'url' => null, 'link_label' => null],
                    ['meta' => 'Posgrado', 'title' => 'Co-Directora Académica', 'text' => 'Actualización en Derecho Constitucional y Procesal Constitucional · UBA.', 'url' => null, 'link_label' => null],
                ],
            ]),
            // 3. Cargos institucionales reutilizables
            $this->block('ContentList', [
                'blockAnchor' => 'cargos',
                'title' => 'Responsabilidades institucionales actuales',
                'description' => 'Funciones vigentes respaldadas por sus fuentes institucionales.',
                'source_mode' => 'institutional_positions',
                'institutional_positions' => $institutionalPositions,
                'items' => [],
            ]),
            // 4. Reconocimiento en video
            $this->block('MediaText', [
                'layout' => 'left',
                'media_type' => 'youtube',
                'youtube_id' => '0QQH1t2nLWU',
                'video_file' => null,
                'image' => null,
                'title' => 'Personalidad Destacada en Ciencias Jurídicas',
                'content' => '<p>La Legislatura de la Ciudad Autónoma de Buenos Aires declaró a la Dra. Marcela I. Basterra Personalidad Destacada de la Cultura en el ámbito de las Ciencias Jurídicas (Expte. 1774-D-2022).</p>',
                'cta' => $this->routeAttrs(null),
            ]),
            // 5. Acceso al CV; los archivos se cargan desde el CMS.
            $this->block('CVAccess', [
                'blockAnchor' => 'cv',
                'title' => 'Currículum vitae',
                'description' => 'Trayectoria académica, institucional y profesional en dos versiones de consulta.',
                'documents' => [],
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

    // ─── Sample page with all registered blocks ────────────────

    private function sampleBlocks(): array
    {
        $trayectoria = Route::where('slug', 'trayectoria')->where('routable_type', Page::class)->first();
        $publicaciones = Route::where('slug', 'publicaciones')->where('routable_type', Page::class)->first();
        $actualidad = Route::where('slug', 'actualidad-y-medios')->where('routable_type', Page::class)->first();
        $sobreMi = Route::where('slug', 'sobre-mi')->where('routable_type', Page::class)->first();
        $contacto = Route::where('slug', 'contacto')->where('routable_type', Page::class)->first();

        return [
            $this->block('Hero', [
                'profile_photo' => null,
                'badge' => 'Muestra de diseño',
                'name' => 'Marcela Basterra',
                'subtitle' => 'Abogada constitucionalista, académica e investigadora',
                'description' => 'Una página de demostración con contenido ficticio para validar los 17 bloques registrados del CMS.',
                'indicators' => [
                    ['label' => '25 años de trayectoria'],
                    ['label' => '120 publicaciones'],
                    ['label' => '45 conferencias'],
                ],
                'cta_primary' => $this->routeAttrs($trayectoria, 'Ver trayectoria'),
                'cta_secondary' => $this->routeAttrs($publicaciones, 'Ver publicaciones'),
                'cta_tertiary' => $this->routeAttrs($actualidad, 'Actualidad y medios'),
            ]),
            $this->block('Intro', [
                'title' => 'Una mirada sobre el derecho constitucional',
                'summary' => '<p>Este texto es ficticio y representa un resumen biográfico editable desde el CMS. Aquí podría presentarse la trayectoria, las áreas de especialización y la perspectiva profesional.</p>',
                'photo' => null,
                'cta_label' => 'Conocer trayectoria',
                'cta_route' => $this->routeAttrs($sobreMi),
            ]),
            $this->block('BookPresentation', [
                'intro_title' => 'Libros y pensamiento',
                'intro_description' => 'Una selección ficticia de publicaciones para probar la presentación editorial.',
                'items' => [
                    ['title' => 'Constitución y democracia', 'description' => 'Ensayo ficticio sobre instituciones y ciudadanía.', 'image' => null],
                    ['title' => 'Derechos en movimiento', 'description' => 'Investigación ficticia sobre derechos humanos.', 'image' => null],
                    ['title' => 'La palabra pública', 'description' => 'Compilación ficticia de conferencias y entrevistas.', 'image' => null],
                ],
            ]),
            $this->block('CTA', [
                'title' => '¿Querés conocer más?',
                'text' => 'Este es un llamado a la acción de ejemplo para invitaciones, consultas y colaboraciones.',
                'button_label' => 'Ir a contacto',
                'button_route' => $this->routeAttrs($contacto),
            ]),
            $this->block('CVDownload', [
                'title' => 'Trayectoria completa',
                'description' => 'Descargá una versión ficticia del curriculum vitae para probar este bloque.',
                'button_text' => 'Descargar CV de muestra',
            ]),
            $this->block('Cards', [
                'title' => 'Áreas de trabajo',
                'description' => '<p>Tarjetas ficticias para representar distintos ejes de actividad.</p>',
                'items' => [
                    ['label' => 'articulo', 'title' => 'Derecho constitucional', 'description' => 'Análisis de instituciones y garantías.', 'image' => null, 'route' => []],
                    ['label' => 'entrevista', 'title' => 'Debate público', 'description' => 'Participación en conversaciones de actualidad.', 'image' => null, 'route' => []],
                    ['label' => 'libro', 'title' => 'Investigación académica', 'description' => 'Producción y divulgación de conocimiento.', 'image' => null, 'route' => []],
                ],
            ]),
            $this->block('ContactForm', [
                'title' => 'Escribinos',
                'description' => 'Formulario ficticio para consultas generales y propuestas de colaboración.',
                'recipient_email' => 'muestra@example.com',
                'success_message' => 'Mensaje de muestra enviado correctamente.',
                'show_phone' => true,
                'show_subject' => true,
            ]),
            $this->block('EventsHighlight', [
                'title' => 'Próximas actividades',
                'description' => 'El bloque mostrará eventos seleccionados cuando existan registros disponibles.',
                'eventos' => [],
                'max_items' => 3,
                'show_past' => false,
            ]),
            $this->block('FeaturedResources', [
                'title' => 'Recursos destacados',
                'description' => 'Selección ficticia de recursos relacionados.',
                'items' => [],
            ]),
            $this->block('InterviewsHighlight', [
                'title' => 'En los medios',
                'description' => 'Entrevistas ficticias destacadas para probar el listado.',
                'entrevistas' => [],
                'max_items' => 3,
            ]),
            $this->block('Media', [
                'media_type' => 'image',
                'youtube_id' => null,
                'video_file' => null,
                'image' => null,
                'caption' => 'Imagen de muestra para el bloque multimedia.',
            ]),
            $this->block('MediaText', [
                'layout' => 'left',
                'media_type' => 'image',
                'youtube_id' => null,
                'video_file' => null,
                'image' => null,
                'title' => 'Imagen y argumento',
                'content' => '<p>Contenido ficticio para validar la convivencia entre una pieza multimedia y un texto editorial.</p>',
                'cta' => [],
            ]),
            $this->block('PublicationsHighlight', [
                'title' => 'Publicaciones destacadas',
                'description' => 'Libros y artículos ficticios seleccionados para esta muestra.',
                'libros' => [],
                'articulos' => [],
                'max_items' => 4,
                'show_type_label' => true,
            ]),
            $this->block('RelatedResources', [
                'title' => 'También puede interesarte',
                'resource_types' => ['libro', 'articulo', 'entrevista'],
                'tags' => ['constitución', 'democracia'],
                'max_items' => 4,
            ]),
            $this->block('Search', [
                'title' => 'Buscar en el sitio',
                'description' => 'Probá el buscador con una palabra clave.',
            ]),
            $this->block('Text', [
                'eyebrow' => 'Texto de muestra',
                'title' => 'Claridad para explicar lo complejo',
                'content' => '<p>Este bloque contiene texto enriquecido ficticio. Su objetivo es permitir la revisión visual de títulos, párrafos, enlaces y estructura editorial.</p><p>La información definitiva se cargará desde el panel de administración.</p>',
                'image' => null,
                'cta_primary' => [],
                'cta_secondary' => [],
                'width' => 'container',
            ]),
            $this->block('Timeline', [
                'title' => 'Hitos de una trayectoria ficticia',
                'items' => [
                    ['year' => '2008', 'title' => 'Inicio de la actividad académica', 'description' => 'Primer hito de ejemplo.'],
                    ['year' => '2016', 'title' => 'Publicación de una obra colectiva', 'description' => 'Segundo hito de ejemplo.'],
                    ['year' => '2025', 'title' => 'Nueva etapa de investigación', 'description' => 'Tercer hito de ejemplo.'],
                ],
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
