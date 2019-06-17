<?php

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

$factory->define(App\Pedido::class, function (Faker $faker) {
    return [
        'codCliente' => 5,
        'codDestino' => 3,
        'dataPedido' => $faker->dateTime,
        'valor' => $faker->randomFloat(2, 0, 800),
        'status' => 1
    ];
});
