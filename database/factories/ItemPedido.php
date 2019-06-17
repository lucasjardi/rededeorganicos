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

$factory->define(App\ItemPedido::class, function (Faker $faker) {
    return [
        'codPedido' => 0,
        'codProduto' => $faker->randomNumber(2, false),
        'quantidade' => $faker->randomDigit,
        'valorTotal' => 0,
        'descricao' => $faker->sentence()
    ];
});
