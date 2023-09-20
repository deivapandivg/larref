<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class UserController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function country_id(Request $request){
        $country_id =$request->country_id;
        if ($country_id!="") 
        {
            $state_select=DB::table('states')->where('country_id', $country_id)->select('*')->get();
            // $StateSelect=mysqli_query($con,"SELECT * FROM states WHERE CountryId='$CountryId'");  
            foreach($state_select as $state)
            {
             
                echo $states='<option value="'.$state->state_id.'">'.$state->state_name.'</option>';
            }
        }
    }

    public function state_id(Request $request){
        $state_id =$request->state_id;
        if($state_id!="")
        {
            $city_select=DB::table('cities')->where('state_id', $state_id)->select('*')->get();
            // $CitySelect=mysqli_query($con,"SELECT * FROM cities  WHERE StateId='$StateId'");  
            foreach($city_select as $city)
            {
             
                echo $cities='<option value="'.$city->city_id.'">'.$city->city_name.'</option>';
                
            }
        }
    }

    public function shift_id(Request $request){

        $shift_id =$request->shift_id;
        if ($shift_id!="") 
        {
            $shift_select=DB::table('shift_timings')->where('shift_id', $shift_id)->select('*')->get();
            foreach($shift_select as $shift)
            {
              
                echo $shifts='<option value="'.$shift->shift_timing_id.'">'.$shift->shift_timing.'</option>';
            }
        }
    }

    public function index()
    {
        return response()->json(User::orderByDesc('id')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'plain_password' => $request->password,
        ]);

        return response()->json('Successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return response()->json(User::whereId($id)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::whereId($id)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->json('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::whereId($id)->first()->delete();

        return response()->json('Successfully Deleted');
    }
}
