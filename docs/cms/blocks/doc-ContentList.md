# ContentList — Props del bloque

## Meta

- **Nombre:** `ContentList`
- **Categoría:** `Listados`
- **Label:** `Listado de contenido`
- **Fuente de verdad:** datos manuales, registros `CargoInstitucional` o el catálogo de publicaciones académicas.
- **Ubicación actual:** Home, inmediatamente después de `Hero`.

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|---|---|---|---|---|
| `title` | `string|null` | no | `null` | Título de la sección. |
| `description` | `string|null` | no | `null` | Introducción breve. |
| `variant` | `string` | sí | `editorial` | `editorial`, `metrics` o `chronological`. |
| `source_mode` | `string` | sí | `manual` | `manual`, `institutional_positions`, `academic_articles` o `academic_publications`. |
| `institutional_positions` | `array<int>` | condicional | `[]` | IDs de cargos; se usa con origen institucional. |
| `items_per_page` | `integer` | condicional | `12` | Entre 6 y 24 publicaciones por página o por carga incremental. |
| `items` | `array` | condicional | `[]` | Ítems editables cuando el origen es manual. |
| `items.*.meta` | `string|null` | no | `null` | Etiqueta o metadato breve. |
| `items.*.title` | `string|null` | no | `null` | Título del ítem. |
| `items.*.text` | `string|null` | no | `null` | Texto descriptivo del ítem. |
| `items.*.url` | `string|null` | no | `null` | Enlace externo opcional. |
| `items.*.link_label` | `string|null` | no | `Ver más` | Etiqueta visible del enlace. |

Los metadatos generales heredados de `PageBlock` (`blockTitle`, `blockAnchor`, espaciado, clases, estilos y visibilidad) no forman parte del contrato editorial específico.

## Contrato de datos

```json
{
  "type": "ContentList",
  "data": {
    "title": "Cargos institucionales",
    "description": null,
    "items": [
      {
        "meta": "Desde 2025",
        "title": "Presidenta",
        "text": "Asociación Argentina de Derecho Constitucional.",
        "url": "https://example.org",
        "link_label": "Visitar institución"
      }
    ]
  }
}
```

## Reglas de renderizado

1. Con `source_mode = institutional_positions`, consulta `CargoInstitucional` y deriva cargo, institución, descripción y fuente sin duplicarlos en la página.
2. Con `source_mode = manual`, conserva el contrato histórico de `items`.
3. Con `source_mode = academic_articles`, delega la consulta a `AcademicArticles`, que carga registros publicados en tandas mediante Livewire y el botón `Ver más`, sin recargar la página.
4. Con `source_mode = academic_publications`, el mismo componente Livewire mezcla libros y artículos sin duplicar su contenido, conserva tipo/año y carga nuevas tandas sin recargar.
6. El bloque se oculta cuando no hay ítems con contenido, salvo el componente Livewire que resuelve su estado desde backend.
7. `meta`, `title`, `text` y enlace son todos opcionales.
8. El enlace externo se abre en una pestaña nueva con la protección `noopener noreferrer`.
9. La variante `metrics` usa `meta` como cifra o concepto destacado y mantiene `title` y `text` como explicación semántica.
10. La variante `chronological` usa `meta` como año y presenta una acción rectangular de descarga.
