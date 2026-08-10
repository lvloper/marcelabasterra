# Inventario de la data desordenada del cliente

**Carpeta fuente:** `data-desordenada/` — 81 archivos.

**Convenciones de acción:**

| Acción | Significado |
|---|---|
| `PLAN` | Documento de planificación/spec: se lee, no se sube. |
| `CARGAR` | Subir como contenido nuevo al CMS (recurso o bloque). |
| `ACTUALIZAR` | Reemplazar/actualizar contenido existente (archivo o campos). |
| `VERIFICAR` | Requiere confirmación con cliente antes de cargar. |
| `PENDIENTE` | No se puede ejecutar hoy (falta inspección visual, dato, etc.). |
| `ELIMINAR` | Retirar del sitio por decisión explícita del cliente. |

---

## 1. Documentos de planificación (spec del cliente)

Estos archivos definen el alcance del rediseño. No se publican en el sitio.

| Archivo | Acción | Destino | Notas |
|---|---|---|---|
| `PAGINA WEB MARCELA BASTERRA.docx` | `PLAN` | Spec maestro (24 puntos + checklist). | Documento de referencia principal: "Paquete para el diseñador web". |
| `Actualizacion de Pagina WEB MIB 2026. (2).docx` | `PLAN` | Propuesta de reordenamiento condensada. | Versión corta de la spec, 11 puntos. |
| `Posgrados, Maestrias y Doctorados WEB M.B.docx` | `PLAN` + `CARGAR` | Contenido de la sección Posgrados (8 universidades/instituciones). | Los datos ya existen en la DB como `Docencia` (ids 1-13). Ver `02-cambios-estructurales.md`. |
| `Marcela I. Basterra. Presentación.2026 docx.docx` | `PLAN` | Texto de presentación / bio principal. | Fuente para actualizar Hero e Intro (punto 2 de la spec). |
| `LISTADO DE ARTICULOS MARCELA BASTERRA.docx` | `PLAN` | Inventario maestro de 16 artículos con citas. | Base del plan `04-carga-articulos.md`. |

## 2. CVs 2026

| Archivo | Acción | Destino | Notas |
|---|---|---|---|
| `CV Marcela I Basterra Completo y actualizado al 01-01-2026.docx` | `CARGAR` (convertir a PDF) | Bloque `CVAccess` en página CV (`/cv`) | Reemplaza el CV completo actual (hoy apunta a PDF 2018). |
| `CV Basterra reducido.docx` | `CARGAR` (convertir a PDF) | Bloque `CVAccess` en página CV (`/cv`) | Nueva versión reducida 2026. Hoy el bloque usa la misma URL vieja para ambas. |

**Estado actual del bloque `CVAccess`** (página `/cv`):

```json
{
  "documents": [
    { "type": "full",  "file": "https://.../CV-Marcela-I-Basterra-Completo-y-actualizado-al-21-9-2017.pdf", "updated_at": "2018-03-01" },
    { "type": "short", "file": "https://.../CV-Marcela-I-Basterra-Completo-y-actualizado-al-21-9-2017.pdf", "updated_at": "2018-03-01" }
  ]
}
```

**Acción:** convertir ambos `.docx` a PDF, subirlos a `storage/app/public/pdfs/cv/` y actualizar los dos ítems del bloque con archivos, fechas y descripciones correctas.

## 3. Artículos del inventario del cliente

Archivos de contenido de los 16 artículos. Detalle completo en `04-carga-articulos.md`. Resumen:

| Archivo | Artículo inventario |
|---|---|
| `La libertad de expresión en el Sistema Interamericano de Derechos Humanos. Breves reflexiones sobre los discursos de odio...pdf` | Art 1 — Jun 2025 |
| `Litigios Estructurales estrategias clave para una adecuada gestión procesal...pdf` | Art 2 — Abr 2025 |
| `El fallo Levinas- Un hito para la judicatura porteña...pdf` | Art 3 — Mar 2025 |
| `BASTER~1.PDF` | Art 4 — El derecho a la educación / fallo "Castillo" (nombre truncado; confirmar) |
| `La evolución del derecho a un medioambiente sano...pdf` | Art 5 — Ago 2024 |
| `La protección jurídica del derecho a la intimidad...docx` | Art 6 — 2024 |
| `Basterra Marcela Democracia y derechos humanos- el derecho a defender los derechos humanos.pdf` | Art 7 — Jul 2024 |
| `Algunas notas en materia de derechos económicos, sociales, culturales y ambientales... Punte...pdf` | Art 8 — Oct 2023 |
| `Diario 15-5-23.pdf` | Art 9 — La interseccionalidad (La Ley, 15/05/2023) |
| `Reflexiones sobre los derechos económicos, sociales, culturales y ambientales de las personas en condiciones de vulnerabilidad...pdf` | Art 10 — Oct 2022 |
| `Basterra Marcela I. Legislación sobre los derechos de género...docx` | Art 11 — Sep 2021 |
| `La Corte Suprema consolida los estándares de la ley 27.275...rtf` | Art 12 — Abr 2019 |
| `Procedimientos para la protección de datos personales en los medios digitales.docx` | Art 13 — Protección datos/honor/intimidad en medios digitales (Jun 2018). **VERIFICAR** si es la misma obra que la del listado. |
| `Reflexión preliminar. Observación 21 de la ONU Chicos de la calle...pdf` | Art 14 — Mar 2018 |
| *(no provisto)* | Art 15 — La regulación de Internet y la libertad de expresión en la sociedad global (2017). **FALTA ARCHIVO.** |
| *(no provisto)* | Art 16 — La protección de datos personales frente a las nuevas tecnologías (2016). **FALTA ARCHIVO.** |
| `caso-denegri-oportunidad.pdf` | Extra — El caso "Denegri": derecho al olvido. Ya existe en DB (ArtículoAcademico id 50). `VERIFICAR` si pide nueva versión. |
| `Hacia una reforma del sistema de protección de datos personales.pdf` | Extra — Ya existe en DB (ArtículoAcademico id 77). `VERIFICAR`. |

**Nota de deduplicación:** la DB ya tiene 103 `ArticuloAcademico`. Ninguno de los 16 del inventario existe (verificado por título). No duplicar; cargar como registros nuevos.

## 4. Material audiovisual (WhatsApp)

**Estado: ✅ inspeccionado** (modelo `opencode-go/mimo-v2.5`). Ver selección completa en `06-inspeccion-visual.md`.

| Archivo | Acción | Destino | Notas |
|---|---|---|---|
| 57 × `WhatsApp Image 2026-08-09...jpeg` | `CARGAR` | Presentación del libro DESCA 2025 en Actualidad (punto 8 y 22 de la spec). | 1 imagen principal + 6 galería seleccionadas en `06-inspeccion-visual.md`. |
| `WhatsApp Video 2026-08-09 at 10.18.02.mp4` | `DESCARTAR` | — | ~6s, calidad baja (rotado/borroso). No apto para publicar. |

## 5. Archivos ya cubiertos por la DB actual (no requiere carga)

| Contenido solicitado por cliente | Estado en DB | Notas |
|---|---|---|
| Libro DESCA 2025 (Rubinzal-Culzoni) | `Libro` id 12, `destacado=1`, tomos/ISBN correctos | Falta enlace Rubinzal-Culzoni. |
| 6 conferencias con links YouTube | `Conferencia` ids 1-6 con URLs coincidentes | Verificar formato "miniatura + título + institución + año + Ver video". |
| Cargo AADC Presidenta 2025-2027 | `CargoInstitucional` id 1 | OK. |
| Cargo Consejo Directivo UBA | `CargoInstitucional` id 2 | OK. |
| 13 posgrados (UBA, Palermo, UCA, Austral, Chile, IDC-Bologna, TEPJF-UBA) | `Docencia` ids 1-13 | Coinciden con `Posgrados...docx`. |
