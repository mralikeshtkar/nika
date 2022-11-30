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
        Schema::create('rahjoo_parents', function (Blueprint $table) {
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
            $table->foreignId('job_id')
                ->nullable()
                ->references('id')
                ->on('jobs')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('grade_id')
                ->nullable()
                ->references('id')
                ->on('grades')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('major_id')
                ->nullable()
                ->references('id')
                ->on('majors')
                ->nullOnDelete()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('mobile');
            $table->tinyInteger('gender');
            $table->date('birthdate')->nullable();
            $table->unsignedInteger('child_count')->nullable();
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
        Schema::dropIfExists('rahjoo_parents');
    }
};
