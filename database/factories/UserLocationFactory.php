<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserLocation;
use Faker\Generator as Faker;

$factory->define(UserLocation::class, function (Faker $faker) {
    return [
        'district' => $faker->numberBetween(1, 16),
        'city' => $faker->city,
        'zipcode' => $faker->numberBetween(10, 99) . '-' . $faker->numberBetween(100, 999),
        'address' => $faker->streetName,
    ];
});
