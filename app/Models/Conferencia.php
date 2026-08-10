<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conferencia extends Model
{
    use HasFactory, HasRoute;

    protected $fillable = [
        'tipo', 'institucion', 'ubicacion', 'ciudad', 'pais', 'tematica', 'fecha', 'descripcion', 'imagen', 'external_url',
        'link_label', 'destacado', 'blocks',
    ];

    protected $casts = [
        'fecha' => 'date',
        'destacado' => 'boolean',
        'blocks' => 'collection',
    ];

    public function getDefaultRouteParentId(): ?int
    {
        return Route::query()
            ->whereFullSlug('actividad-academica/conferencias')
            ->where('routable_type', Page::class)
            ->value('id')
            ?: config('cms-routes.conferences_parent_id')
            ?: Route::query()
            ->where('slug', 'jornadas-y-congresos')
            ->where('routable_type', Page::class)
            ->value('id')
            ?: config('cms-routes.agenda_parent_id');
    }
}
