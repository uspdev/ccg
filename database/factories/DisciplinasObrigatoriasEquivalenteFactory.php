<?php

use App\Models\DisciplinasObrigatoria;
use Faker\Generator as Faker;

$factory->define(App\Models\DisciplinasObrigatoriasEquivalente::class, function (Faker $faker) {
    return [
        'id_dis_obr' => function () {
            return DisciplinasObrigatoria::orderByRaw("RAND()")
                ->take(1)
                ->first()
                ->id;
        },
        'coddis' => $faker->regexify('([C])([A-Z][A-Z])([0][0-9][0-9][0-9])'), # CXX0101 - CXX0999
    ];
});
