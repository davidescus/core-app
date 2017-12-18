<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('associationId')->unsigned()->index();
            $table->integer('eventId')->unsigned();
            $table->string('source');
            $table->string('provider');
            $table->integer('siteId')->unsigned()->index();
            $table->integer('packageId')->unsigned()->index();
            $table->string('tableIdentifier');
            $table->string('tipIdentifier');
            $table->integer('isEmailSend')->unsigned()->index();
            $table->integer('isPublish')->unsigned()->index();
            $table->integer('isNoTip')->unsigned()->index();
            $table->integer('isVip')->unsigned();
            $table->string('country');
            $table->string('countryCode');
            $table->string('league');
            $table->integer('leagueId')->unsigned();
            $table->string('homeTeam');
            $table->integer('homeTeamId')->unsigned();
            $table->string('awayTeam');
            $table->integer('awayTeamId')->unsigned();
            $table->string('odd', 10);
            $table->string('predictionId');
            $table->string('predictionName');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('mailingDate')->nullable()->index();
            $table->string('systemDate')->nullable()->index();
            $table->integer('publishTime')->unsigned();
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
        Schema::dropIfExists('distribution');
    }
}
