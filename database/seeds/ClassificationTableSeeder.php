<?php

use Illuminate\Database\Seeder;

use App\Classification;

class ClassificationTableSeeder extends Seeder
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
                'maxage' => 8,
                'name' => [
                    'fr' => 'Mini',
                    'en' => 'Mini'
                ]
            ],
            [
                'minage' => 9,
                'maxage' => 12,
                'name' => [
                    'fr' => 'Junior',
                    'en' => 'Junior'
                ]
            ],
            [
                'minage' => 13,
                'maxage' => 15,
                'name' => [
                    'fr' => 'Intermédiaire',
                    'en' => 'Intermediate'
                ]
            ],
            [
                'minage' => 16,
                'maxage' => 19,
                'name' => [
                    'fr' => 'Senior',
                    'en' => 'Senior'
                ]
            ],
            [
                'minage' => 20,
                'maxage' => 29,
                'name' => [
                    'fr' => 'Senior +',
                    'en' => 'Senior +'
                ]
            ],
            [
                'minage' => 30,
                'maxage' => 150,
                'name' => [
                    'fr' => 'Adulte',
                    'en' => 'Adult'
                ]
            ],
            [
                'minage' => 0,
                'maxage' => 0,
                'name' => [
                    'fr' => 'Non classé',
                    'en' => 'Unclassified'
                ]
            ]
        ];

        foreach ($data as $item) {
            $model = new Classification();
            $model->minage = $item['minage'];
            $model->maxage = $item['maxage'];

            foreach ($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();

        }
    }
}
