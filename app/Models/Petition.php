<?php

namespace App\Models;

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
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    //TODO: Visto bueno con equipo
}
