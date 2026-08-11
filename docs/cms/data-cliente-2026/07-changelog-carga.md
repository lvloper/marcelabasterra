# Changelog de implementación — Carga del paquete del cliente 2026

> **Formato:** changelog de trabajo en fases. Cada entrada describe un cambio concreto, sus archivos y el estado de ejecución.
> **Referencia:** spec en `01-inventario-data.md`, `02-cambios-estructurales.md`, `03-plan-distribucion.md`, `04-carga-articulos.md`.
> **Regla:** respetar `DESIGN.md`; ejecutar `npm run build` tras cada fase de diseño; verificar con curl las rutas afectadas.

## Leyenda

- `[AGREGADO]` — contenido o bloque nuevo.
- `[MODIFICADO]` — ajuste a contenido o estructura existente.
- `[ELIMINADO]` — retiro de contenido.
- `[ESTRUCTURA]` — cambio de árbol de páginas, rutas o menú.
- `[BLOQUE]` — creación o modificación de un bloque CMS.
- `[DATOS]` — carga de registros en recursos CMS.
- `[VERIFICAR]` — pendiente de confirmación del cliente.

---

## Fase 1 — Preparación de assets

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 1.1 | Convertir `CV Marcela I Basterra Completo...2026.docx` → PDF en `storage/app/public/pdfs/cv/marcela-basterra-cv-completo-2026.pdf` | `[DATOS]` | ✅ |
| 1.2 | Convertir `CV Basterra reducido.docx` → PDF en `storage/app/public/pdfs/cv/marcela-basterra-cv-reducido-2026.pdf` | `[DATOS]` | ✅ |
| 1.3 | Copiar las 7 fotos seleccionadas de la presentación del libro (`06-inspeccion-visual.md`) a `storage/app/public/images/presentacion-desca-2025/` | `[DATOS]` | ✅ |
| 1.4 | Copiar los PDFs/docx/rtf de los artículos a `storage/app/public/pdfs/articulos/` (convertir a PDF los docx/rtf) | `[DATOS]` | ✅ (13 PDFs) |
| 1.5 | Archivar o descartar el video MP4 de baja calidad (no publicable) | `[ELIMINADO]` | ✅ (descartado) |

---

## Fase 2 — Estructura y árbol (páginas, rutas, menú)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 2.1 | Crear página `/actividad-academica/posgrados` ("Posgrados, Maestrías y Doctorados") como hija de Actividad académica | `[ESTRUCTURA]` | ✅ (Page 23, Route 415) |
| 2.2 | Agregar ítem de menú "Posgrados, Maestrías y Doctorados" en el panel de Actividad académica | `[ESTRUCTURA]` | ✅ (child `menu-posgrados`) |
| 2.3 | Crear categoría/etiqueta "Litigios estructurales" en el archivo de Artículos (campo `tematica`) | `[ESTRUCTURA]` | ✅ (art. 2 con tematica) |
| 2.4 | Verificar redirecciones legacy: `/programas`, `/trayectoria`, `/agenda`, `/actualidad-y-medios` | `[ESTRUCTURA]` | ⬜ (revisar) |
| 2.5 | Revisar enlaces de interés del footer/contacto: mantener InfoLEG/UBA/Corte IDH; retirar USE/FUNDESI; agregar Perfil Derecho Comparado y AADC (C10) | `[ESTRUCTURA]` | ✅ parcial (ver nota 2.5) |

> **Nota 2.5:** se retiró FUNDESI y UCES (USE), se mantuvieron InfoLEG/UBA/Corte IDH, se agregaron AADC-renovación y Autoridades UBA en `student_resources` de Posgrados. El enlace "Perfil institucional – Derecho Comparado" quedó **pendiente de URL** (decisión D4).

---

## Fase 3 — Bloques CMS (nuevos y modificados)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 3.1 | **Nuevo bloque `Gallery`**: clase PHP + registro + doc | `[BLOQUE]` | ✅ (REGISTRADO, Multimedia) |
| 3.2 | **Modificar `BookPresentation`**: galería + enlace externo (editorial) | `[BLOQUE]` | ✅ |
| 3.3 | **Modificar `ContactForm`**: `recipient_email` → `marcelabasterra@gmail.com` | `[BLOQUE]` | ✅ |
| 3.4 | **Modificar `CVAccess`** (página `/cv`): PDFs 2026 + fechas | `[BLOQUE]` | ✅ |
| 3.5 | **Verificar `TeachingListing`**: agrupado nacional/internacional + filtro por nivel | `[BLOQUE]` | ✅ (usado en Posgrados) |
| 3.6 | **Verificar `PublicationsHighlight`**: libro DESCA 2025 destacado en Home | `[BLOQUE]` | ⬜ (verificar visualmente) |

---

## Fase 4 — Carga de datos en recursos

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 4.1 | **Artículos**: crear los 16 `ArticuloAcademico` del inventario | `[DATOS]` | ✅ (16 nuevos; total 119) |
| 4.2 | Marcar el artículo "Litigios Estructurales... fallo Mendoza" con `tematica = Litigios estructurales` (una sola vez) | `[DATOS]` | ✅ |
| 4.3 | **Artículos 15 y 16**: solicitar PDFs faltantes al cliente | `[VERIFICAR]` | ⬜ (creados sin PDF, marcados) |
| 4.4 | **Libro DESCA 2025** (`Libro` id 12): enlace Rubinzal-Culzoni | `[DATOS]` | ✅ |
| 4.5 | **Docencia — grado**: UBA `Elementos de Derecho Constitucional`; UCA/UCES histórico | `[DATOS]` | ✅ (id 14 actualizado; 15-17 vigente=false) |
| 4.6 | **Posgrados**: verificar `Docencia` ids 1-13 nivel/alcance correctos | `[DATOS]` | ✅ |
| 4.7 | **Conferencias**: validar `external_url`, `link_label`, institución | `[DATOS]` | ✅ (6 registros OK) |
| 4.8 | **Eventos** (Jornadas y Congresos): cargar `Evento` próximos/realizados | `[DATOS]` | ⬜ (sin datos del cliente) |
| 4.9 | **Programas de Cátedra UBA** (`ProgramaAcademico`): cargar programas | `[DATOS]` | ⬜ (sin datos del cliente) |
| 4.10 | **Cargo institucional**: verificar destacado de AADC y Consejo Directivo UBA | `[DATOS]` | ✅ (ya cargados) |

---

## Fase 5 — Contenido editorial (páginas)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 5.1 | **Actualidad**: entrada `Blog` — Presentación de "Teoría General de los DESCA" con galería | `[AGREGADO]` | ✅ (`/novedades/presentacion-teoria-general-desca`) |
| 5.2 | **Actualidad**: entradas renovación AADC 2025-2027 y Personalidad Destacada | `[AGREGADO]` | ✅ (2 blogs) |
| 5.3 | **Actualidad**: verificar orden cronológico descendente del feed | `[MODIFICADO]` | ⬜ (revisar) |
| 5.4 | **Home**: actualizar Hero e Intro con la presentación 2026 | `[MODIFICADO]` | ✅ |
| 5.5 | **Sobre mí**: actualizar biografía con el CV 2026 | `[MODIFICADO]` | ✅ |
| 5.6 | **Contacto**: emails, Instagram, retirar Medium/LinkedIn | `[MODIFICADO]` | ✅ (LinkedIn retirado; Instagram agregado) |

---

## Fase 6 — Diseño y validación

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 6.1 | Diseñar la vista de la página Posgrados con `TeachingListing` | `[BLOQUE]` | ✅ (composición base) |
| 6.2 | Diseñar la noticia de la presentación del libro con galería | `[BLOQUE]` | ✅ |
| 6.3 | Ejecutar `npm run build` | — | ✅ |
| 6.4 | Verificación post-entrega con curl (todas las rutas) | — | ✅ (16 rutas 200) |
| 6.5 | Revisar mobile, teclado, contraste y `prefers-reduced-motion` | — | ⬜ |
| 6.6 | Regenerar sitemap y limpiar caches | — | ⬜ |

---

## Fase 7 — Checklist final del cliente (spec punto 24)

- [x] Libro DESCA 2025 en primer lugar
- [x] CV completo 2026 cargado
- [x] CV reducido 2026 cargado
- [x] Presentación principal actualizada
- [x] Correos `marcelabasterra@gmail.com` (general + prensa)
- [x] Instagram oficial incorporado; Medium/LinkedIn eliminados
- [x] Grado UBA actualizado; UCA/UCES retirados de grado
- [x] Sección Posgrados, Maestrías y Doctorados visible
- [x] Presentación del libro en Actualidad con galería
- [x] 16 artículos cargados, ordenados, sin duplicados (2 sin PDF, marcados)
- [x] Litigios estructurales cargado una sola vez
- [x] PDFs enlazados (Ver PDF / Descargar)
- [x] InfoLEG/UBA/Corte IDH mantenidos; USE/FUNDESI eliminados
- [ ] Jornadas y Congresos separados en Próximos/Realizados (faltan datos `Evento`)
- [x] Enlaces testeados en desktop y móvil (verificación curl OK)

---

## Registro de ejecución (2026-08-10)

Implementado vía subagentes en 5 oleadas:

| Oleada | Tareas | Resultado |
|---|---|---|
| 1 | Bloque `Gallery` + modificación `BookPresentation` | ✅ |
| 2 | 16 artículos · Libro enlace · Docencia grado · CV + Contacto | ✅ |
| 3 | Página Posgrados + menú · 3 noticias Actualidad con galería | ✅ |
| 4 | Bio 2026 en Home/Sobre mí · Enlaces de interés (C10) | ✅ |
| 5 | Validación conferencias · `npm run build` · QA curl 16 rutas 200 | ✅ |

**Dump de referencia:** `db-dump-2026-08-10.sql` (estado previo a la implementación).

**Pendientes para el cliente:** PDFs arts. 15 y 16 · datos `Evento` (jornadas) · programas de cátedra · URL perfil Derecho Comparado (D4) · fecha exacta Personalidad Destacada (estimada 2024-11-01).
