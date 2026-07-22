# EventsListing — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|---|---|---:|---|---|
| `title` | text | no | — | Título de la sección. |
| `description` | textarea | no | — | Introducción de la sección. |
| `display_mode` | select | sí | `activities` | `activities` para cronología o `videos` para galería. |
| `include_conferences` | toggle | sí | `true` | Incorpora el recurso `Conferencia` a la agenda. |
| `conferencias` | select multiple | no | `[]` | IDs del recurso `Conferencia`; vacío consulta automáticamente las destacadas. |
| `manual_items` | repeater legado | no | `[]` | Compatibilidad temporal con páginas guardadas antes de crear el recurso. |
| `status` | select | sí | `upcoming` | Consulta automática: `upcoming`, `past` o `all`. |
| `event_types` | checkbox list | no | — | Filtro opcional por tipos de actividad. |
| `selected_events` | select multiple | no | — | IDs de `Evento`; si existe, tiene prioridad sobre la consulta automática. |
| `max_items` | number | sí | `12` | Límite entre 1 y 50 actividades. |
| `show_image` | toggle | sí | `true` | Habilita la imagen de cada actividad. |
| `show_description` | toggle | sí | `true` | Habilita el resumen de cada actividad. |
| `show_filters` | toggle | sí | `true` | Habilita filtros públicos por estado, año, país y tipo. |
| `show_empty_fallback` | toggle | sí | `false` | Permite mostrar un enlace cuando no hay resultados. |
| `fallback_route` | route | no | — | Destino del enlace alternativo. Sólo se usa con fallback activo. |
| `fallback_label` | text | no | `Ver actividades realizadas` | Etiqueta del enlace alternativo. |
| `empty_message` | text | no | `No hay actividades...` | Mensaje cuando la combinación de filtros no devuelve resultados. |

## Contrato de datos

```json
{
  "type": "EventsListing",
  "data": {
    "title": "Próximas actividades",
    "description": "Agenda de participación pública y académica.",
    "status": "upcoming",
    "event_types": ["conferencia", "panel"],
    "selected_events": [],
    "max_items": 12,
    "show_image": true,
    "show_description": true,
    "show_filters": true,
    "show_empty_fallback": false,
    "fallback_route": null,
    "fallback_label": "Ver actividades realizadas"
  }
}
```

## Datos de `Evento`

Además de los campos existentes, cada actividad puede incluir `institucion`, `rol`, `modalidad`, `estado_confirmacion`, `imagen` y `video`. Los estados de confirmación disponibles son `pendiente`, `confirmado` y `cancelado`; las modalidades son `presencial`, `virtual` e `hibrida`.

## Reglas de consulta y renderizado

- Los recursos `Conferencia` seleccionados prevalecen y conservan el orden editorial; sin selección se consultan los destacados por fecha.
- El repeater legado sólo se usa cuando no existen conferencias relacionadas.
- La selección manual de recursos (`selected_events`) prevalece sobre cualquier consulta automática cuando el repeater está vacío.
- `upcoming` consulta desde el día actual y ordena por `fecha_inicio` ascendente.
- `past` consulta fechas anteriores al día actual y ordena por `fecha_inicio` descendente.
- `all` ordena por `fecha_inicio` descendente.
- Las comparaciones de fecha usan la zona configurada por la aplicación (`America/Argentina/Buenos_Aires`).
- Los filtros de tipo se aplican sólo a la consulta automática.
- Cada entrada puede mostrar actividad, institución, ubicación o modalidad, fecha, rol, estado de confirmación y enlaces de inscripción o video cuando existan.
- La variante `activities` muestra próximas actividades primero y luego el archivo por fecha descendente.
- Los filtros públicos operan sobre el conjunto consultado y permiten combinar estado, año, país y tipo de actividad sin duplicar registros en la página.
- `Evento` aporta `ciudad`, `pais` y `tema`; `Conferencia` aporta `ciudad`, `pais` y `tematica` para normalizar metadatos y filtros.
- La variante `videos` consulta conferencias, entrevistas y eventos con video y nunca reproduce automáticamente.
- Si el resultado está vacío, el bloque no renderiza contenido salvo que `show_empty_fallback` esté activo y haya un enlace alternativo configurado.
