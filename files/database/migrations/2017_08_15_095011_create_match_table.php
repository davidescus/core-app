<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match', function (Blueprint $table) {
            $table->integer('id');
            $table->string('country');
            $table->string('countryCode');
            $table->string('league');
            $table->string('leagueId');
            $table->string('homeTeam');
            $table->integer('homeTeamId');
            $table->string('awayTeam');
            $table->integer('awayTeamId');
            $table->string('result');
            $table->timestamp('eventDate')->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match');
    }
}
