<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->nullable()->unsigned();
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_mime')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_path_small')->nullable();
            $table->integer('color')->nullable();
            $table->integer('material')->nullable();
            $table->boolean('publish')->nullable()->default(1);
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
        Schema::dropIfExists('images');
    }
}
