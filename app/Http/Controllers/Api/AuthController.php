<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register (Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                'name' => 'required|string|max:250',
                'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
                'password' => 'required|string|min:8|confirmed'
            ]);

            if ($validate->failed()) {
               return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation Error!',
                    'data' => $validate->errors(),
               ], 403);
            }

            $user = User::create([
                'name'      => $request -> name,
                'email'     => $request -> email,
                'password'  =>  Hash::make($request->password)
            ]);

            $data['token'] = $user -> createToken($request->email)->accessToken;
            $response = [
                'status' => 'success',
                'message' => 'User is created successfully.',
                'data' => $data,
            ];

            return response()->json($response, 201);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function login(Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                'email'     => 'required|string|email',
                'password'  => 'required|string'
            ]);

            if($validate->fails()){
                return response()->json([
                    'status'    => 'failed',
                    'message'   => 'Validation error' ,
                    'data'      => $validate->errors(),
                ] , 403);
            }

            //check the email exist
            $user = User::where('email', $request->email)->first();

            //check the password
            if (!$user || !Hash::check($request->password , $user->password)) {
                return response()->json([
                    'status'  => 'failed' ,
                    'message' => 'Invalid credentials'
                ]);
            }

            $data['token'] = $user->createToken($request->email)->accessToken;
            $data['user'] = $user;

            $response = [
                'status'    => 'success' ,
                'message'   => 'User logged in successfully.' ,
                'data'      => $data
            ];

            return response()->json($response , 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            $token->revoke();
            return response()->json([
                'status' => 'success',
                'message' => 'User is logged out successfully'
                ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
