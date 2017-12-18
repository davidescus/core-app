<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionRestrictedTipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_restricted_tip', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscriptionId')->index();
            $table->integer('distributionId')->index();
            $table->string('systemDate')->index();
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
        Schema::dropIfExists('subscription_restricted_tip');
    }
}
