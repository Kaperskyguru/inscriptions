<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryInvoice extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'invoice_id', 'factured', 'subscription_id', 'routine_count' . 'entries'];
}
