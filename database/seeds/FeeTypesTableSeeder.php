<?php

use Illuminate\Database\Seeder;
use App\FeeType;

class FeeTypesTableSeeder extends Seeder
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
                'price'=> 500,
                'name' => [
                    'fr' => 'Inscription Tardive',
                    'en' => 'Late subscription'
                ],
            ],
            [
                'price'=> 2500,
                'name' => [
                    'fr' => 'Modification de catégorie',
                    'en' => 'Change of category'
                ],
            ],
            [
                'price'=> 2500,
                'name' => [
                    'fr' => 'Frais de musique',
                    'en' => 'Music fee'
                ],
            ],
        ];
        foreach($data as $item) {
            $model = new FeeType();
            $model->price = $item['price'];
            foreach($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();
        }
    }
}
