<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @column provider
     *      - hwo want to send email (site|system|...)
     *
     * @column sender
     *      - how to identify provider to get connection credential
     *      - if provider = site => sender = siteId
     *
     * @column type
     *      - ex: subscriptionEmail, adminWarningEmail
     *
     * @column identifierName && identifierValue
     *      - how to connect email with other data in app
     *      - let say we have an subscriptionEmail
     *      - will store the associated subscriptionId
     *
     * @return void
     *
     * @provider => from where came the email (site|system|...)
     * @sender   => ex: if provider is site sender will be the site id
     */
    public function up()
    {
        Schema::create('email_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider')->index();
            $table->string('sender')->index();
            $table->string('type')->index();
            $table->string('identifierName')->index();
            $table->string('identifierValue')->index();
            $table->string('from');
            $table->string('fromName');
            $table->string('to');
            $table->string('toName');
            $table->string('subject');
            $table->text('body');
            $table->timestamp('mailingDate')->nullable()->index();
            $table->string('status')->index();
            $table->string('info');
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
