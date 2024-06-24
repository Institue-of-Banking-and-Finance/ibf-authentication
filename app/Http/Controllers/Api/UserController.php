<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UerController extends Controller
{
    public function creatUser(Request $request)
    {
        try {
            $request-> validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|string|min:8|confirmed',
                'bfi_id'    => 'required|exists:bfis,id',
                'role_id'   => 'required|roles,id'
            ]);

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
            ]);

            $user->bfis()->sync($request->bfi_id);
            $user->roles()->sync($request->role_id);

            return new JsonResponse([
                'status'    => true,
                'message'   => 'User has been created successfully!!'
            ]);

        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
