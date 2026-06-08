<?php

namespace App\Http\Controllers;

use App\Models\Entrevista;
use App\Models\Route;
use Illuminate\Http\Request;

class EntrevistaController extends Controller
{
    public function show(Request $request, Route $route, Entrevista $entrevista)
    {
        return view('entrevista.show', [
            'entrevista' => $entrevista,
            'route' => $route,
        ]);
    }
}
