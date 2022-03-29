<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->integer("identity_number");
            $table->date('date_of_birth')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('address');
            $table->string('place_of_birth');

            $table->enum('gender', Gender::getValues())->nullable();
            $table->enum('category', ['muslim', 'non_muslim'])->default("non_muslim");

            $table->string('avatar_url');
            $table->json('id_or_passport');

            $table->decimal('commission')->default(10);;
            $table->decimal('kpi');

            $table->unsignedBigInteger('municipality_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->blamable();
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
        Schema::dropIfExists('staff');
    }
}
