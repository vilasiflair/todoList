<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>Todo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

	<style>
		body {font-family: Arial, Helvetica, sans-serif;}
	</style>
</head>
<body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
	<div class="container">
		<div class="addTodoSection mt-5">
			<form id="task_form" class="task_form" method="post">
				@csrf
				<div class="addTodo">
					<div class="row">
						<div class="col-md-9">
							<div class="input-group mb-3">
								<input id="task_title" type="text" class="form-control task_title" placeholder="Add Task" aria-label="Add Task" name="task_title" aria-describedby="button-addon2" value="">
								<button  id="add_task" class="btn btn-primary" type="submit">Add Task</button>
							</div>
						</div>
						<div class="col-md-2">
							<button  id="delete_task" class="btn btn-danger" type="button">Clear Completed</button>
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-md-7">
							
						</div>
						<div class="col-md-2">
							<a href="javascript:void(0)" class="btn btn-success import_task" data-bs-toggle="modal" data-bs-target="#import_task">Import Task</a>
						</div>
						<div class="col-md-2">
							<button  id="export_task" class="btn btn-success export_task">Export Task</button>
						</div>
					</div>
				</div>
			</form>
			
		</div>

		<div class="todoListSection mt-5">
			<div class="todoList">
				<table class="table table-bordered">
					<thead>
						<th></th>
						<th>Title</th>
						<th>Created Date</th>
					</thead>
					<tbody id="task_data_row" class="task_data_row">
						<!-- <tr class="task_data_row">
							<td class="task_data"> <input type="check" class="status_data" name="status"><span class="task_title_data"></span></td>
							<td class="task_created_date"></td>
						</tr> -->
					</tbody>
				</table>
			</div>
		</div>

		<div class="section-taskFiltering">
			<div class="row mt-3 taskFiltering" id="taskFiltering">
				<div class="col-md-3">
					
				</div>
				<div class="col-md-2">
					<a href="javascript:void(0)" id="show_all_task" class="btn btn-info show_all_task" onclick="showView('all')">Show All</a>
				</div>
				<div class="col-md-2">
					<!-- <a href="javascript:void(0)" id="show_all_task" class="btn btn-success show_all_task">Show Completed</a> -->
					<button id="show_completed_task" class="btn btn-info show_completed_task" onclick="showView('completed')">Show Completed</button>
				</div>
				<div class="col-md-2">
					<a href="javascript:void(0)" id="show_remaining_task" class="btn btn-info show_remaining_task" onclick="showView('remaining')">Show Remaining</a>
				</div>
				<div class="col-md-3">
					
				</div>

			</div>
		</div>
		
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="import_task" tabindex="-1" aria-labelledby="import_taskLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="importFile" enctype="multipart/form-data">
					@csrf
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="import_taskLabel">Import Tasks</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<a href="{{URL::to('/')}}/Task.csv" target="_blank">
							<button type="button" class="btn"><i class="fa fa-download"></i>Download Example</button>
						</a>
						<div class="mb-3">
							<label for="importCSVFile" class="form-label">CSV File</label>
							<input class="form-control" name="importCSVFile" type="file" id="importCSVFile" accept=".csv" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="import_modal_close" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" id="store_import_task" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
	<script type="text/javascript">
		var task_data = document.getElementsByClassName("status_data");
		var status_data = [];
		
		window.onload = function(e){ 
			let url = '/getTaskData';
			fetch(url, { method: 'GET' })
			.then(Result => Result.json())
			.then(dataResult => {
				let appendTask = document.getElementById("task_data_row");
				appendTask.innerHTML = "";
				dataResult.forEach(function(data){
					let task_id = data.id;
					let status = data.status;
					let task_title = data.task_title;
					let created_at = new Date(data.created_at).toDateString();
					
					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
				});
				setTimeout( function() {
					attachChangeEventToCheckboxes();
				}, 1000); 
			})
			.catch(errorMsg => { console.log(errorMsg); });
		}

		document.getElementById("task_form").addEventListener("submit", function(e) {
			e.preventDefault();
			var task_title = document.getElementById("task_title").value;
			var _token = document.querySelector('input[name=_token]').value;
			if(task_title)
			{
				let url = '/storeTaskData';
				const formData = new FormData(e.target);
				
				fetch(url, {
					method: 'POST', 
					body: formData
				})
				.then(Result => Result.json())
				.then(dataResult => {
					document.getElementById("task_title").value = "";
					var task_id = dataResult.id;
					var task_title = dataResult.task_title;
					var status = dataResult.status;
					var created_at = dataResult.created_at;
					var appendTask = document.getElementById("task_data_row");

					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';

					attachChangeEventToCheckboxes();
				})
				.catch(errorMsg => { console.log(errorMsg); });
			}

			else
			{
				alert("Enter task name")
				return false;
			}
		});
		
		function attachChangeEventToCheckboxes() {
			// status_data = document.getElementsByClassName("status_data");
			for (let i = 0; i < task_data.length; i++) {
				task_data[i].addEventListener("change", function(a) {
					if (a.target.value == 0) {
						a.target.value = 1;
					} else {
						a.target.value = 0
					}

					var task_status = a.target.value;
					var task_id = a.target.parentNode.parentNode.getAttribute("id");
					
					let url = '/updateTaskData';
					const formData = new FormData();
					formData.append('_token', '{{ csrf_token() }}');
					formData.append('task_status', task_status);
					formData.append('task_id', task_id);
					
					fetch(url, {
						method: 'POST', 
						body: formData
					})
					.then(Result => Result.json())
					.then(dataResult => {
						var appendTask = document.getElementById("task_data_row");
						appendTask.innerHTML = "";
						dataResult.forEach(function(value) {
							var task_id = value.id;
							var status = value.status;
							var task_title = value.task_title;
							var created_at = value.created_at;
							
							appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
						});

						attachChangeEventToCheckboxes();
					})
					.catch(errorMsg => { console.log(errorMsg); });
				});
			}
		}

		document.getElementById("delete_task").addEventListener("click", function(){
			// status_data = document.getElementsByClassName("status_data");
			for (let j = 0; j < task_data.length; j++) 
			{
				if(task_data[j].value == 1)
				{
					var task_id = task_data[j].parentNode.parentNode.getAttribute("id");

					let url = '/deleteCompletedTasks';
					const formData = new FormData();
					formData.append('_token', '{{ csrf_token() }}');
					formData.append('task_id', task_id);
					
					fetch(url, {
						method: 'POST', 
						body: formData
					})
					.then(Result => Result.json())
					.then(dataResult => {
						var appendTask = document.getElementById("task_data_row");
						appendTask.innerHTML = "";
						dataResult.forEach(function(value) {
							var task_id = value.id;
							var status = value.status;
							var task_title = value.task_title;
							var created_at = value.created_at;
							
							appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
						});
						attachChangeEventToCheckboxes();
					})
					.catch(errorMsg => { console.log(errorMsg); });
				}
			}
		});
		

		function showView(view) {
			task_data = Array.from(task_data);
			
			if (view == 'completed') {
				task_data = task_data.filter((item) => item.value === "1");
			}
			else if (view == 'remaining') {
				task_data = task_data.filter((item) => item.value === "0");
			}
			console.log("completed::", task_data);
		}
		
		/* document.getElementById("store_import_task").addEventListener("click", function(){
			// var importCSVFile = document.getElementById("importCSVFile").value;
			var importCSVFile = document.getElementById("importCSVFile").files[0].name; 
			console.log("csvFile::", importCSVFile);
			if(importCSVFile)
			{
				let url = '/importTaskData';
				const formData = new FormData();
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('importCSVFile', importCSVFile);
				
				fetch(url, {
					method: 'POST', 
					body: formData
				})
				.then(Result => Result.json())
				.then(dataResult => {
					/* document.getElementById("task_title").value = "";
					var task_id = dataResult.id;
					var task_title = dataResult.task_title;
					var status = dataResult.status;
					var created_at = dataResult.created_at;
					var appendTask = document.getElementById("task_data_row");

					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';

					attachChangeEventToCheckboxes(); 
				})
				.catch(errorMsg => { console.log(errorMsg); });
			}
			else
			{
				alert("Add CSV file");
			}
		}); */

		document.getElementById("store_import_task").addEventListener("click", function(e){
			// var importCSVFile = document.getElementById("importCSVFile").value;
			// var importCSVFile = document.getElementById("importCSVFile").files[0].name; 
			// e.preventDefault();
			// var importCSVFile = new FormData();
			// var importCSVFile = new FormData(document.getElementById("importFile").files);
			var importCSVFileCheck = document.getElementById('importCSVFile').value;
			console.log("importCSVFileCheck", importCSVFileCheck);
			var importCSVFile = new FormData(document.getElementById("importFile"));
			// console.log("csvFile::", importCSVFile);
			if(importCSVFile && importCSVFileCheck != '')
			{
				let url = '/importTaskData';
				/* const formData = new FormData();
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('importCSVFile', importCSVFile); */
				
				fetch(url, {
					method: 'POST', 
					body: importCSVFile
				})
				
				.then(Result => Result.json())
				.then(dataResult => {
					/* console.log("dataResult", dataResult);
					return; */
					if (dataResult.success) {
						document.getElementById("importCSVFile").value = "";
						var task_id = dataResult.id;
						var task_title = dataResult.task_title;
						var status = dataResult.status;
						var created_at = dataResult.created_at;
						var appendTask = document.getElementById("task_data_row");

						appendTask.innerHTML += '<tr id="'+task_id+'"><td><input data-task_id = "'+task_id+'" type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
						let import_modal_close = document.getElementById('import_modal_close');
						import_modal_close.click();
						attachChangeEventToCheckboxes();
					}
					else
					{
						alert(dataResult.message);
						return;
					}
				})
				.catch(errorMsg => { console.log(errorMsg); });
			}
			else
			{
				alert("Add CSV file");
			}
		});
		
	</script>
</body>
</html>
