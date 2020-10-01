<?php

use Illuminate\Database\Seeder;
use App\PaymentType;

class PaymentTypesTableSeeder extends Seeder
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
                    'fr' => 'Chèque',
                    'en' => 'Check'
                ],
            ],
            [
                'name' => [
                    'fr' => 'Argent',
                    'en' => 'Cash'
                ],
            ],
            [
                'name' => [
                    'fr' => 'Virement',
                    'en' => 'Transfer'
                ],
            ],
        ];
        
        foreach($data as $item) {
            $model = new PaymentType();
            foreach($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();
        }
    }
}
