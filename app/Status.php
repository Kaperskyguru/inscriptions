<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Status extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['admin_label', 'public_label'];

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
}
