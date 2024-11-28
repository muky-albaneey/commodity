<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\Wallet;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    public function index()
    {
        $trades = Trade::all();

        return response()->json([
            'trades' => $trades,
        ], 200);
    }
    public function buy(Request $request, $userId, $commodityId)
    {
        // Start a database transaction
        return DB::transaction(function() use ($request, $userId, $commodityId) {
            // Validate the quantity of the commodity to buy
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            // Find the commodity and user's wallet - lock the wallet for update
            $commodity = Commodity::findOrFail($commodityId);
            $wallet = Wallet::where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            // Calculate total cost
            $totalCost = $commodity->price * $request->quantity;

            // Check if the user has enough available balance
            if ($wallet->available_balance < $totalCost) {
                return response()->json([
                    'message' => 'Insufficient available funds in wallet.',
                    'available_balance' => $wallet->available_balance,
                    'total_cost' => $totalCost
                ], 400);
            }

            // Place lien on the funds
            if (!$wallet->placeLien($totalCost)) {
                return response()->json([
                    'message' => 'Unable to reserve funds for trade',
                ], 400);
            }

            // Create the trade
            $trade = Trade::create([
                'user_id' => $userId,
                'commodity_id' => $commodityId,
                'trade_type' => 'buy',
                'quantity' => $request->quantity,
                'total_price' => $totalCost,
                'status' => 'pending' // Add status field if not already present
            ]);

            return response()->json([
                'message' => 'Buy order placed successfully',
                'trade' => $trade,
                'wallet' => [
                    'balance' => $wallet->balance,
                    'available_balance' => $wallet->available_balance,
                    'lien_balance' => $wallet->lien_balance
                ]
            ], 201);
        });
    }

    public function sell(Request $request, $userId, $commodityId)
    {
        // Similar logic for sell orders
        // Note: Might need to place lien on commodity quantity instead of wallet balance
    }
}

