<?php

use Illuminate\Database\Seeder;
use App\Classification;

class AddFloFestClassificationsSeeder extends Seeder
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
                'minage' => 1,
                'maxage' => 12,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Junior',
                    'en' => 'Junior'
                ]
            ],
            [
                'minage' => 13,
                'maxage' => 15,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Varsity',
                    'en' => 'Varsity'
                ]
            ],
            [
                'minage' => 16,
                'maxage' => 150,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Senior',
                    'en' => 'Senior'
                ]
            ],
           
        ];

        foreach ($data as $item) {
            $model = new Classification();
            $model->minage = $item['minage'];
            $model->maxage = $item['maxage'];
            $model->event_type_id = $item['event_type_id'];

            foreach ($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();

        }
    }
}
