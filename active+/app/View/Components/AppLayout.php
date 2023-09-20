<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use DB;

class AppLayout extends Component
{

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $user_id = Auth::id();
        $menus=DB::table('menus')->get();
        $menu_groups=DB::table('menu_groups')->where('menu_groups.deleted', 'No')->select('*')->join('menus', 'menus.menu_group_id', '=', 'menu_groups.menu_group_id')->get();
        $notifications_counts=DB::table('notifications')->where('notification_viewed', '=', 'No')->where('notification_to',$user_id)->count(); 
        $notifications=DB::table('notifications')->where('notification_viewed','No')->where('notification_to',$user_id)->orderBy('notification_id')->take(10)->get(); 

        $auth_designation_id = Auth::user()->designation_id;
        $designations=DB::table('designations')->where('designation_id',$auth_designation_id)->select('*')->first();
        $access_menus=$designations->access_menus;

        
            $access_menus_arr = explode(',', $designations->access_menus);

            $CompletedMenuGroupsArr=array();
            $get_menus= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->whereIn('a.menu_id', $access_menus_arr)->where('a.deleted', 'No')->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->orderby('row_order')->get();

            $master_get_menus= DB::table('menus as a')->select('a.menu_id','a.menu_group_id','a.menu_name','a.menu_icon','a.menu_link','b.menu_group_name','b.menu_group_icon')->whereIn('a.menu_id', $access_menus_arr)->where('a.deleted', 'No')->leftjoin('menu_groups as b', 'b.menu_group_id', '=', 'a.menu_group_id')->orderby('menu_name')->get();

            foreach($get_menus as $get_menu){
                $menu_group_id=$get_menu->menu_group_id;
                $child_menus=DB::table('menus')->select('menu_id','menu_name','menu_link','menu_icon','menu_group_id')->whereIn('menu_id', $access_menus_arr)->where('deleted', 'No')->orderby('row_order')->get();
            }
            
        return view('layouts.app',compact('menus','menu_groups','CompletedMenuGroupsArr','notifications_counts','notifications','designations','get_menus','access_menus','child_menus','master_get_menus'));
    }
}
