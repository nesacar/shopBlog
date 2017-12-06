<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('block_id')->unsigned();
            $table->string('image')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('publish')->default(1);
            $table->timestamps();
        });

        Schema::create('box_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('box_id')->unsigned();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('button')->nullable();
            $table->text('link')->nullable();
            $table->string('locale')->index();

            $table->unique(['box_id','locale']);
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes');
        Schema::dropIfExists('box_translations');
    }
}
