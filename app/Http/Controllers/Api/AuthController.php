<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated=$request->validate([
            'name'=>['required','string','max:100'],
            'email'=>['required','string','email','unique:users,email'],
            'password'=>['required','string','min:8'],
            'password_confirmation'=>['required','same:password']
        ]);

        $user=User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password'])
        ]);

        $token=$user->createToken('auth-token')->plainTextToken;
        
        return response()->json([
            'message'=>"account created successfully!",
            'user'=>$user,
            'token'=>$token
        ],201);
    }

    public function login(Request $request){
        $validated=$request->validate([
            'email'=>['required','email','exists:users,email'],
            'password'=>['required','string','min:8'],
        ]);

        $user=User::where('email',$validated['email'])->first();
        if(!$user || !Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message'=>'Invalid credentials'
            ],401);
        }

        $token=$user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message'=>'Logged in successfully!',
            'user'=>$user,
            'token'=>$token
        ],200);
    }

    public function logout(Request $request){
        $user=$request->user();

        if(!$user){
            return response()->json([
                'message'=>'Unauthenticated'
            ],401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'message'=>'Logged out successfully!'
        ],200);
    }

    public function checkAuth(Request $request){
        $user=$request->user();

        if(!$user){
            return response()->json([
                'message'=>'Unauthenticated'
            ],401);
        }

        return response()->json([
            'message'=>'Authenticated',
            'user'=>$user,
        ],200);
    }
}
