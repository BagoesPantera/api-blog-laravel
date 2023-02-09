<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' =>$request->email, 'password' =>$request->password])){
            $userAuth = Auth::user();
            $success['token'] = $userAuth->createToken('auth_token')->plainTextToken;
            $success['author_id'] = $userAuth->id;
            $success['name'] = $userAuth->name;

            return $this->sendResponse($success, 'user login');
        }
        else
        {
            return $this->sendError('Unauthorised', 'email or password error');
        }
    }

    public function register(Request $request){
        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validate->fails()){
            return $this->sendError('Error validation', 'please fill up all the form');
        }

        $input = $request->all();
        $input['password'] = bcrypt($request['password']);
        $user = user::create($input);
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['author_id'] = $user->id;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'success creating user');
    }

    
}
