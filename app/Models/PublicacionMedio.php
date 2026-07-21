<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicacionMedio extends Model
{
    use HasFactory;
    use HasRoute;

    protected $table = 'publicaciones_medios';

    protected $fillable = [
        'tipo',
        'medio',
        'fecha',
        'resumen',
        'enlace_externo',
        'autoria',
        'tematica',
        'destacado',
        'blocks',
    ];

    protected $casts = [
        'fecha' => 'date',
        'destacado' => 'boolean',
        'blocks' => 'collection',
    ];

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.prensa_parent_id');
    }
}
