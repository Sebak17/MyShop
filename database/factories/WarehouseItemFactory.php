<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WarehouseItem;
use Faker\Generator as Faker;

$factory->define(WarehouseItem::class, function (Faker $faker) {
    return [
        'code'  => $faker->ean13,
        'status' => 'AVAILABLE',
    ];
});
