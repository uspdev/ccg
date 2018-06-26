<?php

use Faker\Generator as Faker;

$factory->define(App\Curriculo::class, function (Faker $faker) {
    return [
        'codcur'            => $faker->numberBetween($min = 27010, $max = 27700), # 27010 - 27700
        'codhab'            => $faker->numberBetween($min = 101, $max = 901), # 101 - 901
        'dtainicrl'         => $faker->date($format = 'Y-m-d', $max = 'now'), 
        'numcredisoptelt'   => $faker->numberBetween($min = 10, $max = 50), # 10 - 50
        'numcredisoptliv'   => $faker->numberBetween($min = 1, $max = 20), # 1 - 20
    ];
});
