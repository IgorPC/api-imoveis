<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $appends = ['_links', 'thumb'];
    protected $fillable = ['user_id', 'title', 'description', 'content', 'price', 'bathrooms', 'bedrooms',
        'property_area', 'total_property_area', 'slug'];


    public function getLinksAttribute()
    {
        return [
            'href' => route('api.real-states.show', ['real_state' => $this->id]),
            'rel' => 'Imoveis'
            ];
    }

    public function getThumbAttribute()
    {
        $thumb = $this->photos()->where('is_thumb', true);
        if($thumb->count() == 0){
            return null;
        }else{
            return $thumb->first()->photo;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }

    public function photos()
    {
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
