<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('brand_id')->nullable()->unsigned();
            $table->string('code')->nullable();
            $table->string('image')->nullable();
            $table->string('tmb')->nullable();
            $table->integer('price_small')->nullable();
            $table->integer('price_outlet')->nullable();
            $table->integer('views')->nullable()->default(0);
            $table->integer('amount')->nullable()->default(0);
            $table->boolean('featured')->nullable()->default(0);
            $table->string('color')->nullable();
            $table->boolean('discount')->nullable()->default(0);
            $table->integer('sold')->nullable()->default(0);
            $table->timestamp('publish_at')->nullable();
            $table->boolean('publish')->nullable();
            $table->timestamps();
        });

        Schema::create('attribute_product', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('attribute_id')->unsigned()->index();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('products');
        Schema::dropIfExists('attribute_product');
    }
}
