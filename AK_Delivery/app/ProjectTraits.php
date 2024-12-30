<?php

namespace App;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Validator;

trait ProjectTraits
{
    public function apiResponse($message=null,$data=null,$status=null){
        $array=[
            'message'=>$message,
            'data'=>$data,
            'status'=>$status
        ];
        return response()->json($array);
    }
    public function uploadFiles(Request $request,$folderName){
        $folder = $request->file('file_path')->getClientOriginalName();
        $path = $request->file('file_path')
            ->storeAs($folderName,$folder,'public2');
        return $path;
    }
    public function getClientId(){
        $clientId=Auth::guard('client')->id();
        return $clientId;
    }
    public function getSuperClientId(){
        $superClientId=Auth::guard('superClient')->id();
        return $superClientId;
    }
    public function getAdminId(){
        $adminId=Auth::guard('user')->id();
        return $adminId;
    }
    public function makeRandom(){
        $code=Random::generate();
        return $code;
    }

}
