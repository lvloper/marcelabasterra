# Roadmap CMS — Marcela Basterra

> Última actualización: 2026-06-08
> Estado general: **10/27 tareas completadas**

---

## Fase 0 — Prerrequisito

| ID | Tarea | Archivo | Estado |
|----|-------|---------|--------|
| T0 | Design system completo | `docs/ux/design-system.md` | ✅ completado |

---

## Fase 1 — Páginas padre

| ID | Tarea | Archivo | Estado |
|----|-------|---------|--------|
| T1 | Crear 6 páginas padre en CMS | `pages/tasks/parent-pages.md` | ⬜ pendiente |
| T2 | Configurar `cms-routes.php` con route_id de cada grupo | `pages/tasks/parent-pages.md` | ⬜ pendiente |

### Páginas padre a crear

| Ruta | Uso (padre de...) |
|------|-------------------|
| `/publicaciones` | Libro, ArtículoAcadémico |
| `/prensa` | Entrevista, DossierPrensa |
| `/agenda` | Evento |
| `/programas` | ProgramaAcadémico |
| `/docencia` | Docencia |
| `/trayectoria` | CargoInstitucional |

---

## Fase 2 — Resources

| ID | Resource | Archivo | Estado |
|----|----------|---------|--------|
| T3 | Libro | `resources/tasks/libro.md` | ✅ completado |
| T4 | ArtículoAcadémico | `resources/tasks/articulo-academico.md` | ✅ completado |
| T5 | Entrevista | `resources/tasks/entrevista.md` | ✅ completado |
| T6 | Evento | `resources/tasks/evento.md` | ✅ completado |
| T7 | ProgramaAcadémico | `resources/tasks/programa-academico.md` | ✅ completado |
| T8 | CargoInstitucional | `resources/tasks/cargo-institucional.md` | ✅ completado |
| T9 | Docencia | `resources/tasks/docencia.md` | ✅ completado |
| T10 | DossierPrensa | `resources/tasks/dossier-prensa.md` | ✅ completado |
| T11 | Registrar controllers en config | `resources/tasks/registro-controllers.md` | ✅ completado |

---

## Fase 3 — Bloques

### 3A — Auditoría existentes

| ID | Tarea | Archivo | Estado |
|----|-------|---------|--------|
| T12 | Auditar MediaBlock, CardsBlock, TextBlock | `blocks/tasks/auditoria-existentes.md` | ⬜ pendiente |

### 3B — Bloques genéricos nuevos

| ID | Bloque | Archivo | Estado |
|----|--------|---------|--------|
| T13 | HeroBlock | `blocks/tasks/hero.md` | ⬜ pendiente |
| T14 | CTABlock | `blocks/tasks/cta.md` | ⬜ pendiente |
| T15 | TimelineBlock | `blocks/tasks/timeline.md` | ⬜ pendiente |
| T16 | CVDownloadBlock | `blocks/tasks/cv-download.md` | ⬜ pendiente |
| T17 | BiographySummaryBlock | `blocks/tasks/biography-summary.md` | ⬜ pendiente |
| T18 | ContactFormBlock | `blocks/tasks/contact-form.md` | ⬜ pendiente |
| T19 | FeaturedResourcesBlock | `blocks/tasks/featured-resources.md` | ⬜ pendiente |

### 3C — Bloques highlight (dependen de Fase 2)

| ID | Bloque | Archivo | Estado |
|----|--------|---------|--------|
| T20 | PublicationsHighlightBlock | `blocks/tasks/publications-highlight.md` | ⬜ pendiente |
| T21 | InterviewsHighlightBlock | `blocks/tasks/interviews-highlight.md` | ⬜ pendiente |
| T22 | EventsHighlightBlock | `blocks/tasks/events-highlight.md` | ⬜ pendiente |
| T23 | RelatedResourcesBlock | `blocks/tasks/related-resources.md` | ⬜ pendiente |

---

## Fase 4 — Pages

| ID | Page | Archivo | Estado |
|----|------|---------|--------|
| T24 | Home (`/`) | `pages/tasks/home.md` | ⬜ pendiente |
| T25 | Sobre mí (`/sobre-mi`) | `pages/tasks/sobre-mi.md` | ⬜ pendiente |
| T26 | Contacto (`/contacto`) | `pages/tasks/contacto.md` | ⬜ pendiente |

---

## Fase 5 — Polish

| ID | Tarea | Archivo | Estado |
|----|-------|---------|--------|
| T27 | Design system compliance review | `ROADMAP.md#fase-5` | ⬜ pendiente |

---

## Dependencias

```
Fase 0 (T0) ──→ Fase 1 (T1-T2) ──→ Fase 2 (T3-T11) ──→ Fase 3C (T20-T23) ──┐
                                                                              ├──→ Fase 4 (T24-T26) ──→ Fase 5 (T27)
Fase 0 (T0) ──→ Fase 3A (T12) ──→ Fase 3B (T13-T19) ────────────────────────┘
```

- **Fase 2 y Fase 3B** son paralelizables entre sí.
- **Fase 3C** requiere Fase 2 completa (necesita modelos de resources).
- **Fase 4** requiere Fase 2 + Fase 3 completas (necesita bloques y resources).
- **Diseño visual diferido** para todos los bloques — primero backend funcional, luego diseño.

---

## Leyenda

| Símbolo | Estado |
|---------|--------|
| ⬜ | Pendiente |
| 🔄 | En progreso |
| ✅ | Completado |
| ❌ | Cancelado |
