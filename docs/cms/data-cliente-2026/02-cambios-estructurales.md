# Cambios estructurales requeridos por el paquete del cliente

**Fecha:** 2026-08-10.
**Regla:** ante conflicto entre la propuesta del cliente y `DESIGN.md`, prevalece `DESIGN.md` (AGENTS.md). El design system establece un header reducido con 2-3 accesos prioritarios + panel amplio "Ver más"; el menú propuesto por el cliente (13 ítems planos) se adapta a esa regla sin perder contenido.

## Resumen de cambios estructurales

| # | Cambio | Tipo | Alcance |
|---|---|---|---|
| C1 | Sección propia de **Posgrados, Maestrías y Doctorados** visible en menú | Estructural nuevo | Nueva página hija bajo Actividad académica |
| C2 | **Grado UBA solo**; retirar UCA y UCES del bloque de grado | Modificación de contenido | `Docencia` + vista Docencia |
| C3 | **CV dual** (completo + reducido 2026) | Actualización de contenido | Bloque `CVAccess` en `/cv` |
| C4 | **Libro DESCA 2025** en primer lugar + enlace Rubinzal-Culzoni | Actualización de contenido | `Libro` id 12 |
| C5 | **Cargar 16 artículos** con PDFs (inventario 2026) | Carga de contenido | `ArticuloAcademico` |
| C6 | Nueva sección/categoría **"Litigios estructurales"** | Estructural menor | Categoría dentro de Artículos o página |
| C7 | **Actualidad**: priorizar 2026/2025 + presentación del libro con galería | Contenido | `Blog` / `PublicacionMedio` |
| C8 | **Conferencias**: formato miniatura + título + institución + año + "Ver video" | Diseño/validación | `Conferencia` |
| C9 | **Contacto y prensa**: unificar email `marcelabasterra@gmail.com`; Instagram; retirar Medium/LinkedIn | Configuración | Bloques de contacto, footer, config |
| C10 | **Enlaces de interés**: mantener InfoLEG/UBA/Corte IDH; retirar USE y FUNDESI | Contenido | Enlaces en página/contacto |
| C11 | **Presentación principal** actualizada con bio 2026 | Contenido | Hero/Intro |
| C12 | **Programas de Cátedra UBA** separados de Posgrados | Estructural menor | `ProgramaAcademico` o ancla Docencia |
| C13 | **Jornadas y Congresos**: separar Realizados/Próximos | Estructural menor | Página + `Evento` |

---

## C1 — Sección "Posgrados, Maestrías y Doctorados"

**Pedido del cliente (spec punto 6):** "Sección obligatoria... claramente visible en el menú, organizada en Universidades Nacionales y Universidades Extranjeras".

**Estado actual:** los 13 registros que coinciden con el docx ya existen en `Docencia` (ids 1-13), pero hoy viven como anclas (`#posgrados`, `#doctorado`, `#programas`) dentro de la página Docencia. No hay página propia.

**Propuesta de implementación:**

- Crear página hija `/actividad-academica/posgrados` (o `/actividad-academica/posgrados-maestrias-doctorados`).
- Usar `TeachingListing` o listado derivado de `Docencia` con filtro por nivel (`posgrado`, `maestria`, `doctorado`), agrupado visualmente en **Universidades Nacionales** y **Extranjeras**.
- Menú: ítem visible dentro del panel de Actividad académica; evaluar acceso prioritario dado el requisito de visibilidad del cliente (validar contra `DESIGN.md §18.3`).
- No duplicar datos: los registros se mantienen en `Docencia` (AGENTS.md: "No duplicar entidades CMS").

**Alternativa de decisión** (ver `05-pendientes-decisiones.md`): el cliente propone "Posgrados, Maestrías y Doctorados" como ítem de nivel principal. Requiere confirmar jerarquía (¿hija de Actividad académica o nivel propio?).

## C2 — Grado UBA únicamente

**Pedido:** eliminar toda referencia a UCA y UCES en la sección de grado; dejar solo UBA con el orden: UBA → Facultad de Derecho → Carrera de Abogacía → Elementos de Derecho Constitucional → Profesora Titular → Cátedra Basterra.

**Estado actual en DB (`Docencia`):**

| id | institucion | nivel | materia |
|---|---|---|---|
| 14 | Universidad de Buenos Aires | grado | Bases Constitucionales del Derecho Privado |
| 15 | UCA | grado | Régimen Jurídico de la Información |
| 16 | UCES | grado | Derecho Constitucional (I) Político |
| 17 | UCES | grado | Derecho Constitucional Político |

**Acción:**

- Actualizar id 14: materia = `Elementos de Derecho Constitucional`, rol = `Profesora Titular`, cátedra = `Marcela Izascum Basterra`, facultad = `Facultad de Derecho`, carrera = `Abogacía`.
- Marcar ids 15-17 como histórico o retirarlos de la vista de grado (no eliminar de la DB: la posgrado de UCA se mantiene en C1).

## C3 — CV dual 2026

**Pedido (spec puntos 2 y checklist):** CV Completo 2026 y CV Reducido 2026, separados, ambos abribles/descargables.

**Acción:**

1. Convertir `CV Marcela I Basterra Completo y actualizado al 01-01-2026.docx` → PDF.
2. Convertir `CV Basterra reducido.docx` → PDF.
3. Subir a `storage/app/public/pdfs/cv/` como `marcela-basterra-cv-completo-2026.pdf` y `marcela-basterra-cv-reducido-2026.pdf`.
4. Actualizar el bloque `CVAccess` de la página `/cv` con los dos archivos, fechas `2026-01-01` y descripciones correctas.

## C4 — Libro DESCA 2025

**Pedido (spec puntos 5 y 7):** libro más reciente primero, tratamiento destacado, botón "Ver obra en Rubinzal-Culzoni".

**Estado actual:** `Libro` id 12 ya tiene título, autoría, editorial, año, tomos con páginas/ISBN e `destacado=1`. Campo `enlaces = null`.

**Acción:** agregar el enlace a la ficha oficial en Rubinzal-Culzoni en `enlaces` del `Libro` id 12. Verificar ordenamiento de libros por fecha descendente.

## C5 — Artículos 2026

**Pedido (spec punto 9 + listado):** cargar 16 artículos con formato título completo · autora · revista/editorial · año · cita · botón VER PDF / DESCARGAR, ordenados del más reciente al más antiguo. Crear además la sección "Litigios estructurales" (punto 10).

**Estado actual:** 103 artículos legacy cargados; ninguno de los 16 del inventario existe. Se aportan archivos para ~13-14; faltan archivos para los arts. 15 y 16 (ver `01-inventario-data.md` y `04-carga-articulos.md`).

**Acción:** crear los 16 registros `ArticuloAcademico` nuevos (o los que tengan archivo) con PDF subido y metadata de cita. Implementar la categoría `litigios-estructurales` (campo `tematica` ya existe en `articulos_academicos`).

## C6 — Litigios estructurales

**Pedido (spec punto 10):** sección temática específica o categoría destacada dentro de Artículos.

**Acción recomendada:** usar el campo `tematica` existente en `ArticuloAcademico` con el valor `Litigios estructurales` y exponerlo como filtro/categoría en el índice de artículos. No crear una base de datos nueva. El artículo de "Litigios Estructurales... fallo Mendoza" se carga una sola vez (la spec advierte que llegó en dos copias equivalentes).

## C7 — Actualidad

**Pedido (spec puntos 8, 12, 22):** priorizar 2026 → 2025; presentación del libro como noticia principal con imagen principal + galería de 5-6 fotos; actividad institucional AADC; Personalidad Destacada; presencia en medios; Instagram como fuente visible.

**Estado actual:** la página `/actualidad` ya consume `Blog` + `PublicacionMedio` + `Entrevista` vía `PressFeed` (variante archive) con filtros.

**Acción:**

- Crear entrada `Blog`/noticia: *Presentación de "Teoría general de los DESCA"* en Facultad de Derecho UBA, con imagen principal + galería (usar las 60 fotos de WhatsApp tras inspección visual).
- Cargar/verificar noticias de la AADC y de Personalidad Destacada.
- Verificar el orden cronológico descendente del feed.

## C8 — Conferencias

**Pedido (spec puntos 11 y 18):** sección audiovisual con miniatura + título + institución + año + botón "Ver video"; incorporar los 5 links de YouTube + Segundo Encuentro Internacional de Mujeres Constitucionalistas.

**Estado actual:** los 6 registros `Conferencia` ya existen con URLs coincidentes.

**Acción:** validar que el bloque/vista de la página Conferencias muestre el formato pedido y que `Conferencia` tenga `link_label` e `external_url` correctos (campos ya existentes).

## C9 — Contacto y prensa

**Pedido (spec punto 3 y checklist):**

| Red/campo | Acción |
|---|---|
| Email general | → `marcelabasterra@gmail.com` |
| Email prensa | → `marcelabasterra@gmail.com` (hoy bloque usa `contacto@marcelabasterra.com`) |
| Instagram | Agregar y jerarquizar → `https://www.instagram.com/marcelabasterra/` |
| Medium | ELIMINAR |
| LinkedIn | ELIMINAR |
| YouTube | No como red principal; conservar videos puntuales |

**Acción:** actualizar bloque `ContactForm` (campo `recipient_email`), cards de contacto y footer. Revisar `floating-share` (hoy enlaza Twitter/Facebook como share, no como redes del perfil).

## C10 — Enlaces de interés

| Enlace | Acción |
|---|---|
| InfoLEG | Mantener |
| UBA / Facultad de Derecho | Mantener |
| Corte Interamericana de Derechos Humanos | Mantener |
| USE | Eliminar (ya no pertenece a la institución) |
| FUNDESI | Eliminar |
| Perfil institucional – Derecho Comparado | Agregar (link provisto en spec punto 14) |
| AADC – renovación de autoridades | Agregar (spec punto 13) |
| Autoridades Facultad de Derecho UBA | Agregar (spec punto 8) |

**Acción:** ubicar dónde se exponen hoy los enlaces (probablemente cards de la página de contacto o Docencia) y actualizar.

## C11 — Presentación principal

**Pedido (spec punto 2):** texto base sugerido para la cabecera:

> Dra. Marcela I. Basterra — Doctora en Derecho. Profesora Titular de Derecho Constitucional de la Facultad de Derecho de la Universidad de Buenos Aires (UBA). Presidenta de la Asociación Argentina de Derecho Constitucional.

**Acción:** actualizar Hero (Home) e Intro (Sobre mí) con la bio 2026 (fuente: `Presentación.2026 docx.docx`).

## C12 — Programas de Cátedra UBA

**Pedido (spec punto 16):** mantener sección específica "Programas – Cátedra de Derecho Constitucional UBA", sin mezclar con Posgrados.

**Estado actual:** `ProgramaAcademico` tiene 0 registros.

**Acción:** crear página/sección o ancla `#programas` en Docencia con los programas de cátedra UBA. Requiere datos (programas de cátedra reales) — confirmar si el cliente los provee o si se referencian desde Docencia.

## C13 — Jornadas y Congresos

**Pedido (spec punto 17):** separar en **Realizados** (últimos años: nombre, institución, lugar, año, rol) y **Próximos** (actividades confirmadas del año en curso).

**Estado actual:** la página `/actividad-academica/jornadas-y-congresos` ya usa `EventsHighlight` + `EventsListing` con filtros por estado. `Evento` tiene 0 registros; hay 39 crónicas legacy en Actualidad.

**Acción:** cargar `Evento` con datos reales de próximos/realizados (nombre, institución, lugar, fecha, rol) para alimentar los listados; no duplicar las crónicas legacy.

---

## Impacto en arquitectura de datos

| Entidad | Cambio |
|---|---|
| `Libro` | Solo agregar `enlaces` (Rubinzal-Culzoni). |
| `ArticuloAcademico` | +16 registros nuevos con `archivo_pdf` y `tematica` (`litigios-estructurales`). |
| `Docencia` | Actualizar grado (id 14), retirar UCA/UCES de la vista de grado. |
| `CargoInstitucional` | Sin cambios (ids 1-2 ya cargados). |
| `Conferencia` | Sin cambios estructurales; validar `external_url`/`link_label`. |
| `ProgramaAcademico` | Cargar programas de cátedra (hoy vacío). |
| `Evento` | Cargar próximos/realizados (hoy vacío). |
| `Blog` / `PublicacionMedio` | Agregar presentación del libro + actividad institucional. |
| Bloques | `CVAccess` actualizado; no requiere bloques nuevos. |
| Config/Contacto | Emails, Instagram, retirar Medium/LinkedIn. |

## Orden de ejecución sugerido

1. **C3 CV** → 2. **C4 Libro** → 3. **C5/C6 Artículos + Litigios** → 4. **C1 Posgrados** → 5. **C2 Grado** → 6. **C7 Actualidad** → 7. **C9/C10 Contacto y enlaces** → 8. **C13 Jornadas/Eventos** → 9. **C8/C11 validación visual** → 10. Post-diseño: `npm run build`.
