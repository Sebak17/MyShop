<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WarehouseItemHistory;
use Faker\Generator as Faker;

$factory->define(WarehouseItemHistory::class, function (Faker $faker) {
    return [
        'data' => $faker->text(50),
    ];
});
