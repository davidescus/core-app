<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoUnitsDailyScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_unit_daily_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date')->index();
            $table->integer('siteId')->index();
            $table->string('tipIdentifier')->index();
            $table->string('tableIdentifier')->index();
            $table->string('predictionGroup');
            $table->integer('statusId');
            $table->string('status')->index();
            $table->text('info');
            $table->date('systemDate');
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
        Schema::dropIfExists('auto_unit_daily_schedule');
    }
}
