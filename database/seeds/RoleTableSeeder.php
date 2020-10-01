<?php

use Illuminate\Database\Seeder;
use App\Role;


class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = new Role();
        $role->name = 'admin';
        $role->description = 'A Admin User';
        $role->save();

        $role = new Role();
        $role->name = 'public';
        $role->description = 'A Public User';
        $role->save();
    }
}
