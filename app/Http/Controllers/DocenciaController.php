<?php

namespace App\Http\Controllers;

use App\Models\Docencia;
use App\Models\Route;
use Illuminate\Http\Request;

class DocenciaController extends Controller
{
    public function show(Request $request, Route $route, Docencia $docencia)
    {
        return view('docencia.show', [
            'docencia' => $docencia,
            'route' => $route,
        ]);
    }
}
