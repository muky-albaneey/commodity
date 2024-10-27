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
        Schema::create('commodities', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID as the primary key
            $table->string('name'); // Name of the commodity
            $table->text('description')->nullable(); // Description of the commodity
            $table->string('image')->nullable(); // Image path for the commodity
            $table->decimal('price', 15, 2); // Price of the commodity
            $table->decimal('market_price', 15, 2)->nullable(); // Current market price, can fluctuate
            $table->string('category')->nullable(); // Category like precious metals, agriculture, etc.
            $table->string('unit')->default('kilogram'); // Unit of measurement, e.g., kg, oz
            $table->integer('stock')->default(0); // Available stock quantity
            $table->string('origin_country')->nullable(); // Country of origin
            $table->string('supplier')->nullable(); // Supplier name or company
            // $table->timestamp('last_purchased_at')->nullable(); // Timestamp of the last purchase
            $table->string('expiry_date')->nullable(); // Expiry date if applicable
            // $table->json('tags')->nullable(); // Tags related to the commodity
            $table->decimal('rating', 3, 2)->default(0); // Average rating, e.g., 4.8
            $table->integer('reviews_count')->default(0); // Count of reviews
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
