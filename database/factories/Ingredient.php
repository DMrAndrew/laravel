<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Ingredient::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName(),
        'stock' => $faker->boolean(),

    ];
});
