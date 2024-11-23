<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, drop columns we don't need anymore
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'market_price',
                'unit',
                'stock',
                'origin_country',
                'supplier',
                'expiry_date',
                'rating',
                'reviews_count'
            ]);
        });

        // Then, add new columns for commodity exchange
        Schema::table('commodities', function (Blueprint $table) {
            $table->string('symbol')->unique()->after('name');
            $table->integer('minimum_quantity')->after('image');
            $table->integer('maximum_quantity')->after('minimum_quantity');
            $table->json('specifications')->after('category');
            $table->string('trading_hours')->after('specifications');
            $table->string('settlement_type')->after('trading_hours');
            $table->string('contract_size')->after('settlement_type');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('contract_size');

            // Add indexes
            $table->index('symbol');
            $table->index('category');
            $table->index('status');
        });

        // Create markets table
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('commodity_id');
            $table->decimal('best_sell', 15, 2)->default(0);
            $table->decimal('best_buy', 15, 2)->default(0);
            $table->decimal('market_price', 15, 2)->default(0);
            $table->decimal('change_24h', 8, 2)->default(0);
            $table->decimal('volume_24h', 15, 2)->default(0);
            $table->integer('buyers_count')->default(0);
            $table->integer('sellers_count')->default(0);
            $table->decimal('market_value', 20, 2)->default(0);
            $table->timestamps();

            $table->foreign('commodity_id')
                  ->references('id')
                  ->on('commodities')
                  ->onDelete('cascade');

            $table->index(['commodity_id', 'market_price']);
        });
    }

    public function down(): void
    {
        // Drop the markets table
        Schema::dropIfExists('markets');

        // Revert commodities table changes
        Schema::table('commodities', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'symbol',
                'minimum_quantity',
                'maximum_quantity',
                'specifications',
                'trading_hours',
                'settlement_type',
                'contract_size',
                'status'
            ]);

            // Restore original columns
            $table->decimal('price', 15, 2);
            $table->decimal('market_price', 15, 2)->nullable();
            $table->string('unit')->default('kilogram');
            $table->integer('stock')->default(0);
            $table->string('origin_country')->nullable();
            $table->string('supplier')->nullable();
            $table->string('expiry_date')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
        });
    }
}; 