# Jornadas y Congresos

- **Ruta canónica:** `/actividad-academica/jornadas-y-congresos`
- **Ruta anterior:** `/jornadas-y-congresos` → redirección 301
- **Agenda anterior:** `/agenda` → sección `#agenda-y-archivo`

## Composición

1. `Hero` (`portrait`): título, descripción institucional e imagen documental de una exposición académica.
2. `EventsHighlight`: consulta automática del próximo evento o del realizado más reciente.
3. `EventsListing`: agenda y archivo unificados, con filtros combinables por estado, año, país y tipo.

Los contenidos de actividades no se duplican dentro de la página: provienen de los recursos ruteables `Evento` y `Conferencia` mediante `App\Support\EventCatalog`.
