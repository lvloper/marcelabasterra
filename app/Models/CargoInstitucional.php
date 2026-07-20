<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoInstitucional extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.trayectoria_parent_id');
    }

    protected $fillable = [
        'cargo', 'institucion', 'fecha_inicio', 'fecha_fin', 'descripcion', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}
