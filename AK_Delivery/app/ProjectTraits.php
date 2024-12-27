<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->storeAs($folderName,$folder,'public');
        return $path;
    }


    public function getUserId(){
        $userId=Auth::id();
        return $userId;
    }
}
