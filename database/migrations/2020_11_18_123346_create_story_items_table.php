<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('story_id')->nullable();
            $table->foreign('story_id')->references('id')->on('stories')->onUpdate('cascade')->onDelete('cascade');

            $table->enum('type', ['image', 'video'])->default('image');

            $table->string('source');

            $table->integer('duration');

            $table->text('caption');

            $table->number('order');

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
        Schema::dropIfExists('story_items');
    }
}
