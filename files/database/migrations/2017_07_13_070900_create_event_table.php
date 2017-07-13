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
            $table->string('league');
            $table->string('homeTeam');
            $table->string('awayTeam');
            $table->float('odd', 5, 2);
            $table->string('predictionId');
            $table->string('result');
            $table->integer('statusId');
            $table->timestamp('eventDate')->nullable();
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
        Schema::dropIfExists('event');
    }
}
