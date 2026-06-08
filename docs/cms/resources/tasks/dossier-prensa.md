# T10 — Resource: Dossier de Prensa

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\DossierPrensa`
- **Tabla**: `dossiers_prensa`
- **Parent route**: `/prensa`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | |
| archivo | file | no | PDF descargable |
| descripcion | textarea/rich | no | |
| fecha | date | no | |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_dossiers_prensa_table`
- [ ] Modelo `DossierPrensa` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.prensa_parent_id')`
- [ ] `DossierPrensaResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListDossiersPrensa`, `CreateDossierPrensa`, `EditDossierPrensa`
- [ ] Controller + vista `dossier-prensa/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
