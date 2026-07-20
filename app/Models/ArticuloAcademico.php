<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloAcademico extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.publicaciones_parent_id');
    }

    protected $fillable = [
        'resumen', 'contenido', 'fecha_publicacion', 'tematica',
        'archivo_pdf', 'destacado', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'destacado' => 'boolean',
        'fecha_publicacion' => 'date',
    ];
}
