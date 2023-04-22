<?php

use App\Enums\Order\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rahjoo_id')
                ->references('id')
                ->on('rahjoos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('rahjoo_support_id')
                ->nullable()
                ->references('id')
                ->on('rahjoo_supports')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('payment_id')
                ->references('id')
                ->on('payments')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('code');
            $table->string('tracking_code')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default(OrderStatus::Preparation);
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
        Schema::dropIfExists('orders');
    }
};
