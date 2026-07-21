<?php
return [
    'custom_controllers' => [
        'App\Models\Blog' => 'App\Http\Controllers\BlogController',
        'App\Models\Libro' => 'App\Http\Controllers\LibroController',
        'App\Models\ArticuloAcademico' => 'App\Http\Controllers\ArticuloAcademicoController',
        'App\Models\Entrevista' => 'App\Http\Controllers\EntrevistaController',
        'App\Models\Evento' => 'App\Http\Controllers\EventoController',
        'App\Models\ProgramaAcademico' => 'App\Http\Controllers\ProgramaAcademicoController',
        'App\Models\CargoInstitucional' => 'App\Http\Controllers\CargoInstitucionalController',
        'App\Models\Docencia' => 'App\Http\Controllers\DocenciaController',
        'App\Models\DossierPrensa' => 'App\Http\Controllers\DossierPrensaController',
    ],
    'blog_index' => 'blog',
    'news_parent_id' => null,

    // IDs de rutas padre para recursos del CMS
    'publicaciones_parent_id' => 6,
    'books_parent_id' => env('CMS_BOOKS_PARENT_ID'),
    'academic_articles_parent_id' => env('CMS_ACADEMIC_ARTICLES_PARENT_ID'),
    'prensa_parent_id' => 7,
    'agenda_parent_id' => 8,
    'programas_parent_id' => 9,
    'docencia_parent_id' => 10,
    'trayectoria_parent_id' => 11,
];
