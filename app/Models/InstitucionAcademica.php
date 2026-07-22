<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitucionAcademica extends Model
{
    use HasFactory;
    use HasRoute;

    protected $table = 'instituciones_academicas';

    protected $fillable = [
        'sigla',
        'pais',
        'alcance',
        'sitio_web',
        'logo',
        'destacada',
        'orden',
        'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'destacada' => 'boolean',
        'orden' => 'integer',
    ];

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.academic_institutions_parent_id')
            ?: Route::whereFullSlug('actividad-academica')->value('id');
    }

    public function docencias(): HasMany
    {
        return $this->hasMany(Docencia::class);
    }
}
