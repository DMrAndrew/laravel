<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Medicament::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName(),
        'stock' => 0,
        'count' => 0
    ];
});
