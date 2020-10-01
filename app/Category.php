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
        return sprintf('%01.2f', ($this->attributes['price'] / 100));
    }
    public function getFormattedRebatePriceAttribute()
    {
        return sprintf('%01.2f', ($this->attributes['rebate_price'] / 100));
    }
    
}
