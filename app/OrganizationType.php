<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class OrganizationType extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['name'];


    public function organizations()
    {
        return $this->hasMany('App\Organization');
    }

}
