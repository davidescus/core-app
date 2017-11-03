<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive_table', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('siteId')->unsigned()->index();
            $table->string('tableIdentifier')->index();
            $table->string('dateStart');
            $table->integer('eventsNumber');
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
        Schema::dropIfExists('archive_table');
    }
}
