<?php

namespace App\Http\Filters\V1;

class UserFilter extends QueryFilter
{

    public function include($value)
    {
        return $this->builder->with($value);
    }
    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where('firstname', 'like', $likeStr);
    }

    // Agregar este método para buscar por fullname
    public function fullname($value)
    {
        $likeStr = '%' . $value . '%'; // Buscamos cualquier coincidencia en nombre o apellido
        return $this->builder->where(function ($query) use ($likeStr) {
            $query->where('first_name', 'like', $likeStr)
                ->orWhere('last_name', 'like', $likeStr);
        });
    }
}