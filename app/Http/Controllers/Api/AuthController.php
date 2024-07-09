<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try 
        {
            $validateuser = Validator::make($request->all(), [
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'is_admin' => 'required|boolean'
            ]);

            if($validateuser->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'Validation error',
                    'errors'=> $validateuser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email, 
                'password' => Hash::make($request->password),
                'is_admin' => $request->is_admin,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 201);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try 
        {
            $validateuser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
                //'_token' => 'required|string'
            ]);

            if($validateuser->fails()){
                return response()->json([
                    'status' => false,
                    'message'=> 'Validation error',
                    'errors'=> $validateuser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message'=> 'Email & password do not match our records'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 201);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message'=> $th->getMessage(),
            ], 500);
        }
    }

    public function profile()
    {
        $userData = auth()->user();
        return response()->json([
            'status' => true,
            'message' => 'Profile Information',
            'data' => $userData,
            'id' => auth()->user()->id
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out',
            'data' => []
        ], 200);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}