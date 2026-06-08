# T1/T2 — Páginas padre para resources

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna

---

## Páginas a crear en el CMS

| # | Nombre | Slug | Ruta | Padre de | Tipo |
|---|--------|------|------|----------|------|
| 1 | Publicaciones | publicaciones | `/publicaciones` | Libro, ArtículoAcadémico | Page |
| 2 | Prensa | prensa | `/prensa` | Entrevista, DossierPrensa | Page |
| 3 | Agenda | agenda | `/agenda` | Evento | Page |
| 4 | Programas | programas | `/programas` | ProgramaAcadémico | Page |
| 5 | Docencia | docencia | `/docencia` | Docencia | Page |
| 6 | Trayectoria | trayectoria | `/trayectoria` | CargoInstitucional | Page |

---

## Pasos

1. Crear cada Page desde `/admin/pages/create` con name y route.
2. Anotar el `route_id` de cada una.
3. Actualizar `config/cms-routes.php` con los IDs:

```php
'publicaciones_parent_id' => <id>,
'prensa_parent_id' => <id>,
'agenda_parent_id' => <id>,
'programas_parent_id' => <id>,
'docencia_parent_id' => <id>,
'trayectoria_parent_id' => <id>,
```

4. `php artisan config:clear`

---

## Checklist

- [ ] Crear Page "Publicaciones" → anotar route_id
- [ ] Crear Page "Prensa" → anotar route_id
- [ ] Crear Page "Agenda" → anotar route_id
- [ ] Crear Page "Programas" → anotar route_id
- [ ] Crear Page "Docencia" → anotar route_id
- [ ] Crear Page "Trayectoria" → anotar route_id
- [ ] Actualizar `config/cms-routes.php` con los 6 IDs
- [ ] `php artisan config:clear`
