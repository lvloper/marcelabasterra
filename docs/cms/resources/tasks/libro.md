# T3 — Resource: Libro

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\Libro`
- **Tabla**: `libros`
- **Parent route**: `/publicaciones`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | Título principal |
| subtitulo | string | no | |
| portada | image | no | Imagen de tapa |
| descripcion | text/rich | no | |
| fecha_publicacion | date | no | |
| editorial | string | no | |
| isbn | string | no | |
| enlaces | json / repeater | no | URL + label |
| destacado | boolean | no | Default false |
| blocks | json | no | Para contenido extra con bloques |

---

## Checklist

- [ ] Migración `create_libros_table`
- [ ] Modelo `Libro` con `HasRoute`, fillable, casts
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.publicaciones_parent_id')`
- [ ] `LibroResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos estructurados personalizados
- [ ] Pages: `ListLibros`, `CreateLibro`, `EditLibro`
- [ ] Controller `LibroController` con vista `libro/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD en `/admin/libros`

---

## Notas de implementación

- `destacado` se usa en `PublicationsHighlightBlock` y `FeaturedResourcesBlock`.
- `enlaces` puede ser un repeater con campos `label` + `url`.
- La vista detail debe mostrar portada, metadatos (editorial, isbn, fecha) y descripción.
