<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserPersonal;
use Faker\Generator as Faker;

$factory->define(UserPersonal::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'surname'  => $faker->lastName,
        'phoneNumber' => $faker->numberBetween(111111111, 999999999),
    ];
});
