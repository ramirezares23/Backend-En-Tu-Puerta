<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\User;

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

    //TODO: Visto bueno con equipo
}
