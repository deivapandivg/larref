<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use DB;
use Redirect, Response, Session;

class LeadsAjaxController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

     public function leads_ajax(Request $request)
    {
        $LeadStatusId=$request->LeadStatusId;
        if ($LeadStatusId=='All')
        {
            $get_all_lead_status= DB::table('lead_stages')->select('lead_stage_id')->where('deleted','No')->get();
            foreach($get_all_lead_status as $all_lead_status)
            {
                $storedArray[] = $all_lead_status->lead_stage_id;
            }
            $string = implode(', ', $storedArray);
            $where =" a.lead_stage_id IN ($string)";
            Session::put('LeadAjaxWhere',$where);
        }
        else
        {
           
            $where="a.lead_stage_id = $LeadStatusId";
            Session::put('LeadAjaxWhere',$where);
        }



        $GetLeadStatusCounts= DB::table('leads as a')->select('a.lead_stage_id',DB::raw('count(a.lead_stage_id) as lead_status_count'))->leftjoin('lead_stages as b','b.lead_stage_id', '=', 'a.lead_stage_id')->leftjoin('lead_sources as c','c.lead_source_id', '=', 'a.source_id')->leftjoin('users as d','d.id', '=', 'a.lead_owner')->groupBy('a.lead_stage_id')->get();
        $LeadStatusCountArray=array();
        foreach ($GetLeadStatusCounts as $GetLeadStatusCount) 
        {
            $LeadStatusCountArray+=array($GetLeadStatusCount->lead_stage_id=>$GetLeadStatusCount->lead_status_count);
        }

        $data='';
        $data.='<ul id="LeadStatusTabs" class="nav nav-tabs nav-underline no-hover-bg" role="tablist">';
            $TotalLeadCount=0;
            $get_leads_stages= DB::table('lead_stages')->select('*')->where('deleted','No')->orderby('lead_stage_id')->get();
            foreach($get_leads_stages as $leads_stage)
            {

                if($LeadStatusId==$leads_stage->lead_stage_id){ $active_1='active'; }else{ $active_1=''; }

                if($leads_stage->lead_stage=='Positive'){ $color=$leads_stage->lead_stage_color; }
                    else{ $color=$leads_stage->lead_stage_color; }

                if(array_key_exists($leads_stage->lead_stage_id,$LeadStatusCountArray))
                {
                    $TempCount=$LeadStatusCountArray[$leads_stage->lead_stage_id]; $TotalLeadCount+=$TempCount;
                }
                else
                {
                    $TempCount='0';
                }

                $data.='
                    <li class="nav-item active" status="'.$leads_stage->lead_stage_id.'">
                        <a href="'.$leads_stage->lead_stage_id.'" class="nav-link '.$active_1.'" data-toggle="tab">
                            <span class="badge badge badge-pill badge-sm mr-1" style="background-color:'.$color.';">
                               '.$TempCount.'
                            </span> '.$leads_stage->lead_stage_name.'
                        </a>
                    </li>';
            }

            if($LeadStatusId=='All'){ $active='active'; }else{ $active=''; }
            $data.='<li class="nav-item active" status="All">
                <a href="All" class="nav-link '.$active.'" data-toggle="tab">
                    <span class="badge badge badge-pill badge-sm mr-1" style="background-color:blue;"> '.$TotalLeadCount.'</span>All
                </a>
            </li> ';
        $data.='</ul>
         <div class="card-content">
                  <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-bordered  LeadsTable" style="width:100%;">
                           <thead>
                               <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>alter_mobile_number</th>
                                   <th>Email</th>
                                   <th>alter_email_id</th>
                                   <th>age</th>
                                   <th>medium_id</th>
                                   <th>source_id</th>
                                   <th>sub_source_id</th>
                                   <th>campaign_id</th>
                                   <th>lead_owner</th>
                                   <th>ad_name_id</th>
                                   <th>product_category_id</th>
                                   <th>product_id</th>
                                   <th>country_id</th>
                                   <th>state_id</th>
                                   <th>city_id</th>
                                   <th>pincode</th>
                                   <th>address</th>
                                   <th>Created By</th>
                                   <th>Created At</th>
                                   <th>Updated By</th>
                                   <th>Updated At</th>
                               </tr>
                           </thead>
                           <tfoot>
                              <tr>
                                   <th>Id</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                   <th>Lead Name</th>
                                   <th>Mobile Number</th>
                                   <th>alter_mobile_number</th>
                                   <th>Email</th>
                                   <th>alter_email_id</th>
                                   <th>age</th>
                                   <th>medium_id</th>
                                   <th>source_id</th>
                                   <th>sub_source_id</th>
                                   <th>campaign_id</th>
                                   <th>lead_owner</th>
                                   <th>ad_name_id</th>
                                   <th>product_category_id</th>
                                   <th>product_id</th>
                                   <th>country_id</th>
                                   <th>state_id</th>
                                   <th>city_id</th>
                                   <th>pincode</th>
                                   <th>address</th>
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

               $(document).ready(function(){
                LoadTableData();
                });
                $(document).on("change", ".AllDate,#from_date,#to_date", function(){
                    var True=$("#AllDate").prop("checked");
                    if(True==true)
                    {
                        $(".DateFilters").addClass("hidden");
                    }
                    else
                    {
                        $(".DateFilters").removeClass("hidden");
                    }
                    LoadTableData(True);
                    });

                    function LoadTableData() {

                        var True=$("#AllDate").prop("checked");
                        if(True==true)
                        {
                            var all_date="yes";
                            var from_date=null;
                            var to_date=null;
                        }
                        else
                        {
                            var all_date="no";
                            var from_date=$("#from_date").val();
                            var to_date=$("#to_date").val();
                        }
                        var table = $(".LeadsTable").DataTable({
                            processing: true,
                            serverSide: true,
                            destroy: true,
                            order: [[0, "desc"]],
                            ajax: {
                                url: ("leads") ,
                                data: function(data) { data.all_date = all_date; data.from_date=from_date;data.to_date=to_date;},
                                },
                                columns: [
                                {data: "lead_id", name: "a.lead_id"},
                                {data: "action", name: "action", orderable: false, searchable: false},
                                {data: "lead_name", name: "a.lead_name"},
                                {data: "mobile_number", name: "a.mobile_number"},
                                {data: "alter_mobile_number", name: "a.alter_mobile_number"},
                                {data: "email_id", name: "a.email_id"},
                                {data: "alter_email_id", name: "a.alter_email_id"},
                                {data: "age", name: "a.age"},
                                {data: "medium_name", name: "d.medium_name"},
                                {data: "lead_source_name", name: "e.lead_source_name"},
                                {data: "lead_sub_source_name", name: "f.lead_sub_source_name"},
                                {data: "campaign_name", name: "g.campaign_name"},
                                {data: "lead_owner", name: "h.first_name"},
                                {data: "ad_name", name: "i.ad_name"},
                                {data: "product_category_name", name: "j.product_category_name"},
                                {data: "product_name", name: "k.product_name"},
                                {data: "country_name", name: "l.country_name"},
                                {data: "state_name", name: "m.state_name"},
                                {data: "city_name", name: "n.city_name"},
                                {data: "pincode", name: "a.pincode"},
                                {data: "address", name: "a.address"},
                                {data: "created_by", name: "b.first_name"},
                                {data: "created_at", name: "a.created_at"},
                                {data: "updated_by", name: "c.first_name"},
                                {data: "updated_at", name: "a.updated_at"},
                                ]
                                });
                            }
                            </script>';
                echo $data;
    }

     public function users_get(Request $request)
    {
    


    }

}
