# T5 — Resource: Entrevista

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\Entrevista`
- **Tabla**: `entrevistas`
- **Parent route**: `/prensa`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | |
| medio | string | no | Medio de comunicación |
| fecha | date | no | |
| enlace | string/url | no | Link externo |
| video | string/url | no | URL de video (YouTube/Vimeo) |
| descripcion | textarea/rich | no | |
| destacado | boolean | no | Default false |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_entrevistas_table`
- [ ] Modelo `Entrevista` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.prensa_parent_id')`
- [ ] `EntrevistaResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListEntrevistas`, `CreateEntrevista`, `EditEntrevista`
- [ ] Controller + vista `entrevista/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
