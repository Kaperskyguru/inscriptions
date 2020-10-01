<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dancer;
use Faker\Generator as Faker;

$factory->define(Dancer::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'date_of_birth' => $faker->dateTimeBetween('-30 years', $endDate = 'now', $timezone = null),
        'organization_id' => 1
    ];
});
