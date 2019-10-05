<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagePortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_ports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('api_key');
            $table->integer('campaign_id');
            $table->integer('sms_port_id');
            $table->string('campaign_name')->nullable();
            $table->string('sms_port_name')->nullable();
            $table->boolean('is_connected')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('message_ports');
    }
}
