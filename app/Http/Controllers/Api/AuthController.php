<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class

AuthController extends Controller
{
    use AuthorizesRequests;
    public function register (Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                'name'      => 'required|string|max:250',
                'email'     => 'required|string|email:rfc,dns|max:250|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
                'bfi_id'       => 'required'
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
                'bfi_id'    => $request -> bfi_id,
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

            $user = User::where('email', $request->email)->with('bfi')->first();

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
                $user =  User::with('bfi')->where('id',Auth::user()->id)->first();
                return response()->json([
                    'message' => true,
                    'user_id' => $user
                ]);
            } else {
                return response()->json(['valid' => false], 401);
            }
       } catch (\Exception $e) {
            return $e->getMessage();
       }
    }

    public function enrollUserInCourse(Request $request)
    {
        try {
                $dynamoDbClient = new DynamoDbClient([
                    'region' => env('AWS_DEFAULT_REGION'),
                    'version' => 'latest',
                    'credentials' => [
                        'key'    =>  env('AWS_ACCESS_KEY_ID'),
                        'secret' =>  env('AWS_SECRET_ACCESS_KEY'),
                    ]
                ]);

                $courses = DB::table('course_user')
                ->where('user_id', $request->userId)
                ->pluck('course_id')
                ->toArray();

                $courseDetails = [];
            foreach ($courses as $courseId) {
                $result = $dynamoDbClient->getItem([
                    'TableName' => 'CourseMaterial',
                    'Key' => [
                        'PK' => ['S' => $courseId]
                    ]
                ]);

                if (isset($result['Items'])) {
                    $courses =  [];
                    foreach ($result['Items'] as $item) {
                        $course = [];
                        foreach ($item as $key => $value) {
                            $course[$key] = $value;
                        }
                        $courses[] = $course;
                    }
                    return response()->json($courses);
                }
            }
                return new JsonResponse($courseDetails);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function createEmployer(Request $request)
    {
        try {
            $request->validate([
                'name'      => 'required',
                'bfi_id'    => 'required|exists:bfis,id',
                'role_id'   => 'required',
                'email'     => 'required|email|unique:users,email',
            ]);
            $password = Str::password(8, true, true, false, false);
            $user = User::create([
                'name'     => $request->name,
                'bfi_id'   => $request->bfi_id,
                'email'    => $request->email,
                'password' => $password,
            ]);

            $userData = $user->toArray();
            $userData['password'] = $password;

            DB::table('role_user')->insert([
                'role_id' => $request->role_id,
                'user_id' => $user->id,
            ]);

            if($user){
                return new JsonResponse([
                    'status' => true,
                    'message'=> 'The employer create successfully!!',
                    'data'   => $userData ,
                ]);
            }else{
                return new JsonResponse([
                    'status' => false,
                    'message'=> 'Something went wrong!!',
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listRole()
    {
        try {
           $roles = Role::get();
           if (isset($roles)) {
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'Get all role of user successfully!!',
                    'data'      => RoleResource::collection($roles),
                ],200);
           }else{
            return new JsonResponse([
                'status'    => false,
                'message'   => 'Something went wrong!!',
            ],400);
           }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
