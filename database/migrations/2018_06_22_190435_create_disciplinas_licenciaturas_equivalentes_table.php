<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasLicenciaturasEquivalentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DisciplinasLicenciaturasEquivalentes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_dis_lic')->unsigned();
            $table->string('coddis');
            $table->unique(array('coddis', 'id_dis_lic'));
            $table->timestamps();

            $table->foreign('id_dis_lic')->references('id')->on('DisciplinasLicenciaturas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DisciplinasLicenciaturasEquivalentes');
    }
}
