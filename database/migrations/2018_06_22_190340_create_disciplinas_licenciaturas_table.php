<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasLicenciaturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DisciplinasLicenciaturas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_crl')->unsigned();
            $table->string('coddis');
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
        Schema::dropIfExists('DisciplinasLicenciaturas');
    }
}
