<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ScheduleItem extends Model
{
    //
    protected $fillable = ['routine_id', 'position', 'order', 'schedule_title_id', 'organization_id', 'start_date', 'end_date', 'event_id'];

    protected $appends = ['year', 'hour'];

    
    public function scheduleTitle()
    {
        return $this->belongsTo('App\ScheduleTitle');
    }
    public function routine()
    {
        return $this->belongsTo('App\Routine');
    }
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function getYearAttribute()
    {

        return Carbon::parse($this->attributes['start_date'])->format('Y-m-d');
    }
    public function getHourAttribute()
    {
        return Carbon::parse($this->attributes['start_date'])->format('H-i-s');
    }
}
