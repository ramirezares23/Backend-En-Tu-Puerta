<?php

namespace App\Http\Filters\V1;

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
    
}