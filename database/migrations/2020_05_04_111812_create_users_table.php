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
            $table->uuid('id')->primary();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('mobile')->unique();
            $table->timestamp('mobile_verified_at')->nullable();

            $table->string('username')->unique();
            $table->string('password');

            $table->string('name');
            $table->string('dob')->default("01-01-1990");
            $table->enum('gender', ['None', 'Male', 'Female'])->default('None');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();

            $table->uuid('language_id')->default("640a547f-97c9-4b05-8b8e-e2b10f797b0f");
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade')->onDelete('cascade');

            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');

            $table->string('version')->nullable();
            $table->string('fcm_token')->nullable();

            $table->boolean('status')->default(true);

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
