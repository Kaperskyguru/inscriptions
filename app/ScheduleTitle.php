<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ScheduleTitle extends Model implements TranslatableContract
{
    use Translatable;
    protected $fillable = ['name', 'schedule_id', 'order'];
    
    public $translatedAttributes = ['name'];
    //
    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
    public function scheduleItems()
    {
        return $this->hasMany('App\ScheduleItem');
    }
}
