<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('url');
            $table->string('smtpHost');
            $table->string('smtpPort');
            $table->string('smtpUser');
            $table->string('smtpPassword');
            $table->string('smtpEncryption');
            $table->string('imapHost');
            $table->string('imapPort');
            $table->string('imapUser');
            $table->string('imapPassword');
            $table->string('imapEncryption');
            $table->string('dateFormat');
            $table->integer('isConnect')->unsigned();
            $table->string('token')->unique()->index();
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
        Schema::dropIfExists('site');
    }
}
