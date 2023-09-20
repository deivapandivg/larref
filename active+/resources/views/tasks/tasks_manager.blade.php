<x-app-layout>
	<section id="tabs-with-icons">
		<div class="row match-height">
			<div class="col-xl-12 col-lg-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Task Manager 
							<ol class="breadcrumb mt-0">
								<li class="breadcrumb-item active "><span  class="btn btn-sm p-0 text-primary" onclick="goBack()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back</span>
								</li>
							</ol>
						</h4>
						<div class="heading-elements d-flex">
							<a>
								<button  type="submit" class="btn btn-primary" data-toggle="modal" data-target="#AddModal">
									<i class="fa fa-plus"></i> Task
								</button>
							</a>
						</div> 

						<!-- todo -->
						<div class="todo-container">
							<div class="status status_div" status_val="1">
								<h2>Created</h2>
								<!-- <a><button id="add_btn" data-toggle="modal" data-target="#todo_form">+ Add Todo</button></a> -->
								@foreach($tasks_list as $task_list)
								@if($task_list->task_status==1)
								<div class="todo bg-primary task_div_list" draggable="true">
									<input class="task_id" type="hidden" value="{{ $task_list->task_id }}">
									<input class="task_status_id" type="hidden" value="{{ $task_list->task_status }}">
									<p class="white">{{ $task_list->task_name }}</p>
									<!-- <span class="close closebtn">&times;</span> -->
								</div>
								@endif
								@endforeach
							</div>
							<div class="status status_div" status_val="2">
								<h2>Planning</h2>
								@foreach($tasks_list as $task_list)
								@if($task_list->task_status==2)
								<div class="todo bg-secondary task_div_list" draggable="true">
									<input class="task_id" type="hidden" value="{{ $task_list->task_id }}">
									<input class="task_status_id" type="hidden" value="{{ $task_list->task_status }}">
									<p class="white">{{ $task_list->task_name }}</p>
									<!-- <span class="close closebtn">&times;</span> -->
								</div>
								@endif
								@endforeach
							</div>
							<div class="status" status_val="3">
								<h2>In Progress</h2>
								@foreach($tasks_list as $task_list)
								@if($task_list->task_status==3)
								<div class="todo bg-info" draggable="true">
									<input class="task_id" type="hidden" value="{{ $task_list->task_id }}">
									<input class="task_status_id" type="hidden" value="{{ $task_list->task_status }}">

									<p class="white">{{ $task_list->task_name }}</p>
									<!-- <span class="close closebtn">&times;</span> -->
								</div>
								@endif
								@endforeach
							</div>
							<div class="status" status_val="4">
								<h2>Pending</h2>
								@foreach($tasks_list as $task_list)
								@if($task_list->task_status==4)
								<div class="todo bg-danger" draggable="true">
									<input class="task_id" type="hidden" value="{{ $task_list->task_id }}">
									<p class="white">{{ $task_list->task_name }}</p>
									<!-- <span class="close closebtn">&times;</span> -->
								</div>
								@endif
								@endforeach
							</div>
							<div class="status">
								<h2>Completed</h2>
								@foreach($tasks_list as $task_list)
								@if($task_list->task_status==5)
								<div class="todo bg-success" draggable="true">
									<p class="white">{{ $task_list->task_name }}</p>
									<!-- <span class="close closebtn">&times;</span> -->
								</div>
								@endif
								@endforeach
							</div>
						</div>
						<div id="overlay"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- <div class="modal fade" id="todo_form"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
			<div class="modal-content modal-task-add">
				<section class="contact-form">
					<div class="modal-header bg-secondary white">
						<h5 class="modal-title white">Add ToDo</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="get">
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Todo Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<input type="text" id="todo_input" required name="todo_name" class="name form-control" placeholder="Todo Name">

										</fieldset>
									</div>
								</div>
							</div> 
						</div>
						<div class="modal-footer">
                           <button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
                              <i class="fa fa-times"></i> Close
                           </button>
                           <button type="submit" class="btn btn-primary btn-md" id="todo_submit">
                              <i class="fa fa-check"></i> Add
                           </button>
                        </div>
					</form>
				</section>
			</div>
		</div>
	</div> -->
	<div class="modal fade" id="AddModal"  role="dialog" aria-labelledby="AddModals" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered  modal-md" role="document">
			<div class="modal-content">
				<section class="contact-form">
					<div class="modal-header bg-primary white">
						<h5 class="modal-title white">Task</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="post" action="{{ route('tasks_submit') }}" enctype="multipart/form-data">
						@csrf
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Client Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<select class="form-control border-primary select2 form-select" name="client_id" id="client_id" data-placeholder="Choose one" style="width:100%;">
											</select>
										</fieldset>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Task Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<input type="text" id="" required name="task_name" class="name form-control" placeholder="Task Name">
										</fieldset>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Project Name <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<select class="form-control border-primary select2 form-select" name="project_id" id="project_id" data-placeholder="Choose one" style="width:100%;">
											</select>
										</fieldset>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Assigned To <sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<select class="form-control border-primary select2 form-select" name="assign_to" data-placeholder="Choose one" style="width:100%;">
											</select>
										</fieldset>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Status<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<select class="form-control border-primary select2 form-select" name="status_id" data-placeholder="Choose one" style="width:100%;">
											</select>
										</fieldset>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Priority Level<sup class="text-danger" style="font-size: 13px;">*</sup> :</b>
											<select class="form-control border-primary select2 form-select" name="priority_id" data-placeholder="Choose one" style="width:100%;">
											</select>
										</fieldset>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label class="label-control">Attachment :</label>
										<table id="AddImageTable" width="50%">
											<tbody id="ImageTBodyAdd">
												<tr class="add_row">
													<td width="100%"><input name="attachment[]" type="file" multiple></td>
													<td width="20%"><button class="btn btn-success btn-sm" type="button" id="add" title='Add new file'><i class="fa fa-plus"></i></button></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<fieldset class="form-group floating-label-form-group"><b>Description : </b>
											<textarea class="form-control" name="description" placeholder="Description"></textarea>   
										</fieldset>   
									</div>
								</div>
							</div> 
						</div>
						<div class="modal-footer">
							<button type="reset" class="btn btn-danger btn-md" data-dismiss="modal">
								<i class="fa fa-times"></i> Close
							</button>
							<button type="submit" class="btn btn-primary btn-md">
								<i class="fa fa-check"></i> Add
							</button>
						</div>
					</form>
				</section>
			</div>
		</div>
	</div>
	<x-slot name="page_level_scripts">
		<script type="text/javascript">

			$(".task_div_list").draggable();
			$( ".status_div" ).droppable(
			{
				accept:".task_div_list",
				drop :function()
				{
					alert("I am dropped");
				}
			});

			// const todos = document.querySelectorAll(".todo");
			// const all_status = document.querySelectorAll(".status");
			// let draggableTodo = null;

			// todos.forEach((todo) => {
			//   todo.addEventListener("dragstart", dragStart);
			//   todo.addEventListener("dragend", dragEnd);
			// });

			// function dragStart() {
			//   // draggableTodo = this;
			//   console.log("From");
			//   console.log($(this).closest(".todo-container").find(".status").attr("status_val"));
			// }

			// function dragEnd() {
			//   // draggableTodo = this;
			//   var task_id = $(this).closest('.todo').find('.task_id').attr('value');
			//   var task_status_id = $(this).closest('.todo').find('.task_status_id').attr('value');
			//   console.log("To");
			//   console.log($(this).closest(".todo-container").find(".status").attr("status_val"));
			// }

			// all_status.forEach((status) => {
			//   status.addEventListener("dragover", dragOver);
			//   status.addEventListener("dragenter", dragEnter);
			//   status.addEventListener("dragleave", dragLeave);
			//   status.addEventListener("drop", dragDrop);
			// });

			// function dragOver(e) {
			//   e.preventDefault();
			//   //   console.log("dragOver");
			// }

			// function dragEnter() {
			//   this.style.border = "1px dashed #ccc";
			//   console.log("dragEnter");
			//   console.log($(this).closest(".todo-container").find(".status").attr("status_val"));
			// }

			// function dragLeave() {
			//   this.style.border = "none";
			//   console.log("dragLeave");
			// }

			// function dragDrop() {
			//   this.style.border = "none";
			//   this.appendChild(draggableTodo);
			//   console.log("dropped");
			//   console.log($(this).closest(".todo-container").find(".status").attr("status_val"));
			// }

			/* modal */
			// const btns = document.querySelectorAll("[data-target-modal]");
			// const close_modals = document.querySelectorAll(".close-modal");
			// const overlay = document.getElementById("overlay");

			// btns.forEach((btn) => {
			//   btn.addEventListener("click", () => {
			//     document.querySelector(btn.dataset.targetModal).classList.add("active");
			//     overlay.classList.add("active");
			//   });
			// });

			// close_modals.forEach((btn) => {
			//   btn.addEventListener("click", () => {
			//     const modal = btn.closest(".modal");
			//     modal.classList.remove("active");
			//     overlay.classList.remove("active");
			//   });
			// });

			// window.onclick = (event) => {
			//   if (event.target == overlay) {
			//     const modals = document.querySelectorAll(".modal");
			//     modals.forEach((modal) => modal.classList.remove("active"));
			//     overlay.classList.remove("active");
			//   }
			// };

			/* create todo  */
			// const todo_submit = document.getElementById("todo_submit");

			// todo_submit.addEventListener("click", createTodo);

			// function createTodo() {

			//   const input_val = document.getElementById("todo_input").value;
			//   if(input_val != ""){

			//   	const todo_div = document.createElement("div");

			//   	const txt = document.createTextNode(input_val);

			//   	todo_div.appendChild(txt);
			//   	todo_div.classList.add("todo");
			//   	todo_div.setAttribute("draggable", "true");

			//   	/* create span */
			// 	  const span = document.createElement("span");
			// 	  const span_txt = document.createTextNode("\u00D7");
			// 	  span.classList.add("close");
			// 	  span.appendChild(span_txt);

			// 	  todo_div.appendChild(span);

			// 	  no_status.appendChild(todo_div);

			// 	  span.addEventListener("click", () => {
			// 	    span.parentElement.style.display = "none";
			// 	  });
			// 	  //   console.log(todo_div);

			// 	  todo_div.addEventListener("dragstart", dragStart);
			// 	  todo_div.addEventListener("dragend", dragEnd);

			// 	  document.getElementById("todo_input").value = "";
			// 	  todo_form.classList.remove("active");
			// 	  overlay.classList.remove("active");

			// 	  setTimeout(function () {
			// 	    $('#todo_form').modal('close');
			// 	  }, 1000)
			//   }

			// }

			// const close_btns = document.querySelectorAll(".closebtn");

			// close_btns.forEach((btn) => {
			//   btn.addEventListener("click", () => {
			//     btn.parentElement.style.display = "none";
			//   });
			// });

		</script>
		<style type="text/css">
			{
				box-sizing: border-box;
			}

			/*body {
			  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			  min-height: 100vh;
			  display: flex;
			  justify-content: center;
			  align-items: center;
			}*/

			.todo-container {
				/*width: 1000px;*/
				height: 80vh;
				display: flex;
			}

			.status {
				width: 25%;
				background-color: #f3f3f3;
				position: relative;
				padding: 60px 1rem 0.5rem;
			}

			.status:nth-child(even) {
				background-color: #E4E4E4;
			}

			.status h2 {
				position: absolute;
				top: 0;
				left: 0;
				background-color: #343434;
				color: #f3f3f3;
				margin: 0;
				width: 100%;
				padding: 0.5rem 1rem;
			}

			#add_btn {
				padding: 0.5rem 1rem;
				background-color: #ccc;
				outline: none;
				border: none;
				width: 100%;
				font-size: 1.5rem;
				margin: 0.5rem 0;
				border-radius: 4px;
				cursor: pointer;
			}

			#add_btn:hover {
				background-color: #aaa;
			}

			.todo {
				display: flex;
				justify-content: space-between;
				align-items: center;
				position: relative;
				background-color: #fff;
				box-shadow: rgba(15, 15, 15, 0.1) 0px 0px 0px 1px,
				rgba(15, 15, 15, 0.1) 0px 2px 4px;
				border-radius: 4px;
				padding: 0.5rem 1rem;
				font-size: 1.5rem;
				margin: 0.5rem 0;
			}

			.todo .close {
				position: absolute;
				right: 1rem;
				top: 0rem;
				font-size: 2rem;
				color: #ccc;
				cursor: pointer;
			}

			.todo .close:hover {
				color: #343444;
			}

			/* modal */

			.close-modal {
				background: none;
				border: none;
				font-size: 1.5rem;
			}

			/*.modal {
			  width: 450px;
			  position: fixed;
			  top: -50%;
			  left: 50%;
			  transform: translate(-50%, -50%);
			  transition: top 0.3s ease-in-out;
			  border: 1px solid #ccc;
			  border-radius: 10px;
			  z-index: 2;
			  background-color: #fff;
			}*/

			.modal.active {
				top: 15%;
			}

			/*.modal .header {
			  display: flex;
			  justify-content: space-between;
			  align-items: center;
			  border-bottom: 1px solid #ccc;
			  padding: 0.5rem;
			  background-color: rgba(0, 0, 0, 0.05);
			}

			.modal .body {
			  padding: 0.75rem;
			}*/

			#overlay {
				display: none;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.3);
			}
			#overlay.active {
				display: block;
			}

			/*#todo_input,
			#todo_submit {
			  padding: 0.5rem 1rem;
			  width: 100%;
			  margin: 0.25rem;
			}

			#todo_submit {
			  background-color: #4caf50;
			  color: #f3f3f3;
			  font-size: 1.25rem;
			  border: none;
			}*/
			.modal-task-add{
				border-radius: 15px;
			}
		</style>
	</x-slot>
</x-app-layout>