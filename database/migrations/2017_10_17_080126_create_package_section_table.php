<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('packageId');
            $table->string('section');
            $table->string('systemDate');
            $table->timestamps();
            $table->index('packageId', 'section', 'systemDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_section');
    }
}
