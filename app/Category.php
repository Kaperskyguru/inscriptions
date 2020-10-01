<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];

    protected $appends = ['formatted_price', 'formatted_rebate_price'];


    public function eventType()
    {
        return $this->belongsTo('App\EventType');
    }

    public function routines()
    {
        return $this->hasMany('App\Routine')->withCount('dancers');
    }

    public function getFormattedPriceAttribute()
    {
        return money_format('%i', ($this->attributes['price'] / 100));
    }
    public function getFormattedRebatePriceAttribute()
    {
        return money_format('%i', ($this->attributes['rebate_price'] / 100));
    }
    
}
