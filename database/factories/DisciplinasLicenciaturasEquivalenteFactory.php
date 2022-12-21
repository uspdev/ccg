<?php

use App\Models\DisciplinasLicenciatura;
use Faker\Generator as Faker;

$factory->define(App\Models\DisciplinasLicenciaturasEquivalente::class, function (Faker $faker) {
    return [
        'id_dis_lic' => function () {
            return DisciplinasLicenciatura::orderByRaw("RAND()")
                ->take(1)
                ->first()
                ->id;
        },
        'coddis' => $faker->regexify('FED([0][0-9][0-9][0-9])'), # FED0101 - FED0999
    ];
});
