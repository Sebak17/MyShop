<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'status'       => 'CREATED',
        'cost'         => $faker->randomFloat(2, 1, 10000),
        'buyer_info'   => json_encode([
            'clientFName' => $faker->firstName,
            'clientSName' => $faker->lastName,
            'clientPhone' => $faker->numberBetween(111111111, 999999999),
        ]),
        'deliver_name' => "COURIER",
        'deliver_info' => json_encode([
            'district' => $faker->numberBetween(1, 16),
            'city'     => $faker->city,
            'zipcode'  => $faker->numberBetween(10, 99) . '-' . $faker->numberBetween(100, 999),
            'address'  => $faker->streetName,
        ]),
        'payment'      => "PAYPAL",
        'note'         => $faker->text(150),
    ];
});
