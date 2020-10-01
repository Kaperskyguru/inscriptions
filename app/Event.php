<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Notifications\Notifiable;


class Event extends Model implements TranslatableContract
{
    use Translatable, Notifiable;
    public $translatedAttributes = ['city'];
    protected $appends = ['slug'];
    //
    protected function getEventInfos($eventSlug){
            
        $events = [
            'gatineau' => [
                'city' => 'Gatineau',
                'id' => 1
            ],
            'toronto' => [
                'city' => 'Toronto',
                'id' => 2
            ],
            'levis' => [
                'city' => 'Lévis',
                'id' => 3
            ],
            'flofest' => [
                'city' => 'FLOFEST',
                'id' => 4
            ],
            'saintehyacinthe' => [
                'city' => 'Saint-Hyacinthe',
                'id' => 5
            ]
        ];
        return $events[$eventSlug];

    }
    public function eventType()
    {
        return $this->belongsTo('App\EventType');
    }
    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
    public function schedules()
    {
        return $this->hasMany('App\Schedule');
    }

    // Accessor
    public function getSlugAttribute()
    {
        return Str::slug($this->getAttribute('city'));

        //return $this->attributes['city']->slug;
    }
}
