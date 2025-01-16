<?php

namespace App\Http\Controllers\super_client_controller;

use App\Http\Controllers\Controller;
use App\Models\SuperClientCs;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CsController2 extends Controller
{
    use ProjectTraits;
    public function addCS(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'text'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $superClientCS=SuperClientCs::create([
            'super_client_id'=>$this->getSuperClientId(),
            'text'=>$request->text,
        ]);
        $superClientCS2=SuperClientCs::where('id',$superClientCS->id)->with('superClient')->get();
        return $this->apiResponse('your CS is added',$superClientCS2,200);
    }
}
