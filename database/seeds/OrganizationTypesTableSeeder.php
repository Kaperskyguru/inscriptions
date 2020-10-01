<?php

use Illuminate\Database\Seeder;
use App\OrganizationType;

class OrganizationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizationTypesNames = [
            [
                'fr' => 'École de danse',
                'en' => 'Dance School'
            ],
            [
                'fr' => 'Groupe indépendant',
                'en' => 'Independent Group'
            ],
            [
                'fr' => 'Danseur indépendant',
                'en' => 'Independent Dancer'
            ],
        ];
        foreach($organizationTypesNames as $names) {
            $organizationType = new OrganizationType();
            foreach($names as $locale => $name) {
                app()->setLocale($locale);
                $organizationType->name = $name;
            }
            $organizationType->save();

        }
    }
}
