# Actualidad

## Ruta

- Canónica: `/actualidad`
- Redirecciones permanentes: `/actualidad-y-produccion-academica` y `/actualidad-y-medios`
- Recurso: `Page` existente, ID 14
- Ubicación de navegación: nivel principal `Actualidad`
- Título editorial visible: `Noticias · Prensa · Entrevistas`

## Composición

1. `PressFeed` en variante `archive`: apertura de página y archivo unificado.
2. Buscador textual sobre título, resumen, medio, tema y tipo.
3. Filtros por `Noticias`, `Prensa`, `Entrevistas` y por categorías históricas del legacy.
4. Paginación tradicional de 12 entradas por página.

Los libros, artículos académicos, conferencias, actividades y videos permanecen en sus secciones específicas y no se duplican en esta página.

## Fuentes de datos

- `Blog`, `PublicacionMedio` y `Entrevista`.
- `AcademicProductionCatalog` normaliza los tres orígenes, elimina coincidencias duplicadas por título y fecha, y ordena el archivo por fecha descendente.

## Carga reproducible

```bash
php artisan db:seed --class=Database\\Seeders\\AcademicProductionPageSeeder
```
