<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisteredPrayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_prayers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->string('full_name')->nullable();
            $table->string('location')->nullable();
            $table->string('language')->nullable();
            $table->string('day_name')->nullable();
            $table->time('sch_time')->nullable();
            $table->integer('prayer_length')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('registered_prayers');
    }
}
