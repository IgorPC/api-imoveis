<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'adresses';
    protected $fillable = ['state_id', 'city_id', 'address', 'number', 'neighborhood', 'complement', 'zip_code'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function real_state()
    {
        return $this->hasOne(RealState::class);
    }
}
