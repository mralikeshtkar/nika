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
        Schema::create('intelligence_point_question', function (Blueprint $table) {
            $table->foreignId('question_id')
                ->references('id')
                ->on('questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('intelligence_point_id')
                ->references('id')
                ->on('intelligence_points')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('max_point');
            $table->text('description')->nullable();
            $table->primary(['question_id', 'intelligence_point_id'],'primary_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intelligence_point_question');
    }
};
