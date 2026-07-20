# T6 — Resource: Evento

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\Evento`
- **Tabla**: `eventos`
- **Parent route**: `/agenda`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | |
| descripcion | textarea/rich | no | |
| fecha_inicio | datetime | sí | |
| fecha_fin | datetime | no | |
| ubicacion | string | no | |
| tipo | string/select | no | Tipo de evento |
| enlace_inscripcion | string/url | no | |
| destacado | boolean | no | Default false |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_eventos_table`
- [ ] Modelo `Evento` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.agenda_parent_id')`
- [ ] `EventoResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListEventos`, `CreateEvento`, `EditEvento`
- [ ] Controller + vista `evento/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
