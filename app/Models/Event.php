<?php

namespace App\Models;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'provider_id',
        'client_id',
        'service_id',
        'title',
        'date',
        'time',
        'status'
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
