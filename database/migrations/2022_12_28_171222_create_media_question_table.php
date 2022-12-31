<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_question', function (Blueprint $table) {
            $table->foreignId('question_id')
                ->references('id')
                ->on('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('media_id')
                ->references('id')
                ->on('media')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->primary(['question_id', 'media_id']);
            $table->unsignedSmallInteger('priority');
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
        Schema::dropIfExists('media_question');
    }
};
