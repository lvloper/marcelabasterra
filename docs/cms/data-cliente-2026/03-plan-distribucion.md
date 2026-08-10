# Plan de distribución por sección

**Fecha:** 2026-08-10.

Este documento indica, por cada página/sección del sitio, qué contenido del paquete del cliente se distribuye allí, contra qué bloque/recurso se implementa y qué acción concreta se ejecuta.

---

## Home `/`

| Contenido del cliente | Bloque actual | Acción |
|---|---|---|
| Bio principal 2026 (`Presentación.2026`) | `Hero` (editorial) | Actualizar nombre/descripción con la presentación 2026 (Dr. en Derecho · Prof. Titular UBA · Presidenta AADC). |
| Presentación (Intro) | `Intro` | Actualizar texto con bio 2026. |
| Libro DESCA 2025 como "último libro" | `PublicationsHighlight` | Verificar que `libro_id` = 12 y que se muestre destacado. |
| Actualidad y publicaciones recientes | `PressFeed` | Verificar feed 2026/2025 prioritario. |
| Conferencias | `EventsListing` | Verificar. |
| Cargo AADC (destacado) | — | Opcional: bloque/cards para destacar Presidencia AADC 2025-2027 (spec punto 13 permite bloque destacado en Inicio). |
| CTA | `CTA` | Verificar textos de invitación. |

## Sobre mí `/sobre-mi`

| Contenido del cliente | Bloque actual | Acción |
|---|---|---|
| Bio 2026 (`Presentación.2026`) | `Intro` | Actualizar summary. |
| Trayectoria en cifras | Timeline/bloque | Verificar cifras (p. ej., "más de doscientos seminarios", "doce libros", "más de ciento cincuenta artículos" — datos del CV reducido 2026). |
| Cargos actuales | `Cards`/`ContentList` | Verificar ids 1-2 destacados (AADC + Consejo Directivo UBA). |
| Reconocimientos | — | Sumar Personalidad Destacada (Legislatura CABA) si no está. |
| CV dual | `CVAccess` o CTA | Acceso contextual al CV (enlazar a `/cv`). |

## Actividad académica `/actividad-academica`

| Contenido del cliente | Bloque actual | Acción |
|---|---|---|
| Portada de sección | `Hero` + `Text`/`Cards` | Verificar accesos a Docencia, Conferencias, Jornadas, Posgrados. |

### Docencia `/actividad-academica/docencia`

| Contenido del cliente | Recurso | Acción |
|---|---|---|
| Grado UBA único (C2) | `Docencia` id 14 | Actualizar materia a `Elementos de Derecho Constitucional`, retirar UCA/UCES de la vista. |
| Posgrados (C1) | `Docencia` ids 1-13 | Migrar a la nueva sección Posgrados. |
| Programas de Cátedra UBA (C12) | `ProgramaAcademico` (0) | Cargar programas reales; mantener separado de Posgrados. |

### Posgrados, Maestrías y Doctorados (NUEVA) `/actividad-academica/posgrados`

| Contenido | Recurso | Acción |
|---|---|---|
| `Posgrados-Maestrias-y-Doctorados-WEB-M.B.docx` | `Docencia` ids 1-13 | Crear página; agrupar en Universidades Nacionales / Extranjeras; usar `TeachingListing` filtrado por nivel. |

### Conferencias `/actividad-academica/conferencias`

| Contenido | Recurso | Acción |
|---|---|---|
| 5 links YouTube + 2º Encuentro (spec 18) | `Conferencia` ids 1-6 | Validar `external_url`, `link_label`, institución y año; formato miniatura + título + botón "Ver video". |

### Jornadas y Congresos `/actividad-academica/jornadas-y-congresos`

| Contenido | Recurso | Acción |
|---|---|---|
| Próximos / Realizados (C13) | `Evento` (0) + `EventsHighlight`/`EventsListing` | Cargar eventos reales; separar estados. |

## Publicaciones `/publicaciones`

| Contenido | Recurso | Acción |
|---|---|---|
| Portada de sección | `Text`/`Cards`/`PublicationsHighlight` | Verificar accesos. |

### Libros `/publicaciones/libros`

| Contenido | Recurso | Acción |
|---|---|---|
| Libro DESCA 2025 (C4) | `Libro` id 12 | Ordenar primero; agregar enlace Rubinzal-Culzoni. |

### Artículos académicos `/publicaciones/articulos-academicos`

| Contenido | Recurso | Acción |
|---|---|---|
| 16 artículos 2026 (C5) | `ArticuloAcademico` | Cargar registros nuevos con PDF y cita. |
| Categoría "Litigios estructurales" (C6) | `tematica` | Crear filtro/categoría; cargar el artículo Mendoza una sola vez. |

## Actualidad `/actualidad`

| Contenido | Recurso | Acción |
|---|---|---|
| Presentación del libro DESCA con galería (C7) | `Blog` | Crear noticia principal 2025 con imagen + 5-6 fotos. |
| Actividad AADC | `Blog` | Noticia renovación de autoridades 2025-2027. |
| Personalidad Destacada | `Blog` | Noticia con video (YouTube). |
| Presencia en medios (Clarín/La Nación, entrevistas) | `Blog`/`PublicacionMedio`/`Entrevista` | Cargar piezas con formato título · medio · fecha · link. |
| Orden 2026 → 2025 | `PressFeed` | Verificar orden descendente; archivo histórico no domina portada. |

## CV `/cv`

| Contenido | Bloque | Acción |
|---|---|---|
| CV Completo 2026 | `CVAccess` (full) | Subir PDF 2026; reemplazar URL 2018. |
| CV Reducido 2026 | `CVAccess` (short) | Subir PDF 2026; reemplazar URL 2018. |

## Contacto `/contacto`

| Contenido | Bloque | Acción |
|---|---|---|
| Email general `marcelabasterra@gmail.com` | `ContactForm` (`recipient_email`) | Actualizar (hoy `contacto@marcelabasterra.com`). |
| Email prensa | Cards/info | Actualizar a `marcelabasterra@gmail.com`. |
| Instagram oficial | Cards/footer | Agregar `https://www.instagram.com/marcelabasterra/`. |
| Medium / LinkedIn | — | Eliminar. |
| Enlaces de consulta (C10) | Cards | Mantener InfoLEG/UBA/Corte IDH; eliminar USE/FUNDESI; agregar Perfil Derecho Comparado, AADC, Autoridades UBA. |

## Configuración global y footer

| Ítem | Acción |
|---|---|
| Redes sociales globales | Agregar Instagram; retirar Medium/LinkedIn; YouTube solo videos puntuales. |
| Menú | Agregar acceso a Posgrados (C1); validar jerarquía con `DESIGN.md §18.3`. |
| Emails | Centralizar `marcelabasterra@gmail.com`. |

---

## Resumen de volumen de carga

| Recurso | Alta | Actualización | Observación |
|---|---|---|---|
| `Libro` | 0 | 1 | Enlace Rubinzal-Culzoni. |
| `ArticuloAcademico` | ~16 | 0 | Arts. 15-16 sin archivo; verificar. |
| `Docencia` | 0 | 1-4 | Grado UBA; mover posgrados a sección propia. |
| `CargoInstitucional` | 0 | 0 | Ya cargados. |
| `Conferencia` | 0 | 6 | Validar formato/links. |
| `Evento` | ~n | 0 | Próximos/realizados (dato pendiente). |
| `ProgramaAcademico` | ~n | 0 | Programas de cátedra (dato pendiente). |
| `Blog` | 2-4 | 0 | Presentación libro, AADC, Personalidad Destacada, prensa. |
| `PublicacionMedio` | ~n | 0 | Prensa (dato pendiente). |
| Bloques | 0 | `CVAccess`, `ContactForm`, Hero, Intro | Sin bloques nuevos. |
