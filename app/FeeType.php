<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class FeeType extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];
    protected $appends = ['formatted_price'];


    public function fees()
    {
        return $this->hasMany('App\Fee');
    }
    public function getFormattedPriceAttribute()
    {
        return sprintf('%01.2f', ($this->attributes['price'] / 100));
    }
}
