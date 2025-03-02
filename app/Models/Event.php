<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['provider_id', 'client_id', 'service_id', 'date', 'time', 'status'];


    public function provider()
    {
        return $this->belongsTo(User::class,'provider_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'client_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
}
