<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TasksController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function tasks(Request $request){
        $Auth_id = Auth::id();
        $TaskAjaxWhere = Session::get('TaskAjaxWhere');
        if($Auth_id==1){
            if ($request->ajax()) {
                $data = DB::table('tasks as a')->whereRaw($TaskAjaxWhere)->select(['a.task_id',
                    'd.client_name as client_id',
                    'e.project_name as project_id',
                    'a.task_name', 
                    'f.status_name as task_status',
                    'a.status_description', 
                    'b.first_name as created_by',
                    'g.first_name as assign_to',
                    'a.created_at', 
                ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d','d.client_id', '=', 'a.client_id')->leftjoin('projects as e','e.project_id', '=', 'a.project_id')->leftjoin('status as f','f.status_id', '=', 'a.task_status')->leftjoin('users as g','g.id', '=', 'a.assign_to');
                // if($request->all_date!="yes")
                // {
                //     $data->whereBetween('a.created_at', [$request->from_date." 00:00:00", $request->to_date." 23:59:59"]);
                // }
                // if($request->user_id!="All")
                // {
                //     $data->where('a.assign_to',("$request->user_id"));
                // }
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn ='<a class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                    $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                    $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('task_update',base64_encode($row->task_id)).'" class="vg-btn-ssp-warning UpdateDataModal text-center text-white" data-toggle="tooltip" data-placement="right" title="Update" data-original-title="Update"><i class="fa fa-check"></i></a>';
                    $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-success TaskViewModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        }
        else{
            if ($request->ajax()) {
            $data = DB::table('tasks as a')->whereRaw($TaskAjaxWhere)->select(['a.task_id',
                'd.client_name as client_id',
                'e.project_name as project_id',
                'a.task_name', 
                'f.status_name as task_status',
                'a.status_description', 
                'b.first_name as created_by',
                'g.first_name as assign_to',
                'a.created_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d','d.client_id', '=', 'a.client_id')->leftjoin('projects as e','e.project_id', '=', 'a.project_id')->leftjoin('status as f','f.status_id', '=', 'a.task_status')->leftjoin('users as g','g.id', '=', 'a.assign_to');
            // if($request->all_date!="yes")
            // {
            //     $data->whereBetween('a.created_at', [$request->from_date." 00:00:00", $request->to_date." 23:59:59"]);
            // }
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn='&nbsp;&nbsp;&nbsp;<a href="'.route('task_update',base64_encode($row->task_id)).'" class="vg-btn-ssp-warning UpdateDataModal text-center text-white" data-toggle="tooltip" data-placement="right" title="Update" data-original-title="Update"><i class="fa fa-check"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }
        }
        $clients = DB::table('clients')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $projects = DB::table('projects')->select(DB::raw('*'))
        ->where('deleted','No')->get();

        $get_auth_user = DB::table('users')->where('deleted', 'No')->where('id',$Auth_id)->first();

        if($get_auth_user->designation_id==1){

            $users_list = DB::table('users')->where('deleted', 'No')->get();

        }
        else{

            
            $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $Auth_id)->where('deleted', 'No')->select('id', 'first_name');

            $users_list = DB::table('users')->where('id', $Auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
            
        }

        $status_lists = DB::table('status')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $priority_lists = DB::table('task_priority')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        return view('tasks.tasks',compact('clients','projects','users_list','status_lists','priority_lists'));
    }

    public function task_edit(Request $request){
        $task_edit=DB::table('tasks')->where('task_id',$request->task_id)->first();
        $task_attachments=DB::table('task_attachments')->where('task_id',$request->task_id)->get();
        $clients_edit=DB::table('clients')->where('deleted', 'No')->get();
        $status_lists=DB::table('status')->where('deleted', 'No')->get();
        $projects = DB::table('projects')->where('deleted','No')->get();
        $users_list = DB::table('users')->where('deleted','No')->get();
        $priority_lists = DB::table('task_priority')->where('deleted','No')->get();
        $model='<div class="modal-body">
        <input type="hidden" name="task_id" value="'.$task_edit->task_id.'">
            <div class="row">
               <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <select class="form-control border-primary select2 form-select" name="client_id" id="edit_client_id" data-placeholder="Choose one" style="width:100%;">
                              <option selected disabled>Select</option>';
                              foreach ($clients_edit as $client){
                                if($client->client_id==$task_edit->client_id){$selected='selected';}else{$selected='';}
                                $model.='<option value="'.$client->client_id.'" '.$selected.'>'.$client->client_name.'</option>';
                              }
                           $model.='</select>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Task Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <input type="text" id="" required name="task_name" class="name form-control" placeholder="Task Name" value="'.$task_edit->task_name.'">
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Project Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <select class="form-control border-primary select2 form-select" name="project_id" id="edit_project_id" data-placeholder="Choose one" style="width:100%;">
                              <option selected disabled>Select</option>';
                              foreach ($projects as $project){
                                if($project->project_id==$task_edit->project_id){$selected='selected';}else{$selected='';}
                              $model.='<option value="'.$project->project_id.'" '.$selected.'>'.$project->project_name.'</option>';
                              }
                           $model.='</select>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Assigned To <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <select class="form-control border-primary select2 form-select" name="assign_to" data-placeholder="Choose one" style="width:100%;">
                              <option selected disabled>Select</option>';
                              foreach($users_list as $user_list){
                                if($user_list->id==$task_edit->assign_to){$selected='selected';}else{$selected='';}
                              $model.='<option value="'.$user_list->id.'" '.$selected.'>'.$user_list->first_name.'</option>';
                              }
                           $model.='</select>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Status<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <select class="form-control border-primary select2 form-select" name="status_id" data-placeholder="Choose one" style="width:100%;">
                              <option selected disabled>Select</option>';
                              foreach ($status_lists as $status_list){
                                if($status_list->status_id==$task_edit->task_status){$selected='selected';}else{$selected='';}
                              $model.='<option value="'.$status_list->status_id.'" '.$selected.'>'. $status_list->status_name.'</option>';
                              }
                           $model.='</select>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Priority Level<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                           <select class="form-control border-primary select2 form-select" name="priority_id" data-placeholder="Choose one" style="width:100%;">
                              <option selected disabled>Select</option>';
                              foreach ($priority_lists as $priority_list){
                                if($priority_list->priority_id==$task_edit->priority_id){$selected='selected';}else{$selected='';}
                              $model.='<option value="'.$priority_list->priority_id.'" '.$selected.'>'.$priority_list->priority_name.'</option>';
                              }
                           $model.='</select>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Attachments :</b>
                            <center>
                                <table id="EditImageTable" width="50%">
                                    <tbody id="ImageTBodyEdit">';
                                        foreach($task_attachments as $task_attachment){
                                            $model.='<tr class="add_row">
                                              <td width="100%"><input name="existing_attachments[]" type="hidden" multiple style="width:100px;" value="'.$task_attachment->attachment.'">'.$task_attachment->attachment.'</td>
                                              <td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td>
                                              <td><a href="public/task_uploads/'.$task_attachment->attachment.'" target="_blank"><button type="button" class="btn btn-primary btn-sm" id="view" title="View file"><i class="fa fa-eye"></i></button></a></td>
                                            </tr>'; 
                                        }
                                        $model.='<tr>
                                            <td width="100%"><input name="attachments[]" type="file" multiple></td>
                                            <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title="Add new file"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </center>
                        </fieldset>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><b>Description : </b>
                            <textarea class="form-control" name="description" placeholder="Description">'.$task_edit->status_description.'</textarea>   
                        </fieldset>   
                    </div>
                </div>
            </div> 
        </div>
        <div class="modal-footer">
           <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
              <i class="fa fa-times"></i> Close
           </button>
           <button type="submit" class="btn btn-primary btn-md">
              <i class="fa fa-check"></i> Update
           </button>
        </div>';
        echo $model;
    }

    public function tasks_submit(Request $request){

        $createdby=Auth::id();
        $updatedby=Auth::id();
        if(isset($request->task_id)){
            
            $data = array('client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $request->status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'updated_by' => $createdby, 'updated_at' => Now());

            $tasks_edit=DB::table('tasks')->where('task_id',$request->task_id)->update($data);   
             $data = array('task_id'=>$request->task_id,'client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $request->status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'updated_by' => $createdby, 'updated_at' => Now());
            $task_log_update = DB::table('tasks_log')->insert($data);

            $DeleteFilesItems = DB::table('task_attachments')->where('task_id', $request->task_id)->delete();

             if(isset($tasks_edit))
             {
                
                $taskIdNew=DB::table('tasks')->select('*')->where('task_id',$request->task_id)->first();
                $task_attachment_id=$taskIdNew->task_id;

                if ($request->existing_attachments!="") {

                    $old_files=[];

                    foreach ($request->existing_attachments as $oldfile) {
                      $name = $oldfile;
                      
                      $old_files[] = $name;
                    }
                   

                    for ($i = 0; $i < count($old_files); $i++) {

                      $OldFilesArr = $old_files[$i];
                      
                      $old_attachment_add = array('task_id' => $task_attachment_id, 'attachment' => $OldFilesArr, 'created_by' => $createdby, 'created_at' => Now());
                      $old_task_attachment_add = DB::table('task_attachments')->insert($old_attachment_add);

                    }
                }

                if($request->attachments!=""){

                    $files = [];
                    foreach ($request->file('attachments') as $file) {
                      $name = time() . rand(1, 100) . '.' . $file->extension();
                      $file->move(public_path('attachments'), $name);
                      $files[] = $name;
                    }

                    for ($i = 0; $i < count($files); $i++) {

                      $attachments = $files[$i];
                      $attachment_add = array('task_id' => $task_attachment_id, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());
                      $task_attachment_add = DB::table('task_attachments')->insert($attachment_add);

                    }
                }
            }
        }
        else
        {
            $files=[];
            if(!is_null($request->file('attachment'))){
                foreach($request->file('attachment') as $file)
                {
                    $name = time() . rand(1, 100) . '.' . $file->extension();
                    $file->move(public_path('task_uploads'),$name);
                    $files[] = $name;
                }
            }
            if($request->status_id!=''){
                $status_id= $request->status_id;  
            }
            else{
                $status_id='Created';
            }
            $data = array('client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'created_by' => $createdby, 'created_at' => Now());
            $tasks_add=DB::table('tasks')->insertGetId($data);
            $log_insert = array('task_id' => $tasks_add,'client_id' => $request->client_id, 'task_name' => $request->task_name, 'project_id' => $request->project_id, 'assign_to' => $request->assign_to,  'task_status' => $status_id,  'priority_id' => $request->priority_id, 'status_description' => $request->description, 'created_by' => $createdby, 'created_at' => Now());
            $task_log_insert = DB::table('tasks_log')->insert($log_insert);
            for($i = 0; $i < count($files); $i++){
                $attachments = $files[$i];
                $attachment_add = array('task_id' => $tasks_add, 'attachment' => $attachments, 'created_by' => $createdby, 'created_at' => Now());

                $task_attachment_add = DB::table('task_attachments')->insert($attachment_add);
            }
            $notification_to = $request->assign_to;
            $get_user_name = DB::table('users')->where('id', $notification_to)->select('first_name')->first();
            $notification_to_name = $get_user_name->first_name;
            $from_user_name = DB::table('users')->where('id', $createdby)->select('first_name')->first();
            $notification_from_name = $from_user_name->first_name;
            $URL="tasks";
            $Descriptions="Dear $notification_to_name, You have received one Task From $notification_from_name : Thank You.";
            $notification_data = array('title' => 'Task Assigned For You', 'description' => $Descriptions, 'url' => $URL, 'notification_to' => $notification_to, 'created_by' => $createdby, 'created_at' => Now());
            $insert_notification = DB::table('notifications')->insert($notification_data);
        }
        return redirect('tasks');
    }

    public function client_change_ajax(Request $request){
        $client_id = $request->client_id;
        $projects_select=DB::table('projects')->where('client_id', $client_id)->get();
        foreach($projects_select as $project_select){
            echo $data='<option value="'.$project_select->project_id.'">'.$project_select->project_name.'</option>';
        }
    }

    public function task_update(Request $request){
        $task_details=DB::table('tasks')->where('task_id', base64_decode($request->task_id))->first();
        $clients = DB::table('clients')->where('client_id',$task_details->client_id)->first();
        $projects = DB::table('projects')->where('project_id',$task_details->project_id)->first();
        $users_list = DB::table('users')->where('id',$task_details->assign_to)->first();
        $createdby = DB::table('users')->where('id',$task_details->created_by)->first();
        $status_lists = DB::table('status')->select(DB::raw('*'))
        ->where('deleted','No')->get();
        $priority_lists = DB::table('task_priority')->where('priority_id',$task_details->priority_id)->first();
        $task_attachments=DB::table('task_attachments')->where('task_id', base64_decode($request->task_id))->get();
        return view('tasks.task_update',compact('task_details','clients','projects','users_list','status_lists','priority_lists','createdby','task_attachments'));
    }

    public function task_update_submit(Request $request){
        $updatedby=Auth::id();
        $updatedat=now();
        $data=array('task_status' => $request->status_id, 'description' => $request->description, 'updated_by' => $updatedby, 'updated_at' => $updatedat);
        $update=DB::table('tasks')->where('task_id', $request->task_id)->update($data);
        $task_details=DB::table('tasks')->where('task_id',$request->task_id)->first();

        $task_log_data=array('task_id'=>$request->task_id,'task_status' => $request->status_id, 'description' => $request->description, 'updated_by' => $updatedby, 'updated_at' => $updatedat,'client_id' => $task_details->client_id, 'task_name' => $task_details->task_name, 'project_id' => $task_details->project_id,'assign_to' => $task_details->assign_to,'priority_id' => $task_details->priority_id, 'status_description' => $task_details->description,);
            $tasks_log_insert = DB::table('tasks_log')->insert($task_log_data);
        return redirect('tasks');
    }

    public function task_view(Request $request){

       if ($request->ajax()) {
            $data = DB::table('tasks_log as a')->where('task_id',$request->task_id)->select([
                'a.task_log_id',
                'a.task_id',
                'd.client_name as client_id',
                'e.project_name as project_id',
                'a.task_name', 
                'f.status_name as task_status',
                'a.status_description', 
                'b.first_name as created_by',
                'g.first_name as assign_to',
                'a.created_at', 
                'h.first_name as updated_by',
                'a.updated_at',
                'a.description',
                'i.priority_name as priority_id'
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by')->leftjoin('clients as d','d.client_id', '=', 'a.client_id')->leftjoin('projects as e','e.project_id', '=', 'a.project_id')->leftjoin('status as f','f.status_id', '=', 'a.task_status')->leftjoin('users as g','g.id', '=', 'a.assign_to')->leftjoin('users as h','h.id', '=', 'a.updated_by')->leftjoin('task_priority as i','i.priority_id', '=', 'a.priority_id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
        }
        $tasklogs=DB::table('tasks_log')->where('task_id',base64_decode($request->task_id))->first();
        $client_details=DB::table('clients')->where('deleted','No')->get();
        $projects_details=DB::table('projects')->where('deleted','No')->get();
        $users_details=DB::table('users')->where('deleted','No')->get();
        $task_status_details=DB::table('status')->where('deleted','No')->get();
        return view('tasks.task_view',compact('tasklogs','client_details','projects_details','users_details','task_status_details'));
    }

    public function task_modal_view(Request $request)
    {
        $tasklogs=DB::table('tasks_log')->where('task_id',$request->task_id)->first();
        $client_details=DB::table('clients')->where('deleted','No')->get();
        $projects_details=DB::table('projects')->where('deleted','No')->get();
        $users_details=DB::table('users')->where('deleted','No')->get();
        $task_status_details=DB::table('status')->where('deleted','No')->get();
        $model ='
        <div class="row">
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>TaskId </b>
                      <p>'.$tasklogs->task_id.'</p>
                   </fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Client Name </b>';
                      foreach($client_details as $client_detail){
                         if($client_detail->client_id==$tasklogs->client_id){
                            $model.='<p value="'.$tasklogs->client_id.'">'.$client_detail->client_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Project Name </b>';
                      foreach($projects_details as $project_detail){
                         if($project_detail->project_id==$tasklogs->project_id){
                            $model.='<p value="'.$tasklogs->project_id.'">'.$project_detail->project_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
              <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Task Name</b>
                      <p>'.$tasklogs->task_name.'</p>
                   </fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Description</b>
                      <p>'.$tasklogs->description.'</p>
                   </fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Assign To</b>';
                      foreach($users_details as $user_detail){
                         if($user_detail->id==$tasklogs->assign_to){
                            $model.='<p value="'.$tasklogs->assign_to.'">'.$user_detail->first_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Status Description</b>
                      <p>'.$tasklogs->status_description.'</p>
                   </fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Task Status</b>';
                      foreach($task_status_details as $task_status_detail){
                         if($task_status_detail->status_id==$tasklogs->task_status){
                            $model.='<p value="'.$tasklogs->task_status.'">'.$task_status_detail->status_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Created By</b>';
                      foreach($users_details as $users_detail){
                         if($users_detail->id==$tasklogs->created_by){
                            $model.='<p value="'.$tasklogs->created_by.'">'.$users_detail->first_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Created At</b>
                      <p>'.$tasklogs->created_at.'</p>
                   </fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Updated By</b>';
                      foreach($users_details as $users_detail){
                         if($users_detail->id==$tasklogs->updated_by){
                            $model.='<p value="'.$tasklogs->created_by.'">'.$users_detail->first_name.'</p>';
                         }
                      }
                   $model.='</fieldset>
                </div>
             </div>
             <div class="col-lg-6">
                <div class="form-group">
                   <fieldset class="form-group floating-label-form-group"><b>Updated At</b>
                      <p>'.$tasklogs->updated_at.'</p>
                   </fieldset>
                </div>
             </div>
            <div class="col-md-12">
               <div class="form-group">
                  <center>
                  <a href="'.route('task_view',base64_encode($tasklogs->task_id)).'" target="_blank">
                     <p><b><u>View More Details</u> </b></p>
                  </a>
               </center>
               </div>
            </div> 
        </div>
        ';
        echo $model;
    }

    public function tasks_ajax(Request $request)
    {
        $FromDate=$request->FromDate;
        $ToDate=$request->ToDate;
        $AllDate=$request->AllDate;
        $user_id=$request->user_id;
        $date_where="";
        $where="a.deleted='No'";
        $UserId = Session::get('user_id');
        Session::put('from_date', $FromDate);
        Session::put('to_date', $ToDate);
        Session::put('all_date', $AllDate);

        if($AllDate!='All')
        {
            $where.="AND a.created_at>='".$FromDate." 00:00:00' AND a.created_at<='".$ToDate." 23:59:59'";
        }

        if($user_id=='All')
        {
            $auth_id = Auth::id();
            if($auth_id==1){
                $get_all_users= DB::table('users')->select('id')->where('deleted','No')->get();
                foreach($get_all_users as $get_all_user)
                {
                    $UserArray[] = $get_all_user->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.assign_to IN ($Users)";
            }
            else{
                $get_reporting_to_users = DB::table('users')->where('reporting_to_id', $auth_id)->where('deleted', 'No')->select('id', 'first_name');

                $users_lists = DB::table('users')->where('id', $auth_id)->where('deleted', 'No')->select('id', 'first_name')->unionAll($get_reporting_to_users)->get();
                foreach($users_lists as $users_list)
                {
                    $UserArray[] = $users_list->id;
                }
                $Users = implode(', ', $UserArray);
                $where.="AND a.assign_to IN ($Users)";
            }
        }
        else
        {
            $where.="AND a.assign_to = '$user_id' ";
        }

        Session::put('TaskAjaxWhere',$where);
        
        $data='';
        $data.='
         <div class="card-content">
              <div class="card-body">
                 <div class="table-responsive">
                    <table class="table table-striped table-bordered tasks" style="width:100%;">
                       <thead>
                          <tr>
                            <th>Task Id</th>';
                            if(Auth::user()->id==1){
                                $data.='<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                            }
                            else{
                                $data.='<th>Action</th>';
                            }
                             $data.='<th>Client Name</th>
                             <th>Project Name</th>
                             <th>Task Name</th>
                             <th>Status</th>
                             <th>Description</th>
                             <th>Assign By</th>
                             <th>Assign To</th>
                             <th>Created At</th>
                          </tr>
                       </thead>
                       <tbody>

                       </tbody>
                       <tfoot>
                          <tr>
                            <th>Task Id</th>';
                            if(Auth::user()->id==1){
                                $data.='<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
                            }
                            else{
                                $data.='<th>Action</th>';
                            }
                             $data.='<th>Client Name</th>
                             <th>Project Name</th>
                             <th>Task Name</th>
                             <th>Status</th>
                             <th>Description</th>
                             <th>Assign By</th>
                             <th>Assign To</th>
                             <th>Created At</th>
                          </tr>
                       </tfoot>
                    </table>
                 </div>
              </div>
           </div>';
        echo $data;

    } 

    public function tasks_manager(Request $request){
        $auth_id = Auth::id();
        $tasks_list = DB::table('tasks')->where('deleted', 'No')->where('assign_to', $auth_id)->get();
        return view('tasks.tasks_manager',compact('tasks_list'));
    }
}
