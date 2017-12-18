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
            $table->integer('parentId')->unsigned();
            $table->string('name');
            $table->integer('customerId')->unsigned();
            $table->integer('siteId')->unsigned();
            $table->integer('packageId')->unsigned();
            $table->integer('isCustom');
            $table->integer('isVip')->unsigned();
            $table->string('type');
            $table->integer('subscription');
            $table->string('dateStart')->nullable();
            $table->string('dateEnd')->nullable();
            $table->integer('tipsLeft');
            $table->integer('tipsBlocked');
            $table->string('status');
            $table->timestamp('archivedDate')->nullable();
            $table->timestamps();
            $table->index(['parentId', 'customerId', 'siteId', 'packageId', 'dateStart', 'dateEnd']);
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
