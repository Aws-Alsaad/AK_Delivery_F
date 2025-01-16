<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Mail\client_mail\ForgotPasswordMail;
use App\Models\Client;
use App\Models\FogrotPassword;
use App\Notifications\ChangePasswordNotification;
use App\Notifications\client_notification\RegisterNotification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;

class AuthController extends Controller
{
    use ProjectTraits;
    public function register(Request $request){
        $validate=Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10|unique:clients',
            'password'=>'required|string|min:6',
            'email'=>'required|email|unique:clients',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $client=Client::create([
            'name'=>request('first_name') . ' ' . request('last_name'),
            'phone_number'=>request('phone_number'),
            'password'=>Hash::make(request('password')),
            'email'=>request('email'),
        ]);
        $local_path='D:\Programming_languages_2025\images\client\images.png';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public2')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $sh='\\';
        $fullPath=str_replace('/',$sh,public_path("app\public\\".$path),);
        $fullPath2=str_replace('\\\\',$sh,$fullPath);
        $client->profile_photo_path = $fullPath2;
        $client->save();
        $token=$client->createToken($client->name);
        Notification::send($client,new RegisterNotification());
        return response()->json(['message'=>'ok','data'=>$client,'token'=>$token->plainTextToken,'status'=>200]);
    }
    public function login(Request $request){
        $validate=Validator::make($request->all(),[
            'phone_number'=>'required|regex:/^09[0-9]{8}$/|min:10|max:10|exists:clients',
            'password'=>'required|string|min:6',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $client=Client::where('phone_number',$request->phone_number)->first();
        if(!$client || !Hash::check($request->password,$client->password)){
            return $this->apiResponse('the client profile is not found or the password is not correct',null,400);
        }
        $token=$client->createToken($client->name);
        return response()->json(['message'=>'ok','data'=>$client,'token'=>$token->plainTextToken,'status'=>200]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->apiResponse('the student profile is successfully logged out',null,200);
    }
    public function forgotPassword1(Request $request){
        $vaildate=Validator::make($request->all(),[
            'email'=>'required|email|exists:clients',
        ]);
        if($vaildate->fails()){
            return $this->apiResponse($vaildate->errors(),null,400);
        }
        $client=Client::where('email',$request->email)->first();
        if(!$client){
            return $this->apiResponse('your email is not correct',null,404);
        }
        $code=$this->makeRandom();
        $for=FogrotPassword::where('email',$client->email)->first();
        if(!$for){
            FogrotPassword::create([
                'email'=>$client->email,
                'code'=>$code,
            ]);
        }
        else{
            $for->update([
                'code'=>$code,
            ]);
        }
        Mail::to($client->email)->send(new ForgotPasswordMail($code));
        return $this->apiResponse('email sent',null,200);
    }
    public function forgotPassword2(Request $request){
        $vaildate=Validator::make($request->all(),[
           'code'=>'required|string|min:10|max:10',
        ]);
        if($vaildate->fails()){
            return $this->apiResponse($vaildate->errors(),null,400);
        }
        $for=FogrotPassword::where('code',$request->code)->first();
        if(!$for){
            return $this->apiResponse('your code is not correct',null,404);
        }
        $client=Client::where('email',$for->email)->first();
        $token=$client->createToken($client->name);
        return response()->json(['message'=>'ok','data'=>$client,'token'=>$token->plainTextToken],200);
    }
    public function passwordChange(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_password'=>'required|string|min:6',
            'confirm_password'=>'required|string|min:6|same:new_password'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $client=Client::where('id',$this->getClientId())->first();
        $client->update([
            'password'=>Hash::make($request->new_password),
        ]);

        $for=FogrotPassword::where('email',$client->email)->first();
        $for->delete();
        Notification::send($client,new ChangePasswordNotification());
        return $this->apiResponse('ok',$client,200);
    }
}
