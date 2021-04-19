<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ApiAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|string',
            'company_id' => 'required|integer|exists:companies,id',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id
        ];
        $user = User::create($data);
       
        $token = $user->createToken('Registration Auth Token')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email', $request->email)->first();
        if($user && $user->is_active == 1){
            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('Login Auth Token')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        }else{
            return response()->json(['error' => 'Unauthorised', 'message' => 'User Blocked'], 401);
        }
 
    }

    /**
     * Logout
     */
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
