# T25 — Page: Sobre mí

- **Estado**: ✅ implementada
- **Depende de**: T15 (TimelineBlock), T17 (IntroBlock), T14 (CTABlock), T8 (CargoInstitucional), T12 (CardsBlock verificado)
- **Ruta**: `/sobre-mi`

---

## Bloques (en orden)

| # | Bloque | Contenido |
|---|--------|-----------|
| 1 | **IntroBlock** | Bio / Presentación completa + foto |
| 2 | **ContentListBlock** | Indicadores destacados de trayectoria |
| 3 | **ContentListBlock** | Cargos actuales consultados desde CargoInstitucional |
| 4 | **MediaTextBlock** | Reconocimiento Personalidad Destacada + video |
| 5 | **CVAccessBlock** | Accesos a CV completo y reducido |

---

## Checklist

- [x] Crear Page "Sobre mí" con route `/sobre-mi`
- [x] Agregar IntroBlock con bio completa
- [x] Agregar indicadores destacados de trayectoria
- [x] Consultar cargos desde CargoInstitucional mediante ContentListBlock
- [x] Agregar reconocimiento y video mediante MediaTextBlock
- [x] Agregar CVAccessBlock (archivos pendientes de carga editorial)
- [x] Verificar frontend en `/sobre-mi`
