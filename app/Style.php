<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Style extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name', 'description', 'note'];

    public function routines()
    {
        return $this->hasMany('App\Routine');
    }
}
