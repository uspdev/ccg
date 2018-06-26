<?php

use App\Curriculo;
use Faker\Generator as Faker;

$factory->define(App\DisciplinasLicenciatura::class, function (Faker $faker) {
    return [
        'id_crl' => function () {
            return Curriculo::orderByRaw("RAND()")
                ->take(1)
                ->first()
                ->id;
        },
        'coddis' => $faker->regexify('FED([0][0-9][0-9][0-9])'), # FED0101 - FED0999
    ];
});
