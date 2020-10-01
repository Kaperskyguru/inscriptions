<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_public  = Role::where('name', 'public')->first();

        $user = new User();
        $user->name = 'Yan Cloutier-Lelievre';
        $user->email = 'ycl@fastmail.fm';
        $user->password = Hash::make(env('USER_PASS'));
        //$user->refreshToken = 'false';
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Nina Célant';
        $user->email = 'nina.celant@hitthefloor.ca';
        $user->password = Hash::make(env('USER_PASS'));
        //$user->refreshToken = 'false';
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Paule Assad-Déry';
        $user->email = 'paule.assad-dery@hitthefloor.ca';
        $user->password = Hash::make(env('USER_PASS'));
        //$user->refreshToken = 'false';
        $user->save();
        $user->roles()->attach($role_admin);

        if(env('APP_ENV') == 'local') {
            $user = new User();
            $user->name = 'Kristina Cormier';
            $user->email = 'kri.corm@gmail.com';
            $user->password = Hash::make('password');
            //$user->refreshToken = 'false';
            $user->save();
            $user->roles()->attach($role_public);
        }
    }
}
