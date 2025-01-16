<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\SuperClient;
use App\Notifications\admin_notification\AddProductNotification;
use App\Notifications\admin_notification\DeleteProductNotification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery\Matcher\Not;

class productController extends Controller
{
    use ProjectTraits;

    public function addProduct(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate = Validator::make($request->all(),[
            'id'=>'required',
            'name'=>'required',
            'price'=>'required',
            'quantity'=>'required',
            'product_date'=>'required|date',
            'end_date'=>'required|date',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::create([
            'store_id'=>$request->id,
            'name'=>$request->name,
            'price'=>$request->price,
            'quantity'=>$request->quantity,
            'product_date'=>$request->product_date,
            'end_date'=>$request->end_date,
        ]);
        $local_path='D:\Programming_languages_2025\images\product\product.png';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public2')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $sh='\\';
        $fullPath=str_replace('/',$sh,public_path("app\public\\".$path),);
        $fullPath2=str_replace('\\\\',$sh,$fullPath);
        $product->product_image_path = $fullPath2;
        $product->save();
        $product_name=$product->name;
        $superClient=SuperClient::where('store_id',$product->store_id)->first();
        $product2=Product::where('id',$product->id)->with('store')->get();
        Notification::send($superClient,new AddProductNotification($product_name));
        return $this->apiResponse('the product is added',$product2,200);
    }
    public function deleteProduct(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate = Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::where('id',$request->id)->first();
        $superClient=SuperClient::where('store_id',$product->store_id)->first();
        $product_name=$product->name;
        Notification::send($superClient,new DeleteProductNotification($product_name));
        $product->delete();
        return $this->apiResponse('the product is deleted',null,200);
    }
    public function getProducts(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $products=Product::where('store_id',$request->id)->get();
        if(!$products){
            return $this->apiResponse('product not found',null,404);
        }
        return $this->apiResponse('products get successfully',$products,200);
    }
    public function getProduct(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::where('id',$request->id)->first();
        return $this->apiResponse('product get successfully',$product,200);
    }
    public function productSearch(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'id'=>'required',
            'product_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $products=Product::where('store_id',$request->id)->where('name','like','%'.$request->product_name.'%')->get();
        if($products->isEmpty()){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$products,200);
    }

}
