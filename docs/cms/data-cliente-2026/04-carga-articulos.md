# Plan de carga — Artículos del inventario 2026

**Fuente:** `LISTADO DE ARTICULOS MARCELA BASTERRA.docx` (inventario del cliente) + archivos aportados en `data-desordenada/`.

**Recurso destino:** `ArticuloAcademico` (tabla `articulos_academicos`, 103 registros actuales, ninguno del inventario nuevo).

**Formato de ficha requerido por el cliente (spec punto 9):** título completo · Marcela I. Basterra · revista/editorial · año · cita si corresponde · botón **VER PDF / DESCARGAR**. Orden: del más reciente al más antiguo.

---

## Tabla de carga

| # | Título (del inventario del cliente) | Fecha | Archivo aportado | Estado |
|---|---|---|---|---|
| 1 | La libertad de expresión en el SIDH. Breves reflexiones sobre los discursos de odio. | Jun 2025 | `La libertad de expresión en el Sistema Interamericano de Derechos Humanos...pdf` | ✅ Archivo listo |
| 2 | Litigios Estructurales: estrategias clave para una adecuada gestión procesal. Consideraciones a partir del fallo "Mendoza". | Abr 2025 | `Litigios Estructurales estrategias clave para una adecuada gestión procesal...pdf` | ✅ Archivo listo — cargar una sola vez (llegó en dos copias equivalentes) |
| 3 | El fallo Levinas: un hito en la autonomía porteña. | 07/03/2025 | `El fallo Levinas- Un hito para la judicatura porteña...pdf` | ✅ Archivo listo |
| 4 | El derecho a la educación... fallo "Castillo". | 30/09/2024 | `BASTER~1.PDF` | ⚠️ VERIFICAR (nombre truncado; confirmar contenido antes de cargar) |
| 5 | La evolución del derecho a un medioambiente sano. | 21/08/2024 | `La evolución del derecho a un medioambiente sano...pdf` | ✅ Archivo listo |
| 6 | La protección jurídica del derecho a la intimidad a 30 años de la reforma de 1994. | 2024 | `La protección jurídica del derecho a la intimidad...docx` | ✅ Archivo listo (convertir a PDF) |
| 7 | Democracia y derechos humanos: el derecho a defender los derechos humanos. | 19/07/2024 | `Basterra Marcela Democracia y derechos humanos- el derecho a defender los derechos humanos.pdf` | ✅ Archivo listo |
| 8 | Algunas notas en materia de DESCA. Homenaje a Roberto Punte. | 29/10/2023 | `Algunas notas en materia de derechos económicos, sociales, culturales y ambientales...Punte...pdf` | ✅ Archivo listo |
| 9 | La interseccionalidad como categoría de análisis. | 15/05/2023 | `Diario 15-5-23.pdf` (diario La Ley del día) | ✅ Archivo listo — extraer/adjuntar el artículo del diario |
| 10 | Reflexiones sobre los DESCA de personas en condiciones de vulnerabilidad. | 24/10/2022 | `Reflexiones sobre los derechos económicos, sociales, culturales y ambientales de las personas en condiciones de vulnerabilidad...pdf` | ✅ Archivo listo |
| 11 | Legislación sobre los derechos de género en Argentina y en el sistema interamericano. | Sep 2021 | `Basterra Marcela I. Legislación sobre los derechos de género...docx` | ✅ Archivo listo (convertir a PDF) |
| 12 | La Corte Suprema consolida los estándares de la Ley 27.275 de Acceso a la Información Pública. | Abr 2019 | `La Corte Suprema consolida los estándares de la ley 27.275...rtf` | ✅ Archivo listo (convertir a PDF) |
| 13 | La protección de los datos personales, el honor y la intimidad en los medios digitales. | 04/06/2018 | `Procedimientos para la protección de datos personales en los medios digitales.docx` | ⚠️ VERIFICAR si corresponde a este ítem o es obra distinta |
| 14 | Reflexión preliminar. Observación 21 de la ONU: Chicos de la calle. | Mar 2018 | `Reflexión preliminar. Observación 21 de la ONU Chicos de la calle...pdf` | ✅ Archivo listo |
| 15 | La regulación de Internet y la libertad de expresión en la sociedad global. | 2017 | — | ❌ FALTA ARCHIVO (solicitar al cliente) |
| 16 | La protección de datos personales frente a las nuevas tecnologías. | 02/09/2016 | — | ❌ FALTA ARCHIVO (solicitar al cliente) |

### Archivos extra aportados (no en el listado de 16)

| Archivo | Coincidencia en DB | Acción |
|---|---|---|
| `caso-denegri-oportunidad.pdf` | `ArticuloAcademico` id 50: El caso "Denegri"... | `VERIFICAR` si es actualización del PDF existente o pieza distinta. |
| `Hacia una reforma del sistema de protección de datos personales.pdf` | `ArticuloAcademico` id 77: Hacia una reforma del proceso de protección de datos personales... | `VERIFICAR` si es actualización del PDF existente. |

---

## Checklist de carga por artículo

- [ ] Convertir a PDF si el archivo fuente es `.docx` o `.rtf`.
- [ ] Subir PDF a `storage/app/public/pdfs/articulos/` con nombre SEO-legible.
- [ ] Crear `ArticuloAcademico` con: título completo, `tematica` (área temática), `fecha_publicacion`, cita completa en `resumen`/`contenido`, `archivo_pdf`.
- [ ] Si corresponde a "Litigios estructurales", setear `tematica = Litigios estructurales` (punto 10 de la spec).
- [ ] Verificar que el índice de artículos muestre el botón **VER PDF / DESCARGAR**.
- [ ] Confirmar que no exista ya en la DB (deduplicación por título normalizado).

## Orden de publicación

El cliente exige orden cronológico descendente. La fecha de publicación del recurso gobierna el ordenamiento del índice; no alterar el orden del archivo legacy.

## Regla de no duplicación (AGENTS.md)

- No duplicar entidades CMS dentro de bloques o páginas derivadas.
- El artículo de Litigios Estructurales se publica **una sola vez** aunque el cliente haya enviado dos copias del archivo.
- Las 103 piezas legacy se conservan; los 16 nuevos se agregan sin tocar los existentes (salvo los 2 `VERIFICAR`).
