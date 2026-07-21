# CVAccess — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|---|---|---:|---|---|
| `title` | `text` | no | — | Título general de la sección. |
| `description` | `textarea` | no | — | Explica las versiones de CV disponibles. |
| `documents` | `repeater` | sí | 2 ítems | Contiene exactamente las versiones completa y reducida. |
| `documents.type` | `select` | sí | — | Identificador único: `full` para el CV completo o `short` para el reducido. |
| `documents.title` | `text` | sí | — | Nombre visible de la versión. |
| `documents.description` | `textarea` | sí | — | Alcance y uso de esa versión. |
| `documents.file` | `file` | sí | — | Archivo PDF, validado como `application/pdf`. |
| `documents.updated_at` | `date` | sí | — | Fecha de última actualización editorial. |
| `documents.view_label` | `text` | sí | `Ver CV` | CTA para abrir el PDF en una nueva pestaña. |
| `documents.download_label` | `text` | sí | `Descargar PDF` | CTA para descargar el PDF. |

## Contrato de datos

```json
{
  "type": "CVAccess",
  "data": {
    "title": "Currículum vitae",
    "description": "Consultá la versión adecuada para cada uso.",
    "documents": [
      {
        "type": "full",
        "title": "CV completo",
        "description": "Trayectoria académica y profesional completa.",
        "file": "pdfs/cv/marcela-basterra-cv-completo.pdf",
        "updated_at": "2026-07-20",
        "view_label": "Ver CV",
        "download_label": "Descargar PDF"
      },
      {
        "type": "short",
        "title": "CV reducido",
        "description": "Síntesis de trayectoria para consulta rápida.",
        "file": "pdfs/cv/marcela-basterra-cv-reducido.pdf",
        "updated_at": "2026-07-20",
        "view_label": "Ver CV",
        "download_label": "Descargar PDF"
      }
    ]
  }
}
```

## Reglas de renderizado

- `documents` debe incluir exactamente dos ítems: uno `full` y uno `short`; no se permiten tipos duplicados.
- Ambos archivos son obligatorios y deben ser PDFs válidos. Si falta uno, el bloque no es publicable.
- La futura vista abre `view_label` en una nueva pestaña y descarga `download_label` usando nombres SEO-legibles: `marcela-basterra-cv-completo.pdf` y `marcela-basterra-cv-reducido.pdf`.
- Debe mostrar `updated_at` como fecha editorial legible.
