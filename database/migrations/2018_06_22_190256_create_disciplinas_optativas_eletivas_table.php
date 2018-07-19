<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasOptativasEletivasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DisciplinasOptativasEletivas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_crl')->unsigned();
            $table->string('coddis');
            $table->unique(array('coddis', 'id_crl'));
            $table->timestamps();

            $table->foreign('id_crl')->references('id')->on('Curriculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DisciplinasOptativasEletivas');
    }
}
