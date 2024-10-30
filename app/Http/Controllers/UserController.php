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
        // Validate request data
        $validatedData = $request->validate([
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'organization' => 'sometimes|nullable|string|max:255',
            'phoneNumber' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'zipCode' => 'sometimes|nullable|string|max:20',
            'country' => 'sometimes|nullable|string|max:255',
            'language' => 'sometimes|nullable|string|max:255',
            'currency' => 'sometimes|nullable|string|max:255',
            'isAdmin' => 'sometimes|boolean',
            'isSuspend' => 'sometimes|boolean',
            // Ensure trades is not included in the validation
        ]);
    
        // Find the user by ID or fail
        $user = User::findOrFail($id);
    
        // Update all fields provided in the request except the password and wallet
        // Exclude 'trades' explicitly
        $user->fill($request->except(['password', 'wallet', 'trades']));
    
        // Update the password if provided, hashing it directly
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Save changes to the user
        $user->save();
    
        // Return a JSON response with the updated user data, including the wallet
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('wallet'),  // Eager load wallet in the response
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