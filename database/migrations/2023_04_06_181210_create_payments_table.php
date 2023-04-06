<?php

use App\Enums\Payment\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('rahjoo_support_id')
                ->references('id')
                ->on('rahjoo_supports')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('action');
            $table->foreignId('paymentable_id');
            $table->string('paymentable_type');
            $table->string('invoice_id');
            $table->string('amount');
            $table->string('gateway');
            $table->string('status')->default(PaymentStatus::Pending);
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
        Schema::dropIfExists('payments');
    }
};
