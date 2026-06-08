<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Route;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function show(Request $request, Route $route, Evento $evento)
    {
        return view('evento.show', [
            'evento' => $evento,
            'route' => $route,
        ]);
    }
}
