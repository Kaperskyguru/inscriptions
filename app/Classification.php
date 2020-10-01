<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Classification extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name', 'schedule_title'];

    public function eventType()
    {
        return $this->belongsTo('App\EventType');
    }
    public function routines()
    {
        return $this->hasMany('App\Routine');
    }
}
