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
        Schema::create('intelligence_package_point_rahjoo', function (Blueprint $table) {
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
            $table->foreignId('intelligence_package_id')
                ->references('pivot_id')
                ->on('intelligence_package')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('intelligence_id')
                ->references('id')
                ->on('intelligences')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('point');
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
        Schema::dropIfExists('intelligence_package_point_rahjoo');
    }
};
