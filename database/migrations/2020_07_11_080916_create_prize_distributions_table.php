<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrizeDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prize_distributions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quiz_info_id')->nullable();
            $table->foreign('quiz_info_id')->references('id')->on('quiz_infos')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('rank');
            $table->string('prize');

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
        Schema::dropIfExists('prize_distributions');
    }
}
