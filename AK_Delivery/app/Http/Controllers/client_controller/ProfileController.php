<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Client;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ProjectTraits;
    public function passwordChanging(Request $request){
        if($this->getClientId()==null){
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
        $client=Client::where('id',$this->getClientId())->first();
        if(!Hash::check($request->get('current_password'),$client->password)){
            return $this->apiResponse('the current password is wrong',null,400);
        }
        $client->update([
            'password'=>Hash::make($request->new_password),
        ]);
        return $this->apiResponse('ok',$client,200);
    }
    public function addProfilePhoto(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'file_path'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $path=$this->uploadFiles($request,'client_profilePhotos');
        $client=Client::where('id',$this->getClientId())->first();
        $client->update([
            'profile_photo_path'=>Storage::path($path)
        ]);
        return $this->apiResponse('ok',$client->profile_photo_path,200);
    }
    public function addAddress(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'lat'=>'required',
            'lon'=>'required',
            'display_name'=>'required',
            'state'=>'required',
            'city'=>'required',
            'road'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $address=Address::create([
            'lat'=>request('lat'),
            'lon'=>request('lon'),
            'display_name'=>request('display_name'),
            'state'=>request('state'),
            'city'=>request('city'),
            'road'=>request('road'),
        ]);
        $client=Client::where('id',$this->getClientId())->first();
        $client->update([
            'address_id'=>$address->id
        ]);
        $client_address=Client::where('id',$client->id)->with('address')->first();
        return $this->apiResponse('ok',$client_address,200);
    }
    public function nameChanging(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_name'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $client=Client::where('id',$this->getClientId())->first();
        $client->update([
            'name'=>$request->new_name
        ]);
        return $this->apiResponse('ok',$client,200);
    }
    public function deleteAccount(){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $client=Client::where('id',$this->getClientId())->first();
        $client->delete();
        return $this->apiResponse('the account is delete',null,200);
    }
}
