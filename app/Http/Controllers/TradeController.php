<?php

// namespace App\Http\Controllers;

// use App\Models\Commodity;
// use App\Models\Wallet;
// use Illuminate\Http\Request;

// class TradeController extends Controller
// {
//     //
//     public function buy(Request $request, $userId, $commodityId)
//     {
//         // Validate the quantity of the commodity to buy
//         $request->validate([
//             'quantity' => 'required|integer|min:1',
//         ]);

//         // Find the commodity and user's wallet
//         $commodity = Commodity::findOrFail($commodityId);
//         $wallet = Wallet::where('user_id', $userId)->firstOrFail();

//         // Calculate total cost
//         $totalCost = $commodity->price * $request->quantity;

//         // Check if the user has enough balance in their wallet
//         if ($wallet->balance < $totalCost) {
//             return response()->json([
//                 'message' => 'Insufficient funds in wallet.',
//                 'wallet_balance' => $wallet->balance,
//                 'total_cost' => $totalCost
//             ], 400);
//         }

//         // Deduct the total cost from the user's wallet
//         $wallet->balance -= $totalCost;
//         $wallet->save();

//         // Optionally, you can log the purchase or create a record in a "Trade" or "Transaction" table

//         return response()->json([
//             'message' => 'Purchase successful.',
//             'remaining_balance' => $wallet->balance,
//             'purchased_quantity' => $request->quantity,
//             'commodity_name' => $commodity->name,
//             'total_cost' => $totalCost,
//         ], 200);
//     }
// }

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\Wallet;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    //
    public function buy(Request $request, $userId, $commodityId)
    {
        // Validate the quantity of the commodity to buy
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the commodity and user's wallet
        $commodity = Commodity::findOrFail($commodityId);
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        // Calculate total cost
        $totalCost = $commodity->price * $request->quantity;

        // Check if the user has enough balance in their wallet
        if ($wallet->balance < $totalCost) {
            return response()->json([
                'message' => 'Insufficient funds in wallet.',
                'wallet_balance' => $wallet->balance,
                'total_cost' => $totalCost
            ], 400);
        }

        // Deduct the total cost from the user's wallet
        $wallet->balance -= $totalCost;
        $wallet->save();

        // Store the trade in the "trades" table
        $trade = Trade::create([
            'user_id' => $userId,
            'commodity_id' => $commodityId,
            'trade_type' => 'buy',  // This is a purchase
            'quantity' => $request->quantity,
            'total_price' => $totalCost,
        ]);

        // Optionally, store the transaction in the "transactions" table if you have it
        // Transaction::create([
        //     'user_id' => $userId,
        //     'commodity_id' => $commodityId,
        //     'quantity' => $request->quantity,
        //     'total_cost' => $totalCost,
        // ]);

        return response()->json([
            'message' => 'Purchase successful.',
            'remaining_balance' => $wallet->balance,
            'purchased_quantity' => $request->quantity,
            'commodity_name' => $commodity->name,
            'total_cost' => $totalCost,
            'trade' => $trade,  // Return the trade details
        ], 200);
    }
}

