<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt(['email' =>$request->email, 'password' =>$request->password])){
            return response()->json(['token' => Auth::user()->createToken('auth_token')->plainTextToken], 401);
        } else {
            return response()->json(['message' => 'Email or Password wrong'], 401);
        }
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = new User([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        $save = $user->save();
        if ($save) {
            if(Auth::attempt(['email' =>$request->email, 'password' =>$request->password])){
                return response()->json(['token' => Auth::user()->createToken('auth_token')->plainTextToken], 401);
            } else {
                return response()->json(['message' => 'Email or Password wrong'], 401);
            }
        } else {
            return response()->json(['message' => 'Register failed.'], 500);
        }
    }
}
