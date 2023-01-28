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
        Schema::create('media_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->references('id')
                ->on('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('media_id')
                ->nullable()
                ->references('id')
                ->on('media')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('text')->nullable();
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
