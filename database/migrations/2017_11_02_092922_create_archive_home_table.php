<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveHomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_home', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('distributionId')->unsigned()->index();
            $table->integer('order')->unsigned()->index();
            $table->integer('associationId')->uunsigned()->index();
            $table->integer('eventId')->unsigned()->index();
            $table->integer('siteId')->unsigned()->index();
            $table->integer('packageId')->unsigned()->index();
            $table->string('source');
            $table->string('provider');
            $table->string('tableIdentifier')->index();
            $table->string('tipIdentifier')->index();
            $table->integer('isPublish')->unsigned()->default(0)->index();
            $table->integer('isPublishInSite')->unsigned()->default(0)->index();
            $table->integer('isVisible')->unsigned()->default(1)->index();
            $table->integer('isNoTip')->unsigned()->default(0);
            $table->integer('isVip')->unsigned()->default(0);
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
            $table->string('predictionName');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('mailingDate')->nullable();
            $table->string('systemDate')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_home');
    }
}
