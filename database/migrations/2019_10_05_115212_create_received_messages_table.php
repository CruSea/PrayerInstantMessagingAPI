<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_port_id')->unsigned();
            $table->string('message_id')->nullable();
            $table->integer('gateway_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('phone');
            $table->string('display_name')->nullable();
            $table->integer('sms_port_id');
            $table->dateTime('received_date');
            $table->timestamps();
            $table->foreign('message_port_id')->references('id')->on('message_ports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_messages');
    }
}
