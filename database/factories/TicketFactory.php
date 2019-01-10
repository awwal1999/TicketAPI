<?php

use Faker\Generator as Faker;
use App\Type;
use App\Ticket;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'price' => $faker->numberBetween(1000, 10000),
        'valid' => $faker->boolean(),
        'type_id' => Type::all()->random()->id,
    ];
});
