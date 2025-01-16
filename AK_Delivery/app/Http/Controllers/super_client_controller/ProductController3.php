<?php

namespace App\Http\Controllers\super_client_controller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SuperClient;
use App\Notifications\AddProfilePhoto;
use App\Notifications\super_client_notification\AddProductNotification;
use App\Notifications\super_client_notification\AddProductPhotoNotification;
use App\Notifications\super_client_notification\ChangeProductDateNotification;
use App\Notifications\super_client_notification\ChangeProductNameNotification;
use App\Notifications\super_client_notification\ChangeProductPriceNotification;
use App\Notifications\super_client_notification\ChangeProductQuantityNotification;
use App\Notifications\super_client_notification\DeleteProductNotification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController3 extends Controller
{
    use ProjectTraits;

    public function addProduct(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate = Validator::make($request->all(),[
            'name'=>'required',
            'price'=>'required',
            'quantity'=>'required',
            'product_date'=>'required|date',
            'end_date'=>'required|date',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::create([
            'store_id'=>$super->store_id,
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
        $product->product_image_path = $path;
        $product->save();
        $product_name=$product->name;
        $superClient=SuperClient::where('store_id',$product->store_id)->first();
        $product2=Product::where('id',$product->id)->with('store')->get();
        Notification::send($superClient,new AddProductNotification($product_name));
        return $this->apiResponse('the product is added',$product2,200);
    }
    public function deleteProduct(Request $request){
        if($this->getSuperClientId()==null){
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
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $products=Product::where('store_id',$super->store_id)->first();
        if(!$products){
            return $this->apiResponse('product not found',null,404);
        }
        return $this->apiResponse('products get successfully',$products,200);
    }
    public function getProduct(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::where('store_id',$super->store_id)->where('id',$request->id)->first();
        return $this->apiResponse('product get successfully',$product,200);
    }
    public function productSearch(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'product_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $products=Product::where('store_id',$super->store_id)->where('name','like','%'.$request->product_name.'%')->get();
        if($products->isEmpty()){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$products,200);
    }
    public function addProductPhoto(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'file_path'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $path=$this->uploadFiles($request,'product_Photos');
        $product=Product::where('id',$request->id)->first();
        $product->update([
            'product_image_path'=>$path
        ]);
        Notification::send($superClient,new AddProductPhotoNotification($product->name));
        return $this->apiResponse('ok',$product->product_image_path,200);
    }
    public function changeProductName(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'new_name'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::where('id',$request->id)->first();
        $old_name=$product->name;
        $product->update([
            'name'=>$request->new_name
        ]);
        $new_name=$product->name;
        Notification::send($super,new ChangeProductNameNotification($old_name,$new_name));
        return $this->apiResponse('product name change successfully',$product,200);
    }
    public function changeProductPrice(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'price'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::where('id',$request->id)->first();
        $old_price=$product->price;
        $product->update([
            'price'=>$request->price
        ]);
        $new_price=$product->price;
        Notification::send($super,new ChangeProductPriceNotification($old_price,$new_price,$product->name));
        return $this->apiResponse('product price change successfully',$product,200);
    }
    public function changeProductQuantity(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'quantity'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::where('id',$request->id)->first();
        $old_quantity=$product->quantity;
        $product->update([
            'quantity'=>$request->quantity,
        ]);
        $new_quantity=$product->quantity;
        $product_name=$product->name;
        Notification::send($super,new ChangeProductQuantityNotification($old_quantity,$new_quantity,$product_name));
        return $this->apiResponse('product quantity change successfully',$product,200);
    }
    public function changeProductDate(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'product_date'=>'required|date',
            'end_date'=>'required|date',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $super=SuperClient::where('id',$this->getSuperClientId())->first();
        $product=Product::where('id',$request->id)->first();
        $product->update([
            'product_date'=>$request->product_date,
            'end_date'=>$request->end_date,
        ]);
        Notification::send($super,new ChangeProductDateNotification($product->name));
        return $this->apiResponse('product date change successfully',$product,200);
    }

}
