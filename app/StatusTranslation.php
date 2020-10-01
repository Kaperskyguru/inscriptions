<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusTranslation extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['admin_label', 'public_label'];
}
