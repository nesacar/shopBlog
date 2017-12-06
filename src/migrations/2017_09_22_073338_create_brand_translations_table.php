<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('short')->nullable();
            $table->text('body')->nullable();
            $table->text('body2')->nullable();
            $table->string('locale')->index();

            $table->unique(['brand_id','locale']);
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_translations');
    }
}
