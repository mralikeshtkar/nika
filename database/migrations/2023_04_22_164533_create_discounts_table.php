<?php

use App\Enums\Discount\DiscountStatus;
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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->boolean('is_percent')->default(false);
            $table->string('amount');
            $table->timestamp('enable_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->string('usage_limitation')->nullable();
            $table->string('status')->default(DiscountStatus::Inactive);
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
        Schema::dropIfExists('discounts');
    }
};
