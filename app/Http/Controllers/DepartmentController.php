<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
   public function createDepartment(Request $request)
   {
        try {
            $department = Department::create([
                'name'      => $request->name,
                'is_active' => $request->is_active,
            ]);
            if(isset($department)){
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'Create department successfully!!',
                    'data'      => new DepartmentResource($department)
                ]);
            }else{
                return new JsonResponse([
                    'status'    => false,
                    'message'   => 'Something went wrong!!'
                ]);
            }
        } catch (\Exception $e) {
        return $e->getMessage();
        }
   }

   public function listAllDepartment()
   {
        try {
            $departments = Department::get();
            if(isset($departments)){
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'List all departments 0!!',
                    'data'      => DepartmentResource::collection($departments)
                ]);
            }else{
                return new JsonResponse([
                    'status'   => false,
                    'message'  => 'Something went wrong!!'
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
   }

   public function updateDepartment(Request $request , $id)
   {
        try {
            $department = Department::findOrFail($id);
            if(isset($department)){
                $department->update([
                    'name'      => $request->name,
                    'is_active' => $request->is_active

                ]);
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'Department update successfully !!',
                    'data'      => new DepartmentResource($department)
                ]);
            }else{
                return new JsonResponse([
                    'status'    => true,
                    'message'   => 'Department not found!!'
                ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
   }

}
