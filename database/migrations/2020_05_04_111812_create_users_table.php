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

            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('mobile')->unique();
            $table->timestamp('mobile_verified_at')->nullable();

            $table->string('username')->nullable()->unique();
            $table->string('password')->nullable();

            $table->string('name')->nullable();
            $table->string('dob')->default("01-01-1990");
            $table->enum('gender', ['None', 'Male', 'Female'])->default('None');
            $table->string('avatar')->nullable();
            $table->text('instagram_username')->nullable();
            $table->text('bio')->nullable();

            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');

            $table->string('version')->nullable();
            $table->string('fcm_token')->nullable();

            $table->boolean('status')->default(false);
            $table->boolean('demo')->default(false);

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
