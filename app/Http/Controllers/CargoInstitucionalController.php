<?php

namespace App\Http\Controllers;

use App\Models\CargoInstitucional;
use App\Models\Route;
use Illuminate\Http\Request;

class CargoInstitucionalController extends Controller
{
    public function show(Request $request, Route $route, CargoInstitucional $cargoInstitucional)
    {
        return view('cargo-institucional.show', [
            'cargoInstitucional' => $cargoInstitucional,
            'route' => $route,
        ]);
    }
}
