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
        Schema::create('intelligence_package', function (Blueprint $table) {
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
            $table->primary(['package_id', 'intelligence_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intelligence_package');
    }
};
