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

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
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
								<input id="task_title" type="text" class="form-control task_title" placeholder="Add Task" aria-label="Add Task" name="task_title" aria-describedby="button-addon2">
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
							<button  id="import_task" class="btn btn-success import_task">Import Task</button>

							<button id="myBtn">Open Modal</button>

								<!-- The Modal -->
								<div id="myModal" class="modal">
								  <!-- Modal content -->
								  <div class="modal-content">
								    <span class="close">&times;</span>
								    <p>Some text in the Modal..</p>
								  </div>
								</div>
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
</body>
</html>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type="text/javascript">

	// Get the modal
		var modal = document.getElementById("myModal");

		// Get the button that opens the modal
		var btn = document.getElementById("myBtn");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		btn.onclick = function() {
		  modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
		  modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		}

	window.onload = function(e){ 
		e.preventDefault();
		
		$.ajax({
            url: "/getTaskData",
            type: "GET",
            data: {
                
            },
            cache: false,
          	success: function(dataResult){
              	if(dataResult)
              	{
              		var appendTask = document.getElementById("task_data_row");
              		appendTask.innerHTML = "";
              		$.each(dataResult, function(key, value) {
							
	              		var task_id = value.id;
	              		var status = value.status;
	              		var task_title = value.task_title;
	              		var created_at = value.created_at;

	              		appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
	          		});
        			
              	}
          	}
        });
        setTimeout( function() {
			attachChangeEventToCheckboxes();
		}, 1000);
	}

	document.getElementById("add_task").addEventListener("click", function(e) {
	    e.preventDefault();
		var task_title = document.getElementById("task_title").value;
		if(task_title)
		{
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});

			$.ajax({
	            url: "/storeTaskData",
	            type: "POST",
	            data: {
	                _token: "{{ csrf_token() }}",
	                task_title: task_title,
	            },
	            cache: false,
	          	success: function(dataResult){
	              	console.log(dataResult);
	              	if(dataResult)
	              	{
	              		document.getElementById("task_title").value = "";
	              		var task_id = dataResult.id;
	              		var task_title = dataResult.task_title;
	              		var status = dataResult.status;
	              		var created_at = dataResult.created_at;

	              		var appendTask = document.getElementById("task_data_row");
	              		// appendTask.innerHTML = "";

	              		appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';

	              		attachChangeEventToCheckboxes();
	              	}
	          	}
	        });
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


	/*let checkedInput = [];
	let inputbox = document.querySelectorAll(".status_data");
	console.log("inputbox", inputbox);
		inputbox.forEach( function(e)
		{
			console.log("e", e);
			e.addEventListener("change", function(a)
			{
				console.log("a", a.target);
				if (a.target.checked == true){
      				checkedInput = a.target.value;
      				console.log("checkedInput", checkedInput);
    			} else {
      				//newValue.style.display = "none";
     				 // newValue.innerHTML = '';
    			}

			});
			// console.log("e",e);

		});*/
		// console.log("inputbox", inputbox);

	document.getElementById("delete_task").addEventListener("click", function(e) {
		e.preventDefault();
		
	});
		/*const table = document.querySelector("table");
		const tbodies = table.tBodies;
		var tbodyLength = tbodies[0].rows.length;
		console.log(tbodies[0]);
		console.log(tbodies[0].rows.length);
		console.log(tbodies.length);
		for(i=0; i<=tbodyLength; i++)
		{

			console.log("test", tbodies[0].tr);
		}*/
		// const idList = Array.from(document.querySelectorAll('tr')).map((element) => element.getAttribute('id'));
		/*const idList = Array.from(document.querySelectorAll('tr'));
		// console.log("idlist", idList);

		idList.forEach( function(b) {
            let tabContentId = b;
            let tabContentIdCode = tabContentId.getAttribute('id');
            let tdTag = tabContentId.getElementsByTagName('td')[0];
            console.log("tdTag", tdTag);
            let inputVal = "";
            if(tdTag != undefined)
            {
            	inputVal = tdTag.getElementsByClassName("status_data");
            	checkedVal = inputVal[0].checked();
            	console.log("checkedVal", checkedVal);
            }
            /*console.log("inputVal",inputVal[0].is(':checked'));
            $(this).closest('td').find("input").each(function() {
		        console.log("td", this.value
		    });*/

            // console.log(tabContentIdCode);

            /*tabContentId.classList.add("hidden");

            if (tabContentIdCode[1] == currentIdCode[1]) {
                tabContentId.classList.remove("hidden");
            }*/

        

		// var statusData = 
		/*idList.forEach(item,index)
		{

		}	*/
			// console.log("ids", idList);


		/*var rows =document.getElementsByTagName("tbody")[0].rows;
		// console.log("rows", rows);
		for(var i=0;i<=rows.length;i++)
		{
			var td = rows[i];
			// console.log(td);
			var task_id = $(this).closest('tr');
			// console.log("task_id", task_id);

			// var sdata = td.closest('.status').value;
			// console.log(sdata);
			// $("td").closest("tr").attr("id","classname");

		}
	    // appendTask.innerHTML();
	});*/

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
		     	// console.log("task_id", task_id);
				
				$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});

				$.ajax({
		            url: "/updateTaskData",
		            type: "POST",
		            data: {
		                _token: "{{ csrf_token() }}",
		                task_id: task_id,
		                task_status: task_status,
		            },
		            cache: false,
		          	success: function(dataResult){
		              	// console.log(dataResult);
		              	if(dataResult)
		              	{
		              		// console.log("dataResult", dataResult);
		              		var appendTask = document.getElementById("task_data_row");
		              		appendTask.innerHTML = "";
		              			
		              		$.each(dataResult, function(key, value) {
							
			              		var task_id = value.id;
			              		var status = value.status;
			              		var task_title = value.task_title;
			              		var created_at = value.created_at;
	              			
			              		appendTask.innerHTML += '<tr id="'+task_id+'"><td><input type="checkbox" class="status_data" name="status" value="'+status+'" ' + (status == 1 ? 'checked' : '') + '></td><td><span class="task_title_data">'+task_title+'</span></td><td>'+created_at+'</td></tr>';
		              		});
		              	}
		              	attachChangeEventToCheckboxes();
		          	}
		        });
		    });
		    
		    document.getElementById("delete_task").addEventListener("click", function(){
		    	console.log("newStatusData", status_data1);
		    	
		    	
		    	status_data1[i].addEventListener("mousehover", function(b){
		    		if(b.target.value == 1)
		    		{
		    			console.log("forDeleteCheckbox");
		    		}
		    		else
		    		{
		    			console.log("notDeleted");
		    		}
		    	});
		    });
		    // status_data1[i].addEventListener
	  	}
	}
</script>