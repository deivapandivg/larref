<x-app-layout>
	<section id="tabs-with-icons">
      <div class="row">
         <div class="col-xl-12 col-lg-12 col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Attendance View
                     <ol class="breadcrumb mt-0">
                        <li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
                        </li>
                     </ol>
                  </h4>
               </div>   
               <div class="card-body">
                  <form class="form">
                     @csrf
                     <div class="row">
                     	@foreach($attendance_details as $attendance_detail)
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Employee Name :</label>
                                 @foreach($user_details as $user_detail)
                                    @if($attendance_detail->user_id==$user_detail->id)
                     				<p value="{{$attendance_detail->user_id}}">{{ $user_detail->first_name }}</p>
                                    @endif
                                 @endforeach
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label for="account-name">Attendance Type :</label>
                     				<p>{{ $attendance_detail->type }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Attendance At :</label>
                     				<p>{{ $attendance_detail->attendance_at }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Description :</label>
                     				<p>{{ $attendance_detail->description }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Created By :</label>
                     				@foreach($user_details as $user_detail)
                                    @if($attendance_detail->created_by==$user_detail->id)
                                 <p value="{{$attendance_detail->created_by}}">{{ $user_detail->first_name }}</p>
                                    @endif
                                 @endforeach
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Created At :</label>
                     				<p>{{ $attendance_detail->created_at }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Ip Address :</label>
                     				<p>{{ $attendance_detail->ip_address }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Country Name :</label>
                     				<p>{{ $attendance_detail->country_name }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Region Name :</label>
                     				<p>{{ $attendance_detail->region_name }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>City Name :</label>
                     				<p>{{ $attendance_detail->city_name }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>zip_code :</label>
                     				<p>{{ $attendance_detail->zip_code }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Latitude :</label>
                     				<p>{{ $attendance_detail->latitude }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Longitude :</label>
                     				<p>{{ $attendance_detail->longitude }}</p>
                     			</div>
                     		</div>
                     	</div>
                     	<div class="col-lg-4">
                     		<div class="form-group">
                     			<div class="controls">
                     				<label>Time Zone :</label>
                     				<p>{{ $attendance_detail->time_zone }}</p>
                     			</div>
                     		</div>
                     	</div>
                        <div class="col-lg-4">
                           <div class="form-group">
                              <div class="controls">
                                 <label class="label-control">Attachment :</label>
                                 @if($attendance_detail->attachments!='')
                                 <br><a href="{{ asset('public/attendance_upload/'.$attendance_detail->attachments) }}" target="_blank"><button type="button" class="btn-sm btn-primary"><i class="fa fa-eye"></i></button></a>&nbsp;&nbsp;{{ $attendance_detail->attachments }}
                                 @endif
                              </div>
                           </div>
                        </div>
                        @if($attendance_detail->type=='checkout')
                        <div class="col-lg-4">
                           <div class="form-group">
                              <div class="controls">
                                 <label for="account-name">Working Hours :</label>
                                 <p>{{ $attendance_detail->work_hours }}</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group">
                              <div class="controls">
                                 <label>Earlier Checkin :</label>
                                 <p>{{ $attendance_detail->earlier_checkin }}</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group">
                              <div class="controls">
                                 <label>Earlier Checkout :</label>
                                 <p>{{ $attendance_detail->earlier_checkout }}</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="form-group">
                              <div class="controls">
                                 <label>Over Time :</label>
                                 <p>{{ $attendance_detail->over_time }}</p>
                              </div>
                           </div>
                        </div>
                        @endif
                     	@endforeach
                     </div>
                     <div class="form-actions right">
                        <a href="{{ route('attendance_log') }}">
                           <button type="button" class="btn btn-danger mr-1">
                              <i class="fa fa-arrow-left"></i> Back
                           </button>
                        </a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
	<x-slot name="page_level_scripts">
		<script type="text/javascript">
			
		</script>
	</x-slot>	
</x-app-layout>