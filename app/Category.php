<?php

namespace App;

use App\Price;
use NumberFormatter;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Category extends Model implements TranslatableContract
{
    //
    use Translatable;
    public $translatedAttributes = ['name'];

    protected $appends = ['formatted_rebate_price'];


    public function eventType()
    {
        return $this->belongsTo('App\EventType');
    }

    public function prices()
    {
        return $this->hasMany('App\Price');
    }


    public function routines()
    {
        return $this->hasMany('App\Routine')->withCount('dancers');
    }

    // public function getRebatePriceAttribute()
    // {
    //     return $this->price->rebate_price;
    // }

    public function getFormattedRebatePriceAttribute()
    {
        $year = session()->get('YEAR', now()->addYear()->year);

        $price = Price::where('category_id', $this->id)->where('year', $year)->first();
        return $price->formatted_rebate_price;
    }

    // public function getPriceAttribute()
    // {
    //     dd($this->price);
    //     return $this->price->price;
    // }

    public function invoices()
    {
        return $this->belongsToMany('App\Invoice', 'category_invoices', 'category_id', 'invoice_id');
    }

    public function dancerRoutines()
    {
        return $this->hasManyThrough(
            'App\DancerRoutine',
            'App\Routine',
            'category_id', // Foreign key on DancerRoutine table...
            'routine_id', // Foreign key on Routine table...
            'id', // Local key on categories table...
            'id' // Local key on DancerRoutine table...
        );
    }
}
