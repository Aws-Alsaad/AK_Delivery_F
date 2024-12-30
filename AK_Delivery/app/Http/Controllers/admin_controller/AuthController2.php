<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Mail\admin_mail\ForgotPasswordMail2;
use App\Models\FogrotPassword;
use App\Models\User;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController2 extends Controller
{
    use ProjectTraits;

    public function register(Request $request){
        $validate=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:6',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $admin=User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>Hash::make(request('password')),
        ]);
        $token=$admin->createToken($request->name);
        return response()->json(['message'=>'ok','data'=>$admin,'token'=>$token->plainTextToken,'status'=>200]);
    }
    public function login(Request $request){
        $validate=Validator::make($request->all(),[
            'email'=>'required|email|exists:users',
            'password'=>'required|string|min:6',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $admin=User::where('email',$request->email)->first();
        if(!$admin || !Hash::check($request->password,$admin->password)){
            return $this->apiResponse('the admin profile is not found or the password is not correct',null,400);
        }
        $token=$admin->createToken($admin->name);
        return response()->json(['message'=>'ok','data'=>$admin,'token'=>$token->plainTextToken,'status'=>200]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->apiResponse('the admin profile is successfully logged out',null,200);
    }
    public function forgotPassword1(Request $request){
        $vaildate=Validator::make($request->all(),[
            'email'=>'required|email|exists:users',
        ]);
        if($vaildate->fails()){
            return $this->apiResponse($vaildate->errors(),null,400);
        }
        $admin=User::where('email',$request->email)->first();
        if(!$admin){
            return $this->apiResponse('you are email is not correct',null,404);
        }
        $code=$this->makeRandom();
        $for=FogrotPassword::where('email',$admin->email)->first();
        if(!$for){
            FogrotPassword::create([
                'email'=>$admin->email,
                'code'=>$code,
            ]);
        }
        else{
            $for->update([
                'code'=>$code,
            ]);
        }
        Mail::to($admin->email)->send(new ForgotPasswordMail2($code));
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
        $client=User::where('email',$for->email)->first();
        $token=$client->createToken($client->name);
        return response()->json(['message'=>'ok','data'=>$client,'token'=>$token->plainTextToken],200);
    }
    public function passwordChange(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'new_password'=>'required|string|min:6',
            'confirm_password'=>'required|string|min:6|same:new_password'
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $admin=User::where('id',$this->getAdminId())->first();
        $admin->update([
            'password'=>Hash::make($request->new_password),
        ]);
        return $this->apiResponse('ok',$admin,200);
    }
}
