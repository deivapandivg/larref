<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;
use Mail;
use App\Mail\SendMail;

class TemplatesController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

   public function mail(Request $request){

         $user_id=Auth::id();
        if ($request->ajax()) {
            $data = DB::table('com_template_mail as a')->where('a.deleted','No')->select(['a.template_id',
                'a.template_name',
                'a.email',
                'a.subject',
                'a.comments',
                'b.first_name as created_by',
                'a.created_at',
            ])->leftJoin('users as b','a.created_by', '=', 'b.id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a href="'.route('mail_edit',($row->template_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }


        return view('templates.mail');
    }

     public function mail_add(Request $request){

        return view('templates.mail_add');
    }
    public function mail_submit(Request $request){

         $createdby = Auth::id();
         $updatedby = Auth::id();
          if(isset($request->template_id))
        {
            $data = array('template_name' => $request->template_name, 'email' => $request->email, 'subject' => $request->subject, 'comments' => $request->comments,'updated_by' => $updatedby, 'updated_at' => Now());
            $mail_update=DB::table('com_template_mail')->where('template_id',$request->template_id)->update($data);
        }
        else
        {
            $data = array('template_name' => $request->template_name, 'email' => $request->email, 'subject' => $request->subject, 'comments' => $request->comments,'created_by' => $createdby, 'created_at' => Now());

            $mail_add=DB::table('com_template_mail')->insert($data);
        }

        return view('templates.mail');
    }

     public function mail_edit(Request $request)
   {
        $mail=DB::table('com_template_mail')->where('template_id', $request->template_id)->where('deleted', 'No')->first();

        return view('templates.mail_edit',compact('mail'));
    }

    public function whatsapp(Request $request){

         $user_id=Auth::id();
        if ($request->ajax()) {
            $data = DB::table('com_template_whatsapp as a')->where('a.deleted','No')->select(['a.template_id',
                'a.template_name',
                'a.content',
                'b.first_name as created_by',
                'a.created_at',
            ])->leftJoin('users as b','a.created_by', '=', 'b.id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a href="'.route('whatsapp_edit',($row->template_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }



        return view('templates.whatsapp');
    }

     public function whatsapp_add(Request $request){

        return view('templates.whatsapp_add');
    }


    public function whatsapp_submit(Request $request){

         $createdby = Auth::id();
         $updatedby = Auth::id();

          if(isset($request->template_id))
        {
            $data = array('template_name' => $request->template_name, 'content' => $request->content,'updated_by' => $updatedby, 'updated_at' => Now());
            $whatsapp_update=DB::table('com_template_whatsapp')->where('template_id',$request->template_id)->update($data);
        }
        else
        {
            $data = array('template_name' => $request->template_name, 'content' => $request->content, 'created_by' => $createdby, 'created_at' => Now());

            $whatsapp_add=DB::table('com_template_whatsapp')->insert($data);
        }

        return view('templates.whatsapp');
    }


      public function whatsapp_edit(Request $request)
   {
        $whatsapp=DB::table('com_template_whatsapp')->where('template_id', $request->template_id)->where('deleted', 'No')->first();

        return view('templates.whatsapp_edit',compact('whatsapp'));
    }



    public function sms(Request $request){

        $user_id=Auth::id();
        if ($request->ajax()) {
            $data = DB::table('com_template_sms as a')->where('a.deleted','No')->select(['a.template_id',
                'a.template_name',
                'a.content',
                'b.first_name as created_by',
                'a.created_at',
            ])->leftJoin('users as b','a.created_by', '=', 'b.id');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a href="'.route('sms_edit',($row->template_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a></div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('templates.sms');
    }


    public function sms_add(Request $request){

        return view('templates.sms_add');
    }

     public function sms_submit(Request $request){

         $createdby = Auth::id();
         $updatedby = Auth::id();
         

          if(isset($request->template_id))
        {
            $data = array('template_name' => $request->template_name, 'content' => $request->content, 'updated_by' => $updatedby, 'updated_at' => Now());
            $sms_update=DB::table('com_template_sms')->where('template_id',$request->template_id)->update($data);
        }
        else
        {
            $data = array('template_name' => $request->template_name, 'content' => $request->content, 'created_by' => $createdby, 'created_at' => Now());

            $sms_add=DB::table('com_template_sms')->insert($data);
             // dd($data);
        }

        return view('templates.sms');
    }

    public function sms_edit(Request $request)
   {
        $sms=DB::table('com_template_sms')->where('template_id', $request->template_id)->where('deleted', 'No')->first();

    return view('templates.sms_edit',compact('sms'));
    }

    public function global_options(Request $request){

        $options = DB::table('global_options')->first();
        return view('templates.global_options',compact('options'));
    }

    public function global_options_submit(Request $request){

        $sms_update=DB::table('global_options')->update(['sms_option' => $request->sms_option, 'mail_option' => $request->mail_option, 'whatsapp_option' => $request->whatsapp_option, 'call_option' => $request->call_option]);

        return redirect('global_options');
    }

    public function sms_option(Request $request)
    {
      $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $sms_templates=DB::table('com_template_sms')->get();
       
      $data='<div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <input type="hidden" name="lead_id" value="'.$leads_details->lead_id.'">
                        <label class="form-group floating-label-form-group">Template  <span class="text-danger">*</span> :</label>
                          <select class="form-control border-primary select2" name="template_id" id="template_id" style="width:100%;">
                                  <option selected value="" disabled>Select</option>';
                                foreach($sms_templates as $sms_template)
                                {
                                    $data.='<option value="'. $sms_template->template_id .'" >'. $sms_template->template_name .'
                                          </option>';
                                }
                              $data.='</select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
         <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
            <i class="fa fa-times"></i> Close
         </button>
         <button type="submit" name="submit" class="btn btn-primary btn-md">
            <i class="fa fa-check"></i> Send
         </button>
      </div>';
      echo $data;
    }

    public function sms_option_submit(Request $request){

        $auth_id = Auth::id();
        $lead_id = $request->lead_id;
        $get_lead = DB::table('leads')->where('lead_id', $lead_id)->select('lead_id', 'mobile_number')->first();
        $mobile_number = $get_lead->mobile_number;
        
        $template_id = $request->template_id;
        $get_template = DB::table('com_template_sms')->where('template_id', $template_id)->select('template_id', 'content')->first();
        $content = $get_template->content;

        $data = ['template_id' => $template_id, 'lead_id' => $lead_id, 'mobile_number' => $mobile_number, 'content' => $content, 'created_at' => now(), 'created_by' => $auth_id];
        $insert = DB::table('com_log_sms')->insert($data);

        return redirect('leads');
    }

    public function whatsapp_option(Request $request)
    {
      $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $whatsapp_templates=DB::table('com_template_whatsapp')->get();
       
      $data='<div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <input type="hidden" name="lead_id" value="'.$leads_details->lead_id.'">
                        <label class="form-group floating-label-form-group">Template  <span class="text-danger">*</span> :</label>
                          <select class="form-control border-primary select2" name="template_id" id="template_id" style="width:100%;">
                                  <option selected value="" disabled>Select</option>';
                                foreach($whatsapp_templates as $whatsapp_template)
                                {
                                    $data.='<option value="'. $whatsapp_template->template_id .'" >'. $whatsapp_template->template_name .'
                                          </option>';
                                }
                              $data.='</select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
         <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
            <i class="fa fa-times"></i> Close
         </button>
         <button type="submit" name="submit" class="btn btn-primary btn-md">
            <i class="fa fa-check"></i> Send
         </button>
      </div>';
      echo $data;
    }

    public function whatsapp_option_submit(Request $request){

        $auth_id = Auth::id();
        $lead_id = $request->lead_id;
        $get_lead = DB::table('leads')->where('lead_id', $lead_id)->select('lead_id', 'mobile_number')->first();
        $mobile_number = $get_lead->mobile_number;
        
        $template_id = $request->template_id;
        $get_template = DB::table('com_template_whatsapp')->where('template_id', $template_id)->select('template_id', 'content')->first();
        $content = $get_template->content;

        $data = ['template_id' => $template_id, 'lead_id' => $lead_id, 'mobile_number' => $mobile_number, 'content' => $content, 'created_at' => now(), 'created_by' => $auth_id];
        $insert = DB::table('com_log_whatsapp')->insert($data);

        return redirect('leads');
    }

    public function mail_option(Request $request)
    {
      $leads_details=DB::table('leads')->where('lead_id', $request->lead_id)->first();
      $mail_templates=DB::table('com_template_mail')->get();
       
      $data='<div class="modal-body">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <input type="hidden" name="lead_id" value="'.$leads_details->lead_id.'">
                        <label class="form-group floating-label-form-group">Template  <span class="text-danger">*</span> :</label>
                          <select class="form-control border-primary select2" name="template_id" id="template_id" style="width:100%;">
                                  <option selected value="" disabled>Select</option>';
                                foreach($mail_templates as $mail_template)
                                {
                                    $data.='<option value="'. $mail_template->template_id .'" >'. $mail_template->template_name .'
                                          </option>';
                                }
                              $data.='</select>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
         <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
            <i class="fa fa-times"></i> Close
         </button>
         <button type="submit" name="submit" class="btn btn-primary btn-md">
            <i class="fa fa-check"></i> Send
         </button>
      </div>';
      echo $data;
    }

    public function mail_option_submit(Request $request){

        $auth_id = Auth::id();
        $lead_id = $request->lead_id;
        
        $template_id = $request->template_id;
        $get_template = DB::table('com_template_mail')->where('template_id', $template_id)->select('template_id', 'comments', 'email', 'subject', 'template_name')->first();
        $comments = $get_template->comments;
        $subject = $get_template->subject;
        $template_name = $get_template->template_name;
        $email = 'selvakumarvingreen@gmail.com';

        $MailContent='<center class="wrapper">
      <table class="top-panel center" width="602" border="0" cellspacing="0" cellpadding="0">
         <tbody>
            <tr>
               <td class="border" colspan="2">&nbsp;</td>
            </tr>
         </tbody>
      </table>
      <div class="spacer">&nbsp;</div>
      <table class="main center" width="602" border="0" cellspacing="0" cellpadding="0">
         <tbody>
            <tr>
               <td class="column">
                  <div class="column-top">&nbsp;</div>
                  <table class="content" border="0" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td class="padded">
                              <h1>'.$template_name.'</h1>
                              <table style="width:100%">
                                 <tr>
                                    <td><strong>Comments</strong></td><br>
                                    <td>'.$comments.'</td>
                                 </tr>
                              </table>
                              <br>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <div class="column-bottom">&nbsp;</div>
               </td>
            </tr>
         </tbody>
      </table>
   </center>';

   $result = app('App\Http\Controllers\SendMailController')->SendMailSMTP($subject,$email,$MailContent);

          $data = ['template_id' => $template_id, 'lead_id' => $lead_id, 'email' => $email, 'subject' => $subject,'comments' => $comments, 'created_at' => now(), 'created_by' => $auth_id];
        $insert = DB::table('com_log_mail')->insert($data);

        return redirect('leads');

    }

    
       
}