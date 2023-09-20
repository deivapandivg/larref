<x-app-layout>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/calendars/fullcalendar.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/calendars/fullcalendar.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/bootstrap-extended.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/colors.css') }}">
	<section id="tabs-with-icons">
		<div class="row match-height">
			<div class="col-xl-12 col-lg-12">
				<div class="card PageBrandingBacground">
					<div class="card-header">
						<div class="row">
							<div class="row">
								<div class="col-md-12">
									<h4 class="card-title ml-1">Calendar
										<ol class="breadcrumb mt-0">
											<li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
											</li>
										</ol>
									</h4>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div id='fc-default'>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" id="CalendarModel"   role="dialog" aria-labelledby="TimelineModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content modal-scroll">
				<div id="CalendarDate">
				</div>
				<form method="post" action="{{ route('reminder_followup_submit') }}" enctype="multipart/form-data">
				@csrf
                    <div class="row" id="GetFollowAjaxYes">
                    </div>
                </form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="Task_modal"  role="dialog" aria-labelledby="Task_modal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
         <div class="modal-content">
            <!-- <section class="contact-form">
               <form method="post" action="{{ route('remainder_task_submit')}}" enctype="multipart/form-data">
                  @csrf
                  <div id="TaskModalData"></div>
               </form>
            </section> -->
         </div>
      </div>
   	</div>
   	@php $CalendarDataArr=array(); @endphp
	@foreach($reminder_lists as $reminder_list)
		@php
			$TodayDate=date("Y-m-d H:i");
				$task_status = $reminder_list->task_status;
				$task_at = $reminder_list->task_at;
				$task_completed_at = $reminder_list->task_completed_at;
				$communication_medium_id = $reminder_list->communication_medium_id;
				$reminder_id = $reminder_list->reminder_id;
			@endphp
				@if($task_status==1)
			
				@if($TodayDate>$task_at)
					@php $Color="Red"; @endphp
				@else
					@php $Color="Blue"; @endphp
				@endif 
			@elseif($task_status==2) 
			
				@if($task_at>$task_completed_at)
					@php $Color="Green"; @endphp
				@else
					@php $Color="Orange"; @endphp
				@endif
			@endif

			@php
			$CalendarData="{title:  '".$reminder_list->communication_medium_id." Task ReminderId:".$reminder_list->reminder_id."',  start: '".$reminder_list->task_at."',color:'".$Color."'}";
			array_push($CalendarDataArr, $CalendarData);
			@endphp
	@endforeach	
	@php
	$CalendarDatas=implode(",", $CalendarDataArr);
	@endphp
	<style type="text/css">
		.fc-more-popover .fc-event-container {
		    overflow-y: scroll;
		    max-height:250px;
		}
	</style>
	<x-slot name="page_level_scripts">
		<script src="{{ asset('public/app-assets/vendors/js/extensions/moment.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/app-assets/vendors/js/extensions/fullcalendar.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('public/app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#fc-default').fullCalendar({
					defaultDate: "{{date('Y-m-d')}}",
					editable: true,
			      eventLimit: true, 
			      events: [
			       	{!! $CalendarDatas !!}
			      ]
			   });
			});

			$(document).on('click',".fc-day-grid-event", function(){
				var content=$(this).closest("a").find(".fc-title").html();
				$.post("{{ route('calendar_model_ajax') }}",{_token:"{{ csrf_token() }}",content:content}, function(data){
					$("#CalendarDate").html(data);
					$("#CalendarModel").modal("show");
					$("#CalendarDate .select2").select2();
				});
			});
		</script>
		<script type="text/javascript">
			$(document).on('click', '.DoFollowUp', function(){
					var reminder=$(this).val();
	            $.ajax({
               	url:  "{{ route('calendar_append',"") }}/"+reminder,
	               type: "post",
	               data : {"_token":"{{ csrf_token() }}"},
	               success:function(data) {
	                  $("#GetFollowAjaxYes").html(data);
	                  $(".select2").select2();
	               }
	            });
         	});

         	$(document).on('change', '#communication_medium', function(){
            	var communication_medium=$(this).val();
	            $.post(" {{route('communication_type_ajax_task')}}",{_token :"{{ csrf_token() }}",communication_medium:communication_medium},function(data){
	               $("#communication_type").html(data);
	            });
         	});

         	$(document).on('change', '#communication_mediumid', function(){
            var communication_id=$(this).val();
            $.post(" {{route('communication_type_ajax')}}",{_token :"{{ csrf_token() }}",communication_id:communication_id},function(data){
               $("#communication_type_id").html(data);
            });
         });

         	$(document).on('change', '#lead_stages_id', function(){
            	var lead_stage_id=$(this).val();
            	$.post(" {{route('lead_substage_ajax')}}",{"_token":"{{ csrf_token() }}","lead_stage_id":lead_stage_id},function(data){
               		$("#lead_sub_stages_id").html(data);
            	});
         	});

         	 $(document).on('click', '#add', function(e) {
            $('#ImageTBodyAdd').append('<tr class="add_row"><td><input  name="Image[]" type="file" multiple /></td><td class="text-center"><button type="button" class="btn btn-danger btn-sm" id="delete" title="Delete file"><i class="fa fa-trash"></i></button></td><tr>');
            e.preventDefault();
         });
         // Delete row In Add Form
         $(document).on('click', "#delete", function(e) {
            if (!confirm("Are you sure you want to remove this file?"))
               return false;
            $(this).closest('tr').remove();
            e.preventDefault();
         });

         $(document).on('change','.lead_stage_id', function(){
         	var lead_stage=$(this).val();
	         if(lead_stage==2){
	            var RadioButton="Enable";
	            $('#disable').attr('disabled','disabled');
	            $("#enable").prop('checked', true);
	         }
	         else{
	            $('#disable').removeAttr('disabled');
	            $('#disable').prop('checked',true);
	            $("#enable").prop('checked', false);
	            // $('#enable').removeattr('checked');

	         }
         	$.ajax({
           		url: "{{ route('calendar_task_add',"") }}/"+RadioButton,
            	type: "post",
            	data : {"_token":"{{ csrf_token() }}"},
            	success:function(data) {
               	$("#GetTaskAjax").html(data);
               	$(".select2").select2();
            	}
         	});
      	});

          $(document).on('click', '.Checkbutton', function(){
            var RadioButton=$(this).val();
            $.ajax({
              url: "{{ route('calendar_task_add',"") }}/"+RadioButton,
               type: "post",
               data : {"_token":"{{ csrf_token() }}"},
               success:function(data) {
                  $("#GetTaskAjax").html(data);
                  $(".select2").select2();
               }
            });
         });
		</script>
	</x-slot>
</x-app-layout>