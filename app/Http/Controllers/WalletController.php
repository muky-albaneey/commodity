<?php

// namespace App\Http\Controllers;

// use App\Models\Wallet;
// use Illuminate\Http\Request;

// class WalletController extends Controller
// {
//     //
//      // Fund the wallet
//      public function fund(Request $request, $userId)
//      {


//          $request->validate([
//              'amount' => 'required|numeric|min:1',
//          ]);



//          $wallet = Wallet::where('user_id', $userId)->firstOrFail();


//         //  $wallet->balance += $request->amount;

//         //  $wallet->save();
//         //  return response()->json($wallet->amount,201);
//         // return response()->json([
//         //     'message' => 'Wallet funded successfully',
//         //     'balance' => $wallet->balance,
//         // ],200);
//         //  return response()->json([
//         //      'message' => 'Wallet funded successfully',
//         //      'balance' => $wallet->balance,
//         //  ], 200);
//      }

//      // Show wallet balance
//      public function show($userId)
//      {
//          $wallet = Wallet::where('user_id', $userId)->firstOrFail();

//          return response()->json([
//              'balance' => $wallet->balance,
//          ], 200);
//      }
// }
namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function index()
    {
        $wallets = Wallet::all();

        return response()->json([
            'wallets' => $wallets,
        ], 200);
    }

    // Fund the wallet
    public function fund(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Since every user should have a wallet, use firstOrFail.
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        // Update the wallet balance
        $wallet->balance += $request->amount;
        $wallet->save();

        return response()->json([
            'message' => 'Wallet funded successfully',
            'balance' => $wallet->balance,
        ], 200);
    }

    // Show wallet balance
    public function show($userId)
    {
        $wallet = Wallet::where('user_id', $userId)->firstOrFail();

        return response()->json([
            'balance' => $wallet->balance,
        ], 200);
    }
}
