<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users with their wallets
    public function index()
    {
        $users = User::with('wallet', 'trades')->get();  // Eager load wallet
        return response()->json($users, 200);
    }

    // View a single user with their wallet
    public function show($id)
    {
        $user = User::with('wallet')->findOrFail($id);  // Eager load wallet
        return response()->json($user, 200);
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Automatically create a wallet with a default balance
        $user->wallet()->create([
            'balance' => $request->wallet_balance ?? 0.00,  // Set the initial wallet balance
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->load('wallet'),  // Load the wallet in the response
        ], 201);
    }

    // Update an existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        $user->update($request->only(['name', 'email']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('wallet'),  // Load the wallet in the response
        ], 200);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
