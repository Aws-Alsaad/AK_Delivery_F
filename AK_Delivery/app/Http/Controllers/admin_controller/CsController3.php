<?php

namespace App\Http\Controllers\admin_controller;

use App\Http\Controllers\Controller;
use App\Models\ClientCs;
use App\Models\SuperClientCs;
use App\ProjectTraits;
use Illuminate\Http\Request;

class CsController3 extends Controller
{
    use ProjectTraits;

    public function getClientCS(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $clientCS=ClientCs::with('client')->get();
        return $this->apiResponse('ok',$clientCS,200);
    }
    public function getSuperClientCS(Request $request){
        if($this->getAdminId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $SuperClientCS=SuperClientCs::with('superClient')->get();
        return $this->apiResponse('ok',$SuperClientCS,200);
    }
}
