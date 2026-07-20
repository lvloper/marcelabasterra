# T11 — Registrar controllers en config

- **Estado**: ⬜ pendiente
- **Depende de**: T3-T10 (controllers creados)

---

## Acciones

Agregar entradas en `config/cms-routes.php` bajo `custom_controllers`:

```php
'App\Models\Libro' => 'App\Http\Controllers\LibroController',
'App\Models\ArticuloAcademico' => 'App\Http\Controllers\ArticuloAcademicoController',
'App\Models\Entrevista' => 'App\Http\Controllers\EntrevistaController',
'App\Models\Evento' => 'App\Http\Controllers\EventoController',
'App\Models\ProgramaAcademico' => 'App\Http\Controllers\ProgramaAcademicoController',
'App\Models\CargoInstitucional' => 'App\Http\Controllers\CargoInstitucionalController',
'App\Models\Docencia' => 'App\Http\Controllers\DocenciaController',
'App\Models\DossierPrensa' => 'App\Http\Controllers\DossierPrensaController',
```

Agregar keys de parent route ID:

```php
'publicaciones_parent_id' => null,
'prensa_parent_id' => null,
'agenda_parent_id' => null,
'programas_parent_id' => null,
'docencia_parent_id' => null,
'trayectoria_parent_id' => null,
```

Los valores `null` se reemplazan con los route_id reales de las páginas padre (T0a).

---

## Checklist

- [ ] Agregar entradas `custom_controllers`
- [ ] Agregar keys de parent route ID
- [ ] `php artisan config:clear`
- [ ] Verificar rutas con `php artisan route:list --path=admin`
