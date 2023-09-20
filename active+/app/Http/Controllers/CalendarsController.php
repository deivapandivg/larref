<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class CalendarsController extends Controller{

	public function __construct(){
      $this->middleware('auth');
    }

	public function calendar(){
		$reminder_lists = DB::table('reminders as a')->select(['a.reminder_id',
			'a.reminder_for_id',
			'a.reminder_for',
			'a.task_at',
			'a.task_status',
			'a.task_completed_at',
			'b.communication_medium as communication_medium_id'])->leftjoin('communication_mediums as b', 'b.communication_medium_id', '=', 'a.communication_medium_id')->get();
		return view('calendars.calendar',compact('reminder_lists'));
	}

	public function calendar_model_ajax(Request $request){
      
      $Content=$request->content;
      $ContentArr=explode(":",$Content);
        $ReminderId=$ContentArr[1];

        $reminder_lists = DB::table('reminders as a')->select(['a.reminder_id',
            'a.reminder_for_id',
            'a.reminder_for',
            'a.task_at',
            'a.task_status',
            'a.task_completed_at',
            'a.Comments',
            'b.communication_medium as communication_medium_id',
            'd.communication_type as communication_type',
            'c.first_name'
        ])->leftjoin('communication_mediums as b','b.communication_medium_id', '=', 'a.communication_medium_id')->leftjoin('users as c','c.created_by', '=', 'a.created_by')->leftjoin('communication_types as d','d.communication_type_id','=', 'a.communication_type')->where('a.reminder_id', $ReminderId)->first();
        $lead_id=$reminder_lists->reminder_for_id;
        $reminder=$reminder_lists->reminder_id;
        $lead_lists= DB::table('leads')->where('lead_id',$lead_id)->first();
        // dd($lead_lists);
        $lead_stage_id=$lead_lists->lead_stage_id;
        // dd($lead_stage_id);
        $lead_sub_stage_id=$lead_lists->lead_sub_stage_id;
        $lead_sub_stage_list= DB::table('lead_sub_stage')->where('lead_sub_stage_id',$lead_sub_stage_id)->first();
        $lead_stage_lists = DB::table('lead_stages')->where('lead_stage_id',$lead_stage_id)->first();
        // dd($lead_stage_lists);
        $users_list=DB::table('users')->where('id',$lead_lists->lead_owner)->first();
        $data='<section class="contact-form">
                  	<div class="modal-header">
                     	<h5 class="modal-title" id="EditAccessModals">Reminder</h5>
                    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                  	</div>
                    
          			<div class="modal-body">
                     <div class="row">
                        <div class="col-md-12 mt-0">
                            <h6 class="form-section text-info">
                               <i class="fa fa-user fa-x1 text-info"></i> Leads Info <hr>
                            </h6>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Name :</b>
                                  <p>'.$lead_lists->lead_name.'</p>
                               </fieldset>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Communication Medium :</b>
                                  <p>'.$reminder_lists->communication_medium_id.'</p>
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Communication Type :</b>
                                  <p>'.$reminder_lists->communication_type.'</p>
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Lead Status :</b>
                                 <p>'.$lead_stage_lists->lead_stage_name.'</p>
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Lead Sub Status :</b>';
                               if(isset($lead_sub_stage_list->lead_sub_stage)){
                                    $data.='<p>'.$lead_sub_stage_list->lead_sub_stage.'</p>';
                               }
                               else{
                                    $data.='';
                               }
                               $data.='</fieldset>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <h6 class="form-section text-info">
                               <i class="fas fa-bookmark "></i> Reminder Details <hr>
                           </h6>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Follow Up Comments :</b>
                                  <p>'.$reminder_lists->Comments.'</p> 
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Lead Owner :</b>
                                  <p>'.$users_list->first_name.'</p>
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Follow Up By :</b>
                                  <p>'.$reminder_lists->first_name.'</p> 
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Created By :</b>
                                  <p>'.$reminder_lists->first_name.'</p> 
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b>Follow Up At :</b>
                                  <p>'.$reminder_lists->task_at.'</p> 
                               </fieldset>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                               <fieldset class="form-group floating-label-form-group"><b></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a target="_target" href="'.route('lead_view',base64_encode($lead_id)).'" style="color: red!important;">View More Details <i class="fa fa-eye"></i></a> 
                               </fieldset>
                           </div>
                       </div>
                     </div>
                  </div>
                  <div class=col-md-12 col-12">
                      	<div class="form-group float-right">
                        	<button class="btn btn-sm btn-primary DoFollowUp" data-toggle="tooltip" id="followup"  data-placement="right" value="'.$reminder.'" title="Double Cilck To Access" data-original-title="Double Cilck To Access"><i class="fa fa-location-arrow fa-x1"></i> Do Follow Up</button> 
                            <button type="reset" class="btn btn-danger btn-sm" data-dismiss="modal">
                               <i class="fa fa-times"></i> Close   
                            </button>
                        </div>
                    </div>
                </div>
            </section>';
      	echo $data;
   	}

   	public function calendar_append(Request $request)
   	{
      $communication_medium_lists=DB::table('communication_mediums')->select('*')->where('deleted','No')->get();
      $lead_stage_lists=DB::table('lead_stages')->select('*')->where('deleted','No')->get();
      $lead_sub_stage_lists=DB::table('lead_sub_stage')->select('*')->where('deleted','No')->get();
      $reminder_data=DB::table('reminders')->where('reminder_id',$request->reminder)->first();
      $reminder_for_id=$reminder_data->reminder_for_id;
      $reminder_for=$reminder_data->reminder_for;
      $lead_details=DB::table('leads')->where('lead_id',$reminder_for_id)->first();
      $data='<section>
            <div class="modal-bodyp px-2">
               <h6 class="form-section">
               <i class="fas fa-clock"></i> Timeline</h6>
               <div class="row ">
                  <div class="col-lg-6">
                     <div class="form-group">
                        <input type="hidden" name="reminder" value="'.$request->reminder.'">
                            <label class="form-group floating-label-form-group"><b>Communication Medium  <span class="text-danger">*</span> :</b></label>
                           <select class="form-control border-primary select2" name="communication_mediumid" id="communication_mediumid" style="width:100%;">
                           <option selected>Select</option>';
                           foreach($communication_medium_lists as $communication_medium_list)
                           {
                              $data.='<option value="'. $communication_medium_list->communication_medium_id .'" >'. $communication_medium_list->communication_medium .'
                              </option>';
                           }
                           $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Communication Type<span class="text-danger">*</span> :</b></label>
                        <select class="form-control border-primary select2"  name="communication_type_id" id="communication_type_id" style="width: 100%">
                        <option selected="selected" value="">Select</option>';
                        $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Lead Status <span class="text-danger">*</span> :</b></label>
                        <select class="form-control border-primary select2" name="lead_stage_id" id="lead_stages_id" style="width:100%;">
                        <option selected>Select</option>';
                        foreach ($lead_stage_lists as $lead_stage_list)
                        { 
                           if($lead_details->lead_stage_id!=$lead_stage_list->lead_stage_id)
                           {

                              $data.='<option value="'.$lead_stage_list->lead_stage_id .'">'. $lead_stage_list->lead_stage_name .'</option>';
                           }
                           else
                           {
                              $data.= '<option value="'. $lead_details->lead_stage_id.'" selected>'. $lead_stage_list->lead_stage_name .'</option>'; 
                           }
                        }
                        $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Lead Sub Status <span class="text-danger">*</span> :</b></label>
                        <select class="form-control border-primary select2" name="lead_sub_stage_id" id="lead_sub_stages_id" style="width:100%;">
                        <option selected>Select</option>';
                        foreach ($lead_sub_stage_lists as $lead_sub_stage_list)
                        {
                           if($lead_details->lead_sub_stage_id!=$lead_sub_stage_list->lead_sub_stage_id){
                              $data.='<option value="'.$lead_sub_stage_list->lead_sub_stage_id .'"> '.$lead_sub_stage_list->lead_sub_stage .'</option>';
                           }
                           else{
                              $data.='<option value="'. $lead_details->lead_sub_stage_id.'" selected>'. $lead_sub_stage_list->lead_sub_stage.'</option>'; 
                           }
                        }
                        $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <div class="form-group">
                        <fieldset class="form-group floating-label-form-group"><label><b>Timeline Attachment :</b></label>
                           <center>
                              <table id="AddImageTable" width="50%">
                                 <tbody id="ImageTBodyAdd">
                                    <tr class="add_row">
                                       <td width="100%"><input name="Image[]"  type="file" multiple ></td>
                                       <td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title="Add new file"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </center>
                        </fieldset>
                     </div>
                  </div>
                  <div class="col-lg-12">
                  &nbsp;&nbsp;&nbsp;<label class="label-control" for="userinput3"><b>Comments :</b></label>
                     <div class="form-group">
                        <center><textarea class="form-control border-primary" placeholder="Comments" name="Comments"></textarea> </center>
                     </div>
                  </div>
                  <div class="col-lg-12">
                     <label class="label-control" for="userinput3"><b>Do You Want Create Followup :</b></label>
                        <input type="radio" class="Checkbutton" name="Task" id="" value="Enable">&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;                               
                        <input type="radio" class="Checkbutton" name="Task" id="" value="Disable" checked>&nbsp;&nbsp;No
                  </div>
                  <div id="GetTaskAjax">
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
                  <i class="fa fa-times"></i> Close
               </button>
               <button type="submit" name="submit" class="btn btn-primary btn-md">
               <i class="fa fa-check"></i> Add
               </button>
            </div>
         </section>';
   	echo $data;
      
   }

   public function calendar_task_add(Request $request)
   {
      // $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $communication_medium_lists=DB::table('communication_mediums')->select('*')->where('deleted','No')->get();
      $lead_stage_lists=DB::table('lead_stages')->select('*')->where('deleted','No')->get();
      $lead_sub_stage_lists=DB::table('lead_sub_stage')->select('*')->where('deleted','No')->get();
      $radio_button=$request->RadioButton;
      if($radio_button=='Enable')
      {     
         $data='<div class="modal-body">  
                     <h6 class="form-section">
                     <i class="fas fa-calendar-day"></i> Reminder/Followup</h6>
                  <div class="row">
                     <div class="col-lg-6">
                        <div class="form-group">
                           <input type="hidden" name="radio_button" value="'.$radio_button.'">
                           <label class="form-group floating-label-form-group"><b>Communication Medium <span class="text-danger">*</span> :</b></label>
                              <select class="form-control border-primary select2" name="communication_medium" id="communication_medium" style="width:100%;">
                              <option selected>Select</option>';
                              foreach($communication_medium_lists as $communication_medium_list)
                              {
                                 $data.='<option value="'. $communication_medium_list->communication_medium_id .'" >'. $communication_medium_list->communication_medium .'
                                 </option>';
                              }
                              $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Communication Type <span class="text-danger">*</span> :</b></label>
                        <select class="form-control border-primary select2"  name="communication_type" id="communication_type" style="width: 100%">
                        <option selected="selected" value="">Select</option>';
                        $data.='</select>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="form-group floating-label-form-group"><b>Followup Date & Time <span class="text-danger">*</span> :</b></label>
                        <input type="datetime-local" required id="Contactdate" class="form-control border-primary" min=" " name="contactdate">
                     </div>
                  </div>
                  <div class="col-lg-12">
                  &nbsp;&nbsp;&nbsp;<label class="label-control" for="userinput3"><b>Comments :</b></label>
                     <div class="form-group">
                        <textarea class="form-control border-primary" placeholder="Comments" name="comments1"></textarea>
                     </div>
                  </div>
               </div>
      </div>';
   echo $data;
      }
   }

   	public function reminder_followup_submit(Request $request){
  		$user_id = Auth::id();
      	$reminder_data=DB::table('reminders')->where('reminder_id',$request->reminder)->first();
      	$reminder_for_id=$reminder_data->reminder_for_id;
      	$reminder_for=$reminder_data->reminder_for;

      	$lead_details=DB::table('leads')->where('deleted','No')->where('lead_id',$reminder_for_id)->first();
      	$data=array(
        'timeline_for_id' => $reminder_for_id,
        'timeline_for' => 'lead',
        'communication_medium_id' => $request->communication_mediumid,
        'communication_medium_type_id' => $request->communication_type_id,
        'lead_stage_id' => $request->lead_stage_id,
        'lead_sub_stage_id' => $request->lead_sub_stage_id,
        'description' => 'Remainders Followup',
        'created_at' => Now(),
        'created_by' => $user_id,
     	);
     	// dd($data);
     	$timeline_insert=DB::table('timelines')->insert($data);
     	if($timeline_insert){
        	$value = ['type' => 'success', 'message' => 'Created Successfully!', 'title' => 'Success!'];
     	}
     	else{
        	$value = ['type' => 'error', 'message' => 'Something Went Wrong!', 'title' => 'Error!'];
     	}
     	$lead_data = array('lead_stage_id' => $request->lead_stage_id,'lead_sub_stage_id' => $request->lead_sub_stage_id, 'updated_by' => $user_id, 'updated_at' => Now());
     	$lead_update = DB::table('leads')->where('lead_id', $reminder_for_id)->update($lead_data);
     	$radio_button=$request->radio_button;
    	if($radio_button=='Enable'){
	        $reminder_insert=array(
	         'reminder_for'=>'lead',
	         'reminder_for_id' => $reminder_for_id,
	         'communication_medium_id' => $request->communication_mediumid,
	         'communication_type'=> $request->communication_type_id,
	         'task_at'=>$request->contactdate,
	         'comments'=>$request->comments1,
	         'created_at' => Now(),
	         'created_by' => $user_id,
	        );
     		$reminder=DB::table('reminders')->insert($reminder_insert);
     	}
     	return redirect('calendar')->with($value);  
   	}
}