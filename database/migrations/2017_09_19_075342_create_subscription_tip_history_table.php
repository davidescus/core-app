<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTipHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_tip_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscriptionId')->unsigned();
            $table->integer('customerId')->unsigned();
            $table->integer('eventId')->unsigned();
            $table->integer('siteId')->unsigned();
            $table->integer('processSubscription');
            $table->string('processType');
            $table->integer('isCustom');
            $table->string('type');
            $table->integer('isNoTip');
            $table->integer('isVip');
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
            $table->timestamp('mailingDate')->nullable();
            $table->string('systemDate')->nullable();
            $table->timestamps();
            $table->index(['subscriptionId', 'customerId', 'eventId', 'siteId', 'mailingDate', 'systemDate']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_tip_history');
    }
}
