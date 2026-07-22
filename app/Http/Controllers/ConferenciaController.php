<?php

namespace App\Http\Controllers;

use App\Models\Conferencia;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConferenciaController extends Controller
{
    public function show(Request $request, Route $route, Conferencia $conferencia): View
    {
        return view('conferencia.show', compact('conferencia', 'route'));
    }
}
