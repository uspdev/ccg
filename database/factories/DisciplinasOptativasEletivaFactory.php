<?php

use App\Models\Curriculo;
use Faker\Generator as Faker;

$factory->define(App\Models\DisciplinasOptativasEletiva::class, function (Faker $faker) {
    return [
        'id_crl' => function () {
            return Curriculo::orderByRaw("RAND()")
                ->take(1)
                ->first()
                ->id;
        },
        'coddis' => $faker->regexify('([C])([A-Z][A-Z])([0][0-9][0-9][0-9])'), # CXX0101 - CXX0999
    ];
});
