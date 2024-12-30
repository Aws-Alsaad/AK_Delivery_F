<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Store;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    use ProjectTraits;
    public function addStore(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'lat'=>'required',
            'lon'=>'required',
            'display_name'=>'required',
            'state'=>'required',
            'city'=>'required',
            'road'=>'required',
            'name'=>'required',
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
        $store=Store::create([
            'address_id'=>$address->id,
            'name'=>request('name'),
        ]);
        $local_path='D:\Programming_languages_2025\images\store\Storefront_A2_Icon-300x300.jpg';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $store->profile_photo_path = $path;
        $store->save();

    }

    public function Register(Request $request){

    }
}
