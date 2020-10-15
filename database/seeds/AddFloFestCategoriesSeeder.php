<?php

use App\Price;
use App\Category;
use Illuminate\Database\Seeder;

class AddFloFestCategoriesSeeder extends Seeder
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
                'rebate_price' => 9000,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Solo',
                    'en' => 'Solo'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4600,
                'rebate_price' => 4600,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Petit groupe',
                    'en' => 'Small group'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4600,
                'rebate_price' => 4600,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Grand Groupe',
                    'en' => 'Large Group'
                ],
            ],
            [
                'duration' => 240, // In second
                'price' => 4600,
                'rebate_price' => 4600,
                'event_type_id' => 2,
                'name' => [
                    'fr' => 'Production',
                    'en' => 'Production'
                ],
            ]
        ];
        foreach ($data as $item) {
            $model = new Category();
            $model->duration = $item['duration'];
            
            foreach ($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            
            if ($model->save()) {
                $price = new Price();
                $price->price = $item['price'];
                $price->rebate_price = $item['rebate_price'];
                $price->category_id = $model->id;
                $price->save();
            }
        }
    }
}
