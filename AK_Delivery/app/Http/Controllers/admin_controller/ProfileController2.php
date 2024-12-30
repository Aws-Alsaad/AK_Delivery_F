<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController2 extends Controller
{
    use ProjectTraits;
    public function passwordChanging(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'current_password'=>'required|string|min:6',
            'new_password'=>'required|string|min:6',
            'confirm_password'=>'required|string|min:6|same:new_password'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $admin=User::where('id',$this->getAdminId())->first();
        if(!Hash::check($request->get('current_password'),$admin->password)){
            return $this->apiResponse('the current password is wrong',null,400);
        }
        $admin->update([
            'password'=>Hash::make($request->new_password),
        ]);
        return $this->apiResponse('ok',$admin,200);
    }
    public function nameChanging(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_name'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $admin=User::where('id',$this->getAdminId())->first();
        $admin->update([
            'name'=>$request->new_name
        ]);
        return $this->apiResponse('ok',$admin,200);
    }
    public function deleteAccount(){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $admin=User::where('id',$this->getClientId())->first();
        $admin->delete();
        return $this->apiResponse('the account is delete',null,200);
    }
}
