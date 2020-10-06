<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = ['payment_type_id', 'subscription_id', 'receive_on', 'amount', 'note'];

    protected $appends = ['formatted_amount'];

    protected $attributes = [
        'note' => '',
    ];

    public function paymentType()
    {
        return $this->belongsTo('App\PaymentType');
    }

    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format(($this->attributes['amount'] / 100), 2, '.', '');
    }
}
