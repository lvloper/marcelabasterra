# Draft de bloque: CVAccess

## Meta
- **Nombre:** CVAccess
- **Categoría:** Interacción
- **Label:** Accesos al CV

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | — | Título general. |
| description | textarea | no | — | Explica las versiones disponibles. |
| documents | repeater | sí | 2 items | Mínimo y máximo 2; identificadores `full` y `short`. |
| documents.type | select | sí | — | `full` (completo) o `short` (reducido), sin duplicados. |
| documents.title | text | sí | — | Nombre visible. |
| documents.description | textarea | sí | — | Alcance y uso de la versión. |
| documents.file | file | sí | — | PDF; tamaño máximo a definir según política del proyecto. |
| documents.updated_at | date | sí | — | Fecha de última actualización editorial. |
| documents.view_label | text | sí | Ver CV | CTA de visualización en navegador. |
| documents.download_label | text | sí | Descargar PDF | CTA de descarga. |

## Comportamiento
- Presenta exactamente dos versiones: completa y reducida.
- `Ver CV` abre el PDF en navegador en una nueva pestaña; `Descargar PDF` fuerza descarga con nombre estable.
- Muestra la fecha de última actualización.
- Si falta cualquiera de los dos PDFs, el bloque no se considera publicable; en preview CMS debe señalar el faltante.

## Notas de implementación
- Reemplaza el uso de `CVDownload` para el mapa final; ese bloque no almacena archivo ni fecha.
- Usar el componente de archivo existente y validar MIME `application/pdf`.
- Definir nombres de descarga SEO-legibles, por ejemplo `marcela-basterra-cv-completo.pdf`.
- **Estado:** pendiente de revisión y aprobación; no implementar todavía.

