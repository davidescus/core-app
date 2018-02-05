<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoUnitsLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_unit_league', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index();
            $table->string('date')->index();
            $table->integer('siteId')->index();
            $table->string('tipIdentifier')->index();
            $table->integer('leagueId')->index();
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
        Schema::dropIfExists('auto_unit_league');
    }
}
