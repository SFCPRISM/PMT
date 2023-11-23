<?php include'db_connect.php';
$where1= "";
$where_subtask = "";
if(isset($_GET['id'])){
  if (isset($_GET['type'] )&& $_GET['type'] == 'total_task') {
    $where1 = "";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_bug'){
    $where1 = "task_type = 2";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_story'){
    $where1 = "task_type = 1";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_subtask'){
    $where1 = "";
    $where_subtask = "sub_task";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_pending'){
    $where1 = "status = 1";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_onprogress'){
    $where1 = "status = 2";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_testing'){
    $where1 = "status = 4";
  }elseif(isset($_GET['type'] )&& $_GET['type'] == 'total_done'){
    $where1 = "status = 3";
  }
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
        <input type="hidden" id="where" value="<?php echo $where1 ?>" />
        <input type="hidden" id="where_subtask" value="<?php echo $where_subtask ?>" />
        <?php if(isset($_GET['id'])) {?>
        <input type="hidden" id="pid" value="<?php echo $_GET['id'] ?>" />
        <?php }?>
                <label>Projects :</label>
            <select name="project" id="project">
                <option value="">--Select--</option>
                <?php
                  $where = "";
                  if($_SESSION['login_type'] == 2 ){
                    $where = " where concat('[',REPLACE(manager_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

                  }elseif($_SESSION['login_type'] == 4){
                    $where = " where concat('[',REPLACE(tech_lead_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

                  }elseif($_SESSION['login_type'] == 6){
                    $where = " where guest_id = '{$_SESSION['login_id']}' ";
                  }elseif($_SESSION['login_type'] == 7){
                    $where = " where concat('[',REPLACE(team_lead,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

                  }elseif($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 5 || $_SESSION['login_type'] == 8){
                    $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
                  }
                $projects = $conn->query("SELECT * FROM project_list $where ORDER BY name ASC");
                while ($row = $projects->fetch_assoc()) {
                  $name = ucfirst($row['name']);
                  $max_length = 15; // Choose the maximum length you want to display
                  
                  if (strlen($name) > $max_length) {
                      $name = substr($name, 0, $max_length) . '...';
                  }
                  
                  if ($row['id'] == $_GET['id']) {
                      echo '<option selected value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
                  } else {
                      echo '<option value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
                  }
                }
                ?>
            </select>
			</div>
		</div>
		<div class="card-body">
				<div class="listview_table table-responsive" id="listview_table">
                    <p class="card-success"> 
                        Please Select Projects !
                    </p>
                </div>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
$(document).ready(function() {
      $('#list').DataTable(); // Initialize DataTable for the initial task list (if any)
    var projectid = document.getElementById('pid').value;
    var where = document.getElementById('where').value;
    var where_subtask = document.getElementById('where_subtask').value;
    
    $.ajax({
    url: 'ajax.php?action=get_tasklist',
    data: { id: projectid ,where:where,where_subtask:where_subtask},
    method: 'POST',
    dataType: 'json', // Set the expected response data type to JSON
    success: function(resp) {
        createListView(resp);
    }
    }); 

    $('.view_task').click(function(){
    	var pid = document.getElementById('project').value;
    	var dataId = $(this).attr('data-id');
    	uni_modal_view("Task Details", "view_task.php?pid=" + pid + "&id=" + dataId, "mid-large");
	});
});

$('#project').change(function() {
    var projectid = $(this).val();
    var where = document.getElementById('where').value;
    var where_subtask = document.getElementById('where_subtask').value;
    $.ajax({
    url: 'ajax.php?action=get_tasklist',
    data: { id: projectid ,where:where,where_subtask:where_subtask},
    method: 'POST',
    dataType: 'json', // Set the expected response data type to JSON
    success: function(resp) {
      console.log('resp @@@ ',resp);
        createListView(resp);
    }
    });
});
function formatDate(dateString) {
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'short', day: '2-digit' };
  return date.toLocaleDateString('en-US', options);
}
function ucwords(str) {
  return str.toLowerCase().replace(/(^|\s)\S/g, function (letter) {
    return letter.toUpperCase();
  });
}
function createListView(resp) {
    let tableHTML = '<table class="table table-hover table-condensed" id="list">';
    tableHTML += '<colgroup>';
    tableHTML += '<col width="5%">';
    tableHTML += '<col width="15%">';
    tableHTML += '<col width="15%">';
    // Check if 'task_type' exists for at least one element in the resp array
    const hasTaskType = resp.some(item => typeof item.task_type !== 'undefined' && item.task_type !== null);

    if (hasTaskType) {
        tableHTML += '<col width="10%">';
    }
    
    tableHTML += '<col width="15%">';
    
    // Check if 'priority' exists for at least one element in the resp array
    const hasPriority = resp.some(item => typeof item.priority !== 'undefined' && item.priority !== null);

    if (hasPriority) {
        tableHTML += '<col width="10%">';
    }
    
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="10%">';
    tableHTML += '<col width="15%">';
    tableHTML += '</colgroup>';
    tableHTML += '<thead>';
    tableHTML += '<tr>';
    tableHTML += '<th class="text-center">#</th>';
    tableHTML += '<th>Task</th>';
    tableHTML += '<th>Sprint</th>';
    if (hasTaskType) {
        tableHTML += '<th>Type</th>';
    }
    
    tableHTML += '<th>Summary</th>';
    tableHTML += '<th>Status</th>';
    
    if (hasPriority) {
        tableHTML += '<th>Priority</th>';
    }
    
    tableHTML += '<th>Developer</th>';
    tableHTML += '<th>Start Date</th>';
    tableHTML += '<th>End Date</th>';
    tableHTML += '<th>Created Date</th>';
    tableHTML += '<th>Updated Date</th>';
    tableHTML += '</tr>';
    tableHTML += '</thead>';
    tableHTML += '<tbody>';

    // Table rows for each data entry
    for (let i = 0; i < resp.length; i++) {
        tableHTML += '<tr>';
        tableHTML += '<td>' + (i + 1) + '</td>'; // Incrementing row number
        tableHTML += '<td><a class="dropdown-item view_task" href="javascript:void(0)" data-id="' + resp[i].id + '" data-task="' + resp[i].task + '">' + (resp[i].sub_task ? resp[i].sub_task : resp[i].task) + '</a></td>';
        tableHTML += '<td>' + resp[i].Sprint_Title + '</td>';
        // Include the condition for task_type and display the corresponding label if available
        if (hasTaskType) {
            let typeLabel = '';
            if (resp[i].task_type == 1) {
                typeLabel = 'Story';
            } else if (resp[i].task_type == 2) {
                typeLabel = 'Bug';
            }
            tableHTML += '<td>' + typeLabel + '</td>';
        }
        
        tableHTML += '<td>' + resp[i].description + '</td>';
        let statusLabel = '';
        if (resp[i].status == 1) {
            statusLabel = 'Pending';
        } else if (resp[i].status == 2) {
            statusLabel = 'On Progress';
        } else if (resp[i].status == 3) {
            statusLabel = 'Done';
        } else if (resp[i].status == 4) {
            statusLabel = 'Testing';
        }
        tableHTML += '<td>' + statusLabel + '</td>';
        
        // Include the condition for priority and display the corresponding label if available
        if (hasPriority) {
            let priorityLabel = '';
            if (resp[i].priority == 1) {
                priorityLabel = 'High';
            } else if (resp[i].priority == 2) {
                priorityLabel = 'Medium';
            } else if (resp[i].priority == 3) {
                priorityLabel = 'Low';
            }
            tableHTML += '<td>' + priorityLabel + '</td>';
        }
        
        tableHTML += '<td>' + resp[i].user_name + '</td>';
        const formattedStartDate = formatDate(resp[i].start_date);
        const formattedEndDate = formatDate(resp[i].end_date);
        const formattedCreatedAt = formatDate(resp[i].Created_At);
        const formattedUpdatedAt = formatDate(resp[i].Updated_At);

        tableHTML += '<td>' + formattedStartDate + '</td>';
        tableHTML += '<td>' + formattedEndDate + '</td>';
        tableHTML += '<td>' + formattedCreatedAt + '</td>';
        tableHTML += '<td>' + formattedUpdatedAt + '</td>';
        tableHTML += '</tr>';
    }

    tableHTML += '</tbody>';
    tableHTML += '</table>';

    // Append the table to the "listview_table" div
    const listViewDiv = document.getElementById('listview_table');
    listViewDiv.innerHTML = tableHTML;

    // Destroy and reinitialize DataTable for the new task list
    // $('#list').DataTable().destroy();
    $('#list').DataTable();
    $('.view_task').click(function(){
    	var pid = document.getElementById('project').value;
    	var dataId = $(this).attr('data-id');
    	uni_modal_view("Task Details", "view_task.php?pid=" + pid + "&id=" + dataId, "mid-large");
	});
}


  </script>