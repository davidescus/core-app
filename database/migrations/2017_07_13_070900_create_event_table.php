<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source');
            $table->string('provider');
            $table->string('country');
            $table->string('countryCode');
            $table->string('league');
            $table->integer('leagueId')->unsigned();
            $table->string('homeTeam');
            $table->integer('homeTeamId')->unsigned();
            $table->string('awayTeam');
            $table->integer('awayTeamId')->unsigned();
            $table->string('odd');
            $table->string('predictionId');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamps();
            $table->index(['source', 'provider', 'league', 'odd']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
}
