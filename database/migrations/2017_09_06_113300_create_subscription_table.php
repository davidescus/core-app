<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('customerId')->unsigned()->index();
            $table->integer('siteId')->unsigned()->index();
            $table->integer('packageId')->unsigned()->index();
            $table->integer('isCustom');
            $table->string('type');
            $table->integer('subscription');
            $table->string('dateStart')->nullable()->index();
            $table->string('dateEnd')->nullable()->index();
            $table->integer('tipsLeft');
            $table->integer('tipsBlocked');
            $table->string('status');
            $table->timestamp('archivedDate')->nullable();
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
        Schema::dropIfExists('subscription');
    }
}
