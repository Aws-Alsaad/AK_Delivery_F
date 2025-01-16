<?php

namespace App\Http\Controllers\super_client_controller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\PhoneNumber;
use App\Models\Store;
use App\Models\StoreEmail;
use App\Models\SuperClient;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController3 extends Controller
{
    use ProjectTraits;
    public function changeAddressStore(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'state'=>'required',
            'city'=>'required',
            'area'=>'required',
            'street'=>'required'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $store=Store::where('id',$super->store_id)->first();
        $address=Address::where('id',$store->address_id)->first();
        $address->update([
            'state'=>request('state'),
            'city'=>request('city'),
            'town'=>request('town'),
            'area'=>request('area'),
            'street'=>request('street'),
            'notes'=>request('notes'),
            'display_name'=>request('state') . ' , ' . request('town') . ' , ' . request('town') . ' , ' .
                request('area') . ' , ' . request('street') . ' , ' . request('notes'),
        ]);
        $store2=Store::where('id',$store->id)->with('address')->get();
        return $this->apiResponse('store added successfully',$store2,200);
    }
    public function addStorePhoneNumber(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10|',
            'type'=>['required','in:store_number,complaints'],
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $phoneNumber=PhoneNumber::create([
            'store_id'=>$superClient->store_id,
            'type'=>$request->type,
            'phone_number'=>$request->phone_number,
        ]);
        $store=Store::where('id',$phoneNumber->store_id)->with('phoneNumbers')->get();
        return $this->apiResponse('store phone number added successfully',$store,200);
    }
    public function deleteStorePhoneNumber(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $phone=PhoneNumber::where('id',$request->id)->first();
        $phone->delete();
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $phone2=PhoneNumber::where('store_id',$super->store_id)->get();
        return $this->apiResponse('phone number deleted successfully',$phone2,200);
    }
    public function changeStorePhoneNumber(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10|',
            'type'=>['required','in:store_number,complaints'],
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $phoneNumber=PhoneNumber::where('id',$request->id)->first();
        $phoneNumber->update([
            'type'=>$request->type,
            'phone_number'=>$request->phone_number,
        ]);
        $phone=PhoneNumber::where('store_id',$phoneNumber->store_id)->get();
        return $this->apiResponse('store phone number changed successfully',$phone,200);
    }
    public function addStorelink(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'link'=>'required',
            'type'=>['required','in:gmail,telegram,facebook,instagram,whatsapp'],
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $link=StoreEmail::create([
            'store_id'=>$super->store_id,
            'type'=>$request->type,
            'link'=>$request->link,
        ]);
        $link2=StoreEmail::where('store_id',$link->store_id)->get();
        return $this->apiResponse('store link added successfully',$link2,200);
    }
    public function deleteStorelink(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $link=StoreEmail::where('id',$request->id)->first();
        $link->delete();
        $link2=StoreEmail::where('store_id',$link->store_id)->get();
        return $this->apiResponse('store link deleted successfully',$link2,200);
    }
    public function changeStorelink(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'link'=>'required',
            'type'=>['required','in:gmail,telegram,facebook,instagram,whatsapp'],
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $link=StoreEmail::where('id',$request->id)->first();
        $link->update([
            'type'=>$request->type,
            'link'=>$request->link,
        ]);
       $link2=StoreEmail::where('store_id',$link->store_id)->get();
        return $this->apiResponse('store link added successfully',$link2,200);
    }
    public function changeStoreName(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_name'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $store=Store::where('id',$super->store_id)->first();
        $store->update([
            'name'=>$request->new_name
        ]);
        return $this->apiResponse('store name change successfully',$store,200);
    }
    public function addStoreProfilePhoto(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'file_path'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $path=$this->uploadFiles($request,'store_profilePhotos');
        $store=Store::where('id',$super->store_id)->first();
        $store->update([
            'profile_photo_path'=>$path
        ]);
        return $this->apiResponse('store profile photo added successfully',$store,200);
    }
    public function getStore(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $store=Store::where('id',$super->store_id)->with('address')->with('phoneNumbers')->with('storeEmails')->get();
        return $this->apiResponse('ok',$store,200);
    }
}
