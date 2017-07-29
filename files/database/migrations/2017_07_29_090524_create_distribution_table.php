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
            $table->integer('id')->default(0)->index();
            $table->integer('packageId')->default(0)->index();
            $table->string('source');
            $table->string('provider');
            $table->integer('isPublish')->default(0);
            $table->integer('isNoTip')->default(0);
            $table->integer('isVip')->default(0);
            $table->string('country');
            $table->string('league');
            $table->string('homeTeam');
            $table->string('awayTeam');
            $table->string('odd');
            $table->string('predictionId');
            $table->string('predictionName');
            $table->string('result');
            $table->string('statusId', 2);
            $table->timestamp('eventDate')->nullable();
            $table->timestamp('mailingDate')->nullable();
            $table->timestamp('systemDate')->nullable();
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
