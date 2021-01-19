<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DancerRoutine extends Pivot
{
    use HasFactory;
    protected $table = "dancer_routine";
    protected $fillable = ['routine_id', 'dancer_id', 'doc_number'];
}
