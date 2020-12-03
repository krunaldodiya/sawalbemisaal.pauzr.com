<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('topic_id');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');

            $table->string('subject');
            $table->string('title');
            $table->text('description');

            $table->string('image')->nullable();
            $table->string('sound')->nullable();

            $table->boolean('status')->default(false);
            $table->json('response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_notifications');
    }
}
