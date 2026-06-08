<?php

namespace App\Http\Controllers;

use App\Models\DossierPrensa;
use App\Models\Route;
use Illuminate\Http\Request;

class DossierPrensaController extends Controller
{
    public function show(Request $request, Route $route, DossierPrensa $dossierPrensa)
    {
        return view('dossier-prensa.show', [
            'dossierPrensa' => $dossierPrensa,
            'route' => $route,
        ]);
    }
}
