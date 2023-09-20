<x-app-layout>
    <section id="tabs-with-icons">
      <div class="row match-height">
         <div class="col-xl-12 col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active ">
                        </li>
                     </ol>
                  </h4>
                  @php
                  $user_id=Auth::user()->id;
                  $today_date=date('Y-m-d H:i:s');
                  @endphp 
                @if($attendance_details_count > 0 )
                  @if($attendance_details->user_id!=$user_id OR $attendance_details->type=='checkout')
                      <div class="heading-elements">
                         <a>
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                               <i class="fa fa-plus"></i> Checkin
                            </button>
                         </a>
                      </div>
                      @elseif($attendance_details->type=='checkin')
                      <div class="heading-elements checkout">
                         <a>
                            <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#CheckoutModal">
                               <i class="fa fa-minus"></i> Checkout
                            </button>
                         </a>
                      </div>
                      @endif
                      @else
                       <div class="heading-elements">
                         <a>
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
                               <i class="fa fa-plus"></i> Checkin
                            </button>
                         </a>
                      </div>
                @endif
               </div>
               <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-12">
                                <a href="{{ route('tasks') }}">
                                    <div class="card bg-gradient-x-orange-yellow">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body text-white text-left align-self-bottom mt-2">
                                                        <span class="d-block mb-1 font-medium-1">Pending Tasks</span>
                                                        <h2 class="text-white mb-0">{{ $pending_tasks_count }}
                                                        </h2>
                                                    </div>
                                                    <div class="align-self-top">
                                                        <i class="icon-earphones icon-opacity text-white font-large-3 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12">
                                <a href="{{ route('tickets') }}">
                                    <div class="card bg-gradient-x-purple-blue">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body text-white text-left align-self-bottom mt-2">
                                                        <span class="d-block mb-1 font-medium-1">Latest Tickets</span>
                                                        <h2 class="text-white mb-0">{{ $latest_tickets_count }}
                                                        </h2>
                                                    </div>
                                                    <div class="align-self-top">
                                                        <i class="icon-earphones icon-opacity text-white font-large-3 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12">
                                <a href="{{ route('leads') }}">
                                    <div class="card bg-gradient-x-blue-green">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body text-white text-left align-self-bottom mt-2">
                                                        <span class="d-block mb-1 font-medium-1">Hot Leads</span>
                                                        <h2 class="text-white mb-0">{{ $leads_count }}
                                                        </h2>
                                                    </div>
                                                    <div class="align-self-top">
                                                        <i class="icon-earphones icon-opacity text-white font-large-3 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-12">
                                <a href="{{ route('timelines') }}">
                                    <div class="card bg-gradient-x-blue-green">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body text-white text-left align-self-bottom mt-2">
                                                        <span class="d-block mb-1 font-medium-1">Timelines</span>
                                                        <h2 class="text-white mb-0">{{ $timelines_count }}
                                                        </h2>
                                                    </div>
                                                    <div class="align-self-top">
                                                        <i class="icon-earphones icon-opacity text-white font-large-3 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
         </div>
      </div>
    </section>
    
    <section id="chartist-bar-charts">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Your Last 7 Days Timelines & Lead Conversions</h4>
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
                                <canvas id="line-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text modal-md" id="AddModal" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" >
                    <h3 class="modal-title text-center text-white" id="myModalLabel35"> Checkin</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('attendance') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="checkin">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group floating-label-form-group">
                                    <b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <textarea class="form-control" required id="title1" rows="3" name="description" placeholder="Description" required></textarea>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group floating-label-form-group">
                                    <b>Attachment : </b>
                                    <input type="file" class="form-control border-primary" name="attachment">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn-success ">
                            <i class="fa fa-check"></i> Checkin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text modal-md" id="CheckoutModal" role="dialog" aria-labelledby="myModalLabel35" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" >
                    <h3 class="modal-title text-center text-white" id="myModalLabel35"> Checkout</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('attendance') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="checkout">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="form-group floating-label-form-group">
                                    <b>Description <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
                                    <textarea class="form-control" required id="title1" rows="3" name="description" placeholder="Description" required></textarea>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group floating-label-form-group">
                                    <b>Attachment : </b>
                                    <input type="file" class="form-control border-primary" name="attachment">
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn-danger ">
                            <i class="fa fa-minus"></i> Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="page_level_scripts">
        <script src="{{ asset('public/assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
            <!-- <script src="{{ asset('public/app-assets/js/scripts/charts/chartjs/line/line.js') }}" type="text/javascript"></script> -->
            <script type="text/javascript">
                $(window).on("load", function(){

            //Get the context of the Chart canvas element we want to select
            var ctx = $("#line-chart");

            // Chart Options
            var chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                },
                hover: {
                    mode: 'label'
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
                            labelString: 'Date'
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
                            labelString: 'Count'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Last 7 Days Performance'
                }
            };

            $.post("{{ route('dashboard_chart_counts') }}",{"_token":"{{ csrf_token() }}"},function(data){
                console.log(data);
                console.log(data.date_lables);
                var line_labels=data.date_lables;
                    // var line_labels=["2022-10-01", "2022-10-02", "2022-10-03", "2022-10-04", "2022-10-05", "2022-10-06", "2022-10-07"];
                    var lead_conversion_counts=data.lead_conversion_counts;
                    // var lead_conversion_counts=["10","15","13","18","30","24","55"];
                    var timeline_counts=data.timeline_counts;
                    // var timeline_counts=["17","25","33","10","20","24","35"];
                    var chartData = {
                        labels: line_labels,
                        datasets: [
                // {
                //     label: "My First dataset",
                //     data: [65, 59, 80, 81, 56, 55, 40],
                //     fill: false,
                //     borderDash: [5, 5],
                //     borderColor: "#9C27B0",
                //     pointBorderColor: "#9C27B0",
                //     pointBackgroundColor: "#FFF",
                //     pointBorderWidth: 2,
                //     pointHoverBorderWidth: 2,
                //     pointRadius: 4,
                // }, 
                {
                    label: "Last 7 Days Timelines",
                    data: timeline_counts,
                    fill: false,
                    // borderDash: [5, 5],
                    borderColor: "#00A5A8",
                    pointBorderColor: "#00A5A8",
                    pointBackgroundColor: "#FFF",
                    pointBorderWidth: 5,
                    pointHoverBorderWidth: 5,
                    pointRadius: 4,
                 }, {
                    
                    label: "Last 7 Days Converted Leads",
                    data: lead_conversion_counts,
                    lineTension: 0,
                    fill: false,
                    borderColor: "#FF7D4D",
                    pointBorderColor: "#FF7D4D",
                    pointBackgroundColor: "#FFF",
                    pointBorderWidth: 5,
                    pointHoverBorderWidth: 5,
                    pointRadius: 4,
                 }]
              };

              var config = {
                type: 'line',

                // Chart Options
                options : chartOptions,

                data : chartData
             };

            // Create the chart
            var lineChart = new Chart(ctx, config);
         });

            // Chart Data
            
         });
        </script>
    </x-slot>
</x-app-layout>
