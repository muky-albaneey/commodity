<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function getBanks()
    {
        $banks = Bank::all(['id', 'name', 'code']);
        return response()->json(['banks' => $banks]);
    }

    public function getUserBankAccounts(Request $request)
    {
        $user = $request->user();
        $bankAccounts = BankAccount::where('user_id', $user->id)
            ->with('bank:id,name,code')
            ->get();

        return response()->json(['bank_accounts' => $bankAccounts]);
    }

    public function addBankAccount(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'account_number' => 'required|string|size:10',
            'account_name' => 'required|string'
        ]);

        $user = $request->user();
        
        $bankAccount = BankAccount::create([
            'user_id' => $user->id,
            'bank_id' => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'is_verified' => false
        ]);

        return response()->json([
            'message' => 'Bank account added successfully',
            'bank_account' => $bankAccount
        ]);
    }
} 