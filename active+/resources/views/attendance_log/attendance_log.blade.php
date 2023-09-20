<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row match-height">	
			<div class="col-xl-12 col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Attendance Log
							<ol class="breadcrumb mt-0">
								<li class="breadcrumb-item active">
									<span class="btn btn-sm p-0 text-primary" onclick="goBack()">
										<i class="fa fa-arrow-left">
										</i> Go Back
									</span>
								</li>
							</ol>
						</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered attendance" style="width:100%;">
									<thead>
										<tr>
											<th>Attendance Id</th>
											<th>Action</th>
											<th>Employee Name</th>
											<th>Attendance Type</th>
											<th>Work Hours</th>
											<th>Early Checkin</th>
											<th>Early Checkout</th>
											<th>Over Time</th>
											<th>Date & Time</th>
											<th>Ip Address</th>
											<th>Country</th>
											<th>Region</th>
											<th>City</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
									<tfoot>
										<tr>
											<th>Attendance Id</th>
											<th>Action</th>
											<th>Employee Name</th>
											<th>Attendance Type</th>
											<th>Work Hours</th>
											<th>Early Checkin</th>
											<th>Early Checkout</th>
											<th>Over Time</th>
											<th>Date & Time</th>
											<th>Ip Address</th>
											<th>Country</th>
											<th>Region</th>
											<th>City</th>
										</tr>	
									</tfoot>
								</table>		
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<x-slot name="page_level_scripts">
		<script type="text/javascript">
			$(function(){
				var table = $('.attendance').DataTable({
					processing: true,
					serverSide: true,
					order: [[ 0, "desc" ]],
					ajax:"{{ route('attendance_log') }}",
					columns: [
					{data: 'attendance_id', name: 'attendance.attendance_id'},
					{data: 'action', name: 'attendance.action', orderable: false, searchable: false},
					{data: 'first_name', name: 'users.first_name'},
					{data: 'type', name: 'attendance.type'},
					{data: 'work_hours', name: 'attendance.work_hours'},
					{data: 'earlier_checkin', name: 'attendance.earlier_checkin'},
					{data: 'earlier_checkout', name: 'attendance.earlier_checkout'},
					{data: 'over_time', name: 'attendance.over_time'},
					{data: 'attendance_at', name: 'attendance.attendance_at'},
					{data: 'ip_address', name: 'attendance.ip_address'},
					{data: 'country_name', name: 'attendance.country_name'},
					{data: 'region_name', name: 'attendance.region_name'},
					{data: 'city_name', name: 'attendance.city_name'},
					]
				});
			});			
		</script>
	</x-slot>
</x-app-layout>