<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect, Response, Session;
use DataTables;
use Illuminate\Support\Facades\Auth;


class NotificationsController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function notifications(Request $request){
        $auth_id=Auth::id();
        if ($request->ajax()) {
            $data = DB::table('notifications')->where('notifications.deleted', 'No')->where('notification_to', $auth_id)->select(['notifications.notification_id','notifications.title','notifications.description','notifications.url','notifications.notification_viewed','notifications.created_at','notifications.view_at']);
            return Datatables::of($data)
            ->addIndexColumn()
           ->make(true);
        }
        return view('notifications.notifications');
    }

    public function notification_viewed(Request $request)
    {
        $user_id = Auth::id();
        $data = array('notification_viewed' => 'Yes', 'updated_by' => $user_id, 'view_at' => Now());
        $update=DB::table('notifications')->where('notification_id',$request->notification_id)->update($data);
    }

    public function notification_all_read(Request $request)
    {
        $user_id = Auth::id();
        $data = array('notification_viewed' => 'Yes', 'updated_by' => $user_id, 'view_at' => Now());
        $update=DB::table('notifications')->where('notification_to',$request->auth_id)->update($data);
    }

    public function get_notifications_popup(Request $request)
    {   
        $session_user_id=Auth::id();
        if(isset($session_user_id))
        {
            $user_id = Auth::id();
            $get_notifications=DB::table('notifications')->where('notification_viewed','No')->where('notification_to',$user_id)->limit(1);
            if(mysqli_num_rows($get_notifications)>0)
            {
                $notifications_arr=array();
                foreach($get_notifications as $get_notification)
                {
                    $notification_id=$get_notification->notification_id;
                    $data=array('notification_pop_up' => 'Yes');
                    $update_notif_pop_up=DB::table('notifications')->where('notification_id',$notification_id)->update($data);

                    $notifications_arr[]="<span class='pull-right NotifDate'>".$get_notification->created_at."</span><i class='fas fa-bell pull-left'></i><br><span class='NotifHead'>".$get_notification->title."</span><hr><p>".$get_notification->description."</p><center><a href='".$get_notification->url."'><button class='btn btn-sm btn-success NotificationClr' style='width: 100%;' value=".$notification_id.">View</button></a></center>";
                }
                echo $notifications=implode("*_*_*_*_*", $notifications_arr);
            }
            else
            {
                echo "0";
            }
        }
        else
        {
            echo "0";
        }
        
    }

    public function get_remainders(Request $request){

        $session_user_id=Auth::id();
        if(isset( $session_user_id))
        {
            $remainders_details=DB::table('remainders')->where('task_at', '<', DB::raw('NOW() + INTERVAL 5 MINUTE'))->where('popup_done', '=', 'No')->where('assigned_to',$session_user_id)->where('notification','=','Yes')->orderBy('remainder_id', 'DESC')->first();
                if($remainders_details->remainder_id!="" )
                {
                    echo $RemainderId=$remainders_details->remainder_id;
                }
                else
                {
                    echo $RemainderId=0;
                }
        }
        else
        {
            echo $RemainderId=0;
        }
    }

   public function get_remainder_later(Request $request){
    $user_id=Auth::id();
    $data=array('notification' => 'No', 'updated_by' =>$user_id, 'updated_at'=>Now());
    $remainder_update=DB::table('remainders')->where('remainder_id',$request->RemainderId)->update($data);
    // dd($remainder_update);
   } 
}
