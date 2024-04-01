<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AuthController extends Controller
{
    use AuthorizesRequests;
    public function register (Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                'name'      => 'required|string|max:250',
                'email'     => 'required|string|email:rfc,dns|max:250|unique:users,email',
                'password'  => 'required|string|min:8|confirmed'
            ]);

            if ($validate->failed()) {
               return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error!',
                    'data'    => $validate->errors(),
               ], 403);
            }

            $user = User::create([
                'name'      => $request -> name,
                'email'     => $request -> email,
                'password'  =>  Hash::make($request->password)
            ]);

            $data['token'] = $user -> createToken($request->email)->accessToken;
            $response = [
                'status'    => true,
                'message'   => 'User is created successfully.',
                'data'      => $data,
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
                    'status'    => false,
                    'message'   => 'Validation error' ,
                    'data'      => $validate->errors(),
                ] , 403);
            }

            $user = User::where('email', $request->email)->first();

            $data['roles'] = $user->roles()->get()
            ->flatten()
            ->pluck('name')
            ->values()
            ->toArray();

            $data['permissions'] = $user->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();


            if (!$user || !Hash::check($request->password , $user->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid credentials'
                ]);
            }

            $data['token'] = $user->createToken($request->email)->accessToken;
            $data['user'] = new UserResource($user);

            $response = [
                'status'    => true,
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
                'status'    => true,
                'message'   => 'User is logged out successfully'
                ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listUser()
    {
        try {
            // dd(Auth::user()->can('delete-user'));
             $user = User::with('roles.permissions')->where('id',Auth::user()->id)->first();
             $data = new UserResource($user);
            return response()->json([
                'status'    => true,
                'message'   => 'get user successfully',
                'data'      => $data,
                ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
     public function getUserCourse(Request $request)
    {
       try {
            if(Auth::check()){
                $response = Http::get(env('API_COURSE_ENGINE'). '/api/v1/course/get-all-course', [
                ]);
                if ($response->successful()) {
                    $data = $response->json();
                    return response()->json($data); // Return JSON data
                } else {

                    $statusCode = $response->status();
                    return response()->json(['error' => 'API call failed'], $statusCode);
                }
            }
            else{
                return response()->json([
                    'status'  => false,
                    'message' => 'user need to login',
                ]);
            }
       } catch (\Exception $e) {
            return $e->getMessage();
       }
    }

    public function validateToken(Request $request)
    {
       try {
            if (Auth::guard('api')->check()) {
                return response()->json(['valid' => true]);
            } else {
                return response()->json(['valid' => false], 401);
            }
       } catch (\Exception $e) {
            return $e->getMessage();
       }
    }

}
