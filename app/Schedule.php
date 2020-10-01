<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classification;
use App\Level;
use App\Style;
use App\Category;
use App\Routine;

class Schedule extends Model
{
    //

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function scheduleTitles()
    {
        return $this->hasMany('App\ScheduleTitle');
    }

    public static function getBaseSchedule($event_type_id) {
        $schedules =  [];

        if($event_type_id == 2) {
            $styles_order = [];
        }else {
            $styles_order = [4,5,6,3,2,8,1,7];
        }
        
        
        $classifications = Classification::where('event_type_id', $event_type_id)->where('id','<>', 7)->get();
        $levels = Level::where('event_type_id', $event_type_id)->get();
        $styles = Style::where('event_type_id', $event_type_id)->orderByRaw('FIELD (id, ' . implode(', ', $styles_order) . ') ASC')->get();
        $categories = Category::where('event_type_id', $event_type_id)->where('id','<>', 7)->get();

        foreach($classifications as $classification) {
            
            foreach($levels as $level) {
                
                foreach($styles as $style) {
                    
                    foreach($categories as $category) {
                        $titleFR = $classification->schedule_title . ' | ' . $level->translate('en')->name . ' | ' . $style->name . ' | ' . $category->translate('en')->name;

                        $titleEN = $classification->translate('en')->schedule_title . ' | ' . $level->translate('en')->name . ' | ' . $style->translate('en')->name . ' | ' . $category->translate('en')->name;

                        $ids = ['classification_id' => $classification->id, 'level_id' => $level->id, 'style_id' => $style->id, 'category_id' => $category->id];
                        
                        $tmp = [
                            'name' => [
                                'fr' => $titleFR,
                                'en' => $titleEN
                            ],
                            'ids' => $ids
                        ];
                        array_push($schedules, $tmp);

                        
                    }
                }
            }
        }

        return $schedules;
    }
}
