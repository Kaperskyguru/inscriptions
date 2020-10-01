<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = ['fee_type_id', 'subscription_id', 'entries', 'note'];

    protected $attributes = [
        'note' => '',
    ];
    protected $appends = ['total_amount'];

    //
    public function feeType()
    {
        return $this->belongsTo('App\FeeType');
    }
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }
    public function getTotalAmountAttribute()
    {
        return sprintf('%01.2f', (($this->feeType->attributes['price'] * $this->attributes['entries'])/ 100));
    }
}
