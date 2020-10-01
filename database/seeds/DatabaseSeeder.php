<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            CountryTableSeeder::class,
            StateTableSeeder::class,
            RoleTableSeeder::class,
            UsersTableSeeder::class,
            OrganizationTypesTableSeeder::class,
            OrganizationTableSeeder::class,
            DancerTableSeeder::class,
            EventTypesTableSeeder::class,
            EventTableSeeder::class,
            StatusTableSeeder::class,
            CategoryTableSeeder::class,
            LevelTableSeeder::class,
            StyleTableSeeder::class,
            ClassificationTableSeeder::class,
            PaymentTypesTableSeeder::class,
            AddFlofestEventSeeder::class,
            AddFloFestClassificationsSeeder::class,
            AddFloFestCategoriesSeeder::class,
            AddFloFestStylesSeeder::class,
        ]);
    }
}
