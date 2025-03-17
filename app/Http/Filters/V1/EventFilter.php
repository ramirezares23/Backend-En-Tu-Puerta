<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class EventFilter extends QueryFilter
{

    public function include($value)
    {
        return $this->builder->with($value);
    }
    public function client($value)
    {
        return $this->builder->whereIn('client_id', explode(',', $value));
    }

    public function provider($value)
    {
        return $this->builder->whereHas('provider', function (Builder $query) use ($value) {
            $query->where('provider_id', $value);
        });
    }

}