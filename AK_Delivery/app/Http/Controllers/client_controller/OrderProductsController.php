<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderProductsController extends Controller
{
    use ProjectTraits;
    public function addProductsOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'quantity'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::where('id',$request->id)->first();
        $product_quantity=$product->quantity;
        if($product_quantity<$request->quantity){
            return response()->json(['message1'=>'the quantity that you want it is bigger than the quantity of the product.',
                'message2'=>"the quantity of the product = $product_quantity",'data'=>null,'status'=>400]);
        }
        $product->update([
            'quantity'=>$product_quantity-$request->quantity
        ]);
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $store=Store::where('id',$product->store_id)->first();
        $store_name=$store->name;
        $price=($product->price)*($request->quantity);
        $order_product=OrderProduct::create([
            'order_id'=>$order->id,
            'product_id'=>$product->id,
            'store_name'=>$store_name,
            'product_name'=>$product->name,
            'quantity'=>$request->quantity,
            'the_price'=>$price,
        ]);
        $product_price=$order->products_price;
        $order->update(['products_price'=>$product_price+$price]);
        $order_product2=OrderProduct::where('id',$order_product->id)->with('order')->get();
        return $this->apiResponse('order added successfully',$order_product2,200);
    }

    public function changeQuantity(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'quantity'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $product=Product::where('id',$request->id)->first();
        $quantity=$product->quantity;
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $products_price=$order->products_price;
        $order_product=OrderProduct::where('order_id',$order->id)->where('product_id',$product->id)->first();
        $quantity2=$order_product->quantity+$quantity;
        if($quantity2<$request->quantity){
            return response()->json(['message1'=>'the quantity that you want it is bigger than the quantity of the product.',
                'message2'=>"the quantity of the product = $quantity",'data'=>null,'status'=>400]);
        }
        $product->update([
            'quantity'=>$quantity2-$request->quantity
        ]);
        $order->update([
            'products_price'=>$products_price-$order_product->the_price
        ]);
        $price=$product->price*$request->quantity;
        $order_product->update([
            'quantity'=>$request->quantity,
            'the_price'=>$price,
        ]);
        $order->update(['products_price'=>$products_price-$price]);
        $order2=Order::where('id',$order->id)->with('orderProducts')->get();
        return $this->apiResponse('order added successfully',$order2,200);
    }
    public function deleteOrderProduct(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order_product=OrderProduct::where('id',$request->id);
        $order_product->delete();
        return $this->apiResponse('order product deleted successfully',null,200);
    }
    public function getOrderProducts1(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $order_products=OrderProduct::where('order_id',$order->id)->get();
        return $this->apiResponse('ok',$order_products,200);
    }
    public function getOrderProducts2(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order_products=OrderProduct::where('order_id',$request->id)->get();
        return $this->apiResponse('ok',$order_products,200);
    }
}
