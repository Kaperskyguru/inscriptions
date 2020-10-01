<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTypeTranslation extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['name'];
}
