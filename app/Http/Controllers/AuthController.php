<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request) 
    {
        // Validate for required fields e.g email and password
        $request->validated($request->all());

        // Check if auth attempt has not been made
        if(!Auth::attempt($request->only('email','password'))) {
            // Returns error with no data, error message and code
            return $this->error('', 'Credentials do not match', 401);
        }

        // The user trying to login
        $user = User::where('email', $request->email)->first();

        // Returns a succuss and creates a token for the user
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of' . $user->name)->plainTextToken
        ]);

    }

    public function register(StoreUserRequest $request) 
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout() 
    {
        return response()->json('This is my logout method');
    }
}
