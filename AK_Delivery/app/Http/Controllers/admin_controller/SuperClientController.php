<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Mail\admin_mail\SuperClientPassword;
use App\Models\SuperClient;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuperClientController extends Controller
{
    use ProjectTraits;

    public function addSuperClient(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:super_clients',

        ]);
        if($validator->fails()){
            return $this->apiResponse($validator->errors(),null,400);
        }
        $password=$this->makeRandom();
        $superClient=SuperClient::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>Hash::make($password),
        ]);
        $local_path='D:\Programming_languages_2025\images\super_client\images (1).png';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public2')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $sh='\\';
        $fullPath=str_replace('/',$sh,public_path("app\public\\".$path),);
        $fullPath2=str_replace('\\\\',$sh,$fullPath);
        $superClient->profile_photo_path = $fullPath2;
        $superClient->save();
        Mail::to($superClient->email)->send(new SuperClientPassword($password));
        return $this->apiResponse('SuperClient added successfully',null,200);
    }
    public function deleteSuperClient(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse($validator->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$request->id)->first();
        $superClient->delete();
        return $this->apiResponse('SuperClient deleted successfully',null,200);
    }
    public function getSuperClients(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $superClients=SuperClient::all();
        if(!$superClients){
            return $this->apiResponse('superClients not found',null,404);
        }
        $superClients2=SuperClient::with('store')->get();
        return $this->apiResponse('superClients retrieved successfully',$superClients2,200);
    }
    public function getSuperClient(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator=Validator::make($request->all(),[
            'id'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse($validator->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$request->id)->with('store')->get();
        return $this->apiResponse('superClient retrieved successfully',$superClient,200);
    }
    public function superClientSearch(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required',
        ]);
        if($validator->fails()){
            return $this->apiResponse('error',$validator->errors(),400);
        }
        $superClient=SuperClient::where('name','like','%'.$request->name.'%')->get();
        $count=count($superClient);
        if($count==0){
            return $this->apiResponse('there are no results',null,'400');
        }
        return $this->apiResponse('ok',$superClient,200);
    }

}
