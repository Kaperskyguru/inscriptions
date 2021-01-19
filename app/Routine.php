<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $fillable = [
        'name', 'teacher', 'music', 'organization_id', 'subscription_id', 'category_id', 'style_id', 'level_id', 'classification_id', 'doc_number'
    ];
    protected $attributes = [
        'music' => 'default.mp3',
    ];
    protected $append = [
        'dancersCount',
    ];

    //
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function style()
    {
        return $this->belongsTo('App\Style');
    }
    public function level()
    {
        return $this->belongsTo('App\Level');
    }
    public function classification()
    {
        return $this->belongsTo('App\Classification');
    }
    public function dancers()
    {
        return $this->belongsToMany('App\Dancer')->orderBy('last_name', 'ASC');
    }

    public function newdancers()
    {
        return $this->belongsToMany('App\Dancer')
            ->using('App\DancerRoutine')
            ->withPivot([
                'doc_number'
            ]);
    }
    // additional helper relation for the count
    // public function dancersCount()
    // {
    //     // return $this->belongsToMany('App\Dancer')
    //     //     ->selectRaw('count(dancers.id) as aggregate')
    //     //     ->groupBy('routine_id');
    // }
    // public function getDancersCountAttribute()
    // {
    //     // if ( ! array_key_exists('dancersCount', $this->relations)) $this->load('dancersCount');

    //     // $related = $this->getRelation('dancersCount')->first();

    //     // return ($related) ? $related->dancersCount->aggregate : 0;
    //     // //return
    //     return 0;
    // }
    public function scheduleItem()
    {
        return $this->hasOne('App\ScheduleItem');
    }
}
