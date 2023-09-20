<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;

use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;


class UserProfileController extends Controller
{
   //
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function user_profile_edit(Request $request){

      return view('users_profile.user_profile_edit');
   }

   public function user_theme_submit(Request $request){
      $user_id = Auth::id();
      $data = array('navbar_color' => $request->navbar_color, 'background_image' => $request->background_image, 'background_view' => $request->background_view, 'updated_by' => $user_id, 'updated_at' => Now());

      $update=DB::table('users')->where('id',$request->user_id)->update($data);
      return Redirect('user_profile_edit');
   }

   public function password_chage_submit(Request $request){
      $user_id = Auth::id();
      $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
      ]);
      $data = array('password' => Hash::make($request->new_password), 'updated_by' => $user_id, 'updated_at' => Now());
      $update=DB::table('users')->where('id',$user_id)->update($data);
      
      return Redirect('user_profile_edit');
   }

}
