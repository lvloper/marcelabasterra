# Plan de distribución — Paquete de datos del cliente 2026

> **Fuente:** carpeta `data-desordenada/` con el material enviado por el cliente para actualizar marcelabasterra.com.ar.
> **Fecha de relevamiento:** 2026-08-10.
> **Regla visual:** todo cambio de vista debe respetar `DESIGN.md`. Ante conflicto con la propuesta del cliente, prevalece `DESIGN.md`.

## Documentos que componen este plan

| Archivo | Contenido |
|---|---|
| `01-inventario-data.md` | Mapeo archivo por archivo: dónde va cada cosa y qué acción requiere. |
| `02-cambios-estructurales.md` | Cambios de estructura del sitio (páginas, menú, recursos, bloques). |
| `03-plan-distribucion.md` | Distribución por sección: qué se carga, dónde, con qué bloque/recurso. |
| `04-carga-articulos.md` | Plan de carga de los 16 artículos del inventario del cliente. |
| `05-pendientes-decisiones.md` | Material pendiente de inspección y decisiones abiertas con el cliente. |
| `06-inspeccion-visual.md` | Resultado de la inspección visual del material fotográfico (selección de galería). |
| `07-changelog-carga.md` | Changelog de implementación: orden de carga fase por fase. |

## Resumen ejecutivo

El cliente envió **tres tipos de material**:

1. **Documentos de planificación** (`PAGINA WEB MARCELA BASTERRA.docx`, `Actualizacion de Pagina WEB MIB 2026.docx`, `Posgrados...docx`, `Presentación.2026 docx.docx`, `LISTADO DE ARTICULOS...docx`): definen el rediseño integral y los contenidos a cargar.
2. **CVs 2026** (`CV Marcela I Basterra Completo...`, `CV Basterra reducido.docx`): reemplazan el CV actual del sitio.
3. **Archivos de artículos** (PDFs/docx/rtf): los 16 artículos del inventario, de los cuales se aporta archivo para ~13-14.
4. **Material audiovisual** (57 imágenes + 1 video de WhatsApp): todas las fotos corresponden a la presentación del libro DESCA 2025. **Inspeccionado** (ver `06-inspeccion-visual.md`).

### Hallazgos clave del relevamiento

- **El libro DESCA 2025 ya está cargado** en la DB como `Libro` id 12 (destacado, tomos e ISBNs correctos). Falta agregar el enlace a Rubinzal-Culzoni.
- **Los posgrados ya están cargados** como registros `Docencia` (ids 1-13) y coinciden exactamente con el docx `Posgrados-Maestrias-y-Doctorados-WEB-M.B.docx`. Falta exponerlos como sección propia.
- **Las 6 conferencias ya están cargadas** en `Conferencia` con los links de YouTube exactos que pide la spec (punto 18).
- **El cargo AADC 2025-2027 y el Consejo Directivo UBA ya existen** en `CargoInstitucional` (ids 1 y 2).
- **Los 16 artículos del inventario NO están en la DB** (103 artículos actuales son el archivo legacy, otro set). Hay que cargarlos nuevos.
- **El CV actual apunta a PDFs de 2018**; debe reemplazarse por las versiones 2026 (completo + reducido).
- **Grado:** actualmente hay registros de grado UBA/UCA/UCES; el cliente pide solo UBA con `Elementos de Derecho Constitucional`.

### Estado general

| Bloque de trabajo | Estado |
|---|---|
| Data relevada e inventariada | ✅ |
| Cambios estructurales definidos | ✅ |
| Plan de carga por sección | ✅ |
| Inspección de imágenes/video | ✅ (MiMo V2.5) |
| Confirmaciones del cliente | ⏳ pendientes |
