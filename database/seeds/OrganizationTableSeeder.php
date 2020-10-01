<?php

use Illuminate\Database\Seeder;
use App\Organization;

class OrganizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            $organization = new Organization();
            $organization->name = 'La Top Famille';
            $organization->address = '123 rue fantome';
            $organization->city = 'Sept-iles';
            $organization->zipcode = 'g0c1v0';
            $organization->phone = '4188022933';
            $organization->locale = 'fr';
            $organization->user_id = 4;
            $organization->organization_type_id = 1;
            $organization->country_id = 2;
            $organization->state_id = 57;
            $organization->save();
        }
    }
}
