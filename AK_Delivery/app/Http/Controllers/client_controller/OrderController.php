<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\client_notification\SendOrderNotification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ProjectTraits;
    public function addOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->get();
        if(!$order){
            return $this->apiResponse('order already exist',null,400);
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
        $address=Address::create([
            'state'=>request('state'),
            'city'=>request('city'),
            'town'=>request('town'),
            'area'=>request('area'),
            'street'=>request('street'),
            'notes'=>request('notes'),
            'display_name'=>request('state') . ' , ' . request('town') . ' , ' . request('town') . ' , ' .
            request('area') . ' , ' . request('street') . ' , ' . request('notes'),

        ]);
        $order2=Order::create([
            'client_id'=>$this->getClientId(),
            'address_id'=>$address->id,
        ]);
        $order2->where('client_id',$this->getClientId())->where('status','uncancelled')->get();
        $count=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->count();
        if($count>1){
            return $this->apiResponse('you must be do one order',null,400);
        }
        return $this->apiResponse('order added successfully',null,200);
    }
    public function phoneNumberOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $order->update(['phone_number'=>request('phone_number')]);
        return $this->apiResponse('order phoneNumber added successfully',null,200);
    }
    public function changeAddressOrder(Request $request){
        if($this->getClientId()==null){
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
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $address=Address::where('id',$order->address_id)->first();
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
        $order2=Order::where('id',$order->id)->with('orderProducts')->with('address')->get();
        return $this->apiResponse('address order changed successfully',$order2,200);
    }
    public function changePhoneNumberOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->first();
        $order->update(['phone_number'=>request('phone_number')]);
        $order2=Order::where('id',$order->id)->with('orderProducts')->get();
        return $this->apiResponse('order changed successfully',$order2,200);
    }
    public function deleteOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('id',$request->id)->first();
        $order->delete();
        return $this->apiResponse('order deleted successfully',null,200);
    }
    public function getOrder1(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $order=Order::where('client_id',$this->getClientId())->where('status','uncancelled')->with('orderProducts')->with('client')->get();
        return $this->apiResponse('order',$order,200);
    }
    public function getOrder2(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('id',$request->id)->with('orderProducts')->with('client')->get();
        return $this->apiResponse('order',$order,200);
    }
    public function getOrders(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $client=Client::where('id',$this->getClientId())->first();
        $orders=Order::where('client_id',$client->id)->with('orderProducts')->with('client')->get();
        return $this->apiResponse('ok',$orders,200);
    }
    public function sendOrder(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('id',$request->id)->first();
        $products_price=$order->products_price;
        $total_price=$order->total_price;
        $order->update([
            'total_price'=>$total_price+$products_price,
            'status'=>'sending',
        ]);
        $order2=Order::where('id',$order->id)->with('orderProducts')->with('client')->first();
        $client=Client::where('id',$this->getClientId())->first();
        $order_number=$order2->id;
        Notification::send($client,new SendOrderNotification($order_number));
        return $this->apiResponse('order sending',$order,200);
    }

}
