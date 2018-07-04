<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curriculos = 30;
        
        $disciplinasLicenciaturas = 150;
        $disciplinasLicenciaturasEquivalentes = 300;
        
        $disciplinasObrigatorias = 300;
        $disciplinasObrigatoriasEquivalentes = 600;
        
        $disciplinasOptativasEletivas = 150;

        echo "Creating $curriculos Currículos...\n";
        factory(App\Curriculo::class, $curriculos)->create();

        echo "Creating $disciplinasLicenciaturas Disciplinas Licenciaturas...\n";
        factory(App\DisciplinasLicenciatura::class, $disciplinasLicenciaturas)->create();

        echo "Creating $disciplinasLicenciaturasEquivalentes Disciplinas Licenciaturas Equivalentes...\n";
        factory(App\DisciplinasLicenciaturasEquivalente::class, $disciplinasLicenciaturasEquivalentes)->create();

        echo "Creating $disciplinasObrigatorias Disciplinas Obrigatórias...\n";
        factory(App\DisciplinasObrigatoria::class, $disciplinasObrigatorias)->create();

        echo "Creating $disciplinasObrigatoriasEquivalentes Disciplinas Obrigatórias Equivalentes...\n";
        factory(App\DisciplinasObrigatoriasEquivalente::class, $disciplinasObrigatoriasEquivalentes)->create();

        echo "Creating $disciplinasOptativasEletivas Disciplinas Optativas Eletivas...\n";
        factory(App\DisciplinasOptativasEletiva::class, $disciplinasOptativasEletivas)->create();
    }
}
