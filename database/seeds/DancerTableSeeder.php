<?php

use Illuminate\Database\Seeder;

class DancerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\User::class, 50)->create()->each(function ($user) {
        // $user->posts()->save(factory(App\Post::class)->make());
        // });
        if(env('APP_ENV') == 'local') {
            factory(App\Dancer::class, 50)->create();
        }
        
    }
}
