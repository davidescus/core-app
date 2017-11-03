<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveHomeConfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('archive_home_conf', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('siteId')->unsigned()->index();
            $table->string('tableIdentifier')->index();
            $table->integer('eventsNumber');
            $table->string('dateStart');
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
        Schema::dropIfExists('archive_home_conf');
    }
}
