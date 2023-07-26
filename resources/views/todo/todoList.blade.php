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
							<button  id="delete_task" class="btn btn-danger">Remaining Task</button>
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-md-7">
							
						</div>
						<div class="col-md-2">
							<a href="javascript:void(0)" class="btn btn-success import_task" data-bs-toggle="modal" data-bs-target="#import_task">Import Task</a>
						</div>
						<div class="col-md-2">
							<button  id="export_task" class="btn btn-primary export_task">Export Task</button>
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
		var status_data2 = [];
		window.onload = function(e){ 
			let url = '/getTaskData';
			// let url = 'https://jsonplaceholder.typicode.com/todos/1';
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
					
					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
				});
				setTimeout( function() {
					attachChangeEventToCheckboxes();
				}, 1000); 
			})
			.catch(errorMsg => { console.log(errorMsg); });
		}

		document.getElementById("task_form").addEventListener("submit", function(e) {
		// document.getElementById("add_task").addEventListener("click", function(e) {
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

					appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';

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


		/*let cboxs = document.querySelectorAll('.status_data');

		cboxs.forEach(function(e) {
			console.log("cboxs", e);
			e.addEventListener("click", function(a) {
				if (a.target.checked == true){
					console.log("checkavalue", a.target.value);
				// newValue.innerHTML = a.target.value;
				} else {
					console.log("unchecked")
				//newValue.style.display = "none";
				// newValue.innerHTML = '';
				}
			})
		})*/

		document.getElementById("delete_task").addEventListener("click", function(e) {
			e.preventDefault();
		});
		
		function attachChangeEventToCheckboxes() {
			let status_data1 = document.getElementsByClassName("status_data");
			for (let i = 0; i < status_data1.length; i++) {
				status_data1[i].addEventListener("change", function(a) {
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
							
							appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
						});

						attachChangeEventToCheckboxes();
					})
					.catch(errorMsg => { console.log(errorMsg); });
				});
				// status_data1[i].addEventListener
			}
		}

		document.getElementById("delete_task").addEventListener("click", function(){
			let status_data2 = document.getElementsByClassName("status_data");
			console.log("status_data", status_data2[0]);
			console.log("status_data", status_data2[2]);
			// return;
			for (let j = 0; j < status_data2.length; j++) 
			{
				if(status_data2[j].value == 1)
				{
					varGetRow = this.parent("tr");
					console.log("varGetRow", varGetRow);
				}

				/*addEventListener("change", function(a) {
				if (a.target.value == 0) {
					a.target.value = 1;
				} else {
					a.target.value = 0
				}*/	
			}
			// status_data1.forEach(function(b){
				// console.log("b:::", b);

			// });
			// status_data1[i].addEventListener("mousehover", function(b){
			// 	if(b.target.value == 1)
			// 	{
			// 		console.log("forDeleteCheckbox");
			// 	}
			// 	else
			// 	{
			// 		console.log("notDeleted");
			// 	}
			// });
		});
	</script>
</body>
</html>
