<?php

namespace App\Http\Filters\V1;

class ServiceFilter extends QueryFilter
{

    public function include($value)
    {
        return $this->builder->with($value);
    }

    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('service_name', 'like', $likeStr);
    }
}