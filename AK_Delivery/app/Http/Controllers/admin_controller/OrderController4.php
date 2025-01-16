<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Notifications\super_client_notification\ChangeOrderStatus;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OrderController4 extends Controller
{
    use ProjectTraits;

    public function getOrders(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $orders=Order::where('status','sending')->with('orderProducts')->get();
        if(!$orders){
            return $this->apiResponse('orders not found',null,404);
        }
        return $this->apiResponse('success',$orders,200);
    }
    public function searchOrders(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $orders=Order::where('status','sending')->where('id',$request->id)->with('orderProducts')->get();
        if(!$orders){
            return $this->apiResponse('order not found',null,404);
        }
        return $this->apiResponse('success',$orders,200);
    }
    public function getOrder(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('status','sending')->where('id',$request->id)->with('orderProducts')->with('client')->first();
        return $this->apiResponse('success',$order,200);
    }
    public function changeOrderStatus(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'id'=>'required',
            'status'=>'required|in:cancelled',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $order=Order::where('status','sending')->where('id',$request->id)->first();
        $order->update([
            'status'=>$request->status,
        ]);
        $client=Client::where('id',$order->client_id)->first();
        Notification::send($client,new ChangeOrderStatus($order->id));
        return $this->apiResponse('order status is changed',$order,200);
    }

}
