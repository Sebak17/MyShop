<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Category;
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

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name'         => str_replace(".", "", $faker->text(18)),
        'orderID'      => Category::where('overcategory', 0)->count() + 1,
        'overcategory' => 0,
        'active'       => 1,
        'visible'      => 1,
        'icon'         => 'fa-question',
    ];
});
