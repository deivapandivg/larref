<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row match-height">	
			<div class="col-xl-12 col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Notifications
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
								<table class="table table-striped table-bordered notifications" style="width:100%;">
									<thead>
										<tr>
											<th>Notification Id</th>
											<th>Title</th>
											<th>Description</th>
											<th>Url</th>
											<th>Notification Viewed</th>
											<th>Created At</th>
											<th>Viewed At</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
									<tfoot>
										<tr>
											<th>Notification Id</th>
											<th>Title</th>
											<th>Description</th>
											<th>Url</th>
											<th>Notification Viewed</th>
											<th>Created At</th>
											<th>Viewed At</th>
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
				var table = $('.notifications').DataTable({
					processing: true,
					serverSide: true,
					order: [[ 0, "desc" ]],
					ajax:"{{ route('notifications') }}",
					columns: [
					{data: 'notification_id', name: 'notifications.notification_id'},
					{data: 'title', name: 'notifications.title'},
					{data: 'description', name: 'notifications.description'},
					{data: 'url', name: 'notifications.url'},
					{data: 'notification_viewed', name: 'notifications.notification_viewed'},
					{data: 'created_at', name: 'notifications.created_at'},
					{data: 'view_at', name: 'notifications.view_at'},
					]
				});
			});			
		</script>
	</x-slot>
</x-app-layout>