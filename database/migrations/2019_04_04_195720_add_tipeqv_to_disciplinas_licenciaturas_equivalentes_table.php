<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipeqvToDisciplinasLicenciaturasEquivalentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('DisciplinasLicenciaturasEquivalentes', function($table) {
            # Tipo de equivalência de disciplina
            # 'E' ou 'OU'
            $table->string('tipeqv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('DisciplinasLicenciaturasEquivalentes', function($table) {
            $table->dropColumn('tipeqv');
        });
    }
}
