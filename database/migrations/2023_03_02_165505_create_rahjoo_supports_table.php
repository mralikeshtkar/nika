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
        Schema::create('rahjoo_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('rahjoo_id')
                ->references('id')
                ->on('rahjoos')
                ->cascadeOnDelete()
                ->cascadeOnDelete();
            $table->string('pay_url')->nullable();
            $table->timestamp('pay_url_generated_at')->nullable();
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
        Schema::dropIfExists('rahjoo_supports');
    }
};
