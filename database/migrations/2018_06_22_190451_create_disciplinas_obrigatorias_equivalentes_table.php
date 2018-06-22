<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasObrigatoriasEquivalentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DisciplinasObrigatoriasEquivalentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_dis_obr')->unsigned();
            $table->string('coddis');
            $table->timestamps();

            $table->foreign('id_dis_obr')->references('id')->on('DisciplinasObrigatorias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DisciplinasObrigatoriasEquivalentes');
    }
}
