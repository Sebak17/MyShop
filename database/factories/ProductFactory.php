<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Product;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(Product::class, function (Faker $faker) {

    $price = $faker->randomFloat(2, 1, 10000);

    return [
        'title'        => str_replace(".", "", $this->faker->sentence(5)),
        'priceCurrent' => $price,
        'priceNormal'  => $price,
        'description'  => $faker->text(200),
        'status'       => "ACTIVE",
    ];
});
