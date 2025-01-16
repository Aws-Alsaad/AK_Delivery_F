<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Mail\admin_mail\SuperClientPassword;
use App\Models\Client;
use App\Models\Order;
use App\Models\Store;
use App\Models\SuperClient;
use App\Notifications\super_client_notification\ChangeOrderStatus;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

class ClientController extends Controller
{
    use ProjectTraits;
    public function deleteClient(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse($validator->errors(),null,400);
        }
        $client=Client::where('id',$request->id)->first();
        $client->delete();
        return $this->apiResponse('client deleted successfully',null,200);
    }
    public function getClients(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $clients=Client::all();
        if(!$clients){
            return $this->apiResponse('clients not found',null,404);
        }
        return $this->apiResponse('clients retrieved successfully',$clients,200);
    }
    public function getClient(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse($validator->errors(),null,400);
        }
        $client=Client::where('id',$request->id)->first();
        return $this->apiResponse('client retrieved successfully',$client,200);
    }
    public function clientSearch(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'client_name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $client=Client::where('name','like','%'.$request->client_name.'%')->get();
        $count=count($client);
        if($count==0){
            return $this->apiResponse('there are no results',null,'404');
        }
        return $this->apiResponse('ok',$client,200);
    }

}
