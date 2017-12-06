<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('role')->default(0);
            $table->boolean('block')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        \DB::table('users')->insert([
            'username' => 'Nebojsa',
            'email' => 'nebojsart1409@yahoo.com',
            'password' => bcrypt('nebojsa'),
            'block' => 0,
            'role' => 5,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
