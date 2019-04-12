<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipeqvToDisciplinasObrigatoriasEquivalentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('DisciplinasObrigatoriasEquivalentes', function($table) {
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
        Schema::table('DisciplinasObrigatoriasEquivalentes', function($table) {
            $table->dropColumn('tipeqv');
        });
    }
}
