<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaAcademico extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.programas_parent_id');
    }

    protected $fillable = [
        'descripcion', 'institucion', 'fecha_inicio', 'fecha_fin', 'enlace', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}
