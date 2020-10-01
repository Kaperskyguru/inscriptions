<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = ['organization_id', 'event_id', 'status_id', 'consent_video', 'consent_rules'];
    
    protected $attributes = [
        'status_id' => 1,
        'consent_video' => 0,
        'consent_rules' => 0,
    ];

    protected $appends = ['total_dancer', 'sub_total', 'tps', 'tvq', 'tvh', 'total', 'sum_payments', 'balance'];

   
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }
    public function status()
    {
        return $this->belongsTo('App\Status');
    }
    public function routines()
    {
        return $this->hasMany('App\Routine')->orderBy('category_id');
    }
    public function payments()
    {
        return $this->hasMany('App\Payment')->orderBy('receive_on');
    }
    public function fees()
    {
        return $this->hasMany('App\Fee');
    }
    public function getTotalDancerAttribute()
    {
        $sum = $this->routines->reduce(function ($total, $routine) {
            return $total + count($routine->dancers);
        }, 0);

        return $sum;
    }
    public function getSubTotalAttribute()
    {
        $subtotal = 0;

        foreach ($this->routines as $routine) {
            $total_cost = (count($routine->dancers) * $routine->category->rebate_price);
            $subtotal += $total_cost;
        }
        foreach ($this->fees as $fee) {
            $total_cost = ($fee->feeType->price * $fee->entries);
            $subtotal += $total_cost;
        }

        return sprintf('%01.2f', ($subtotal / 100)); //sprintf('%01.2f', ($subtotal / 100));
    }
    public function getTpsAttribute()
    {
        $tps = $this->getSubTotalAttribute() * env('TAX_TPS');
        return sprintf('%01.2f', ($tps));
    }
    public function getTvqAttribute()
    {
        $tvq = $this->getSubTotalAttribute() * env('TAX_TVQ');
        return sprintf('%01.2f', ($tvq));
    }
    public function getTvhAttribute()
    {
        $tvq = $this->getSubTotalAttribute() * env('TAX_TVH');
        return sprintf('%01.2f', ($tvq));
    }
    public function getTotalAttribute()
    {
        // TODO Better way handle taxes
        if ($this->event->state_id == 58) { // Ontario

            $total = $this->getSubTotalAttribute() + $this->getTvhAttribute();
        } elseif ($this->event->state_id == 57) {
            $total = $this->getSubTotalAttribute() + $this->getTpsAttribute() + $this->getTvqAttribute();
        }
        return round($total, 2);
    }
    public function getSumPaymentsAttribute()
    {
        $sum = $this->payments->reduce(function ($total, $payment) {
            return $total + $payment->amount;
        }, 0);

        return sprintf('%01.2f', ($sum / 100));
    }
    public function getBalanceAttribute()
    {
        $total = $this->getTotalAttribute();
        $payments = $this->getSumPaymentsAttribute();

        //$balance = (($total - $payments) < 0) ? 0 : ($total - $payments);
        $balance = ($total - $payments);

        return sprintf('%01.2f', round($balance, 2));
    }
}
