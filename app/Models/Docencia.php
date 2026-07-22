<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Docencia extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.docencia_parent_id');
    }

    protected $fillable = [
        'institucion_academica_id', 'institucion', 'facultad', 'programa',
        'materia', 'catedra', 'rol', 'nivel', 'modalidad', 'periodo',
        'enlace', 'vigente', 'orden', 'descripcion', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'vigente' => 'boolean',
        'orden' => 'integer',
    ];

    public function institucionAcademica(): BelongsTo
    {
        return $this->belongsTo(InstitucionAcademica::class);
    }
}
