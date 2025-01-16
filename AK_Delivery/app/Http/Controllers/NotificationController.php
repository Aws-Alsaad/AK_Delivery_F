<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Notification;
use App\ProjectTraits;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class NotificationController extends Controller
{
    use ProjectTraits;
    public function getClientNotification(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $notification=Notification::where('notifiable_type','App\Models\Client')
            ->where('notifiable_id',$this->getClientId())->get();
        if(!$notification){
            return $this->apiResponse('no notification',null,404);
        }
        foreach ($notification as $notification2){
            $notification2->update(['read'=>true]);
        }
        $notification3=Notification::where('notifiable_type','App\Models\Client')
            ->where('notifiable_id',$this->getClientId())->get();
        return $this->apiResponse('ok',$notification3,200);
    }
    public function getNotReadNotification1(Request $request){
        if($this->getClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $notification=Notification::where('notifiable_type','App\Models\Client')
            ->where('notifiable_id',$this->getClientId())
            ->where('read',false)->get();
        $read=0;
        if(!$notification){
            return $this->apiResponse('ok',$read,404);
        }
        $count=Count($notification);
        return $this->apiResponse('ok',$count,200);
    }
    public function getSuperClientNotification(Request $request){
        if($this->getSuperclientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $notification=Notification::where('notifiable_type','App\Models\SuperClient')
            ->where('notifiable_id',$this->getSuperclientId())->get();
        if(!$notification){
            return $this->apiResponse('no notification',null,404);
        }
        foreach ($notification as $notification2){
            $notification2->update(['read'=>true]);
        }
        $notification3=Notification::where('notifiable_type','App\Models\SuperClient')
            ->where('notifiable_id',$this->getSuperclientId())->get();
        return $this->apiResponse('ok',$notification3,200);
    }
    public function getNotReadNotification2(Request $request){
        if($this->getSuperClientId()==null){
            return $this->apiResponse('not authenticated',null,401);
        }
        $notification=Notification::where('notifiable_type','App\Models\SuperClient')
            ->where('notifiable_id',$this->getSuperclientId())
            ->where('read',false)->get();
        if(!$notification){
            return $this->apiResponse('no notification',null,404);
        }
        $count=Count($notification);
        return $this->apiResponse('ok',$count,200);
    }
}
