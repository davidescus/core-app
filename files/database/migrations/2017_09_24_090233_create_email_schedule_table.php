<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender')->index();
            $table->string('provider')->index();
            $table->string('type')->index();
            $table->string('identifierName')->index();
            $table->string('identifierValue')->index();
            $table->string('host');
            $table->string('user');
            $table->string('pass');
            $table->integer('port');
            $table->string('encryption');
            $table->string('from');
            $table->string('fromName');
            $table->string('to');
            $table->string('toName');
            $table->string('subject');
            $table->string('body');
            $table->timestamp('mailingDate')->nullable()->index();
            $table->string('status');
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
        Schema::dropIfExists('email_schedule');
    }
}
