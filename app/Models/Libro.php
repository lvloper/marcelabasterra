<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.publicaciones_parent_id');
    }

    protected $fillable = [
        'subtitulo', 'portada', 'descripcion', 'fecha_publicacion',
        'editorial', 'isbn', 'enlaces', 'destacado', 'blocks',
    ];

    protected $casts = [
        'enlaces' => 'collection',
        'blocks' => 'collection',
        'destacado' => 'boolean',
        'fecha_publicacion' => 'date',
    ];
}
