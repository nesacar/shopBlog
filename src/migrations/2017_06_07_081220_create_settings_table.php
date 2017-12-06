<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->text('google')->nullable();
            $table->text('analytics')->nullable();
            $table->text('map')->nullable();
            $table->boolean('blog')->nullable();
            $table->boolean('shop')->nullable();
            $table->boolean('colorDependence')->nullable();
            $table->boolean('materialDependence')->nullable();
            $table->boolean('newsletter')->nullable();
            $table->timestamps();
        });

        \DB::table('settings')->insert([
            'id' => '1',
            'language_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
