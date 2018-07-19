<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Curriculos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codcur');
            $table->integer('codhab');
            $table->date('dtainicrl');
            $table->integer('numcredisoptelt');
            $table->integer('numcredisoptliv');
            $table->unique(array('codcur', 'codhab', 'dtainicrl'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Curriculos');
    }
}
