<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('p_category_id')->unsigned();

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('desc')->nullable();
            $table->string('body')->nullable();
            $table->string('locale')->index();

            $table->unique(['p_category_id','locale']);
            $table->foreign('p_category_id')->references('id')->on('p_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_category_translations');
    }
}
