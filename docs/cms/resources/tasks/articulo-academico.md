# T4 — Resource: Artículo Académico

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\ArticuloAcademico`
- **Tabla**: `articulos_academicos`
- **Parent route**: `/publicaciones`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | |
| resumen | textarea | no | |
| contenido | rich text | no | Cuerpo completo |
| fecha_publicacion | date | no | |
| tematica | string / select | no | Etiqueta temática |
| archivo_pdf | file | no | |
| destacado | boolean | no | Default false |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_articulos_academicos_table`
- [ ] Modelo `ArticuloAcademico` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.publicaciones_parent_id')`
- [ ] `ArticuloAcademicoResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListArticulosAcademicos`, `CreateArticuloAcademico`, `EditArticuloAcademico`
- [ ] Controller + vista `articulo-academico/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
