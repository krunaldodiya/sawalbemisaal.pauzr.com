<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankingToQuizParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_participants', function (Blueprint $table) {
            $table->integer('rank')->nullable()->default(0);
            $table->decimal('prize', 8, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_participants', function (Blueprint $table) {
            $table->dropColumn('rank');
            $table->dropColumn('prize');
        });
    }
}
