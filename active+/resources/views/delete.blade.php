<div class="modal fade" id="DeleteDataModal" tabindex="-1" role="dialog" aria-labelledby="DeleteDataModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
		<div class="modal-content">
			<section class="contact-form">
				<div class="modal-header btn-primary">
					<h5 class="modal-title text-white">Delete Reason</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="{{ route('delete') }}">
					@csrf
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<input type="hidden" name="deleted" value="Yes">
									<input type="hidden" name="DeleteTableName" value="{{ $DeleteTableName }}">
									<input type="hidden" name="DeleteColumnName" value="{{ $DeleteColumnName }}">
									<input type="hidden" id="DeleteColumnValue" name="DeleteColumnValue" value="">
									<textarea required name="DeleteReason" class="form-control" onfocus="onfocus"></textarea>
								</div>
							</div>
						</div> 
						<div class="modal-footer">
							<button type="reset" class="btn btn-primary btn-md" data-dismiss="modal">
								<i class="fa fa-times"></i> Close
							</button>
							<button type="submit" class="btn btn-danger btn-md" id="success">
								<i class="fa fa-trash"></i> Delete
							</button>
						</div>
					</div>
				</form>
			</section>
		</div>
	</div>
</div>
	
