<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AdminResource;
class AuthController extends Controller

{
    public function index(Request $request){

        if((auth('sanctum')->user())->is_super_admin){
            $admin = Admin::all();
            $adminCollection =  AdminResource::collection($admin);
            return response()->json([
                'status' => true,
                'message' => 'All admin records retrieved successfully.',
                'data' => $adminCollection,
            ]);
        }
        else{
            return response()->json([
            'status' => false,
            'message' => 'Only the super admin can view the admin records.',
        ], 401);
        }
    }


    /**
    * Register a new admin (only super admin can do this)
    */

    public function create(RegisterRequest $request)
        {
            $validatedData = $request->validated();
            // Only super admin can register new admin
            if (!auth('sanctum')->user()->is_super_admin) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }


            $admin = Admin::where('email', $validatedData['email'])->first();

            if ($admin) {
                    return response()->json(['status' => false, 'message' => 'The email already belongs to an admin account'], 302);
                }
            if (!$admin)
            {
                $admin = new Admin();

                if(isset($validatedData['email'])) {
                    $admin->email = $validatedData['email'];
                }
                if(isset($validatedData['password'])) {
                    $admin->password = bcrypt($validatedData['password']);
                }

                $admin->save();

                if(!empty($admin))
                {
                    return response()->json(['status' =>true, 'message' => 'Admin account created successfully'], 201);

                    
                }
                return response()->json(['status' => false, 'message' => 'Account could not be created!'], 400);
            }

        }

        /**
         *  Login admin
         */
    
    public function login(LoginRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Check if the admin exists
        $admin = Admin::where('email', $validatedData['email'])->first();

        // If admin does not exist
        if (!$admin) {
            return response()->json(['status' => false, 'message' => 'Admin not found'], 404);
        }

        // Attempt to authenticate the user using email with the provided password
        if (!(Auth::guard('web')->attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']], $request->remember) )) 
        {
            // If authentication fails, return an error response
            return response()->json(['status' => false, 'message' => 'Either email or password is wrong'], 400);
        }

        // If authentication succeeds, get the authenticated user
       // $user = Auth::user();
        $user = Auth('sanctum')->user();

        // Create a new API token for the user
        $token = $user->createToken('AuthToken')->plainTextToken;

        // Return the authenticated user's data with the generated token
        //return new AdminResource($user, $token, 'Bearer', 'Login successful');

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
             'header' => [
                'accessToken' => $token,
                'tokenType' => 'Bearer',
            ],
            'data' => new AdminResource($user)
        ]);
    }


        /**
         * Logout admin
         */
        
        public function logout(Request $request)
        {
            if ($request->user()) {
                $token = $request->user()->currentAccessToken();
                
                if ($token) {
                    $token->delete();   //delete token given by sanctum guard
                    Auth::guard('web')->logout();       //delete the session by the auth web guard
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Token not found"
                    ], 401);
                }
            }

            return response()->json([
                "status" => true,
                "message" => "logged out sucessfully"
            ], 200);
        }

          /**
     * Delete Account
     *
     * Requesting for account deletion
     * @authenticated
     */
        public function destroy($id)
        {
            // Only super admin can delete an admin account
            if (!auth()->user()->is_super_admin) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            //ensure that the request contains a valid admin_id and that the admin_id exists in the admins table.
            // $validator = Validator::make($request->all(), [
            //     'admin_id' => 'required|exists:admins,id',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }

           // $admin = Admin::find($request->admin_id);
            $admin = Admin::findOrFail($id);

            if ($admin->is_super_admin) {
                return response()->json(['error' => 'Cannot delete a super admin'], 400);
            }

            $admin->delete();

            return response()->json(['message' => 'Admin account deleted successfully']);
        }
}






