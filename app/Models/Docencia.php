<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docencia extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.docencia_parent_id');
    }

    protected $fillable = [
        'institucion', 'materia', 'catedra', 'nivel', 'descripcion', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
    ];
}
