<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'price_list';

    protected $appends = ['formatted_price', 'formatted_rebate_price'];


    public function Category()
    {
        return $this->belongsTo('App\Category');
    }


    public function getFormattedPriceAttribute()
    {
        // return NumberFormatter('en_US', NumberFormatter::CURRENCY)::formatCurrency(($this->attributes['price'] / 100));
        //attributes['price']
        return number_format(($this->attributes['price']  / 100), 2, '.', '');
    }
    public function getFormattedRebatePriceAttribute()
    {
        // return $formatter =  NumberFormatter('en_US', NumberFormatter::CURRENCY)::formatCurrency(($this->attributes['rebate_price'] / 100));
        // dd($this->price->rebate_price);
        return number_format(($this->attributes['rebate_price'] / 100), 2, '.', '');
    }
}
