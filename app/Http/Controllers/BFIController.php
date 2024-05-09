<?php

namespace App\Http\Controllers;

use App\Http\Resources\BfiResource;
use App\Models\BFI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BFIController extends Controller
{

    public function create(Request $request)
    {
        try {
            $bfi = BFI::create([
                'name' => $request->name,
                'type' => $request->type,
            ]);
            if(isset($bfi)){
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'The bfi create successfully !!',
                    'data'      => new BfiResource($bfi),
                ],200);
            }else{
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'Something went wrong!!',
                ],400);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function listBfi()
    {
        try {
            $bfis = BFI::get();
            if (isset($bfis)) {
                return new JsonResponse([
                    'status'  => true,
                    'message' => 'Get all bfi successfully!!',
                    'data'    => BfiResource::collection($bfis)
                ]);
            }else{
                return new JsonResponse([
                    'status'    => false,
                    'message'   => 'The bfi not found!!'
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request,$id)
    {
        try {
            $bfi = BFI::findOrFail($id);
            if(isset($bfi)){
                $bfi->update([
                    $bfi->name = $request->name,
                    $bfi->type = $request->type,
                ]);
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'The bfi update successfully!!',
                ],200);
            }else{
                return new JsonResponse([
                    'status'    => false,
                    'message'   => 'The bfi not found!!'
                ],400);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
