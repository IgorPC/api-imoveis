<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $fillable = ['state_id', 'name', 'slug'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function adresses()
    {
        return $this->hasMany(Address::class);
    }
}
