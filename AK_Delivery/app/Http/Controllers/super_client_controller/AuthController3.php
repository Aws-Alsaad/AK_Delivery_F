<?php

namespace App\Http\Controllers\super_client_controller;

use App\Http\Controllers\Controller;
use App\Mail\admin_mail\ForgotPasswordMail2;
use App\Mail\client_mail\ForgotPasswordMail;
use App\Models\FogrotPassword;
use App\Models\SuperClient;
use App\Models\SuperClientCs;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController3 extends Controller
{
    use ProjectTraits;

    public function login(Request $request){
        $validate=Validator::make($request->all(),[
            'email'=>'required|email|exists:super_clients',
            'password'=>'required|string|min:6',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClient=SuperClient::where('email',$request->email)->first();
        if(!$superClient || !Hash::check($request->password,$superClient->password)){
            return $this->apiResponse('the superClient profile is not found or the password is not correct',400);
        }
        $token=$superClient->createToken($superClient->name);
        return response()->json(['message'=>'ok','data'=>$superClient,'token'=>$token->plainTextToken,'status'=>200]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->apiResponse('the superClient profile is successfully logged out',null,200);
    }

    public function forgotPassword1(Request $request){
        $vaildate=Validator::make($request->all(),[
            'email'=>'required|email|exists:super_clients',
        ]);
        if($vaildate->fails()){
            return $this->apiResponse($vaildate->errors(),null,400);
        }
        $superClient=SuperClient::where('email',$request->email)->first();
        if(!$superClient){
            return $this->apiResponse('your email is not correct',null,404);
        }
        $code=$this->makeRandom();
        $for=FogrotPassword::where('email',$superClient->email)->first();
        if(!$for){
            FogrotPassword::create([
                'email'=>$superClient->email,
                'code'=>$code,
            ]);
        }
        else{
            $for->update([
                'code'=>$code,
            ]);
        }
        Mail::to($superClient->email)->send(new ForgotPasswordMail($code));
        return $this->apiResponse('email sent',null,200);
    }
    public function forgotPassword2(Request $request){
        $vaildate=Validator::make($request->all(),[
            'code'=>'required|string|min:10|max:10|',
        ]);
        if($vaildate->fails()){
            return $this->apiResponse($vaildate->errors(),null,400);
        }
        $for=FogrotPassword::where('code',$request->code)->first();
        if(!$for){
            return $this->apiResponse('your code is not correct',null,404);
        }
        $superClient=SuperClient::where('email',$for->email)->first();
        $token=$superClient->createToken($superClient->name);
        return response()->json(['message'=>'ok','data'=>$superClient,'token'=>$token->plainTextToken],200);
    }
    public function passwordChange(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_password'=>'required|string|min:6',
            'confirm_password'=>'required|string|min:6|same:new_password'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClient=SuperClient::where('id',$this->getSuperClientId())->first();
        $superClient->update([
            'password'=>Hash::make($request->new_password),
        ]);
        $for=FogrotPassword::where('email',$superClient->email)->first();
        $for->delete();
        return $this->apiResponse('ok',$superClient,200);
    }
}
