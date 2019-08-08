<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDarkhastFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('darkhast_fields', function (Blueprint $table) {
            $table->primary(['field_id','type_id','category']);
            $table->unsignedTinyInteger('field_id');
            $table->unsignedTinyInteger('type_id');
            $table->enum('category',['sell','rent']);
            $table->unsignedTinyInteger('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('darkhast_fields');
    }
}
