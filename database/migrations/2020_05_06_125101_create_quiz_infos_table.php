<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->boolean('auto')->default(true);
            $table->string('title')->unique();

            $table->integer('entry_fee')->default(0);

            $table->integer('total_participants')->default(100);
            $table->integer('total_winners')->default(10);
            $table->integer('required_participants')->default(100);

            $table->integer('all_questions_count')->default(50);
            $table->integer('answerable_questions_count')->default(10);

            $table->integer('expiry')->default(60)->comment('expiry in minutes');
            $table->integer('notify')->default(15)->comment('reading in minutes');
            $table->integer('time')->default(10)->comment('time in seconds');

            $table->json('prize_distribution')->nullable();

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
        Schema::dropIfExists('quiz_infos');
    }
}
