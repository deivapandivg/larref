<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class RecruitmentController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function candidate_view(Request $request){
        $candidate_details=DB::table('candidates')->where('candidate_id',base64_decode($request->candidate_id))->first();
        $country=DB::table('countries')->where('country_id',$candidate_details->country_id)->first();
        $state=DB::table('states')->where('state_id',$candidate_details->state_id)->first();

        $city=DB::table('cities')->where('city_id',$candidate_details->city_id)->first();
        
        $candidate_experience_details=DB::table('candidate_experience_details')->where('candidate_id',$request->candidate_id)->get();

        $candidate_attachments=DB::table('candidate_attachments')->where('candidate_id',$request->candidate_id)->get();

        $more_details_list=DB::table('candidate_ additional_details')->select('*')->where('candidate_id',$request->candidate_id)->get();

        
        $recruitment_stage_lists=DB::table('recruitment_stages')->select('*')->where('deleted', '=', 'No')->get();

        return view('recruitments.candidate_view', compact('candidate_details','country','state','city','candidate_experience_details','candidate_attachments','more_details_list','recruitment_stage_lists'));
    }
    public function recruitments(Request $request){
        if ($request->ajax()) {
             $RecruitmentAjaxWhere = Session::get('RecruitmentAjaxWhere');
            $data = DB::table('candidates as a')->whereRaw($RecruitmentAjaxWhere)->select(['a.candidate_id',
                'a.first_name as candidate_name',
                'a.mail_id',
                'a.mobile_number',
                'a.description',
                'a.attachments',
                'b.first_name as created_by',
                'a.created_at', 
                'c.first_name as updated_by', 
                'a.updated_at', 
            ])->leftjoin('users as b','b.id', '=', 'a.created_by')->leftjoin('users as c','c.id', '=', 'a.updated_by');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn ='<a  href="'.route('candidate_view',base64_encode($row->candidate_id)).'" class="vg-btn-ssp-success ViewCampaignModal text-center" data-toggle="tooltip" data-placement="right" title="View" data-original-title="View"><i class="fa fa-eye text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a href="'.route('candidate_edit',base64_encode($row->candidate_id)).'" class="vg-btn-ssp-primary edit_model_btn text-center" data-toggle="tooltip" data-placement="right" title="Edit" data-original-title="Edit"><i class="fa fa-edit  text-white text-center"></i></a>';
                $btn.='&nbsp;&nbsp;&nbsp;<a class="vg-btn-ssp-danger DeleteDataModal text-center" data-toggle="tooltip" data-placement="right" title="delete" data-original-title="delete"><i class="fa fa-trash text-white text-center"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('recruitments.recruitments');
    }

    public function recruitment_submit(Request $request){
        $createdby = Auth::id();
        $updatedby = Auth::id();

       
        if(isset($request->candidate_id))
        {
            if(isset($request->exisiting_images_id))
            {
                $OldFilesArr=$request->exisiting_images_id;
                $DeleteFilesItems=DB::table('candidate_attachments')->where('candidate_id',$request->candidate_id)->whereNotIn('candidate_attachment_id', $OldFilesArr)->delete();
                $old_attachements_name=$request->exisiting_attachment_name;
            }
            
                for ($i=0; $i<count($request->exisiting_images_id); $i++) {

                    DB::table('candidate_attachments')
                        ->where('candidate_attachment_id',$request->exisiting_images_id[$i])
                        ->update([
                            'attachment_name' => $request->exisiting_attachment_name[$i],
                    ]);
                }


            if(isset($request->exisiting_exp_id))
            {
                $old_exp_id_arr=$request->exisiting_exp_id;
                $delete_exp=DB::table('candidate_experience_details')->where('candidate_id',$request->candidate_id)->whereNotIn('experience_detail_id', $old_exp_id_arr)->delete();

            }
           
                for ($i=0; $i<count($request->exisiting_exp_id); $i++) {

                    DB::table('candidate_experience_details')
                        ->where('experience_detail_id',$request->exisiting_exp_id[$i])
                        ->update([
                            'company_name' => $request->exisiting_company_name[$i],
                            'from_year' => $request->exisiting_from_year[$i],
                            'to_year' => $request->exisiting_to_year[$i],
                            'year_of_exp' => $request->exisiting_experience_years[$i]
                    ]);
                } 

                
           
                $files= [];
                if(!is_null($request->file('attachment'))){
                    $attachment_names=$request->attachment_name;
                    foreach ($request->file('attachment') as $file) {
                        $name = time() . rand(1, 100) . '.' . $file->extension();
                        $file->move(public_path('recruitmentattachment'), $name);
                        $files[] = $name;
                    } 
                }

            
            $data = array('candidate_id' => $request->candidate_id, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'mail_id' => $request->mail_id, 'mobile_number' => $request->mobile_number, 'description' => $request->description,'updated_by' => $createdby, 'updated_at' => Now());

            $department_update=DB::table('candidates')->where('candidate_id',$request->candidate_id)->update($data);
            
                for ($i = 0; $i < count($files); $i++) {
                    $attachments = $files[$i];
                    $attachment_name = $attachment_names[$i];

                    $CandidateAttachAdd = array('candidate_id' => $request->candidate_id,'attachment_name' => $attachment_name, 'attachment' => $attachments,'created_by' => $createdby, 'created_at' => Now());
                    $candidate_attach_add=DB::table('candidate_attachments')->insert($CandidateAttachAdd);
                }

        }
        else  
        {
            $files= [];
            $attachment_names=$request->attachment_name;
            $company_name_arr=$request->company_name;
            $from_year_arr=$request->from_year;
            $to_year_arr=$request->to_year;
            $experience_years_arr=$request->experience_years;
            $heading_arr=$request->heading;
            $detail_arr=$request->detail;


            foreach ($request->file('attachment') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('recruitmentattachment'), $name);
                $files[] = $name;
            }

            $data = array( 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'mail_id' => $request->mail_id, 'mobile_number' => $request->mobile_number, 'dob' => $request->dob,'job_role' => $request->job_role, 'country_id' => $request->country_id,'state_id' => $request->state_id,'city_id' => $request->city_id,'educational_qualification' => $request->educational_qualification,'year_of_exprience' => $request->year_of_exprience,'year_of_passing' => $request->year_of_passing,'description' => $request->description,'pincode' => $request->pincode,'gender' => $request->gender,'created_by' => $createdby, 'created_at' => Now());

            $candidate_add=DB::table('candidates')->insertGetId($data);
            for ($i = 0; $i < count($files); $i++) {
                $attachments = $files[$i];
                $attachment_name = $attachment_names[$i];

                $CandidateAttachAdd = array('candidate_id' => $candidate_add,'attachment_name' => $attachment_name, 'attachment' => $attachments,'created_by' => $createdby, 'created_at' => Now());
                $candidate_attach_add=DB::table('candidate_attachments')->insert($CandidateAttachAdd);
            }

            for ($i=0; $i < count($company_name_arr); $i++) { 
                $company_name = $company_name_arr[$i];
                $from_year = $from_year_arr[$i];
                $to_year = $to_year_arr[$i];
                $experience_years = $experience_years_arr[$i];
                $exp_data = array('candidate_id' => $candidate_add,'company_name' => $company_name, 'from_year' => $from_year,'to_year' => $to_year, 'year_of_exp' => $experience_years);
                $exp_details=DB::table('candidate_experience_details')->insert($exp_data);
            }

            for ($i=0; $i < count($heading_arr); $i++){
                $heading = $heading_arr[$i];
                $detail = $detail_arr[$i];
                $add_more_details= array('candidate_id' => $candidate_add, 'heading' => $heading, 'detail' => $detail, 'created_by' => $createdby, 'created_at' => Now());
                $add_more_detail=DB::table('candidate_ additional_details')->insert($add_more_details);
            }
        }
        return redirect('recruitments');
    }
    public function candidate_add(){
        $country_lists=DB::table('countries')->select('*')->where('deleted', '=', 'No')->get();
        return view('recruitments.candidate_add',compact('country_lists'));
    }

    public function candidate_edit(Request $request){

        $candidate_details=DB::table('candidates')->where('candidate_id',base64_decode($request->candidate_id))->first();

        $candidate_experience_details=DB::table('candidate_experience_details')->where('candidate_id',$candidate_details->candidate_id)->get();

        $country_lists=DB::table('countries')->select('*')->get();

        $state_lists=DB::table('states')->select('*')->where('country_id',$candidate_details->country_id)->get();

        $city_lists=DB::table('cities')->select('*')->where('state_id',$candidate_details->state_id)->get();

        $attachments=DB::table('candidate_attachments')->select('*')->where('candidate_id',$candidate_details->candidate_id)->get();

        $add_more_details=DB::table('candidate_ additional_details')->select('*')->where('candidate_id',$candidate_details->candidate_id)->get();

        return view('recruitments.candidate_edit', compact('candidate_details','country_lists','state_lists','city_lists','attachments','candidate_experience_details','add_more_details'));
    }

     public function recruitment_process_submit(Request $request){
        $created_by = Auth::id();
        $candidate_id = $request->candidate_id;
        $recruit_stage_id = $request->recruit_stage_id;
        $rating = $request->rating;
        $description = $request->description;
        $recruitment_process_add = array('candidate_id' => $candidate_id, 'recruitment_stage_id' => $recruit_stage_id,'rating' => $rating, 'description' => $description, 'created_by' => $created_by, 'created_at' => now());
        $recruitment_insert_query = DB::table('recruitment_process')->insert($recruitment_process_add);
        $data= array('recruitment_stage_id' => $recruit_stage_id,'interviewer_id' => $created_by, 'updated_at' => now());
        $recruitment_stage_update=DB::table('candidates')->where('candidate_id',$request->candidate_id)->update($data);
        return redirect('recruitments');
    }
}
