<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string("name_kh");
            $table->string("name_en");
            $table->integer("identity_number");
            $table->date('date_of_birth')->nullable();
            $table->string('primary_phone');
            $table->string('address');
            $table->string('place_of_birth');
            $table->enum('gender', ['female', 'male', 'other'])->nullable();
            $table->enum('category', ['muslim', 'non_muslim'])->nullable();

            $table->string('avatar_url');
            $table->json('id_or_passport');

            $table->timestamps();
        });

//        Schema::create('payments', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('policy_id');
//            $table->decimal('amount', 10, 3);
//            $table->string('method');
//
//            $table->timestamps();
//
//            $table->foreign('policy_id')->references('id')->on('subscribers')->onDelete('cascade');
//        });

        Schema::create('subscriber_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('type')->nullable();

            $table->timestamps();

            $table->foreign('policy_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_policies');
        Schema::dropIfExists('subscribers');
    }
}
