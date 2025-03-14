<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeems', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('image')->nullable();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->string('gateway');
            $table->string('mobile');
            $table->integer('coins');
            $table->decimal('amount', 8, 2)->default(0);

            $table->enum('status', ['pending', 'success', 'fail'])->default('pending');

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
        Schema::dropIfExists('redeems');
    }
}
