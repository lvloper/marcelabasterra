<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Status;
use App\Models\Libro;
use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class ImportWordPressBooks extends Command
{
    protected $signature = 'wordpress:import-books {--apply : Guarda los libros; sin esta opción sólo muestra la propuesta}';

    protected $description = 'Importa los libros reales publicados en el WordPress anterior';

    public function handle(): int
    {
        $books = $this->books();

        $this->table(
            ['Año', 'Título', 'Editorial', 'Portada', 'Enlace'],
            array_map(fn (array $book): array => [
                $book['year'],
                $book['title'],
                $book['editorial'],
                'Sí',
                $book['link'] ? 'Sí' : 'No',
            ], $books),
        );

        if (! $this->option('apply')) {
            $this->warn('Vista previa: no se modificó la base. Usá --apply para importar.');

            return self::SUCCESS;
        }

        $parent = Route::whereFullSlug('publicaciones/libros')->first()
            ?: Route::find(config('cms-routes.publicaciones_parent_id'));
        if (! $parent) {
            $this->error('No existe la ruta padre configurada para Publicaciones.');

            return self::FAILURE;
        }

        $placeholders = Libro::doesntHave('route')
            ->where('descripcion', 'like', 'Libro ejemplo %')
            ->orderBy('id')
            ->get();

        try {
            DB::transaction(function () use ($books, $parent, $placeholders): void {
                foreach ($books as $index => $data) {
                    $slug = Str::slug($data['title']);
                    $route = Route::where('parent_id', $parent->id)->where('slug', $slug)->first();
                    $book = $route?->routable;

                    if (! $book instanceof Libro) {
                        $book = $placeholders->get($index) ?? new Libro();
                    }

                    $cover = $this->downloadCover($data['cover'], $slug);

                    $book->fill([
                        'subtitulo' => $data['subtitle'],
                        'portada' => $cover,
                        'descripcion' => $data['description'],
                        'fecha_publicacion' => "{$data['year']}-01-01",
                        'editorial' => $data['editorial'],
                        'isbn' => $data['isbn'],
                        'enlaces' => $data['link'] ? [['label' => 'Más información', 'url' => $data['link']]] : [],
                        'destacado' => $index === 0,
                    ]);
                    $book->save();

                    $routeData = [
                        'title' => $data['title'],
                        'slug' => $slug,
                        'layout' => 'default',
                        'status' => Status::Draft,
                        'parent_id' => $parent->id,
                        'full_slug' => "{$parent->full_slug}/{$slug}",
                        'description' => strip_tags($data['description']),
                        'image' => $cover,
                    ];

                    if ($book->route) {
                        $book->route->update($routeData);
                    } else {
                        $book->route()->create($routeData);
                    }

                    $this->line("Importado: {$data['title']}");
                }
            });
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf('Importación terminada: %d libros reales.', count($books)));

        return self::SUCCESS;
    }

    private function downloadCover(string $url, string $slug): string
    {
        $extension = strtolower(pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
        $path = "libros/{$slug}.{$extension}";

        if (Storage::disk('public')->exists($path)) {
            return $path;
        }

        $response = Http::withUserAgent('MarcelaBasterraMigration/1.0')->timeout(30)->retry(3, 500, throw: false)->get($url);
        if ($response->failed()) {
            throw new RuntimeException("No se pudo descargar la portada {$url}: HTTP {$response->status()}.");
        }

        Storage::disk('public')->put($path, $response->body());

        return $path;
    }

    /** @return array<int, array<string, int|string|null>> */
    private function books(): array
    {
        return [
            [
                'title' => 'Acceso a Información Pública y Transparencia',
                'subtitle' => 'Ley 27.275 y decreto reglamentario 206/17. Comentados, anotados y concordados',
                'description' => 'Prólogo de Jorge Reinaldo Vanossi. Legitimación activa, entrega de información, vías de reclamo, Agencia de Acceso a Información Pública, responsables, transparencia activa y tratados internacionales.',
                'year' => 2017, 'editorial' => 'Editorial Astrea / Editorial Jusbaires', 'isbn' => null, 'link' => null,
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2017/11/MIB_LIBRO-1000x600.jpg',
            ],
            [
                'title' => 'El Proceso Constitucional de Amparo', 'subtitle' => null,
                'description' => 'Buenos Aires, 457 páginas.', 'year' => 2013, 'editorial' => 'Abeledo Perrot', 'isbn' => '978-950-20-2539-1',
                'link' => 'https://thomsonreuters.com.ar/es/tienda/pdp/libros_impresos_ydigitales.html?pid=41554860',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/EL_PROCESO_CONSTITUCIONAL_DE_AMPARO_MIB.jpg',
            ],
            [
                'title' => 'Derecho a la Información vs. Derecho a la Intimidad', 'subtitle' => null,
                'description' => 'Primera edición, Santa Fe, Argentina, 542 páginas.', 'year' => 2012, 'editorial' => 'Rubinzal Culzoni Editores', 'isbn' => '978-987-30-0270-0',
                'link' => 'http://www.rubinzal.com.ar/libros/derecho-a-la-informacion-vs-derecho-a-la-intimidad/3631/',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/DERECHO_A_LA_INFORMACION_VS_DERECHO_A_LA_INTIMIDAD_MIB.jpg',
            ],
            [
                'title' => 'Protección de Datos Personales. Ley 25.326 y Dto. 1558/01 Comentados', 'subtitle' => null,
                'description' => 'Primera edición, Buenos Aires, Argentina y Universidad Nacional Autónoma de México (UNAM), México, 624 páginas.', 'year' => 2008, 'editorial' => 'Editorial Ediar / UNAM', 'isbn' => '978-950-574-244-8',
                'link' => 'http://www.libreriajupiter.com.ar/fichaLibro?bookId=9227',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/PROTECCION_DE_DATOS_PERSONALES_MIB.jpg',
            ],
            [
                'title' => 'El Derecho Fundamental de Acceso a la Información Pública', 'subtitle' => null,
                'description' => 'Buenos Aires, Argentina, 480 páginas.', 'year' => 2006, 'editorial' => 'Editorial Lexis Nexis Argentina S.A.', 'isbn' => '987-592-102-5',
                'link' => 'http://www.redalyc.org/articulo.oa?id=72001619',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/EL_DERECHO_FUNDAMENTAL_DE_ACCESO_A_LA_INFORMACION_PUBLICA_MIB.jpg',
            ],
            [
                'title' => 'Principios de Teoría del Estado y de la Constitución', 'subtitle' => 'Libro en coautoría',
                'description' => 'Obra de Eduardo Graña y César Álvarez, con colaboración especial. Ciudad Autónoma de Buenos Aires.', 'year' => 2003, 'editorial' => 'Editorial Ad-Hoc', 'isbn' => '950-894-383-1', 'link' => null,
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/12/PRINCIPIOS_DE_TEORIA_DEL_ESTADO_Y_DE_LA_CONSTITUCION_01.jpg',
            ],
            [
                'title' => 'Habeas Data y otras Garantías Constitucionales', 'subtitle' => 'Libro en coautoría',
                'description' => 'En colaboración con Alberto R. Dalla Vía. Ciudad de Quilmes, Provincia de Buenos Aires.', 'year' => 1999, 'editorial' => 'Editorial Némesis', 'isbn' => '950-9457-29-9', 'link' => null,
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/12/HABEAS_DATA_Y_OTRAS_GARANTIAS_CONSTITUCIONALES_MARCELA_BASTERRA.jpg',
            ],
            [
                'title' => 'Manual de Teoría del Estado y del Gobierno', 'subtitle' => 'Libro en coautoría',
                'description' => 'En colaboración con Alberto R. Dalla Vía, Eduardo Graña y Nicolás Sisinni. Universidad de Belgrano, Ciudad Autónoma de Buenos Aires.', 'year' => 1999, 'editorial' => 'Fundación Editorial Belgrano', 'isbn' => '950-577-184-3', 'link' => null,
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/12/MANUAL_DE_TEORIA_DEL_ESTADO_Y_DEL_GOBIERNO_MARCELA_BASTERRA.jpg',
            ],
            [
                'title' => 'Constitución de la Ciudad Autónoma de Buenos Aires. Edición Comentada', 'subtitle' => 'Dirección de libro',
                'description' => 'Primera edición, Ciudad Autónoma de Buenos Aires.', 'year' => 2016, 'editorial' => 'Editorial Jusbaires', 'isbn' => '978-987-4057-32-7',
                'link' => 'https://marcelabasterra.com.ar/una-obra-unica-en-materia-de-constitucion-comentada/',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/New-Doc-37-1000x1432.jpg',
            ],
            [
                'title' => 'Tratado Sobre Amparo en el Derecho Federal y Constitucional Provincial. Tomo I y Tomo II', 'subtitle' => 'Dirección de libro',
                'description' => 'Ciudad Autónoma de Buenos Aires.', 'year' => 2014, 'editorial' => 'Abeledo Perrot', 'isbn' => '978-950-20-2649-7',
                'link' => 'https://marcelabasterra.com.ar/presentacion-de-nuevo-tratado-sobre-amparo/',
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/06/Tratado-Sobre-Amparo-en-el-Derecho-Federal-y-Constitucional-Provincial.jpg',
            ],
            [
                'title' => 'El Derecho de Acceso a la Información Pública en Iberoamérica', 'subtitle' => 'Dirección de libro',
                'description' => 'Obra codirigida con Eloy Espinosa Saldaña Barrera. Arequipa, Perú, 306 páginas.', 'year' => 2009, 'editorial' => 'Editorial Adrus', 'isbn' => '978-612-4049-05-07', 'link' => null,
                'cover' => 'https://marcelabasterra.com.ar/wp-content/uploads/2016/12/EL_DERECHO_DE_ACCESO_A_LA_INFORMACION_PUBLICA_MARCELA_BASTERRA.jpg',
            ],
        ];
    }
}
