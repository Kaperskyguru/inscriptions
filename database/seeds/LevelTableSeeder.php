<?php

use Illuminate\Database\Seeder;
use App\Level;

class LevelTableSeeder extends Seeder
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
                'name' => [
                    'fr' => 'Novice',
                    'en' => 'Novice'
                ],
            ],
            [
                'name' => [
                    'fr' => 'Pré-Compétitif',
                    'en' => 'Pre-Competitive'
                ],
            ],
            [
                'name' => [
                    'fr' => 'Compétitif',
                    'en' => 'Competitive'
                ],
            ],
            [
                'name' => [
                    'fr' => 'Elite',
                    'en' => 'Elite'
                ],
            ],
        ];
        foreach($data as $item) {
            $model = new Level();
            foreach($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();

        }
    }
}
