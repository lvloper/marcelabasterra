<?php

namespace App\Http\Controllers;

use App\Models\ArticuloAcademico;
use App\Models\Route;
use Illuminate\Http\Request;

class ArticuloAcademicoController extends Controller
{
    public function show(Request $request, Route $route, ArticuloAcademico $articuloAcademico)
    {
        return view('articulo-academico.show', [
            'articuloAcademico' => $articuloAcademico,
            'route' => $route,
        ]);
    }
}
