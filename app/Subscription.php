<?php

namespace App;

// use NumberFormatter;
use App\Price;
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

    protected $appends = ['total_dancer', 'credit_sub_total', 'factured_sub_total', 'sub_total', 'sub_total_payment', 'tps', 'tps_payment', 'tvq', 'tvq_payment', 'tvh', 'tvh_payment', 'total', 'total_payment', 'sum_payments', 'balance'];


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
        $year = session()->get('YEAR') ? session()->get('YEAR') : now()->addYear()->year;

        $subtotal = 0;

        foreach ($this->routines as $routine) {
            $price = Price::where('category_id', $routine->category->id)->where('year', $year)->first();
            if (!$routine->doc_number) {
                $total_cost = (count($routine->dancers) * $price->rebate_price);
                $subtotal += $total_cost;
            }
        }
        foreach ($this->fees as $fee) {
            $total_cost = ($fee->feeType->price * $fee->entries);
            $subtotal += $total_cost;
        }
        return number_format(($subtotal / 100), 2, '.', '');
    }
    public function getTpsAttribute()
    {
        $tps = $this->getSubTotalAttribute() * env('TAX_TPS');

        return number_format(($tps), 2, '.', '');
    }
    public function getTvqAttribute()
    {
        $tvq = $this->getSubTotalAttribute() * env('TAX_TVQ');
        return number_format(($tvq), 2, '.', '');
    }
    public function getTvhAttribute()
    {
        $tvq = $this->getSubTotalAttribute() * env('TAX_TVH');
        return number_format(($tvq), 2, '.', '');
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

        return number_format($sum, 2, '.', '');
    }
    public function getBalanceAttribute()
    {
        $total = $this->getTotalPaymentAttribute();
        $payments = $this->getSumPaymentsAttribute();

        //$balance = (($total - $payments) < 0) ? 0 : ($total - $payments);
        $balance = ($total - $payments);

        return number_format(round($balance, 2), 2, '.', '');
    }

    // Re write all Payment calculations here without using Doc_number

    public function getSubTotalPaymentAttribute()
    {
        $year = session()->get('YEAR') ? session()->get('YEAR') : now()->addYear()->year;

        $subtotal = 0;

        // foreach ($this->routines as $routine) {
        //     $price = Price::where('category_id', $routine->category->id)->where('year', $year)->first();
        //     if ($routine->doc_number) {
        //         $total_cost = (count($routine->dancers) * $price->rebate_price);
        //         $subtotal += $total_cost;
        //     }
        // }
        // foreach ($this->fees as $fee) {
        //     $total_cost = ($fee->feeType->price * $fee->entries);
        //     $subtotal += $total_cost;
        // }
        $factured = $this->getFacturedSubTotalAttribute();
        $credit = $this->getCreditSubTotalAttribute();
        $subtotal = ($factured - $credit);

        // dd($subtotal);
        return number_format(($subtotal), 2, '.', '');
    }

    // Get Fatured Sub total
    public function getFacturedSubTotalAttribute()
    {
        $allinvoices = Invoice::whereHas('categories')->groupBy('doc_number')->where('subscription_id', $this->id)->get();
        return number_format($allinvoices->sum('total'), 2, '.', '');
    }


    //Get Credit Sub total
    public function getCreditSubTotalAttribute()
    {
        $allcredits = Credit::whereHas('categories')->where('subscription_id', $this->id)->get();
        return number_format($allcredits->sum('total'), 2, '.', '');
    }

    public function getTpsPaymentAttribute()
    {
        $tps = $this->getSubTotalPaymentAttribute() * env('TAX_TPS');

        return number_format(($tps), 2, '.', '');
    }
    public function getTvqPaymentAttribute()
    {
        $tvq = $this->getSubTotalPaymentAttribute() * env('TAX_TVQ');
        return number_format(($tvq), 2, '.', '');
    }
    public function getTvhPaymentAttribute()
    {
        $tvh = $this->getSubTotalPaymentAttribute() * env('TAX_TVH');
        return number_format(($tvh), 2, '.', '');
    }

    public function getTotalPaymentAttribute()
    {
        // TODO Better way handle taxes
        if ($this->event->state_id == 58) { // Ontario

            $total = $this->getSubTotalPaymentAttribute() + $this->getTvhPaymentAttribute();
        } elseif ($this->event->state_id == 57) {
            $total = $this->getSubTotalPaymentAttribute() + $this->getTpsPaymentAttribute() + $this->getTvqPaymentAttribute();
        }
        return number_format(round($total, 2), 2, '.', '');
    }
}
