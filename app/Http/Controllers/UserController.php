<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BillingAddress;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function index()
    {
        $users = User::all();
        return response()->json([
            "users" => $users,
            "message" => "Users retrieved successfully",
        ], 200);
    }

    
    // List customers
    public function customers()
    {
        /** 
         * Return all users
         * pagination, searching, sorting 
         * will be done client side 
         * using optimized data tables
         * 
         */
        $customers = User::where('isAdmin', false)->get();
        return response()->json([
            "customers" => $customers,
            "message" => "Users retrieved successfully",
        ], 200);
    }


    public function createCustomer(Request $request)
    {
        try{
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'country' => 'required|string|max:255',
                'addressLine1' => 'string|max:255',
                'addressLine2' => 'nullable|string|max:255',
                'state' => 'string|max:255',
                'phoneNumber' => 'string|max:255',
                'useBillingAddress' => 'nullable|boolean',
            ]);

            
            // create random password for user 
            // on login, user will be asked to change password
            $validated['password'] = bcrypt('commodity123');
            $validated['address'] = $validated['addressLine1'] . ', ' . $validated['addressLine2'];

            $user = User::create($validated);

            $user->isAdmin = false;
            $user->isSuspend = false;
            $user->save();

            if($validated['useBillingAddress'] ?? false){
                BillingAddress::create([
                    'user_id' => $user->id,
                    'address_1' => $validated['addressLine1'],
                    'address_2' => $validated['addressLine2'],
                    'state' => $validated['state'],
                    'mobile_number' => $validated['phoneNumber'],
                    'town' => $validated['town'],
                    'post_code' => $validated['postCode'],
                ]);
            }

            return response()->json([
                "message" => "Customer created successfully",
                "user" => $user,
            ], 200);
        }  catch (\Illuminate\Validation\ValidationException $e) {
            error_log($e->getMessage());
            return response()->json([
                "message" => $e->getMessage(),
                "errors" => $e->errors(),
            ], 400);
        } catch(\Exception $e){
            error_log($e->getMessage());
            return response()->json([
                "message" => "Error creating customer",
                "errors" => $e->getMessage(),
            ], 400);
        }
    }

    
    public function getCustomer(Request $request, string $customerId)
    {
        try{
    
            $user = User::where('customerID', $customerId)->first();            
            
            if(!$user){
                return response()->json([
                    "error" => "Customer not found",
                    "message" => "Customer not found",
                ], 404);
            }


            return response()->json([
                'user' => $user,
                'message' => 'Customer profile retrieved successfully',
            ], 200);
        } catch (\Exception $e){
            return response()->json([
                "message" => "Error retrieving customer",
                "error" => $e->getMessage(),
            ], 400);
        }
        
    }


    public function show(Request $request, User $user)
    {
        return response()->json([
            'user' => $user,
            'message' => 'User profile retrieved successfully',
        ], 200);
    }

    public function createUser(Request $request, User $user)
    {
        try{
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'country' => 'required|string|max:255',
                'address_1' => 'required|string|max:255',
                'address_2' => 'nullable|string|max:255',
                'town' => 'required|string|max:255',
                'state' => 'nullable|string|max:255',
                'post_code' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:255',
            ]);

            $user = User::create($validated);

            $billingAddress = BillingAddress::create([
                'user_id' => $user->id,
                'address_1' => $validated['address_1'],
                'address_2' => $validated['address_2'],
                'town' => $validated['town'],
                'state' => $validated['state'],
                'post_code' => $validated['post_code'],
                'mobile_number' => $validated['mobile_number'],
            ]);

            return response()->json([
                "message" => "User created successfully",
                "user" => $user,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error creating user",
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function suspendUser(Request $request)
    {
        try{
            $customerIds = $request->input('customerIds');

            if(!$customerIds){
                return response()->json([
                    "message" => "No customer IDs provided",
                    "error" => "No customer IDs provided",
                ], 400);
            }

            // Check if trying to delete the authenticated user
            if (in_array(Auth::user()->customerID, $customerIds)) {
                return response()->json([
                    'message' => 'You cannot suspend your own account',
                ], 403);
            }

            User::whereIn('customerID', $customerIds)->update(['isSuspend' => true]);
            return response()->json([
                "message" => "Users suspended successfully",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error suspending users",
                "error" => $e->getMessage(),
            ], 400);
        }
    }

    public function unsuspendUser(Request $request)
    {
        try{    
            $customerIds = $request->input('customerIds');

            if(!$customerIds){
                return response()->json([
                    "message" => "No customer IDs provided",
                    "error" => "No customer IDs provided",
                ], 400);
            }

            // Check if trying to delete the authenticated user
            if (in_array(Auth::user()->customerID, $customerIds)) {
                return response()->json([
                    'message' => 'You cannot unsuspend your own account',
                ], 403);
            }


            User::whereIn('customerID', $customerIds)->update(['isSuspend' => false]);
            return response()->json([
                "message" => "Users unsuspended successfully",
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                "message" => "Error unsuspending users",
                "error" => $e->getMessage(),
            ], 400);
        }
    }

    public function deleteUsers(Request $request)
    {
        try{
     
            $customerIds = $request->input('customerIds');
            
            if(!$customerIds){
                return response()->json([
                    "message" => "No customer IDs provided",
                    "error" => "No customer IDs provided",
                ], 400);
            }

            // Check if trying to delete the authenticated user
            if (in_array(Auth::user()->customerID, $customerIds)) {
                return response()->json([
                    'message' => 'You cannot delete your own account',
                ], 403);
            }

            
            User::whereIn('customerID', $customerIds)->delete();

            return response()->json([
                'message' => 'Users deleted successfully',
            ], 200);
        }catch(\Exception $e){ 
            return response()->json([
                "message" => "Error deleting users",
                "error" => $e->getMessage(),
            ], 400);
        }
    }

    public function updateUser(Request $request, User $user)
    {
        try{
            $user->update($request->all());
            return response()->json([
                "message" => "User updated successfully",
                "user" => $user,
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error updating user",
                "error" => $e->getMessage(),
            ], 400);
        }
    }

    public function makeAdmin(Request $request, User $user)
    {
        try{
            $user->isAdmin = true; 
            $user->save();
        
            return response()->json([
                'message' => 'User status updated successfully',
                ], 200);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error updating user status",
                "error" => $e->getMessage(),
            ], 400);
        }
    }

    
    public function removeAdmin(Request $request, User $user)
    {
        try{
            $user->isAdmin = false;
            $user->save();
            return response()->json([
                "message" => "User removed from admins successfully",
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                "message" => "Error removing user from admins",
                "error" => $e->getMessage(),
            ], 400);
        }
    }
}
