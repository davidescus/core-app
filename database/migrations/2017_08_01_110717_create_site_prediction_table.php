<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitePredictionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_prediction', function(Blueprint $table) {
            $table->increments('id');
            $table->string('siteId');
            $table->string('predictionIdentifier');
            $table->string('name');
            $table->index(['siteId', 'predictionIdentifier']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_prediction');
    }
}
