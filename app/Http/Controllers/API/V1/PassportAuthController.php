<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PassportAuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => 0
        ]);

        return $this->sendResponse([], 'Registration successful');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $validatedData = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($validatedData)) {
            $token = auth()->user()->createToken('RakaminBlog')->accessToken;
            return $this->sendResponse(['token' => $token], 'Login successful');
        }
        else {
            return $this->sendError('Invalid Credentials', ['message' => 'Invalid Credentials']);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return $this->sendResponse([], 'Logout succesful');
    }
}
