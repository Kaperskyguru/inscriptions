<?php

use Illuminate\Database\Seeder;
use App\Event;

class AddSHEventTableSeeder extends Seeder
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
                'start_date' => '2020-03-01 07:00:00',
                'end_date' => '2020-03-04 07:00:00',
                'email' => 'competition.sainte-hyacinthe@hitthefloor.ca',
                'type' => 1,
                'state_id' => 57,
                'city' => [
                    'fr' => 'Saint-Hyacinthe',
                    'en' => 'Saint-Hyacinthe'
                ],
            ],
        ];
        foreach($data as $item) {
            $model = new Event();
            $model->start_date = $item['start_date'];
            $model->email = $item['email'];
            $model->end_date = $item['end_date'];
            $model->event_type_id = $item['type'];
            $model->state_id = $item['state_id'];
            foreach($item['city'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->city = $subitem;
            }
            $model->save();

        }
    }
}
