# T9 — Resource: Docencia

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\Docencia`
- **Tabla**: `docencias`
- **Parent route**: `/docencia`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| institucion | string | no | |
| materia | string | no | |
| catedra | string | no | |
| nivel | string/select | no | Grado, posgrado, etc. |
| descripcion | textarea/rich | no | |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_docencias_table`
- [ ] Modelo `Docencia` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.docencia_parent_id')`
- [ ] `DocenciaResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListDocencias`, `CreateDocencia`, `EditDocencia`
- [ ] Controller + vista `docencia/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
