<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Store;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    use ProjectTraits;
    public function addFavorite(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $favorite=Favorite::where('client_id',$this->getClientId())->where('product_id',$request->id)->first();
        if($favorite){
            return $this->apiResponse('the product is already exist in favorite',null,400);
        }
        $product=Product::where('id',$request->id)->first();
        $store=Store::where('id',$product->store_id)->first();
        $client=Client::where('id',$this->getClientId())->first();
        $favorite2=Favorite::create([
            'client_id'=>$client->id,
            'product_id'=>$product->id,
            'store_id'=>$store->id,
        ]);
        $favorite3=Favorite::where('id',$favorite2->id)->with('store')->with('product')->get();
        return $this->apiResponse('ok',$favorite3,200);
    }
    public function deleteFavorite(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::where('id',$request->id)->first();
        $favorite=Favorite::where('client_id',$this->getClientId())->where('product_id',$product->id)->first();
        $favorite->delete();
        return $this->apiResponse('the product is deleted from favorite',null,200);
    }
    public function getFavorite(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $favorite=Favorite::where('id',$this->getClientId())->with('store')->with('product')->get();
        return $this->apiResponse('ok',$favorite,200);
    }
}
