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
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="import_taskLabel">Import Tasks</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="importCSVFile" class="form-label">CSV File</label>
						<input class="form-control" type="file" id="importCSVFile">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
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
					let created_at = data.created_at;
					
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

		/* document.getElementsByClassName("taskFiltering").addEventListener("click", function(e){
			console.log("taskFiltering::", e);
		}); */
		/* document.getElementById("taskFiltering").addEventListener("click", function(e){
			var filterBtnIdAttr = e.target.getAttribute("id");
			var filterData = document.getElementsByClassName("status_data");
			var completed_task_ids = [], remaining_task_ids = [], all_task_ids = [];
			for (let j = 0; j < filterData.length; j++) 
			{
				var all_task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				if(filterData[j].value == 1)
				{
					var completed_task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				}
				else if(filterData[j].value == 0)
				{
					var remaining_task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				}
				completed_task_ids.push(completed_task_id); 
				remaining_task_ids.push(remaining_task_id); 
				all_task_ids.push(all_task_id); 
			}
			
			if(filterBtnIdAttr == "show_all_task")
			{
				getFilteredData(all_task_ids);
			}

			if(filterBtnIdAttr == "show_completed_task")
			{
				getFilteredData(completed_task_ids);
			}

			if(filterBtnIdAttr == "show_remaining_task")
			{
				getFilteredData(remaining_task_ids);	
			}
			
			/* let filterData = document.getElementsByClassName("status_data");
			var task_ids = [];
			for (let j = 0; j < filterData.length; j++) 
			{
				if(filterData[j].value == 1)
				{
					var task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				}
				else if(filterData[j].value == 0)
				{
					var task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				}
				else
				{
					var task_id = filterData[j].parentNode.parentNode.getAttribute("id");
				}
				task_ids.push(task_id); 
			}
			
			console.log("task_id::", task_ids);
			return;
				const formData = new FormData();
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('task_id', task_id);

				let url = '/getFilteredTaskData';
				fetch(url, {
					method: 'POST', 
					body: formData
				})
				.then(Result => Result.json())
				.then(dataResult => {
					let appendTask = document.getElementById("task_data_row");
					appendTask.innerHTML = "";
					dataResult.forEach(function(data){
						let task_id = data.id;
						let status = data.status;
						let task_title = data.task_title;
						let created_at = data.created_at;
						
						appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
					});
					setTimeout( function() {
						attachChangeEventToCheckboxes();
					}, 1000); 
				})
				.catch(errorMsg => { console.log(errorMsg); }); 
			
		});


		function getFilteredData(task_ids)
		{
			const formData = new FormData();
			formData.append('_token', '{{ csrf_token() }}');
			formData.append('task_id', task_ids);

			let url = '/getFilteredTaskData';
			fetch(url, {
				method: 'POST', 
				body: formData
			})
			.then(Result => Result.json())
			.then(dataResult => {
				console.log("dataResult:", dataResult);
				let appendTask = document.getElementById("task_data_row");
				appendTask.innerHTML = "";
				dataResult.forEach(function(data){
					let task_id = data.id;
					let status = data.status;
					let task_title = data.task_title;
					let created_at = data.created_at;
					
					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
				});
				setTimeout( function() {
					attachChangeEventToCheckboxes();
				}, 1000); 
			})
			.catch(errorMsg => { console.log(errorMsg); }); 
		} */
	</script>
</body>
</html>
