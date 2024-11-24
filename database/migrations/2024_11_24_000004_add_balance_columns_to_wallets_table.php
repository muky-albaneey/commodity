<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Wallet;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('available_balance', 15, 2)->default(0.00)->after('balance');
            $table->decimal('lien_balance', 15, 2)->default(0.00)->after('available_balance');
        });

        // Update existing wallets to set available_balance equal to current balance
        Wallet::query()->update([
            'available_balance' => DB::raw('balance'),
            'lien_balance' => 0
        ]);
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['available_balance', 'lien_balance']);
        });
    }
}; 