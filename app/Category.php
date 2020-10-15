<?php

namespace App;

use App\Price;
use NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Category extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];

    protected $appends = ['formatted_rebate_price'];


    public function eventType()
    {
        return $this->belongsTo('App\EventType');
    }

    public function prices()
    {
        return $this->hasMany('App\Price');
    }


    public function routines()
    {
        return $this->hasMany('App\Routine')->withCount('dancers');
    }

    // public function getRebatePriceAttribute()
    // {
    //     return $this->price->rebate_price;
    // }

    public function getFormattedRebatePriceAttribute()
    {
        $price = Price::where('category_id', $this->id)->where('year', now()->addYear()->year)->first();
        return $price->formatted_rebate_price;
    }

    // public function getPriceAttribute()
    // {
    //     dd($this->price);
    //     return $this->price->price;
    // }
}
