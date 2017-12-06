<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent')->nullable()->default(0);
            $table->integer('order')->nullable();
            $table->string('image')->nullable();
            $table->integer('level')->nullable()->default(1);
            $table->boolean('publish')->nullable();
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
        Schema::dropIfExists('p_categories');
    }
}
