<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onUpdate('cascade')->onDelete('cascade');

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->decimal('points', 8, 2)->nullable()->default(0);
            $table->integer('rank')->nullable()->default(0);
            $table->decimal('prize', 8, 2)->nullable()->default(0);

            $table->enum('status', ['joined', 'started', 'finished', 'missed', 'suspended'])->default('joined');

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
        Schema::dropIfExists('quiz_participants');
    }
}
