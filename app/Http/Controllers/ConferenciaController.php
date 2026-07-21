<?php

namespace App\Http\Controllers;

use App\Models\Conferencia;
use App\Models\Route;
use Illuminate\Http\Request;

class ConferenciaController extends Controller
{
    public function show(Request $request, Route $route, Conferencia $conferencia)
    {
        return view('conferencia.show', compact('conferencia', 'route'));
    }
}
