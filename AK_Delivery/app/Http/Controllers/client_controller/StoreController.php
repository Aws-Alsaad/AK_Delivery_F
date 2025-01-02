<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        $store=Store::where('id',$request->id)->with('storeEmails')->with('phoneNumbers')->first();
        return $this->apiResponse('ok',$store,200);
    }
    public function storeSearch(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'store_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $stores=Store::where('name','like','%'.$request->store_name.'%')->with('address')->get();
        if(!$stores){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$stores,200);
    }
    public function getProducts(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'id'=>'required'
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $store=Store::where('id',$request->id)->first();
        $products=Product::where('store_id',$store->id)->get();
        return $this->apiResponse('ok',$products,200);
    }
    public function getProduct(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'id'=>'required'
        ]);
       if($validator->fails()){
           return $this->apiResponse('error',$validator->errors(),400);
       }
       $product=Product::where('id',$request->id)->first();
       return $this->apiResponse('ok',$product,200);
    }
    public function productSearch1(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'product_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $products=Product::where('name','like','%'.$request->product_name.'%')->get();
        if($products->isEmpty()){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$products,200);
    }
    public function productSearch2(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'product_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $products=Product::where('name','like','%'.$request->product_name.'%')->with('store')->get();
        if(!$products){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$products,200);
    }


}
