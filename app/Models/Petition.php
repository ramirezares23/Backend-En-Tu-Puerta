<?php

namespace App\Models;

use App\Http\Filters\V1\PetitionFilter;
use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;

class Petition extends Model
{
    /** @use HasFactory<\Database\Factories\PetitionFactory> */
    use HasFactory;

    protected $fillable = [
        'id_user',
        'description',
        'type',
        'date',
        'status',
        'time',
        'message',
        'id_service'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    // provider que quiero implementar
    public function provider()
    {
        return $this->hasOneThrough(User::class, Service::class, 'id', 'id', 'id_service', 'id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    

    //TODO: Visto bueno con equipo
}
