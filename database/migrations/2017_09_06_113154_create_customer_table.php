<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('siteId')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('activeEmail');
            $table->timestamps();
            $table->index(['siteId', 'email', 'activeEmail']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
