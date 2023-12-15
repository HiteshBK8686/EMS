<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\RolePermissionResource;

class RoleController extends BaseController
{
    //
    public function all_permission(){
        try {
            $get_permission = Permission::all();
           // dd($get_permission);
            if(count($get_permission) > 0){
                return $get_permission;
            }else{
                return response()->json([
                    'errors' => ['Invalid Permission Request'],
                ], 200);
            }
        }
        catch (\Throwable $e) {
           // dd($e);
            return response()->json([
                'errors' => ['Invalid Permission Request Kindly Check it Again'],
            ], 200);
        }
        
    }
    public function getRolePermission(){
       
        try {
            $role_permissions = Role::with('permissions')->get();
            
           // dd($role_permissions);
            if(count($role_permissions) > 0){
                return RolePermissionResource::collection($role_permissions);
            }else{
                return response()->json([
                    'errors' => ['Invalid Permission Request'],
                ], 200);
            }
        }
        catch (\Throwable $e) {
           // dd($e);
            return response()->json([
                'errors' => ['Invalid Permission Request Kindly Check it Again'],
            ], 200);
        }
    }
    public function selectedRolePermission($id){
       
        try {
            $selected_role_permissions = Role::with('permissions')->where('id',$id)->first();
            return $selected_role_permissions;
            // return $roledatas= RolePermissionResource::collection($selected_role_permissions);
            // dd($roledatas);
            //  if($selected_role_permissions){
                
               
            // }else{
            //     return response()->json([
            //         'errors' => ['Invalid Permission Request'],
            //     ], 200);
            // }

        }catch (\Throwable $e) {
            //dd($e);
             return response()->json([
                 'errors' => ['Invalid Permission Request Kindly Check it Again'],
             ], 200);
         }
    }
    public function rolePermissionInsert(Request $request)
    {
       // dd($request->all());

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ],[
            'name.required' => 'Role field is required.',
            'name.unique' => 'Role Name Already Exist. kindly another one..',
            'permission.required' => 'Permission is required.',
        ]);

       // dd($request->input('rolePermission'));
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        if ($role) {
            return response()->json([
                'success'=>['true'],
            ], 200);
        }

    }
    public function rolePermissionUpdate(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ],[
                'name.required' => 'Role field is required.',
                'name.unique' => 'Role Name Already Exist. kindly another one..',
                'permission.required' => 'Permission is required.',
            ]);
            $role = Role::updateOrCreate(['id' => $request->input('id')],['name'=>$request->input('name')]);
            $role->syncPermissions($request->input('permission'));
            if ($role) {
                return response()->json([
                    'success'=>['true'],
                ], 200);
            }  
        }catch (\Throwable $e) {
            //dd($e);
             return response()->json([
                 'errors' => ['Invalid Role Permission Update Request'],
             ], 200);
         }    

    }

    public function rolePermissionDelete(Request $request){
        try{
            //dd($request->id);
            $this->validate($request, [
                'id' => 'required', 
            ],[
                'id.required' => 'Role ID field is required.',
            ]); 
            $role_permission=Role::find($request->id);
            $role_permission->delete(); //returns true/false
            $role_permission->syncPermissions($request->input('permission'));
            // $role = Role::updateOrCreate(['id' => $request->input('id')],['name'=>$request->input('name')]);
            // $role->syncPermissions($request->input('permission'));
            if ($role_permission) {
                return response()->json([
                    'success'=>['true'],
                ], 200);
            }
            else{
                return response()->json([
                    'errors' => ['Invalid Role Permission Delete Request by User'],
                ], 200);
            }
        }catch (\Throwable $e){
            return response()->json([
                'errors' => ['Invalid Role Permission Delete Request'],
            ], 200);
        }
    }
}
