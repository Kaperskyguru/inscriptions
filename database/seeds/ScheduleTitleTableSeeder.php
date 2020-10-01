<?php

use Illuminate\Database\Seeder;
use App\Schedule;
use App\ScheduleTitle;
use App\ScheduleItem;
use App\Routine;
use Carbon\Carbon;
use App\Classification;

class ScheduleTitleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $schedules = Schedule::all();

        
        $base = Schedule::getBaseSchedule(1);

       
        foreach($schedules as $schedule) {
            
            $data = [];
            $event_id = $schedule['event_id'];
            $routines = Routine::orderBy('name')->whereHas('subscription', function ($query) use ($event_id) { 
                $query->where(['subscriptions.event_id' => $event_id])
                ->whereIn('subscriptions.status_id', [2,3,4]); 
            })
            ->get();
            
            
                foreach($base as $key => $scheduleItem) {
               $routines_matches = arraySearch($routines, $scheduleItem['ids']);
                if(count($routines_matches) > 0) {
                    
                    $model = new ScheduleTitle();
                    $model->schedule_id = $schedule['id'];
                    $model->order = $key + 1;
                    app()->setLocale('fr');
                    $model->name = $scheduleItem['name']['fr'];
                    app()->setLocale('en');
                    $model->name = $scheduleItem['name']['en'];
                    $model->save();

                    foreach($routines_matches as $j => $routine) {

                        $submodel = new ScheduleItem();
                        $submodel->routine_id = $routine->id;
                        $submodel->event_id = $event_id;
                        $submodel->organization_id = $routine->organization_id;
                        $submodel->schedule_title_id = $model->id;
                        $submodel->order = $j + 1;
                        $submodel->block = 1;
                        $submodel->start_date = Carbon::now();
                        $submodel->end_date = Carbon::now();
                        $submodel->save();
                    }
                }    
            }
        }

    }

    
}
