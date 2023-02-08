<?php

use App\Enums\User\UserBackground;
use App\Enums\User\UserColor;
use App\Enums\UserStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('background')->default(UserBackground::Black);
            $table->string('color')->default(UserColor::Pink);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mobile')->unique();
            $table->string('national_code')->nullable();
            $table->string('ip')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('password')->nullable();
            $table->string('verification_code')->nullable();
            $table->dateTime('verification_code_expired_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->unsignedTinyInteger('status')->default(UserStatus::Active);
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
        Schema::dropIfExists('users');
    }
};
