<?php

namespace App\Models;

use App\Models\Traits\HasRoute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierPrensa extends Model
{
    use HasFactory, HasRoute;

    public function getDefaultRouteParentId(): ?int
    {
        return config('cms-routes.prensa_parent_id');
    }

    protected $fillable = [
        'archivo', 'descripcion', 'fecha', 'blocks',
    ];

    protected $casts = [
        'blocks' => 'collection',
        'fecha' => 'date',
    ];
}
