<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCredit extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'credit_id', 'entries', 'routines'];
}
