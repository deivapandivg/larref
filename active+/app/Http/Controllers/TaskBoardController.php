<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TaskBoardController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth');
   }

   public function taskboard(Request $request){
      return view('tasks.taskboard');
   }
   public function tasksboard(Request $request){
      return view('tasks.tasksboard');
   }
}