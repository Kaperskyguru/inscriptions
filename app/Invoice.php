<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['doc_number', 'amount', 'subscription_id'];

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_invoices', 'invoice_id', 'category_id');
    }
}
