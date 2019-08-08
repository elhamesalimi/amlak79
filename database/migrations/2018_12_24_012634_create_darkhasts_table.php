<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDarkhastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('darkhasts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('region_ids');
            $table->string('similar_estate_ids');
            $table->unsignedInteger('type_id');
            $table->enum('category',['sell','rent']);
            $table->integer('min_price')->nullable();
            $table->integer('min_area');
            $table->integer('max_area')->nullable();
            $table->integer('max_price')->nullable();
            $table->integer('min_rent')->nullable();
            $table->integer('max_rent')->nullable();
            $table->integer('min_mortgage')->nullable();
            $table->integer('max_mortgage')->nullable();
            $table->boolean('elevator')->default(false);
            $table->boolean('room')->nullable();
            $table->boolean('parking')->default(false);
            $table->date('expired_at');

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
        Schema::dropIfExists('darkhasts');
    }
}
