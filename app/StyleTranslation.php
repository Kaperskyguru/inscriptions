<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StyleTranslation extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'note'];
}
