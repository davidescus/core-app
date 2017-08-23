<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveBigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_big', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('distributionId')->index();
            $table->integer('associationId')->index();
            $table->integer('eventId')->index();
            $table->integer('siteId')->index();
            $table->integer('packageId')->index();
            $table->string('source');
            $table->string('provider');
            $table->string('tableIdentifier');
            $table->integer('isPublish')->default(0);
            $table->integer('isVisible')->default(1);
            $table->integer('isNoTip')->default(0);
            $table->integer('isVip')->default(0);
            $table->string('country');
            $table->string('countryCode');
            $table->string('league');
            $table->integer('leagueId');
            $table->string('homeTeam');
            $table->integer('homeTeamId');
            $table->string('awayTeam');
            $table->string('awayTeamId');
            $table->string('odd');
            $table->string('predictionId');
            $table->string('predictionName');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('mailingDate')->nullable();
            $table->string('systemDate')->nullable();
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
        Schema::dropIfExists('archive_big');
    }
}
