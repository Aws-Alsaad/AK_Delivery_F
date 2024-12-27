<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ProjectTraits;
    public function register(Request $request){
        $validate=Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_number'=>'required|min:10|max:10',
            'password'=>'required|string|min:6',
            'code_number'=>'required|min:4|max:4',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }

        $client=Client::create([
            'name'=>request('first_name') . ' ' . request('last_name'),
            'phone_number'=>request('phone_number'),
            'password'=>Hash::make(request('password')),
            'code_number'=>Hash::make(request('code_number')),
        ]);
        $local_path='D:\Programming_languages_2025\images\cuties_3.jpeg';
        $fileContents = file_get_contents($local_path);
        $filename=basename($local_path);
        $path = Storage::disk('public')->putFileAs('default_profilePhoto2' , new \Illuminate\Http\File($local_path), $filename);
        $client->profile_photo_path = $path;
        $client->save();

        $token=$client->createToken($client->name);

        return response()->json(['message'=>'ok','data'=>$client,'token'=>$token->plainTextToken,'status'=>200]);
    }

    public function login(Request $request){
        $validate=Validator::make($request->all(),[
            'phone_number'=>'required|min:10|max:10',
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
}
