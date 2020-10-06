<?php

namespace App;

use NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

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
        // return NumberFormatter('en_US', NumberFormatter::CURRENCY)::formatCurrency(($this->attributes['price'] / 100));

        return number_format(($this->attributes['price'] / 100), 2, '.', '');
    }
    public function getFormattedRebatePriceAttribute()
    {
        // return $formatter =  NumberFormatter('en_US', NumberFormatter::CURRENCY)::formatCurrency(($this->attributes['rebate_price'] / 100));

        return number_format(($this->attributes['rebate_price'] / 100), 2, '.', '');
    }
}
