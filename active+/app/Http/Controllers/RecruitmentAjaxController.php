<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class RecruitmentAjaxController extends Controller
{
    public function __construct()
   {
      $this->middleware('auth');
   }

    public function recruitment_ajax(Request $request)
    {
        $RecruitmentStageId=$request->RecruitmentStageId;

        if ($RecruitmentStageId=='All')
        {
            $get_recruitment_stages= DB::table('recruitment_stages')->select('recruitment_stage_id')->where('deleted','No')->get();
            foreach($get_recruitment_stages as $get_recruitment_stage)
            {
                $storedArray[] = $get_recruitment_stage->recruitment_stage_id;
            }
            $string = implode(', ', $storedArray);
            $recruitment_stage_id =" a.recruitment_stage_id IN ($string)";
            Session::put('RecruitmentAjaxWhere',$recruitment_stage_id);
        }
        else
        {

            $recruitment_stage_id="a.recruitment_stage_id = $RecruitmentStageId ";
            Session::put('RecruitmentAjaxWhere',$recruitment_stage_id);
        }

        $GetRecruitmentStageCounts= DB::table('candidates as a')->select('a.recruitment_stage_id',DB::raw('count(a.recruitment_stage_id) as recruitment_stage_count'))->leftjoin('recruitment_stages as b','b.recruitment_stage_id', '=', 'a.recruitment_stage_id')->leftjoin('users as c','c.id', '=', 'a.created_by')->groupBy('a.recruitment_stage_id')->get();
        $RecruitmentCountArray=array();
        foreach ($GetRecruitmentStageCounts as $GetRecruitmentStageCount) 
        {
            $RecruitmentCountArray+=array($GetRecruitmentStageCount->recruitment_stage_id=>$GetRecruitmentStageCount->recruitment_stage_count);
        }

        $data='<ul id="RecruitmentTabs" class="nav nav-tabs nav-underline no-hover-bg" role="tablist">';
        $TotalRecruitmentCount=0;
        $get_recruit_stages= DB::table('recruitment_stages')->select('*')->where('deleted','No')->orderby('tab_list', 'asc')->get();
        foreach($get_recruit_stages as $recruitment_stage)
        {

            if($RecruitmentStageId==$recruitment_stage->recruitment_stage_id){ $active_1='active'; }else{ $active_1=''; }

            if($recruitment_stage->recruit_stage=='Positive'){ $color=$recruitment_stage->recruitment_stage_color; }
            else{ $color=$recruitment_stage->recruitment_stage_color; }

            if(array_key_exists($recruitment_stage->recruitment_stage_id,$RecruitmentCountArray))
            {
                $TempCount=$RecruitmentCountArray[$recruitment_stage->recruitment_stage_id]; $TotalRecruitmentCount+=$TempCount;
            }
            else
            {
                $TempCount='0';
            }

            $data.='
            <li class="nav-item active" status="'.$recruitment_stage->recruitment_stage_id.'">
            <a href="'.$recruitment_stage->recruitment_stage_id.'" class="nav-link '.$active_1.'" data-toggle="tab">
            <span class="badge badge badge-pill badge-sm mr-1" style="background-color:'.$color.';">
            '.$TempCount.'
            </span> '.$recruitment_stage->recruitment_stage.'
            </a>
            </li>';
        }

        if($RecruitmentStageId=='All'){ $active='active'; }else{ $active=''; }

        $data.='<li class="nav-item active" status="All">
        <a href="All" class="nav-link '.$active.'" data-toggle="tab">

        <span class="badge badge badge-pill badge-sm mr-1" style="background-color:blue;"> '.$TotalRecruitmentCount.'</span>All
        </a>
        </li> ';

        $data.='</ul>
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered Recruitment " style="width:100%;">
                        <thead>
                            <tr>
                                <th>Recruitment Id</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Candidate Name</th>
                                <th>Email</th>
                                <th>MobileNumber</th>
                                <th>Description</th>
                                <th>Attachment</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Recruitment Id</th>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>Candidate Name</th>
                                <th>Email</th>
                                <th>MobileNumber</th>
                                <th>Description</th>
                                <th>Attachment</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <script type="text/javascript">
                    
            $(function () {
            var table = $(".Recruitment").DataTable({
               processing: true,
               order: [[ 0, "desc" ]],
               serverSide: true,
               ajax: "recruitments",
               columns: [
               {data: "candidate_id", name: "a.candidate_id"},
               {data: "action", name: "a.action", orderable: false, searchable: false},
               {data: "candidate_name", name: "a.first_name"},
               {data: "mail_id", name: "a.mail_id"},
               {data: "mobile_number", name: "a.mobile_number"},
               {data: "description", name: "a.description"},
               {data: "attachments", name: "a.attachments"},
               {data: "created_by", name: "b.first_name"},
               {data: "created_at", name: "a.created_at"},
               {data: "updated_by", name: "c.first_name"},
               {data: "updated_at", name: "a.updated_at"},
               ]
            });
         });
        </script>';
        echo $data;
    }
}
