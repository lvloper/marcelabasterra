# Actualidad y Producción Académica

## Ruta

- Actual: `/actualidad-y-produccion-academica`
- Redirección permanente: `/actualidad-y-medios`
- Recurso: `Page` existente, ID 14

## Composición

1. `Hero`: apertura editorial con fotografía institucional existente.
2. `Search`: explorador común por categoría, texto, año y tema.
3. `FeaturedResources`: contenido más reciente del catálogo.
4. `ContentList`: libros y artículos académicos cronológicos.
5. `PressFeed`: noticias institucionales, prensa y entrevistas.
6. `EventsListing` (`activities`): conferencias y actividades, con próximas primero.
7. `PublicationsHighlight` (`library_grid`): biblioteca digital.
8. `EventsListing` (`videos`): archivo audiovisual.

## Fuentes de datos

- `Libro`, `ArticuloAcademico`, `Blog`, `PublicacionMedio`, `Entrevista`, `Conferencia` y `Evento`.
- `AcademicProductionCatalog` normaliza filtros y evita duplicar contenido editorial dentro del JSON de la página.
- `Conferencia` admite además `ubicacion` y `tematica` para completar la ficha cronológica.

## Carga reproducible

```bash
php artisan db:seed --class=Database\\Seeders\\AcademicProductionPageSeeder
```
