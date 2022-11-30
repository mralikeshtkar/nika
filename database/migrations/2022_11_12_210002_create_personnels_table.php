<?php

use App\Enums\Personnel\PersonnelComputerLevel;
use App\Enums\Personnel\PersonnelLanguageLevel;
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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('major_id')
                ->nullable()
                ->references('id')
                ->on('majors')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('job_id')
                ->nullable()
                ->references('id')
                ->on('majors')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('birth_certificate_place_id')
                ->nullable()
                ->references('id')
                ->on('cities')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->boolean('is_married')->nullable();
            $table->string('birth_certificate_number')->nullable();
            $table->string('email')->nullable();
            $table->enum('language_level', PersonnelLanguageLevel::asArray())->nullable();
            $table->enum('computer_level', PersonnelComputerLevel::asArray())->nullable();
            $table->string('research_history')->nullable();
            $table->boolean('is_working')->nullable();
            $table->text('work_description')->nullable();
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
        Schema::dropIfExists('personnels');
    }
};
