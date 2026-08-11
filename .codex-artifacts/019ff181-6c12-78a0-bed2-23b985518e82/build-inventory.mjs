import fs from "node:fs/promises";
import { execFileSync } from "node:child_process";
import { SpreadsheetFile, Workbook } from "@oai/artifact-tool";

const projectDir = "/media/leveloper/Proyectos/Dev/marcelabasterra";
const outputDir = `${projectDir}/outputs/019ff181-6c12-78a0-bed2-23b985518e82`;
const previewDir = `${projectDir}/.codex-artifacts/019ff181-6c12-78a0-bed2-23b985518e82/previews`;
const outputPath = `${outputDir}/inventario-eventos-sin-duplicados.xlsx`;
const sourcePath = `${projectDir}/storage/app/imports/wordpress-posts.json`;

const source = JSON.parse(await fs.readFile(sourcePath, "utf8"));
const legacyPosts = source.posts.filter((post) =>
  (post.categories ?? []).includes("Jornadas & Congresos"),
);

function tinkerJson(code) {
  const output = execFileSync("php", ["artisan", "tinker", `--execute=${code}`], {
    cwd: projectDir,
    encoding: "utf8",
  }).trim();
  const jsonStart = Math.min(
    ...[output.indexOf("["), output.indexOf("{")].filter((index) => index >= 0),
  );
  return JSON.parse(output.slice(jsonStart));
}

const localBlogs = tinkerJson(
  `echo App\\Models\\Blog::with(['route','tags'])->get()->map(fn($b)=>['id'=>$b->id,'slug'=>$b->route?->slug,'title'=>$b->route?->title,'date'=>$b->published_at?->toIso8601String(),'tags'=>$b->tags->pluck('name')->values()->all()])->values()->toJson();`,
);

const localConferences = tinkerJson(
  `echo App\\Models\\Conferencia::with('route')->get()->map(fn($c)=>['id'=>$c->id,'title'=>$c->route?->title,'slug'=>$c->route?->slug,'date'=>$c->fecha?->toDateString(),'type'=>$c->tipo,'institution'=>$c->institucion,'location'=>$c->ubicacion,'city'=>$c->ciudad,'country'=>$c->pais,'url'=>$c->external_url])->values()->toJson();`,
);

const blogBySlug = new Map(localBlogs.map((blog) => [blog.slug, blog]));
const likelyEvent = (title) =>
  /(jornada|congreso|seminario|encuentro|conferencia|foro|feria|presentaci[oó]n|curso|panel)/i.test(
    title ?? "",
  );

const inventory = [];

for (const post of legacyPosts) {
  const local = blogBySlug.get(post.slug);
  const localTags = local?.tags ?? [];
  const correctlyTagged = localTags.includes("Jornadas & Congresos");

  inventory.push({
    id: `WP-${post.wordpress_id}`,
    title: post.title,
    date: post.date ? new Date(post.date) : null,
    origin: "WordPress legacy",
    currentResource: "Actualidad",
    canonicalResource: "Actualidad",
    categories: (post.categories ?? []).join(" · "),
    localStatus: correctlyTagged ? "Etiquetado" : "Corregir etiqueta",
    action: correctlyTagged
      ? "Conservar la crónica; no copiar sus datos a otro recurso"
      : "Agregar etiqueta Jornadas & Congresos; conservar como una sola crónica",
    eventCandidate: likelyEvent(post.title) ? "Revisar vínculo" : "No",
    relation: "Vincular a Evento únicamente si la agenda confirma que es la misma actividad",
    source: post.url,
    notes: correctlyTagged
      ? "La fecha corresponde a la publicación; validar la fecha real del evento"
      : `En el CMS figura solo con: ${localTags.join(", ") || "sin etiquetas"}`,
  });
}

for (const conference of localConferences) {
  const location = [conference.location, conference.city, conference.country]
    .filter(Boolean)
    .join(", ");
  inventory.push({
    id: `CONF-${conference.id}`,
    title: conference.title,
    date: conference.date ? new Date(`${conference.date}T12:00:00`) : null,
    origin: "CMS actual",
    currentResource: "Conferencia",
    canonicalResource: "Conferencia",
    categories: [conference.type, conference.institution].filter(Boolean).join(" · "),
    localStatus: "Existe",
    action: "Conservar video/intervención; no replicar la crónica ni la ficha de Evento",
    eventCandidate: conference.date ? "Revisar vínculo" : "No hasta validar",
    relation: "Vincular a Evento solo cuando exista fecha y actividad institucional verificadas",
    source: conference.url,
    notes: location
      ? `Ubicación informada: ${location}`
      : "Sin ubicación estructurada; no completar por inferencia",
  });
}

inventory.sort((a, b) => {
  const resourceOrder = { Actualidad: 0, Conferencia: 1 };
  const resourceDiff =
    (resourceOrder[a.currentResource] ?? 9) - (resourceOrder[b.currentResource] ?? 9);
  if (resourceDiff !== 0) return resourceDiff;
  return (b.date?.getTime() ?? 0) - (a.date?.getTime() ?? 0);
});

const colors = {
  navy: "#24314D",
  blue: "#4169A1",
  paleBlue: "#EAF0F7",
  sand: "#F3EEE7",
  green: "#DDEFE5",
  amber: "#FFF0C9",
  red: "#FADCDC",
  ink: "#222936",
  gray: "#6B7280",
  light: "#F7F8FA",
  white: "#FFFFFF",
  border: "#D6DAE1",
};

const workbook = Workbook.create();
const summary = workbook.worksheets.add("Resumen");
const inventorySheet = workbook.worksheets.add("Inventario");
const modelSheet = workbook.worksheets.add("Modelo canónico");
const sourcesSheet = workbook.worksheets.add("Fuentes");

for (const sheet of [summary, inventorySheet, modelSheet, sourcesSheet]) {
  sheet.showGridLines = false;
}

// Inventario
const headers = [
  "ID fuente",
  "Título",
  "Fecha publicación / ficha",
  "Origen",
  "Recurso actual",
  "Recurso canónico",
  "Categorías / contexto",
  "Estado local",
  "Acción propuesta",
  "¿Vincular a Evento?",
  "Relación sin duplicar",
  "Fuente",
  "Observaciones",
  "Control",
];
inventorySheet.getRange("A1:N1").merge();
inventorySheet.getRange("A1").values = [["Inventario de agenda y archivo — sin duplicación"]];
inventorySheet.getRange("A1:N1").format = {
  fill: colors.navy,
  font: { bold: true, color: colors.white, size: 18 },
  verticalAlignment: "center",
};
inventorySheet.getRange("A2:N2").merge();
inventorySheet.getRange("A2").values = [[
  "La fecha de las crónicas es la fecha de publicación. Ningún registro se transforma automáticamente en Evento sin validar la actividad real.",
]];
inventorySheet.getRange("A2:N2").format = {
  fill: colors.sand,
  font: { color: colors.ink, italic: true },
  wrapText: true,
  verticalAlignment: "center",
};
inventorySheet.getRange("A5:N5").values = [headers];
inventorySheet.getRange("A5:N5").format = {
  fill: colors.blue,
  font: { bold: true, color: colors.white },
  wrapText: true,
  verticalAlignment: "center",
};

const dataRows = inventory.map((item) => [
  item.id,
  item.title,
  item.date,
  item.origin,
  item.currentResource,
  item.canonicalResource,
  item.categories,
  item.localStatus,
  item.action,
  item.eventCandidate,
  item.relation,
  item.source,
  item.notes,
  null,
]);
const inventoryStart = 6;
const inventoryEnd = inventoryStart + dataRows.length - 1;
inventorySheet.getRange(`A${inventoryStart}:N${inventoryEnd}`).values = dataRows;
inventorySheet.getRange(`C${inventoryStart}:C${inventoryEnd}`).format.numberFormat = "yyyy-mm-dd";
inventorySheet.getRange(`N${inventoryStart}`).formulas = [[
  `=IF(COUNTIF($B$${inventoryStart}:$B$${inventoryEnd},B${inventoryStart})>1,"Título repetido","")`,
]];
inventorySheet.getRange(`N${inventoryStart}:N${inventoryEnd}`).fillDown();

inventorySheet.getRange(`A${inventoryStart}:N${inventoryEnd}`).format = {
  font: { color: colors.ink, size: 10 },
  verticalAlignment: "top",
  wrapText: true,
  borders: {
    insideHorizontal: { style: "thin", color: colors.border },
  },
};
inventorySheet.getRange(`A${inventoryStart}:A${inventoryEnd}`).format.font = {
  color: colors.gray,
  size: 9,
};
inventorySheet.getRange(`H${inventoryStart}:H${inventoryEnd}`).conditionalFormats.add(
  "containsText",
  { text: "Corregir", format: { fill: colors.red, font: { bold: true, color: colors.ink } } },
);
inventorySheet.getRange(`J${inventoryStart}:J${inventoryEnd}`).conditionalFormats.add(
  "containsText",
  { text: "Revisar", format: { fill: colors.amber, font: { bold: true, color: colors.ink } } },
);
inventorySheet.getRange(`N${inventoryStart}:N${inventoryEnd}`).conditionalFormats.add(
  "containsText",
  { text: "repetido", format: { fill: colors.red, font: { bold: true, color: colors.ink } } },
);
inventorySheet.getRange(`J${inventoryStart}:J${inventoryEnd}`).dataValidation = {
  rule: { type: "list", values: ["No", "Revisar vínculo", "No hasta validar"] },
};

inventorySheet.tables.add(`A5:N${inventoryEnd}`, true, "InventarioAgendaTable");
inventorySheet.freezePanes.freezeRows(5);
inventorySheet.freezePanes.freezeColumns(2);
inventorySheet.getRange("A1:N1").format.rowHeight = 32;
inventorySheet.getRange("A2:N2").format.rowHeight = 38;
inventorySheet.getRange("A5:N5").format.rowHeight = 34;
inventorySheet.getRange(`A${inventoryStart}:N${inventoryEnd}`).format.rowHeight = 54;
const inventoryWidths = [12, 42, 18, 18, 17, 18, 27, 18, 44, 20, 44, 38, 42, 17];
inventoryWidths.forEach((width, index) => {
  inventorySheet.getRangeByIndexes(0, index, inventoryEnd, 1).format.columnWidth = width;
});

// Resumen
summary.getRange("A1:H1").merge();
summary.getRange("A1").values = [["Agenda y archivo: inventario sin duplicaciones"]];
summary.getRange("A1:H1").format = {
  fill: colors.navy,
  font: { bold: true, color: colors.white, size: 20 },
  verticalAlignment: "center",
};
summary.getRange("A3:H3").merge();
summary.getRange("A3").values = [[
  "Objetivo: preservar todo el contenido real, definir un único dueño para cada dato y decidir los vínculos antes de crear registros Evento.",
]];
summary.getRange("A3:H3").format = {
  fill: colors.sand,
  font: { color: colors.ink, italic: true },
  wrapText: true,
};

const kpis = [
  ["Candidatos inventariados", `=COUNTA('Inventario'!$A$${inventoryStart}:$A$${inventoryEnd})`],
  ["Crónicas de Actualidad", `=COUNTIF('Inventario'!$E$${inventoryStart}:$E$${inventoryEnd},"Actualidad")`],
  ["Conferencias actuales", `=COUNTIF('Inventario'!$E$${inventoryStart}:$E$${inventoryEnd},"Conferencia")`],
  ["Etiquetas a corregir", `=COUNTIF('Inventario'!$H$${inventoryStart}:$H$${inventoryEnd},"Corregir etiqueta")`],
  ["Vínculos Evento a revisar", `=COUNTIF('Inventario'!$J$${inventoryStart}:$J$${inventoryEnd},"Revisar vínculo")`],
];
summary.getRange("A6:B10").values = kpis.map(([label]) => [label, null]);
kpis.forEach(([, formula], index) => {
  summary.getRange(`B${6 + index}`).formulas = [[formula]];
});
summary.getRange("A6:B10").format = {
  fill: colors.paleBlue,
  font: { color: colors.ink },
  borders: { preset: "outside", style: "thin", color: colors.border },
};
summary.getRange("A6:A10").format.font = { bold: true, color: colors.ink };
summary.getRange("B6:B10").format = {
  font: { bold: true, color: colors.navy, size: 16 },
  horizontalAlignment: "right",
  numberFormat: "#,##0",
};

summary.getRange("D6:H6").merge();
summary.getRange("D6").values = [["Decisión de arquitectura"]];
summary.getRange("D6:H6").format = {
  fill: colors.blue,
  font: { bold: true, color: colors.white },
};
summary.getRange("D7:H11").merge();
summary.getRange("D7").values = [[
  "Evento es dueño de fecha, lugar, institución, rol, tipo y estado. Actualidad es dueña de la crónica y galería. Conferencia es dueña del video o intervención. Cuando coinciden, se enlazan mediante evento_id; no se copian campos ni archivos.",
]];
summary.getRange("D7:H11").format = {
  fill: colors.green,
  font: { color: colors.ink, size: 12 },
  wrapText: true,
  verticalAlignment: "center",
  borders: { preset: "outside", style: "thin", color: colors.border },
};

summary.getRange("A14:H14").merge();
summary.getRange("A14").values = [["Orden de trabajo recomendado"]];
summary.getRange("A14:H14").format = {
  fill: colors.blue,
  font: { bold: true, color: colors.white },
};
summary.getRange("A15:H18").values = [
  ["1", "Corregir la etiqueta faltante en Actualidad.", null, null, null, null, null, null],
  ["2", "Extraer la agenda pública y comparar por título, fecha e institución.", null, null, null, null, null, null],
  ["3", "Crear Evento solo cuando no exista una ficha canónica equivalente.", null, null, null, null, null, null],
  ["4", "Agregar relaciones evento_id desde Blog/Conferencia y validar la vista unificada.", null, null, null, null, null, null],
];
for (let row = 15; row <= 18; row += 1) summary.getRange(`B${row}:H${row}`).merge();
summary.getRange("A15:H18").format = {
  fill: colors.light,
  font: { color: colors.ink },
  wrapText: true,
  borders: { insideHorizontal: { style: "thin", color: colors.border } },
};
summary.getRange("A15:A18").format = {
  fill: colors.navy,
  font: { bold: true, color: colors.white },
  horizontalAlignment: "center",
};
summary.freezePanes.freezeRows(1);
summary.getRange("A1:H1").format.rowHeight = 36;
summary.getRange("A3:H3").format.rowHeight = 42;
summary.getRange("D7:H11").format.rowHeight = 28;
summary.getRange("A15:H18").format.rowHeight = 30;
summary.getRange("A:A").format.columnWidth = 28;
summary.getRange("B:B").format.columnWidth = 14;
summary.getRange("C:C").format.columnWidth = 4;
summary.getRange("D:H").format.columnWidth = 16;

// Modelo canónico
modelSheet.getRange("A1:F1").merge();
modelSheet.getRange("A1").values = [["Modelo canónico de contenido"]];
modelSheet.getRange("A1:F1").format = {
  fill: colors.navy,
  font: { bold: true, color: colors.white, size: 18 },
};
modelSheet.getRange("A4:F4").values = [[
  "Recurso",
  "Qué conserva",
  "Qué no debe repetir",
  "Relación propuesta",
  "Cuándo se crea",
  "Ejemplo",
]];
modelSheet.getRange("A4:F4").format = {
  fill: colors.blue,
  font: { bold: true, color: colors.white },
  wrapText: true,
};
modelSheet.getRange("A5:F8").values = [
  [
    "Evento",
    "Fecha, horario, lugar, ciudad, país, institución, rol, tipo, modalidad, inscripción y estado",
    "Crónica completa, galería, transcripción o video duplicado",
    "Es el padre canónico; Blog y Conferencia pueden apuntar con evento_id",
    "Solo para actividades verificadas que deban aparecer en Agenda",
    "Congreso con fecha y sede confirmadas",
  ],
  [
    "Actualidad / Blog",
    "Crónica editorial, contexto, citas, galería y documentos periodísticos",
    "Fecha/lugar/tipo ya definidos en Evento",
    "evento_id nullable; si existe, la interfaz lee metadatos desde Evento",
    "Cuando existe una noticia o crónica propia",
    "Nota posterior a una jornada",
  ],
  [
    "Conferencia",
    "Video, audio, título de la intervención, enlace externo y material audiovisual",
    "Ficha institucional completa del encuentro si ya existe Evento",
    "evento_id nullable; puede existir sola si no hay actividad verificable",
    "Cuando hay una intervención o pieza audiovisual reutilizable",
    "Video de una exposición",
  ],
  [
    "Página / Bloque",
    "Selección, filtros, orden y texto editorial de presentación",
    "Copias manuales de eventos, crónicas o conferencias",
    "Consulta los recursos canónicos; guarda IDs solo para selección editorial",
    "Cuando se necesita una composición de página",
    "Agenda y archivo unificados",
  ],
];
modelSheet.getRange("A5:F8").format = {
  wrapText: true,
  verticalAlignment: "top",
  borders: { insideHorizontal: { style: "thin", color: colors.border } },
};
modelSheet.getRange("A5:A8").format = {
  fill: colors.paleBlue,
  font: { bold: true, color: colors.navy },
};
modelSheet.tables.add("A4:F8", true, "ModeloCanonicoTable");
modelSheet.freezePanes.freezeRows(4);
modelSheet.getRange("A1:F1").format.rowHeight = 32;
modelSheet.getRange("A4:F4").format.rowHeight = 34;
modelSheet.getRange("A5:F8").format.rowHeight = 72;
[20, 42, 38, 42, 38, 32].forEach((width, index) => {
  modelSheet.getRangeByIndexes(0, index, 8, 1).format.columnWidth = width;
});

// Fuentes
sourcesSheet.getRange("A1:F1").merge();
sourcesSheet.getRange("A1").values = [["Fuentes y cobertura"]];
sourcesSheet.getRange("A1:F1").format = {
  fill: colors.navy,
  font: { bold: true, color: colors.white, size: 18 },
};
sourcesSheet.getRange("A4:F4").values = [[
  "Fuente",
  "Ubicación",
  "Cobertura",
  "Estado",
  "Uso",
  "Riesgo / control",
]];
sourcesSheet.getRange("A4:F4").format = {
  fill: colors.blue,
  font: { bold: true, color: colors.white },
  wrapText: true,
};
sourcesSheet.getRange("A5:F9").values = [
  [
    "Exportación WordPress local",
    sourcePath,
    `${source.total} publicaciones; ${legacyPosts.length} clasificadas como Jornadas & Congresos`,
    "Disponible",
    "Archivo histórico y control de taxonomía",
    "La fecha de publicación no siempre coincide con la fecha del evento",
  ],
  [
    "CMS local — Blog",
    "Base de datos local",
    `${localBlogs.length} publicaciones actuales; 39 conservan la etiqueta de Jornadas`,
    "Disponible",
    "Fuente operativa de Actualidad",
    "Una publicación multicategoría perdió la etiqueta Jornadas",
  ],
  [
    "CMS local — Conferencia",
    "Base de datos local",
    `${localConferences.length} conferencias; ${localConferences.filter((item) => item.date).length} con fecha`,
    "Disponible",
    "Archivo audiovisual",
    "No convertir en Evento sin fecha e institución verificadas",
  ],
  [
    "Agenda pública legacy",
    "https://marcelabasterra.com.ar/category/presentaciones-home/",
    "Tarjetas históricas con fecha, horario, lugar, tipo y enlaces",
    "Pendiente de extracción controlada",
    "Candidatos para Evento realizado",
    "Mezcla jornadas, libros, cursos y otras actividades; deduplicar antes de importar",
  ],
  [
    "Archivo público legacy",
    "https://marcelabasterra.com.ar/category/jornadas-y-congresos/",
    "Crónicas de jornadas y congresos",
    "Cubierto por la exportación local",
    "Verificación visual y enlaces históricos",
    "No volver a importar como un segundo Blog",
  ],
];
sourcesSheet.getRange("A5:F9").format = {
  wrapText: true,
  verticalAlignment: "top",
  borders: { insideHorizontal: { style: "thin", color: colors.border } },
};
sourcesSheet.getRange("D5:D9").conditionalFormats.add("containsText", {
  text: "Pendiente",
  format: { fill: colors.amber, font: { bold: true, color: colors.ink } },
});
sourcesSheet.tables.add("A4:F9", true, "FuentesAgendaTable");
sourcesSheet.freezePanes.freezeRows(4);
sourcesSheet.getRange("A1:F1").format.rowHeight = 32;
sourcesSheet.getRange("A4:F4").format.rowHeight = 34;
sourcesSheet.getRange("A5:F9").format.rowHeight = 66;
[28, 54, 42, 30, 36, 46].forEach((width, index) => {
  sourcesSheet.getRangeByIndexes(0, index, 9, 1).format.columnWidth = width;
});

await fs.mkdir(outputDir, { recursive: true });
await fs.mkdir(previewDir, { recursive: true });

const inspect = await workbook.inspect({
  kind: "table",
  range: `Resumen!A1:H18`,
  include: "values,formulas",
  tableMaxRows: 20,
  tableMaxCols: 10,
});
console.log(inspect.ndjson);

const errors = await workbook.inspect({
  kind: "match",
  searchTerm: "#REF!|#DIV/0!|#VALUE!|#NAME\\?|#N/A",
  options: { useRegex: true, maxResults: 300 },
  summary: "final formula error scan",
});
console.log(errors.ndjson);

for (const [sheetName, range] of [
  ["Resumen", "A1:H18"],
  ["Inventario", `A1:N18`],
  ["Modelo canónico", "A1:F8"],
  ["Fuentes", "A1:F9"],
]) {
  const preview = await workbook.render({ sheetName, range, scale: 1, format: "png" });
  await fs.writeFile(
    `${previewDir}/${sheetName.toLowerCase().replaceAll(" ", "-").replaceAll("ó", "o")}.png`,
    new Uint8Array(await preview.arrayBuffer()),
  );
}

const xlsx = await SpreadsheetFile.exportXlsx(workbook);
await xlsx.save(outputPath);
console.log(JSON.stringify({ outputPath, inventoryRows: inventory.length, inventoryEnd }));
