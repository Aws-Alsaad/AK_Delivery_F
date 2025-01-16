<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\PhoneNumber;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreEmail;
use App\Models\SuperClient;
use App\Notifications\admin_notification\AddProductNotification;
use App\Notifications\admin_notification\DeleteProductNotification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController2 extends Controller
{
    use ProjectTraits;

    public function addStore(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $superClient=SuperClient::where('store_id',null)->first();
        if(!$superClient){
            return $this->apiResponse('all of the super clients have a stores',null,404);
        }
        $validate=Validator::make($request->all(),[
            'name'=>'required',
            'state'=>'required',
            'city'=>'required',
            'area'=>'required',
            'street'=>'required'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $address=Address::create([
            'state'=>request('state'),
            'city'=>request('city'),
            'town'=>request('town'),
            'area'=>request('area'),
            'street'=>request('street'),
            'notes'=>request('notes'),
            'display_name'=>request('state') . ' , ' . request('city') . ' , ' . request('town') . ' , ' .
                request('area') . ' , ' . request('street') . ' , ' . request('notes'),
        ]);
        $store=Store::create([
            'address_id'=>$address->id,
            'name'=>$request->name,
        ]);
        $local_path='D:\Programming_languages_2025\images\store\Storefront_A2_Icon-300x300.jpg';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public2')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $store->profile_photo_path = Storage::path($path);
        $store->save();
        $superClient->update([
            'store_id'=>$store->id,
        ]);
        $store2=Store::where('id',$store->id)->with('address')->get();
        return $this->apiResponse('store added successfully',$store2,200);
    }
    public function deleteStore(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $store=Store::where('id',$request->id)->first();
        $store->delete();
        return $this->apiResponse('store deleted successfully',null,200);
    }

    public function getStores(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $stores=Store::all();
        if(!$stores){
            return $this->apiResponse('stores not found',null,404);
        }
        return $this->apiResponse('stores get successfully',$stores,200);
    }
    public function getStore(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $store=Store::where('id',$request->id)->with('phoneNumbers')->with('storeEmails')->with('address')->get();
        return $this->apiResponse('store get successfully',$store,200);
    }

    public function storeSearch(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'store_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $stores=Store::where('name','like','%'.$request->store_name.'%')->get();
        $count=Count($stores);
        if($count==0){
            return $this->apiResponse('store not found',null,404);
        }
        return $this->apiResponse('ok',$stores,200);
    }

}
