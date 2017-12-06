<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->nullable();
            $table->integer('cat_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->string('desc')->nullable();
            $table->string('sufix')->nullable();
            $table->integer('type')->nullable();
            $table->integer('order')->nullable();
            $table->integer('parent')->nullable();
            $table->integer('level')->nullable();
            $table->string('image')->nullable();
            $table->string('locale')->nullable();
            $table->boolean('publish')->default(1);
            $table->timestamps();

            //$table->unique(['cat_id','locale']);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_links');
    }
}
