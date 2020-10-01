<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];

    public function states()
    {
        return $this->hasMany('App\State');
    }
    public function organizations()
    {
        return $this->hasMany('App\Organization');
    }
}
