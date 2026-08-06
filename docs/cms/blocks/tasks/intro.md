# T17 — IntroBlock

- **Estado**: ✅ completado
- **Depende de**: Ninguna
- **Categoría**: Hero

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| tag | text | no | | Etiqueta editorial sobre el título |
| title | text | no | | Título (ej: "Sobre mí") |
| summary | rich | sí | | Resumen biográfico |
| photo | image | no | | Foto de perfil |
| cta_label | text | no | | Texto del CTA |
| cta_route | route | no | | Ruta del CTA |
| highlights | repeater | no | [] | Datos destacados (number + label) |

---

## Checklist

- [x] Draft → `docs/cms/blocks/draft-biography-summary.md`
- [x] Clase `IntroBlock.php`
- [x] Vista `Intro.blade.php` funcional
- [x] Registrar en `DefaultTemplate.php`
- [x] `php -l` + `view:clear`
- [x] Probar en admin
- [x] Rediseño editorial: foto 5 cols + texto 7 cols + highlights inferiores
- [x] Responsive: foto → contenido → highlights en mobile

---

## Notas

- Se usa en Home (resumen breve) y en Sobre mí (versión completa).
- Layout rediseñado: fotografía más protagonista (col-span-5), tag editorial opcional, highlights de trayectoria en sección aparte.
- Diseño alineado con DESIGN.md: asimetría (5+7), tipografía Bellota Text + Source Serif 4 + Work Sans, sin sombras ni bordes redondeados.
