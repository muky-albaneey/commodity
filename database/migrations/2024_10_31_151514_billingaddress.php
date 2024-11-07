<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('state')->nullable();
            $table->string('town')->nullable();
            $table->string('post_code')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_addresses');
    }
};
