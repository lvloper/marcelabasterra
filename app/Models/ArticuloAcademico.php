<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArticuloAcademico extends Model
{
    use HasFactory, HasRoute;

    protected $table = 'articulos_academicos';

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.academic_articles_parent_id')
            ?: Route::whereFullSlug('publicaciones/articulos-academicos')->value('id')
            ?: config('cms-routes.publicaciones_parent_id');
    }

    protected $fillable = [
        'resumen', 'contenido', 'fecha_publicacion', 'tematica',
        'archivo_pdf', 'archivo_pdf_url', 'destacado', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'destacado' => 'boolean',
        'fecha_publicacion' => 'date',
    ];

    public function getDocumentUrlAttribute(): ?string
    {
        if ($this->archivo_pdf_url) {
            return $this->archivo_pdf_url;
        }

        return $this->archivo_pdf ? Storage::url($this->archivo_pdf) : null;
    }
}
