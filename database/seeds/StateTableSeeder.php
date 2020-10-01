<?php

use Illuminate\Database\Seeder;
use App\State;
class StateTableSeeder extends Seeder
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
                'code' => 'AK',
                'country_id' => 1,
                'name' => ['fr' => 'Alaska', 'en' => 'Alaska']
            ],
            [
                'code' => 'AL',
                'country_id' => 1,
                'name' => ['fr' => 'Alabama', 'en' => 'Alabama']
            ],
            [
                'code' => 'AZ',
                'country_id' => 1,
                'name' => ['fr' => 'Arizona', 'en' => 'Arizona']
            ],
            [
                'code' => 'AR',
                'country_id' => 1,
                'name' => ['fr' => 'Arkansas', 'en' => 'Arkansas']
            ],
            [
                'code' => 'CA',
                'country_id' => 1,
                'name' => ['fr' => 'Californie', 'en' => 'California']
            ],
            [
                'code' => 'CO',
                'country_id' => 1,
                'name' => ['fr' => 'Colorado', 'en' => 'Colorado']
            ],
            [
                'code' => 'CT',
                'country_id' => 1,
                'name' => ['fr' => 'Connecticut', 'en' => 'Connecticut']
            ],
            [
                'code' => 'DE',
                'country_id' => 1,
                'name' => ['fr' => 'Delaware', 'en' => 'Delaware']
            ],
            [
                'code' => 'DC',
                'country_id' => 1,
                'name' => ['fr' => 'Washington, D.C.', 'en' => 'Washington, D.C.']
            ],
            
            [
                'code' => 'FL',
                'country_id' => 1,
                'name' => ['fr' => 'Floride', 'en' => 'Florida']
            ],
            [
                'code' => 'GA',
                'country_id' => 1,
                'name' => ['fr' => 'Géorgie', 'en' => 'Georgia']
            ],
            [
                'code' => 'GU',
                'country_id' => 1,
                'name' => ['fr' => 'Guam', 'en' => 'Guam']
            ],
            [
                'code' => 'HI',
                'country_id' => 1,
                'name' => ['fr' => 'Hawaï', 'en' => 'Hawaii']
            ],
            [
                'code' => 'ID',
                'country_id' => 1,
                'name' => ['fr' => 'Idaho', 'en' => 'Idaho']
            ],
            [
                'code' => 'IL',
                'country_id' => 1,
                'name' => ['fr' => 'Illinois', 'en' => 'Illinois']
            ],
            [
                'code' => 'IN',
                'country_id' => 1,
                'name' => ['fr' => 'Indiana', 'en' => 'Indiana']
            ],
            [
                'code' => 'IA',
                'country_id' => 1,
                'name' => ['fr' => 'Iowa', 'en' => 'Iowa']
            ],
            [
                'code' => 'KS',
                'country_id' => 1,
                'name' => ['fr' => 'Kansas', 'en' => 'Kansas']
            ],
            [
                'code' => 'KY',
                'country_id' => 1,
                'name' => ['fr' => 'Kentucky', 'en' => 'Kentucky']
            ],
            [
                'code' => 'LA',
                'country_id' => 1,
                'name' => ['fr' => 'Louisiane', 'en' => 'Louisiana']
            ],
            [
                'code' => 'ME',
                'country_id' => 1,
                'name' => ['fr' => 'Maine', 'en' => 'Maine']
            ],
            [
                'code' => 'MD',
                'country_id' => 1,
                'name' => ['fr' => 'Maryland', 'en' => 'Maryland']
            ],
            [
                'code' => 'MA',
                'country_id' => 1,
                'name' => ['fr' => 'Massachusetts', 'en' => 'Massachusetts']
            ],
            [
                'code' => 'MI',
                'country_id' => 1,
                'name' => ['fr' => 'Michigan', 'en' => 'Michigan']
            ],
            [
                'code' => 'MN',
                'country_id' => 1,
                'name' => ['fr' => 'Minnesota', 'en' => 'Minnesota']
            ],
            [
                'code' => 'MS',
                'country_id' => 1,
                'name' => ['fr' => 'Mississippi', 'en' => 'Mississippi']
            ],
            [
                'code' => 'MO',
                'country_id' => 1,
                'name' => ['fr' => 'Missouri', 'en' => 'Missouri']
            ],
            [
                'code' => 'MT',
                'country_id' => 1,
                'name' => ['fr' => 'Montana', 'en' => 'Montana']
            ],
            [
                'code' => 'NE',
                'country_id' => 1,
                'name' => ['fr' => 'Nebraska', 'en' => 'Nebraska']
            ],
            [
                'code' => 'NV',
                'country_id' => 1,
                'name' => ['fr' => 'Nevada', 'en' => 'Nevada']
            ],
            [
                'code' => 'NH',
                'country_id' => 1,
                'name' => ['fr' => 'New Hampshire', 'en' => 'New Hampshire']
            ],
            [
                'code' => 'NJ',
                'country_id' => 1,
                'name' => ['fr' => 'New Jersey', 'en' => 'New Jersey']
            ],
            [
                'code' => 'NM',
                'country_id' => 1,
                'name' => ['fr' => 'Nouveau-Mexique', 'en' => 'New Mexico']
            ],
            [
                'code' => 'NY',
                'country_id' => 1,
                'name' => ['fr' => 'New York', 'en' => 'New York']
            ],
            [
                'code' => 'NC',
                'country_id' => 1,
                'name' => ['fr' => 'Caroline du nord', 'en' => 'North Carolina']
            ],
            [
                'code' => 'ND',
                'country_id' => 1,
                'name' => ['fr' => 'Dakota du nord', 'en' => 'North Dakota']
            ],
           
            [
                'code' => 'OH',
                'country_id' => 1,
                'name' => ['fr' => 'Ohio', 'en' => 'Ohio']
            ],
            [
                'code' => 'OK',
                'country_id' => 1,
                'name' => ['fr' => 'Oklahoma', 'en' => 'Oklahoma']
            ],
            [
                'code' => 'OR',
                'country_id' => 1,
                'name' => ['fr' => 'Oregon', 'en' => 'Oregon']
            ],
            
            [
                'code' => 'PA',
                'country_id' => 1,
                'name' => ['fr' => 'Pennsylvanie', 'en' => 'Pennsylvania']
            ],
            [
                'code' => 'RI',
                'country_id' => 1,
                'name' => ['fr' => 'Rhode Island', 'en' => 'Rhode Island']
            ],
            [
                'code' => 'SC',
                'country_id' => 1,
                'name' => ['fr' => 'Caroline du sud', 'en' => 'South Carolina']
            ],
            [
                'code' => 'SD',
                'country_id' => 1,
                'name' => ['fr' => 'Dakota du sud', 'en' => 'South Dakota']
            ],
            [
                'code' => 'TN',
                'country_id' => 1,
                'name' => ['fr' => 'Tennessee', 'en' => 'Tennessee']
            ],
            [
                'code' => 'TX',
                'country_id' => 1,
                'name' => ['fr' => 'Texas', 'en' => 'Texas']
            ],
            [
                'code' => 'UT',
                'country_id' => 1,
                'name' => ['fr' => 'Utah', 'en' => 'Utah']
            ],
            [
                'code' => 'VT',
                'country_id' => 1,
                'name' => ['fr' => 'Vermont', 'en' => 'Vermont']
            ],
            [
                'code' => 'VA',
                'country_id' => 1,
                'name' => ['fr' => 'Virginia', 'en' => 'Virginia']
            ],
            [
                'code' => 'WA',
                'country_id' => 1,
                'name' => ['fr' => 'Washington', 'en' => 'Washington']
            ],
            [
                'code' => 'WV',
                'country_id' => 1,
                'name' => ['fr' => '', 'en' => 'West Virginia']
            ],
            [
                'code' => 'WI',
                'country_id' => 1,
                'name' => ['fr' => 'Wisconsin', 'en' => 'Wisconsin']
            ],
            [
                'code' => 'WY',
                'country_id' => 1,
                'name' => ['fr' => 'Wyoming', 'en' => 'Wyoming']
            ],
            [
                'code' => 'NL',
                'country_id' => 2,
                'name' => ['fr' => 'Terre-Neuve et Labrador', 'en' => 'Newfoundland and Labrador']
            ],
            [
                'code' => 'PE',
                'country_id' => 2,
                'name' => ['fr' => 'Île-du-Prince-Édouard', 'en' => 'Prince Edward Island']
            ],
            [
                'code' => 'NS',
                'country_id' => 2,
                'name' => ['fr' => 'Nouvelle-Écosse', 'en' => 'Nova Scotia']
            ],
            [
                'code' => 'NB',
                'country_id' => 2,
                'name' => ['fr' => 'Nouveau-Brunswick', 'en' => 'New Brunswick']
            ],
            [
                'code' => 'QC',
                'country_id' => 2,
                'name' => ['fr' => 'Québec', 'en' => 'Quebec']
            ],
            [
                'code' => 'ON',
                'country_id' => 2,
                'name' => ['fr' => 'Ontario', 'en' => 'Ontario']
            ],
            [
                'code' => 'MB',
                'country_id' => 2,
                'name' => ['fr' => 'Manitoba', 'en' => 'Manitoba']
            ],
            [
                'code' => 'SK',
                'country_id' => 2,
                'name' => ['fr' => 'Saskatchewan', 'en' => 'Saskatchewan']
            ],
            [
                'code' => 'AB',
                'country_id' => 2,
                'name' => ['fr' => 'Alberta', 'en' => 'Alberta']
            ],
            [
                'code' => 'BC',
                'country_id' => 2,
                'name' => ['fr' => 'Colombie-Britannique', 'en' => 'British Columbia']
            ],
            [
                'code' => 'YT',
                'country_id' => 2,
                'name' => ['fr' => 'Yukon', 'en' => 'Yukon']
            ],
            [
                'code' => 'NT',
                'country_id' => 2,
                'name' => ['fr' => 'Territoires du Nord-Ouest', 'en' => 'Northwest Territories']
            ],
            [
                'code' => 'NU',
                'country_id' => 2,
                'name' => ['fr' => 'Nunavut', 'en' => 'Nunavut']
            ],
            
        ];
        foreach ($data as $item) {
            $model = new State();
            $model->code = $item['code'];
            $model->country_id = $item['country_id'];

            foreach ($item['name'] as $locale => $subitem) {
                app()->setLocale($locale);
                $model->name = $subitem;
            }
            $model->save();
        }
    }
}
