<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteResultStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_result_status', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('siteId');
            $table->integer('statusId');
            $table->string('statusName');
            $table->string('statusClass');
            $table->index(['siteId', 'statusId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_result_status');
    }
}
