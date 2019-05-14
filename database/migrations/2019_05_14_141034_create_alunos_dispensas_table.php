<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlunosDispensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AlunosDispensas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_crl')->unsigned();
            $table->integer('codpes');
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
        Schema::dropIfExists('AlunosDispensas');
    }
}
