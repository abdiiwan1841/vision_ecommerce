<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Roles and Permissions');
        
    }

    
    public function index(){
       $roles = Role::where('name','!=','Super Admin')->get();
       $permissions = Permission::all();
       return view('admin.roles.index',compact('roles','permissions'));
    }


    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|max:25|unique:roles',
            'permissions' => 'required',
        ]);

        $role =  Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        Toastr::success('New Role Created Succesfully');
        Toastr::success('Permission Assigned Succesfully');
        return redirect()->back();
     }

     public function edit($id){
         return Role::with('permissions')->findOrFail($id);
     }


     public function update(Request $request,$id){
        $this->validate($request,[
            'name' => 'required|max:25|unique:roles,name,'.$id,
        ]);

        $role =  Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();
        if($request->has('permissions')){
            $role->syncPermissions($request->permissions);
            Toastr::success('Permission Synced Succesfully');
        }
        Toastr::success('Role Updated Succesfully');
        return redirect()->back();
     }
}
