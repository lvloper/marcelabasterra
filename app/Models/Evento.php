<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.agenda_parent_id');
    }

    protected $fillable = [
        'descripcion', 'fecha_inicio', 'fecha_fin', 'ubicacion',
        'institucion', 'rol', 'modalidad', 'estado_confirmacion', 'imagen', 'video',
        'tipo', 'enlace_inscripcion', 'destacado', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'destacado' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}
