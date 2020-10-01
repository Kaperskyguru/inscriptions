<?php

use Illuminate\Database\Seeder;
use App\EventType;

class EventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventTypesNames = [
            [
                'fr' => 'Hit the Floor',
                'en' => 'Hit the Floor'
            ],
            [
                'fr' => 'Flofest',
                'en' => 'Flofest'
            ],
           
        ];
        foreach($eventTypesNames as $names) {
            $eventType = new EventType();
            foreach($names as $locale => $name) {
                app()->setLocale($locale);
                $eventType->name = $name;
            }
            $eventType->save();

        }
    }
}
