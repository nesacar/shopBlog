<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->nullable();
            $table->string('image')->nullable();
            $table->string('order')->nullable();
            $table->boolean('publish');
            $table->timestamps();
        });

        Schema::create('menu_image_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_image_id')->unsigned();

            $table->string('title')->nullable();
            $table->string('button')->nullable();
            $table->string('link')->nullable();
            $table->string('locale')->index();

            $table->unique(['menu_image_id','locale']);
            $table->foreign('menu_image_id')->references('id')->on('menu_images')->onDelete('cascade');
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
        Schema::dropIfExists('menu_images');
        Schema::dropIfExists('menu_image_translations');
    }
}
