<?php

namespace App\Http\Controllers;

use App\Models\ArticuloAcademico;
use App\Models\Route;
use Illuminate\Http\Request;

class ArticuloAcademicoController extends Controller
{
    public function show(Request $request, Route $route, ArticuloAcademico $articuloAcademico)
    {
        abort_unless($articuloAcademico->document_url, 404);

        return redirect()->away($articuloAcademico->document_url);
    }
}
