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
        'description',
        'datetime',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'id_service');
    }
    
    public function scopeFilter(Builder $builder, QueryFilter $filters){
        return $filters->apply($builder);
    }

    //TODO: Visto bueno con equipo
}
