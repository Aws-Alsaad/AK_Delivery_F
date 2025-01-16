<?php

namespace App\Http\Controllers\super_client_controller;

use App\Http\Controllers\Controller;
use App\Models\SuperClient;
use App\Notifications\AddProfilePhoto;
use App\Notifications\changeNameNotification;
use App\Notifications\ChangePasswordNotification;
use App\ProjectTraits;
use Dotenv\Store\File\Paths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController3 extends Controller
{
    use ProjectTraits;
    public function passwordChanging(Request $request){
        if($this->getSuperClientId()==null){
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
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        if(!Hash::check($request->get('current_password'),$superClient->password)){
            return $this->apiResponse('the current password is wrong',null,400);
        }
        $superClient->update([
            'password'=>Hash::make($request->new_password),
        ]);
        Notification::send($superClient,new ChangePasswordNotification());
        return $this->apiResponse('ok',$superClient,200);
    }
    public function addProfilePhoto(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'file_path'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $path=$this->uploadFiles($request,'super_client_profilePhotos');
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $superClient->update([
            'profile_photo_path'=>$path,
        ]);
        Notification::send($superClient,new AddProfilePhoto());
        return $this->apiResponse('ok',$path,200);
    }

    public function nameChanging(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_name'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $superClient->update([
            'name'=>$request->new_name
        ]);
        Notification::send($superClient,new ChangeNameNotification());
        return $this->apiResponse('ok',$superClient,200);
    }
    public function deleteAccount(){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $superClient->delete();
        return $this->apiResponse('the account is delete',null,200);
    }
}
