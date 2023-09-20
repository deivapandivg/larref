<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMailController;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;

class OtpLoginController extends Controller
{
     public function create()
    {
        return view('auth.otp_login');
    }

      public function otp_submit(Request $request)
    {
        $otp= rand(100000, 999999);
        $data = array('otp' => $otp);
        $email=$request->email;
        Session::put('email', $email);
        $update=DB::table('users')->where('email',$request->email)->update($data);
            $SendMailController=new SendMailController();
            $data = array('to_mail_id' => $request->email,'otp' => $otp,
            'subject' => 'Login OTP '
            );
            $send_mail=$SendMailController->send_mail("Login_OTP",$data);

        // $request->authenticate();

        // $request->session()->regenerate();
         return redirect('otp_validate');

        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function otp_validate(Request $request){
        return view('auth.otp_validate');
    }

    public function otp_check(Request $request){
        $otp=$request->otp;
        $email=Session::get('email');
        $user=DB::table('users')->where('email',$email)->first();
        Auth::login($user, true);
        // $user=DB::table('users')->where('email',$email)->first();
        // // dd($users_list);
        // // dd($otp);
        // if($user->otp == $otp){
        //     Auth::login($user, true);
        //     DB::table('users')->where('id','=',$user->id)->update(['otp' => null]);
        //     return redirect('/dashboard');
        // }
        // else
        // {
        //     return redirect('login');
        // }

    }

}
