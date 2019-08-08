<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('component');
            $table->string('type')->nullable();
            $table->string('name');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->string('js_id')->nullable();
            $table->string('class')->nullable();
            $table->string('validation')->nullable();
            $table->string('attribute')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
