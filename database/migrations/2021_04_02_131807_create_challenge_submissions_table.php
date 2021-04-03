<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('challenge_id');
            $table->integer('user_id');
            $table->string('submission_url')->nullable();
            $table->string('repository_url')->nullable();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->softDeletes();
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
        Schema::dropIfExists('challenge_submissions');
    }
}
