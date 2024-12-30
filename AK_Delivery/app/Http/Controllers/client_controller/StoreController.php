<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    use ProjectTraits;
    public function getStores(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $stores=Store::with('address')->get();
        return $this->apiResponse('ok',$stores,200);
    }
    public function getStore(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'id'=>'required'
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $store=Store::where('id',$request->id)->with('products')->with('phoneNumbers')->first();
        return $this->apiResponse('ok',$store,200);
    }
}
