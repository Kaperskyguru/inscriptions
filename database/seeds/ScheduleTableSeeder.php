<?php

use Illuminate\Database\Seeder;
use App\Schedule;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'event_id'=> 1,
                'version'=> 1,
                'published'=> 0,
                
            ],
            [
                'event_id'=> 2,
                'version'=> 1,
                'published'=> 0,
                
            ],
            [
                'event_id'=> 3,
                'version'=> 1,
                'published'=> 0,
                
            ],
           
        ];
        foreach($data as $item) {
            $model = new Schedule();
            $model->event_id = $item['event_id'];
            $model->version = $item['version'];
            $model->published = $item['published'];
            // foreach($item['name'] as $locale => $subitem) {
            //     app()->setLocale($locale);
            //     $model->name = $subitem;
            // }
            $model->save();
        }
    }
}
