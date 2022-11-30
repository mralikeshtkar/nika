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
        Schema::create('psychological_question_skill', function (Blueprint $table) {
            $table->foreignId('skill_id')
                ->references('id')
                ->on('skills')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('psychological_question_id')
                ->references('id')
                ->on('psychological_questions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->primary(['skill_id', 'psychological_question_id'], 'pk_psychological_question_skill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psychological_question_skill');
    }
};
