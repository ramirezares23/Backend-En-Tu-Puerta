<?php

namespace App\Models;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Petition;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'service_price'
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_provider');
    }

    public function petitions(){
        return $this->hasMany(Petition::class, 'id_service');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters){
        return $filters->apply($builder);
    }
}
