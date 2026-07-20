# T7 — Resource: Programa Académico

- **Estado**: ⬜ pendiente
- **Depende de**: T1, T2 (páginas padre)
- **Modelo**: `App\Models\ProgramaAcademico`
- **Tabla**: `programas_academicos`
- **Parent route**: `/programas`

---

## Campos

| Campo | Tipo | Requerido | Notas |
|-------|------|-----------|-------|
| titulo | string → route.title | sí | |
| descripcion | textarea/rich | no | |
| institucion | string | no | |
| fecha_inicio | date | no | |
| fecha_fin | date | no | |
| enlace | string/url | no | |
| blocks | json | no | |

---

## Checklist

- [ ] Migración `create_programas_academicos_table`
- [ ] Modelo `ProgramaAcademico` con `HasRoute`
- [ ] `getDefaultRouteParentId()` → `config('cms-routes.programas_parent_id')`
- [ ] `ProgramaAcademicoResource` extendiendo `ResourceBase`
- [ ] `mainTab()` con campos personalizados
- [ ] Pages: `ListProgramasAcademicos`, `CreateProgramaAcademico`, `EditProgramaAcademico`
- [ ] Controller + vista `programa-academico/show.blade.php`
- [ ] Registrar en `config/cms-routes.php`
- [ ] `php artisan migrate`
- [ ] Probar CRUD
