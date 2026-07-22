<?php

declare(strict_types=1);

namespace App\Filament\Resources\InstitucionAcademicaResource\Pages;

use App\Filament\Resources\Bases\ListBase;
use App\Filament\Resources\InstitucionAcademicaResource;

class ListInstitucionesAcademicas extends ListBase
{
    protected static string $resource = InstitucionAcademicaResource::class;
}
