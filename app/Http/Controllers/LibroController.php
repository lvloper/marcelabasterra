<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Route;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function show(Request $request, Route $route, Libro $libro)
    {
        return view('libro.show', [
            'libro' => $libro,
            'route' => $route,
        ]);
    }
}
