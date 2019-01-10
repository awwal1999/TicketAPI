<?php

use Faker\Generator as Faker;
use App\Type;

$factory->define(Type::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});
