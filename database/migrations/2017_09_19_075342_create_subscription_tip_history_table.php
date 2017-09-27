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
            $table->integer('subscriptionId')->unsigned()->index();
            $table->integer('customerId')->unsigned()->index();
            $table->integer('eventId')->unsigned()->index();
            $table->integer('siteId')->unsigned()->index();
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
            $table->string('odd');
            $table->string('predictionId');
            $table->string('predictionName');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('mailingDate')->nullable()->index();
            $table->string('systemDate')->nullable()->index();
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
        Schema::dropIfExists('subscription_tip_history');
    }
}
