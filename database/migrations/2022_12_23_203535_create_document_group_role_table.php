<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_group_role', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('document_group_id')
                ->references('id')
                ->on('document_groups')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->primary(['role_id', 'document_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_group_role');
    }
};
