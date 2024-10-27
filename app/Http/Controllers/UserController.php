<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // List all users with their wallets
    public function index()
    {
        $users = User::with('wallet', 'trades')->paginate(10);  // 10 users per page
        return response()->json($users, 200);
    }
    


    // View a single user with their wallet and trades
        public function show($id)
        {
            // Eager load both 'wallet' and 'trades' relationships
            $user = User::with('wallet', 'trades')->findOrFail($id);
            return response()->json($user, 200);
        }

        // storeUser
        public function storeUser(Request $request)
        {
            // Validate required and optional fields
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
        
            // // Create the user
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization' => $request->organization,
                'phoneNumber' => $request->phoneNumber,
                'address' => $request->address,
                'state' => $request->state,
                'zipCode' => $request->zipCode,
                'country' => $request->country,
                'language' => $request->language,
                'currency' => $request->currency,
                'isAdmin' => false,
                'isSuspend' => false,
            ]);
        
            // Return a response with the created user data
            // Auth
            Auth::login($user);
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        }

        public function login(Request $request)
        {
            // Validate email and password fields
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        
            // Attempt to log the user in with the provided credentials
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Get the authenticated user
                $user = Auth::user();
                Auth::login($user);
                // Return a successful response with user data
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user->load('wallet', 'trades'),
                ], 200);
            } else {
                // Return an error if authentication fails
                return response()->json([
                    'message' => 'Invalid email or password',
                ], 401);
            }
        }
        
    public function store(Request $request)
    {
        // Validate required and optional fields
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        // // Create the user
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization' => $request->organization,
            'phoneNumber' => $request->phoneNumber,
            'address' => $request->address,
            'state' => $request->state,
            'zipCode' => $request->zipCode,
            'country' => $request->country,
            'language' => $request->language,
            'currency' => $request->currency,
            'isAdmin' => false,
            'isSuspend' => false,
        ]);
    
        // Return a response with the created user data
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        // Find the user by ID or fail
        $user = User::findOrFail($id);

        // Validate only the fields that are being updated
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        // Update the user with the validated data
        $user->update($request->only(['name', 'email']));

        // If a password is provided, hash it and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Save changes to the user
        $user->save();

        // Return a JSON response with the updated user data
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('wallet'),  // Load the wallet in the response
        ], 200);
    }
    public function isSuspend(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->isSuspend = !$user->isSuspend; // Toggle the suspend status
        $user->save();
    
        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user->load('wallet'),
        ], 200);
    }
    
    

    public function isAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->isAdmin = !$user->isAdmin; // Toggle the suspend status
        $user->save();
    
        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user->load('wallet'),
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