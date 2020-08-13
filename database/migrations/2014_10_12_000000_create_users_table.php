<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        //Most of these fields are nullable because socialite use may not have these info filled up.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('city')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('birthday'); //It's not smart to make it nullable because we are filtering birthdays. Null birthday will throw an error.
            $table->string('interest')->nullable();
            $table->text('about')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('provider_id')->nullable(); //It will be null for the user who will try to login using register form.
            $table->string('provider_name')->nullable(); //It will be null for the user who will try to login using register form.
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
