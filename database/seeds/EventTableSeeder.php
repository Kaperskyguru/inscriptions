<?php

use Illuminate\Database\Seeder;
use App\Event;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $events = [
            [
                'start_date' => '2020-03-01 07:00:00',
                'end_date' => '2020-03-04 07:00:00',
                'email' => 'competition.gatineau@hitthefloor.ca',
                'type' => 1,
                'city' => [
                    'fr' => 'Gatineau',
                    'en' => 'Gatineau'
                ],
            ],
            [
                'start_date' => '2020-04-01 07:00:00',
                'end_date' => '2020-04-04 07:00:00',
                'email' => 'competition.toronto@hitthefloor.ca',
                'type' => 1,
                'city' => [
                    'fr' => 'Toronto',
                    'en' => 'Toronto'
                ],
            ],
            [
                'start_date' => '2020-05-01 07:00:00',
                'end_date' => '2020-05-04 07:00:00',
                'email' => 'competition.levis@hitthefloor.ca',
                'type' => 1,
                'city' => [
                    'fr' => 'Lévis',
                    'en' => 'Lévis'
                ]
            ]
        ];
        foreach($events as $event) {
            $eventModel = new Event();
            $eventModel->start_date = $event['start_date'];
            $eventModel->email = $event['email'];
            $eventModel->end_date = $event['end_date'];
            $eventModel->event_type_id = $event['type'];
            foreach($event['city'] as $locale => $city) {
                app()->setLocale($locale);
                $eventModel->city = $city;
            }
            $eventModel->save();

        }
    }
}
