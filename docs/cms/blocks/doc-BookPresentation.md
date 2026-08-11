# Block: BookPresentation

## Meta
- **Nombre:** BookPresentation
- **Categoría:** Contenido
- **Label:** Presentación de libro

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `intro_title` | text | sí | — | Título de introducción del bloque |
| `intro_description` | textarea | no | — | Descripción de introducción (3 filas) |
| `items` | repeater | no | 3 items (máx. 3) | Cards de presentación. Cada item: `title` (text, requerido), `description` (textarea), `image` (image 400x400 en `images/libros`) |
| `gallery` | gallery (FileUpload múltiple) | no | — | Galería de fotos del evento de presentación, se suben a `images/presentaciones` (resize 1280x640) |
| `external_url` | text | no | — | URL externa a la editorial (se abre en pestaña nueva) |
| `external_label` | text | no | `Ver obra en la editorial` | Texto del botón externo |

## Comportamiento
- Renderiza un encabezado de introducción (título + descripción) opcional sobre un fondo `primary`.
- Debajo, hasta 3 cards con imagen y descripción distribuidas en 3 columnas desktop / 1 columna mobile.
- Si `gallery` tiene imágenes, se renderiza un carrusel Swiper reutilizando la vista `blocks.Gallery` con estilo `full`.
- Si `external_url` existe, se renderiza un botón/link al final del bloque que abre la URL en `target="_blank"` (con `rel="noopener noreferrer"`). Usa `external_label` como texto o el default `Ver obra en la editorial`.
- Los nuevos campos (`gallery`, `external_url`, `external_label`) son todos opcionales: el bloque funciona igual con solo los campos originales.
- Título y descripción de las cards, la introducción y las imágenes tienen animaciones de entrada GSAP con ScrollTrigger.

## Contrato de datos (JSON)

```json
{
  "blockTitle": "Presentación — Obra de Marcela",
  "intro_title": "Presentación de la obra",
  "intro_description": "Encuentro con la autora en la Feria del Libro.",
  "items": [
    {
      "title": "La obra",
      "description": "Sinopsis del libro.",
      "image": "images/libros/obra.jpg"
    }
  ],
  "gallery": [
    "images/presentaciones/foto-01.jpg",
    "images/presentaciones/foto-02.jpg",
    "images/presentaciones/foto-03.jpg"
  ],
  "external_url": "https://www.rubinzalculzoni.com/obra",
  "external_label": "Ver obra en Rubinzal-Culzoni"
}
```

## Notas de implementación
- `gallery` llega como array de strings (rutas públicas de las imágenes subidas). Pasar tal cual a `@include('blocks.Gallery', ['images' => $gallery, 'style' => 'full'])`; la vista Gallery resuelve cada ruta con `Storage::url`.
- No renderizar la galería si `gallery` está vacío (`@if (!empty($gallery))`).
- El botón externo usa las mismas clases del design system que los botones de `CTA.blade.php` (fondo blanco, texto `primary`, hover invertido).
- `external_url` debe ser una URL absoluta con protocolo (`https://...`) para que el `target="_blank"` sea seguro.
