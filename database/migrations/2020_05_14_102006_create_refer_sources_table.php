<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refer_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name')->nullable()->unique();
            $table->string('url')->nullable()->unique();

            $table->string('youtube')->nullable()->unique();
            $table->string('instagram')->nullable()->unique();
            $table->string('facebook')->nullable()->unique();
            $table->string('website')->nullable()->unique();


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
        Schema::dropIfExists('refer_sources');
    }
}
