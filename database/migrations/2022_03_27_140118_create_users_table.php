<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->morphs('profileable');
            $table->string('username');
            $table->string('full_name')->nullable();
            $table->string('password');
            $table->string('email');
            $table->string('timezone')->default('Asia/Bangkok');

            $table->boolean('disabled')->default(true);
            $table->boolean('required_password_update')->default(false);
            $table->boolean('force_change_password')->default(false);
            $table->boolean('activated')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->blamable();
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
