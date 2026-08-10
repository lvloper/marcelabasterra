# Pendientes y decisiones abiertas

**Fecha:** 2026-08-10.

Lista de ítems que bloquean o condicionan la ejecución del plan. Actualizar a medida que el cliente responda.

---

## 1. Inspección visual (material audiovisual) — ✅ RESUELTA

El paquete contenía 57 imágenes JPEG y 1 video MP4 de WhatsApp (fechados 2026-08-09). Fueron inspeccionados con el modelo de visión **`opencode-go/mimo-v2.5`** (el más económico del entorno) el 2026-08-10.

**Resultado:** todas las fotos corresponden a la presentación del libro DESCA 2025 (mesa de panelistas, tomos del libro, banderas, audiencia). Selección editorial (1 principal + 6 galería) en `06-inspeccion-visual.md`.

| ítem | Resolución |
|---|---|
| 57 × `WhatsApp Image...jpeg` | Clasificadas y seleccionadas → `06-inspeccion-visual.md` |
| `WhatsApp Video...mp4` | ~6s, calidad baja (rotado/borroso). **No apto para publicar**; descartar o solicitar mejor grabación. |

**Siguiente acción:** mover las fotos seleccionadas a `images/presentacion-desca-2025/` y componer la noticia de Actualidad (C7).

---

## 2. Decisiones estructurales con el cliente

| # | Decisión | Opciones | Recomendación |
|---|---|---|---|
| D1 | Ubicación de la sección **Posgrados, Maestrías y Doctorados** | (a) hija de Actividad académica; (b) nivel principal del menú | (a) hija, con acceso destacado en panel — respeta `DESIGN.md §18.3` y no rompe la jerarquía actual. |
| D2 | **Grado UBA**: se elimina UCA/UCES del bloque de grado. ¿Se marcan como histórico o se retiran de la vista? | (a) histórico; (b) retirar | (a) histórico — la posgrado de UCA se mantiene. |
| D3 | **Equipo docente**: cliente permite mantener solo UBA o eliminar el ítem si no hay nómina verificable | (a) UBA vigente; (b) eliminar | Confirmar con el cliente si existe nómina actualizada de la cátedra UBA. |
| D4 | **Derecho Comparado** (perfil institucional): confirmar URL exacta | — | Solicitar link final. |
| D5 | **Enlace Rubinzal-Culzoni** del libro DESCA 2025 | — | Solicitar/confirmar URL de la ficha oficial. |

---

## 3. Datos que faltan proveer

| Dato | Uso | Estado |
|---|---|---|
| Arts. 15 y 16 del inventario (PDFs) | `ArticuloAcademico` | ❌ Sin archivo. |
| Eventos (próximos/realizados) para Jornadas y Congresos | `Evento` | ❌ `Evento` = 0 registros. |
| Programas de Cátedra UBA | `ProgramaAcademico` | ❌ `ProgramaAcademico` = 0 registros. |
| Piezas de prensa en Clarín/La Nación con links | `Blog`/`PublicacionMedio` | ⏳ no provistas en el paquete (solo se pide incorporarlas). |
| Fotos de eventos distintos a la presentación (si las hay entre las 60) | Galerías/noticias | ⏳ pendiente de inspección visual. |

---

## 4. Verificaciones editoriales antes de cargar

| Ítem | Qué verificar | Riesgo |
|---|---|---|
| `BASTER~1.PDF` (Art 4, fallo "Castillo") | Confirmar contenido (nombre truncado) | Cargar artículo equivocado. |
| `Procedimientos para la protección de datos personales en los medios digitales.docx` (Art 13) | Confirmar si es el mismo artículo del listado o una obra distinta | Duplicar o cargar pieza errónea. |
| `caso-denegri-oportunidad.pdf` y `Hacia una reforma...pdf` | Si actualizan PDFs de artículos ya existentes (ids 50 y 77) | Crear duplicados. |
| Contenidos legacy con UCA/UCES/equipo docente antiguo | Identificar registros a marcar histórico | Que quede info desactualizada publicada. |
| Correos en CV vs sitio | CV 2026 usa `marcebasterra@gmail.com`; spec pide `marcelabasterra@gmail.com` para contacto/prensa | Dato inconsistente; confirmar con cliente cuál es el oficial. |

---

## 5. Checklist final del cliente (spec punto 24)

Estado de cumplimiento proyectado tras la ejecución del plan:

- [x] Libro DESCA 2025 en primer lugar (requiere enlace Rubinzal-Culzoni).
- [ ] CV completo 2026 cargado.
- [ ] CV reducido 2026 cargado.
- [ ] Presentación principal actualizada (Hero/Intro).
- [ ] Correo general `marcelabasterra@gmail.com`.
- [ ] Correo de prensa `marcelabasterra@gmail.com`.
- [ ] Instagram oficial incorporado.
- [ ] Medium eliminado.
- [ ] LinkedIn eliminado.
- [ ] Grado UBA actualizado; UCA/UCES retirados de grado.
- [ ] Equipo docente UBA verificado o sección eliminada.
- [ ] Sección Posgrados, Maestrías y Doctorados creada y visible (8 instituciones).
- [ ] Presentación del libro en Actualidad con galería.
- [ ] 16 artículos cargados, ordenados, sin duplicados (faltan 2 PDFs).
- [ ] Litigios Estructurales cargado una sola vez.
- [ ] PDF de cada artículo enlazado (Ver PDF / Descargar).
- [ ] InfoLEG/UBA/Corte IDH mantenidos; USE/FUNDESI eliminados.
- [ ] Jornadas y Congresos separados en Próximos/Realizados.
- [ ] Enlaces testeados; revisión desktop/móvil (verificación post-entrega obligatoria).
