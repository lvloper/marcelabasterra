# Block: Text

## Meta
- **Nombre:** Text
- **Categoría:** Contenido
- **Label:** Texto enriquecido

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `eyebrow` | text | no | — | Volanta / sobre-título |
| `title` | text | no | — | Título principal |
| `content` | rich (avanced) | sí | — | Editor de texto completo con formato avanzado |
| `image` | image | no | — | Imagen opcional, se muestra a la izquierda del contenido en desktop |
| `cta_primary` | route | no | — | Botón principal debajo del texto |
| `cta_secondary` | route | no | — | Botón secundario debajo del texto |
| `width` | toggleButtons | sí | `container` | Opciones: `narrow` (Angosto), `container` (Contenedor), `wide` (Amplio) |

## Comportamiento
- Renderiza un bloque de texto enriquecido con volanta y título opcionales.
- Si se carga una imagen, se muestra a la izquierda del texto en desktop (layout `flex` de 2 columnas). En mobile, la imagen se apila arriba del contenido.
- El campo `width` controla el ancho máximo del contenedor en frontend:
  - `narrow`: ~640px (lectura cómoda).
  - `container`: ~1200px (ancho de página estándar).
  - `wide`: ~1440px (ancho completo).
- El contenido rich text puede incluir párrafos, encabezados, listas, enlaces, y otros formatos avanzados.

## Notas de implementación
- La vista debe renderizar `eyebrow` y `title` como elementos de texto semánticos.
- `content` debe renderizarse con `{!! $content !!}` o un pipe de Blade que procese HTML seguro.
- Aplicar clase CSS según el valor de `width` para limitar el ancho del contenedor.
