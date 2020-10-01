<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class EventType extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];

    public function events()
    {
        return $this->hasMany('App\Event');
    }
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
    public function classifications()
    {
        return $this->hasMany('App\Classification');
    }
}
