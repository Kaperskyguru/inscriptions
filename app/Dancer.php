<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Dancer extends Model
{
    //
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'organization_id'
    ];
    protected $appends = ['age', 'name'];


    /**
     * Get the post that owns the comment.
     */
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }
    public function routines()
    {
        return $this->belongsToMany('App\Routine');
    }
     /**
     * Get the dancer's age.
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        

        if(isset($this->attributes['date_of_birth'])) {

            $birth_date = Carbon::parse($this->attributes['date_of_birth']);
            $limit_date = Carbon::parse('01-01-2020 00:00:00');
            $age = $birth_date->diffInYears($limit_date); 
            return $age;
           // return Carbon::parse($this->attributes['date_of_birth'])->age;
        }
        return '';
        
    }
    public function getNameAttribute()
    {
        if(isset($this->attributes['first_name']) && isset($this->attributes['last_name'])) {
            return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
        }
        return '';
    }
}
