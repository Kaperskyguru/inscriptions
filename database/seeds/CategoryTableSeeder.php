<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
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
                'duration' => 120, // In second
                'price' => 9000,
                'rebate_price' => 8500,
                'name' => [
                    'fr' => 'Solo',
                    'en' => 'Solo'
                ],
            ],
            [
                'duration' => 180, // In second
                'price' => 5500, 
                'rebate_price' => 5000,
                'name' => [
                    'fr' => 'Duo',
                    'en' => 'Duo'
                ],
            ],
            [
                'duration' => 180, // In second
                'price' => 5500, 
                'rebate_price' => 5000,
                'name' => [
                    'fr' => 'Trio',
                    'en' => 'Trio'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4700, 
                'rebate_price' => 4200,
                'name' => [
                    'fr' => 'Petit groupe',
                    'en' => 'Small group'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4700, 
                'rebate_price' => 4200,
                'name' => [
                    'fr' => 'Grand Groupe',
                    'en' => 'Large Group'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4700, 
                'rebate_price' => 4200,
                'name' => [
                    'fr' => 'Production',
                    'en' => 'Production'
                ],
            ],
            [
                'duration' => 0, // In second
                'price' => 0, 
                'rebate_price' => 0,
                'name' => [
                    'fr' => 'Non classé',
                    'en' => 'Unclassified'
                ]
            ]
        ];
        foreach($data as $item) {
            $model = new Category();
            $model->duration = $item['duration'];
            $model->price = $item['price'];
            $model->rebate_price = $item['rebate_price'];
            foreach($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();

        }
    }
}
