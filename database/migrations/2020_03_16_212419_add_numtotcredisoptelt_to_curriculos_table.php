<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumtotcredisopteltToCurriculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculos', function (Blueprint $table) {
            $table->integer('numtotcredisoptelt')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculos', function (Blueprint $table) {
            $table->IfExists('numtotcredisoptelt');
        });
    }
}
