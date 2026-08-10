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
| 1.1 | Convertir `CV Marcela I Basterra Completo...2026.docx` → PDF en `storage/app/public/pdfs/cv/marcela-basterra-cv-completo-2026.pdf` | `[DATOS]` | ⬜ |
| 1.2 | Convertir `CV Basterra reducido.docx` → PDF en `storage/app/public/pdfs/cv/marcela-basterra-cv-reducido-2026.pdf` | `[DATOS]` | ⬜ |
| 1.3 | Copiar las 7 fotos seleccionadas de la presentación del libro (`06-inspeccion-visual.md`) a `storage/app/public/images/presentacion-desca-2025/` | `[DATOS]` | ⬜ |
| 1.4 | Copiar los PDFs/docx/rtf de los artículos a `storage/app/public/pdfs/articulos/` (convertir a PDF los docx/rtf) | `[DATOS]` | ⬜ |
| 1.5 | Archivar o descartar el video MP4 de baja calidad (no publicable) | `[ELIMINADO]` | ⬜ |

---

## Fase 2 — Estructura y árbol (páginas, rutas, menú)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 2.1 | Crear página `/actividad-academica/posgrados` ("Posgrados, Maestrías y Doctorados") como hija de Actividad académica | `[ESTRUCTURA]` | ⬜ |
| 2.2 | Agregar ítem de menú "Posgrados, Maestrías y Doctorados" en el panel de Actividad académica (validar jerarquía con `DESIGN.md §18.3` — decisión D1) | `[ESTRUCTURA]` | ⬜ |
| 2.3 | Crear categoría/etiqueta "Litigios estructurales" en el archivo de Artículos (campo `tematica`) | `[ESTRUCTURA]` | ⬜ |
| 2.4 | Verificar redirecciones legacy: `/programas`, `/trayectoria`, `/agenda`, `/actualidad-y-medios` | `[ESTRUCTURA]` | ⬜ |
| 2.5 | Revisar enlaces de interés del footer/contacto: mantener InfoLEG/UBA/Corte IDH; retirar USE/FUNDESI; agregar Perfil Derecho Comparado y AADC (C10) | `[ESTRUCTURA]` | ⬜ |

---

## Fase 3 — Bloques CMS (nuevos y modificados)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 3.1 | **Nuevo bloque `Gallery`**: registrar la vista existente `resources/views/blocks/Gallery.blade.php` (Swiper carrousel) como bloque CMS con clase PHP y fields (imágenes + opciones de carrousel) | `[BLOQUE]` | ⬜ |
| 3.2 | **Modificar `BookPresentation`**: ampliar a galería de fotos de la presentación + enlace Rubinzal-Culzoni (reutilizar para la noticia del libro; hoy es demo técnica en `/muestra-bloques`) | `[BLOQUE]` | ⬜ |
| 3.3 | **Modificar `ContactForm`**: actualizar `recipient_email` → `marcelabasterra@gmail.com` en el bloque de la página Contacto | `[BLOQUE]` | ⬜ |
| 3.4 | **Modificar `CVAccess`** (página `/cv`): cargar los 2 PDFs 2026, actualizar `updated_at`, descripciones y labels | `[BLOQUE]` | ⬜ |
| 3.5 | **Verificar `TeachingListing`**: confirmar que soporta el agrupado "Universidades nacionales / internacionales" por `alcance` + filtro por nivel para la página de Posgrados (ya implementado en Blade) | `[BLOQUE]` | ⬜ |
| 3.6 | **Verificar `PublicationsHighlight`**: confirmar que el libro DESCA 2025 se muestra como destacado en Home | `[BLOQUE]` | ⬜ |

**Nota:** no se requiere un bloque nuevo para Posgrados ni para Litigios estructurales: se reutilizan `TeachingListing` (con filtros de nivel/alcance) y la categoría `tematica` de Artículos.

---

## Fase 4 — Carga de datos en recursos

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 4.1 | **Artículos**: crear los 16 `ArticuloAcademico` del inventario (tabla completa en `04-carga-articulos.md`) con título, temática, fecha, cita y PDF enlazado | `[DATOS]` | ⬜ |
| 4.2 | Marcar el artículo "Litigios Estructurales... fallo Mendoza" con `tematica = Litigios estructurales` (cargar **una sola vez**, aunque el archivo llegó en dos copias) | `[DATOS]` | ⬜ |
| 4.3 | **Artículos 15 y 16**: `[VERIFICAR]` — solicitar PDFs faltantes al cliente | `[VERIFICAR]` | ⬜ |
| 4.4 | **Libro DESCA 2025** (`Libro` id 12): agregar enlace Rubinzal-Culzoni en `enlaces`; confirmar orden como primero | `[DATOS]` | ⬜ |
| 4.5 | **Docencia — grado**: actualizar id 14 a `Elementos de Derecho Constitucional` (UBA, Prof. Titular, cátedra Basterra); marcar históricos ids 15-17 (UCA/UCES) | `[DATOS]` | ⬜ |
| 4.6 | **Posgrados**: verificar que `Docencia` ids 1-13 tengan `nivel`/`institucion_academica_id`/`alcance` correctos para el agrupado nacional/extranjera (rutas `institucion-uba`… ya existen) | `[DATOS]` | ⬜ |
| 4.7 | **Conferencias**: validar `external_url`, `link_label`, institución y año de los 6 registros (formato miniatura + "Ver video") | `[DATOS]` | ⬜ |
| 4.8 | **Eventos** (Jornadas y Congresos): cargar `Evento` próximos/realizados (nombre, institución, lugar, fecha, rol) — `[VERIFICAR]` si el cliente provee datos | `[DATOS]` | ⬜ |
| 4.9 | **Programas de Cátedra UBA** (`ProgramaAcademico`, hoy vacío): cargar programas — `[VERIFICAR]` fuente de datos | `[DATOS]` | ⬜ |
| 4.10 | **Cargo institucional**: verificar destacado de AADC Presidenta 2025-2027 (id 1) y Consejo Directivo UBA (id 2) en Home/Sobre mí | `[DATOS]` | ⬜ |

---

## Fase 5 — Contenido editorial (páginas)

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 5.1 | **Actualidad**: crear entrada `Blog` — *Presentación de "Teoría General de los DESCA"* (Facultad de Derecho UBA) con imagen principal + galería (`Gallery`/`BookPresentation`) + fecha 2025 | `[AGREGADO]` | ⬜ |
| 5.2 | **Actualidad**: crear entradas para la renovación de autoridades AADC 2025-2027 y Personalidad Destacada (con video YouTube) | `[AGREGADO]` | ⬜ |
| 5.3 | **Actualidad**: verificar orden cronológico descendente del feed (2026 → 2025), archivo histórico no domina portada | `[MODIFICADO]` | ⬜ |
| 5.4 | **Home**: actualizar Hero e Intro con la presentación 2026 (fuente `Presentación.2026 docx.docx`) | `[MODIFICADO]` | ⬜ |
| 5.5 | **Sobre mí**: actualizar biografía, trayectoria en cifras y reconocimientos con el CV 2026 | `[MODIFICADO]` | ⬜ |
| 5.6 | **Contacto**: actualizar emails (general y prensa → `marcelabasterra@gmail.com`), agregar Instagram, retirar Medium/LinkedIn | `[MODIFICADO]` | ⬜ |

---

## Fase 6 — Diseño y validación

| # | Cambio | Tipo | Estado |
|---|---|---|---|
| 6.1 | Diseñar la vista de la página Posgrados con `TeachingListing` (agrupado nacional/extranjera) respetando `DESIGN.md` | `[BLOQUE]` | ⬜ |
| 6.2 | Diseñar la noticia de la presentación del libro con galería (`Gallery`/`BookPresentation`) | `[BLOQUE]` | ⬜ |
| 6.3 | Ejecutar `npm run build` | — | ⬜ |
| 6.4 | Verificación post-entrega con curl: `/`, `/sobre-mi`, `/publicaciones`, `/actualidad`, `/cv`, `/contacto`, `/actividad-academica/posgrados` | — | ⬜ |
| 6.5 | Revisar mobile, teclado, contraste y `prefers-reduced-motion` | — | ⬜ |
| 6.6 | Regenerar sitemap y limpiar caches | — | ⬜ |

---

## Fase 7 — Checklist final del cliente (spec punto 24)

- [x] Libro DESCA 2025 en primer lugar
- [ ] CV completo 2026 cargado
- [ ] CV reducido 2026 cargado
- [ ] Presentación principal actualizada
- [ ] Correos `marcelabasterra@gmail.com` (general + prensa)
- [ ] Instagram oficial incorporado; Medium/LinkedIn eliminados
- [ ] Grado UBA actualizado; UCA/UCES retirados de grado
- [ ] Sección Posgrados, Maestrías y Doctorados visible
- [ ] Presentación del libro en Actualidad con galería
- [ ] 16 artículos cargados, ordenados, sin duplicados
- [ ] Litigios estructurales cargado una sola vez
- [ ] PDFs enlazados (Ver PDF / Descargar)
- [ ] InfoLEG/UBA/Corte IDH mantenidos; USE/FUNDESI eliminados
- [ ] Jornadas y Congresos separados en Próximos/Realizados
- [ ] Enlaces testeados en desktop y móvil

---

## Decisiones que destraban la carga

| # | Decisión | Bloquea |
|---|---|---|
| D1 | Jerarquía de Posgrados: hija de Actividad académica (recomendado) vs. nivel principal | Fase 2.2 |
| D2 | Grado UCA/UCES: histórico vs. retirado | Fase 4.5 |
| D3 | Equipo docente: nómina UBA vigente vs. eliminar ítem | Fase 5.5 |
| D4 | URL del perfil Derecho Comparado | Fase 2.5 |
| D5 | URL de la ficha Rubinzal-Culzoni del libro | Fase 4.4 |
| D6 | PDFs de artículos 15 y 16 | Fase 4.3 |
| D7 | Datos de Eventos y Programas de Cátedra | Fase 4.8, 4.9 |
