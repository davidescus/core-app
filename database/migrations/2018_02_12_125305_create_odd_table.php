<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odd', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('matchId')->index();
            $table->integer('leagueId')->index();
            $table->string('predictionId')->index();
            $table->string('odd')->index();
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
        Schema::dropIfExists('odd');
    }
}
