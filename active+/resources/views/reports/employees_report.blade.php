<x-app-layout>
   <style type="text/css">

   </style>
   <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Employees Report
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>

                  </h4>
               </div>
               <div class="row mr-1 ml-1">
                  <div class="col-lg-3">
                     <div class="form-group">
                        <label class="label-control" for="department_id">Employee :</label>
                        <select class="form-control border-primary select2 form-select" name="user" data-placeholder="Choose one" id="user" style="width:100%;" required>
                           <option selected>All Employees</option>
                           @foreach ($user_lists as $user_list)
                           <option value="{{  $user_list->id }}">{{ $user_list->first_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-3">
                     <div class="form-group">
                        <label class="label-control" for="period_id">Report Period :</label>
                        <select class="form-control border-primary select2 form-select" name="period_id" data-placeholder="Choose one" id="period_id" style="width:100%;" required>
                           <option selected>Select Period</option>
                           <option value="1">Last N Day</option>
                           <option value="2">Last N Week</option>
                           <option value="3">Last N Month</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-4">
                     <div class="form-group">
                        <label class="label-control" for="userinput4">Number of Days / Weeks / Months :</label>
                        <input type="number" class="form-control" name="nth_day" id="nth_day" placeholder="Eg: 1 " required>
                     </div>
                  </div>
                  <div class="col-md-1">
                     <div class="form-group">
                        <label class="label-control" for="search">Search&nbsp;:</label><br>
                        <i class="fa fa-search fa-2x text-primary" style="margin-top:5px;margin-left: 9px; font-size:33px;" aria-hidden="true" id="search"></i> 
                     </div>
                  </div>
               </div>
            </div>
            <div class="card">
               <div class="card-content">
                  <div class="card-body">
                     <section id="chartist-bar-charts">
                        <div class="row">
                           <div class="col-12">
                              <div class="card">
                                 <div class="card-header">
                                    <h4 class="card-title">Leads & Timeline Report</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                       <ul class="list-inline mb-0">
                                          <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                          <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                          <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                          <li><a data-action="close"><i class="ft-x"></i></a></li>
                                       </ul>
                                    </div>
                                 </div>
                                 <div class="card-content collapse show">
                                    <div class="card-body chartjs">
                                       <div class="height-500">
                                          <canvas id="column-chart"></canvas>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </section>
                     <section>
                        <div class="AjaxData hidden" id="AjaxData">
                           <div class="row">
                              <div class="table-responsive">
                                 <table class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                       <tr align="center">
                                          <th></th>
                                          <th>Timeline</th>
                                          <th>Lead Conversion</th>
                                       </tr>
                                       
                                 </thead>
                                 
                                 <tbody id="date">
                                    
                                 </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </section>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <x-slot name="page_level_scripts">
      <script src="{{ asset('public/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
      <script type="text/javascript">
         $(document).ready(function(){

            //Get the context of the Chart canvas element we want to select
            var ctx = $("#column-chart");

             // Chart Options
             var chartOptions = {
              // Elements options apply to all of the options unless overridden in a dataset
              // In this case, we are setting the border of each bar to be 2px wide and green
              elements: {
                  rectangle: {
                      borderWidth: 2,
                      borderColor: 'rgb(0, 255, 0)',
                      borderSkipped: 'bottom'
                  }
              },
              responsive: true,
              maintainAspectRatio: false,
              responsiveAnimationDuration:500,
              legend: {
                  position: 'top',
              },
              scales: {
                  xAxes: [{
                      display: true,
                      gridLines: {
                          color: "#f3f3f3",
                          drawTicks: false,
                      },
                      scaleLabel: {
                          display: true,
                      }
                  }],
                  yAxes: [{
                      display: true,
                      gridLines: {
                          color: "#f3f3f3",
                          drawTicks: false,
                      },
                      scaleLabel: {
                          display: true,
                      }
                  }]
              },
              title: {
                  display: true,
                  text: 'Employees Individual Lead & Timeline Performance'
              }
            };

            $.post("{{ route('report_chart_counts') }}",{"_token":"{{ csrf_token() }}"},function(data){
              
               var line_labels=data.date_lables;
               // var line_labels=["2022-10-01", "2022-10-02", "2022-10-03", "2022-10-04", "2022-10-05", "2022-10-06", "2022-10-07"];

               var timeline_counts=data.timeline_counts;
               
               var lead_conversion_counts=data.lead_conversion_counts;
               
               
               var chartData = {
                     labels: line_labels,
                     datasets: [
                 
                     {
                     
                        label: "Completed Timelines",
                        data: timeline_counts,
                        backgroundColor: "#28D094",
                        hoverBackgroundColor: "rgba(40,208,148,.9)",
                        borderColor: "transparent"
                     },
                     {
                     
                        label: "Completed Lead Conversions",
                        data: lead_conversion_counts,
                        backgroundColor: "#1E9FF2",
                        hoverBackgroundColor: "rgba(30,159,242,.9)",
                        borderColor: "transparent"
                     }]
                  };
   

               var config = {
                 type: 'bar',

                 // Chart Options
                 options : chartOptions,

                 data : chartData
               };

               // Create the chart
               var lineChart = new Chart(ctx, config);

            });
         });
         
         $("#search").on("click", function(){

             //Get the context of the Chart canvas element we want to select
               var ctxLine = document.getElementById("column-chart").getContext("2d");
             
             // Chart Options
                var chartOptions = {
                 // Elements options apply to all of the options unless overridden in a dataset
                 // In this case, we are setting the border of each bar to be 2px wide and green
                 elements: {
                     rectangle: {
                         borderWidth: 2,
                         borderColor: 'rgb(0, 255, 0)',
                         borderSkipped: 'bottom'
                     }
                 },
                 responsive: true,
                 maintainAspectRatio: false,
                 responsiveAnimationDuration:500,
                 legend: {
                     position: 'top',
                 },
                 scales: {
                     xAxes: [{
                         display: true,
                         gridLines: {
                             color: "#f3f3f3",
                             drawTicks: false,
                         },
                         scaleLabel: {
                             display: true,
                         }
                     }],
                     yAxes: [{
                         display: true,
                         gridLines: {
                             color: "#f3f3f3",
                             drawTicks: false,
                         },
                         scaleLabel: {
                             display: true,
                         }
                     }]
                 },
                 title: {
                     display: true,
                     text: 'Employees Individual Lead & Timeline ToDo Performance'
                 }
             };
          
             var user = $("#user").val();
             var nth_day = $("#nth_day").val();
             var period_id = $("#period_id").val();
          
             $.post("{{ route('report_chart_counts') }}",{"_token":"{{ csrf_token() }}","user":user,"nth_day":nth_day,"period_id":period_id},function(data){
               console.log(data);
               console.log(data.date_lables);
               $("#date").empty();
               if(data != undefined){
                  var table_labels=data.date_lables;
                  var table_timeline_counts=data.timeline_counts;
                  var table_lead_conversion_counts=data.lead_conversion_counts;

                  $("#AjaxData").removeClass('hidden');
                  for(var i=0,j=0,k=0;i<table_labels.length,j<table_timeline_counts.length,k<table_lead_conversion_counts.length;i=i+1,j=j+1,k=k+1){

                     $("#date").append("<tr><th>"+table_labels[i]+"</th><td align='center'>"+table_timeline_counts[j]+"</td><td align='center'>"+table_lead_conversion_counts[k]+"</td></tr>");
                     
                  }
                  
               }
               else{
                  $("#AjaxData").addClass('hidden');
               }
               var line_labels=data.date_lables;
                  // var line_labels=["2022-10-01", "2022-10-02", "2022-10-03", "2022-10-04", "2022-10-05", "2022-10-06", "2022-10-07"];
                  
                  var timeline_counts=data.timeline_counts;

                  var lead_conversion_counts=data.lead_conversion_counts;

                  var chartData = {
                     labels: line_labels,
                     datasets: [
                 
                     {
                     
                        label: "Completed Timelines",
                        data: timeline_counts,
                        backgroundColor: "#28D094",
                        hoverBackgroundColor: "rgba(40,208,148,.9)",
                        borderColor: "transparent"
                     },
                     {
                     
                        label: "Completed Lead Conversions",
                        data: lead_conversion_counts,
                        backgroundColor: "#1E9FF2",
                        hoverBackgroundColor: "rgba(30,159,242,.9)",
                        borderColor: "transparent"
                     }]
                  };
      

                  var config = {
                    type: 'bar',

                    // Chart Options
                    options : chartOptions,

                    data : chartData
                  };

                  // Create the chart
                  if(window.bar != undefined)
                     window.bar.destroy();
                     window.bar = new Chart(ctxLine, config);
                  data.abort();
                  });
         });


         

      </script>
      <style type="text/css">
         #search:hover{
            cursor:pointer;
         }
      </style>
   </x-slot>
</x-app-layout>