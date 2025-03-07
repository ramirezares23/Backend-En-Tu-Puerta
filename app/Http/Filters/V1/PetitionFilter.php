<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class PetitionFilter extends QueryFilter
{

    public function include($value)
    {
        return $this->builder->with($value);
    }

    public function status($value)
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }
    public function user($value)
    {
        return $this->builder->whereIn('id_user', explode(',', $value));
    }

    public function provider($value)
    {
        return $this->builder->whereHas('service', function (Builder $query) use ($value) {
            $query->where('id_provider', $value);
        });
    }

}