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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('city_id')
                ->nullable()
                ->references('id')
                ->on('cities')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('grade_id')
                ->nullable()
                ->references('id')
                ->on('grades')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('birth_place_id')
                ->nullable()
                ->references('id')
                ->on('cities')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('grade_id');
            $table->dropConstrainedForeignId('birth_place_id');
        });
    }
};
