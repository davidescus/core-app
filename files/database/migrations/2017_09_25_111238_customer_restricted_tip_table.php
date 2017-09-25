<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerRestrictedTipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_restricted_tip', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customerId')->index();
            $table->integer('packageId')->index();
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
        Schema::dropIfExists('customer_restricted_tip');
    }
}
