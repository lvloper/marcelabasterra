# T8 — Resource: Cargo Institucional

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\CargoInstitucional`
- **Tabla**: `cargos_institucionales`
- **Parent route**: `/trayectoria`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| cargo | string | sí | Nombre del cargo |
| institucion | string | no | |
| fecha_inicio | date | no | |
| fecha_fin | date | no | Null = actualidad |
| descripcion | textarea/rich | no | |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_cargos_institucionales_table`
- [ ] Modelo `CargoInstitucional` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.trayectoria_parent_id')`
- [ ] `CargoInstitucionalResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListCargosInstitucionales`, `CreateCargoInstitucional`, `EditCargoInstitucional`
- [ ] Controller + vista `cargo-institucional/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD

---

## Notas

- Ordenar por `fecha_inicio` DESC por defecto.
- Si `fecha_fin` es null, mostrar "Actualidad".
