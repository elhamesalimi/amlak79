<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',100);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('owner_id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('city_id');
            $table->unsignedTinyInteger('region_id');
            $table->unsignedTinyInteger('plan_id');
            $table->unsignedInteger('email_id');
            $table->integer('price');
            $table->bigInteger('total_price');
            $table->integer('area');
            $table->string('title',50);
            $table->json('fields')->nullable();
//            $table->string('fields_floor')->virtualAs('meta->>"$.floor"');
//            $table->index('fields_floor');
//            $table->boolean('elevator')->default(0);
//            $table->boolean('parking')->default(0);
//            $table->boolean('exchange')->default(0);
            $table->enum('category',['sell','rent','presell'])->default('sell');
            $table->enum('advertiser',['admin','agent','owner']);
            $table->boolean('reference')->default(0);
            $table->enum('offer',['special','lux','underprice'])->nullable();
            $table->integer('view_count')->default(0);
            $table->boolean('listen')->default(1);
            $table->enum('status',['FAILED','PUBLISHED','PENDING','HIDDEN','DRAFT','WAITING','REMOVED','SOLD'])->default('FAILED');
            $table->softDeletes();
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
        Schema::dropIfExists('estates');
    }
}
