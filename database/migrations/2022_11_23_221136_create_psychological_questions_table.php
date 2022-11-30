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
        Schema::create('psychological_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('rahjoo_id')
                ->references('id')
                ->on('rahjoos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('favourite_job_id')
                ->nullable()
                ->references('id')
                ->on('jobs')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('parent_favourite_job_id')
                ->nullable()
                ->references('id')
                ->on('jobs')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->string('negative_positive_points')->nullable();
            $table->string('favourites')->nullable();
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
        Schema::dropIfExists('psychological_questions');
    }
};
