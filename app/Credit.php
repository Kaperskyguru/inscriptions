<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;
    protected $fillable = ['doc_number', 'amount', 'subscription_id'];

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_credits', 'credit_id', 'category_id');
    }
}
