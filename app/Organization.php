<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
//class Organization extends Model
{
    //use Translatable;
    //public $translatedAttributes = ['name'];

    protected $fillable = [
        'name', 'address', 'city', 'zipcode', 'phone', 'locale', 'user_id', 'country_id', 'state_id', 'organization_type_id', 'accronyme'
    ];
    protected $appends = ['updatedago'];
    /**
     * Get the user that owns the organization.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function organizationType()
    {
        return $this->belongsTo('App\OrganizationType');
    }
    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    public function state()
    {
        return $this->belongsTo('App\State');
    }
    public function dancers()
    {
        return $this->hasMany('App\Dancer')->orderBy('last_name', 'ASC');
    }
    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
    public function scheduleItems()
    {
        return $this->hasMany('App\ScheduleItem');
    }
    public function getUpdatedAgoAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
