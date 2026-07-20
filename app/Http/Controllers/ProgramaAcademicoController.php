<?php

namespace App\Http\Controllers;

use App\Models\ProgramaAcademico;
use App\Models\Route;
use Illuminate\Http\Request;

class ProgramaAcademicoController extends Controller
{
    public function show(Request $request, Route $route, ProgramaAcademico $programaAcademico)
    {
        return view('programa-academico.show', [
            'programaAcademico' => $programaAcademico,
            'route' => $route,
        ]);
    }
}
