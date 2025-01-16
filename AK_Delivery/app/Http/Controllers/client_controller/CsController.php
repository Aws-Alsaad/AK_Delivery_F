<?php

namespace App\Http\Controllers\client_controller;

use App\Http\Controllers\Controller;
use App\Models\ClientCs;
use App\ProjectTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CsController extends Controller
{
    use ProjectTraits;

    public function addCS(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $validate=Validator::make($request->all(),[
            'text'=>'required',
        ]);
        if($validate->fails()){
            return $this->apiResponse($validate->errors(),null,400);
        }
        $clientCS=ClientCs::create([
            'client_id'=>$this->getClientId(),
            'text'=>$request->text,
        ]);
        $clientCs2=ClientCs::where('id',$clientCS->id)->with('client')->get();
        return $this->apiResponse('your CS is added',$clientCs2,200);
    }
}
