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
            'name' => 'required',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('RakaminBlog')->accessToken;

        return $this->sendResponse(['token' => $token], 'Registration successful');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6',
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
            return $this->sendError('Unauthorized', [], 401);
        }
    }
}
