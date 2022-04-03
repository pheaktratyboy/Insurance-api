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
            $table->bigIncrements('id');
            $table->string("name_kh");
            $table->string("name_en");
            $table->string("identity_number");
            $table->date('date_of_birth')->nullable();
            $table->string('phone_number');
            $table->string('address');
            $table->string('place_of_birth');
            $table->enum('gender', ['female', 'male', 'other'])->nullable();
            $table->enum('religion', ['muslim', 'non_muslim'])->nullable();
            $table->text('note')->nullable();
            $table->decimal('total', 10, 2)->default(0);

            $table->json('avatar')->nullable();
            $table->json('id_or_passport_front')->nullable();;
            $table->json('id_or_passport_back')->nullable();
            $table->json('attachments')->nullable();

            $table->string('status');
            $table->boolean('disabled')->default(false);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id')->nullable();

            $table->timestamps();
        });

        Schema::create('subscriber_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('policy_id');
            $table->string('payment_method');
            $table->dateTime('expired_at');

            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
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
