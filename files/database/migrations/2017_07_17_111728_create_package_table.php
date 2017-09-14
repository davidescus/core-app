<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('siteId');
            $table->string('name');
            $table->string('identifier');
            $table->string('tipIdentifier');
            $table->string('tableIdentifier');
            $table->string('paymentName');
            $table->string('vipFlag');
            $table->boolean('isVip');
            $table->boolean('isRecurring');
            $table->string('subscriptionType');
            $table->integer('tipsPerDay');
            $table->integer('subscription');
            $table->string('aliasTipsPerDay');
            $table->string('aliasSubscriptionType');
            $table->string('oldPrice');
            $table->string('discount');
            $table->string('price');
            $table->string('fromName');
            $table->string('subject');
            $table->text('template');
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
        Schema::dropIfExists('package');
    }
}
