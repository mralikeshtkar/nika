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
        Schema::create('intelligence_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('package_id')
                ->references('id')
                ->on('packages')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('intelligence_id')
                ->references('id')
                ->on('intelligences')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('intelligence_point_name_id')
                ->references('id')
                ->on('intelligence_point_names')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('max_point');
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
        Schema::dropIfExists('intelligence_points');
    }
};
