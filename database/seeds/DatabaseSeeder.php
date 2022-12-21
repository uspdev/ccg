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
        factory(App\Models\Curriculo::class, $curriculos)->create();

        echo "Creating $disciplinasLicenciaturas Disciplinas Licenciaturas...\n";
        factory(App\Models\DisciplinasLicenciatura::class, $disciplinasLicenciaturas)->create();

        echo "Creating $disciplinasLicenciaturasEquivalentes Disciplinas Licenciaturas Equivalentes...\n";
        factory(App\Models\DisciplinasLicenciaturasEquivalente::class, $disciplinasLicenciaturasEquivalentes)->create();

        echo "Creating $disciplinasObrigatorias Disciplinas Obrigatórias...\n";
        factory(App\Models\DisciplinasObrigatoria::class, $disciplinasObrigatorias)->create();

        echo "Creating $disciplinasObrigatoriasEquivalentes Disciplinas Obrigatórias Equivalentes...\n";
        factory(App\Models\DisciplinasObrigatoriasEquivalente::class, $disciplinasObrigatoriasEquivalentes)->create();

        echo "Creating $disciplinasOptativasEletivas Disciplinas Optativas Eletivas...\n";
        factory(App\Models\DisciplinasOptativasEletiva::class, $disciplinasOptativasEletivas)->create();
    }
}
