<?php
    // Check if 'login_id' session variable is set
    if (isset($_SESSION['login_id'])) {
        $login_id = $_SESSION['login_id'];
    } else {
        $login_id = ''; // Set a default value if the session variable is not set
    }

    $currentDateTime = date('Y-m-d H:i:s');
    $where1 = "";
    if($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 5 || $_SESSION['login_type'] == 8 || $_SESSION['login_type'] == 7){
        $where1 = " where user_id = '{$_SESSION['login_id']}' ";
    }
    else{
        $where1 = " ";
    }
?>
<div class="col-lg-12">
<input type="hidden" name="where" id="where" value="<?php echo $where1; ?>">

	<div class="card">
		<div class="card-body">
			<form action="" id="manage_productivity">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<input type="hidden" name="updated_at" value="<?php echo $currentDateTime; ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Project Name</label>
                            <select name="project_id" class="form-control form-control-sm" required id="project_id">
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
                                
                                if (isset($project_id) && $row['id'] == $project_id) {
                                    echo '<option selected value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
                                } else {
                                    echo '<option value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
                                }
                                
                                
                                }
                                ?>
                            </select>

						</div>
						<div class="form-group">
							<label for="" class="control-label">Task Title</label>
                            <input type="text" name="task_id" value="<?php echo isset($task_id) ? ($task_id) : ''; ?>" class="form-control required form-control-sm" />
						</div>
						
						<div class="form-group">
							<label for="" class="control-label">Start Time</label>
                            <input type="time" name="start_time" value="<?php echo isset($start_time) ? date("H:i", strtotime($start_time)) : ''; ?>" class="form-control required form-control-sm" />
						</div>
						
						<div class="form-group">
							<label for="" class="control-label">End Time</label>
                            <input type="time" name="end_time" value="<?php echo isset($end_time) ? date("H:i", strtotime($end_time)) : ''; ?>" class="form-control required form-control-sm" />
						</div
						
					</div>
                    </div>
					<div class="col-md-6">
						
						<div class="form-group">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                <textarea class="form-control" required id="comment" name="comment" rows="6"><?php echo isset($comment) ? $comment : ''; ?></textarea>
                            </div>
						</div>
                            
                        <div class="form-group">
						<label for="">Date</label>
						<input type="date" class="form-control form-control-sm" name="date" value="<?php echo isset($date) ? date("Y-m-d",strtotime($date)) : '' ?>" required>
					</div>

					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=work_report'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){

$('#project_id').change(function(){
  var project_id = $('#project_id').val();
  var where = document.getElementById('where').value;
  if(project_id != '')
  {
    $.ajax({
    url: 'ajax.php?action=get_taskdetail',
    data: { id: project_id,where:where},
    method: 'POST',
    dataType: 'json', // Set the expected response data type to JSON
    success: function(resp) {
        var loginId = <?php echo json_encode($login_id); ?>;
        var taskoption = '<option value="">Select task</option>';
        var taskData = resp;
        console.log(loginId);
        console.log('taskData @@ ',taskData);
        taskData.forEach(function (data) {
            if(loginId == data.user_id || loginId == data.tester_id){
                taskoption = taskoption + '<option value="'+data.id+'">'+data.task+'</option>';
            }
        });
        $('#task_id').html(taskoption);
    }
    });
  }
  else
  {
   $('#task_id').html('<option value="">Select Task</option>');
   
  }
 });
});
$('#manage_productivity').submit(function(e){
		e.preventDefault()
        if (isFormDataEmpty()) {
            return;
        }
		$.ajax({
			url:'ajax.php?action=save_progress',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.reload()
					},750)
				}else{
					alert_toast('Please try Again',"warning");
					setTimeout(function(){	
				
					},750)
					end_load()
				}
			}
		})
})
function isFormDataEmpty() {
    var formData = new FormData($('#manage_productivity')[0]);
    var comment = formData.get('comment');
    var date = formData.get('date');
    var start_time = formData.get('start_time');
    var end_time = formData.get('end_time');
    var task_id = formData.get('task_id');

    if (comment.trim() === '' || date.trim() === '' || start_time.trim() === '' || end_time.trim() === '') {
        alert_toast('Please fill all the fields. All fields are required.', 'warning');
        return true;
    }

    // Convert time strings to Date objects for comparison
    var startTimeObj = new Date('1970-01-01 ' + start_time);
    var endTimeObj = new Date('1970-01-01 ' + end_time);

    // Compare start_time and end_time
    if (startTimeObj >= endTimeObj) {
        alert_toast('Start time must be greater than end time.', 'warning');
        return true;
    }

    return false;
}


</script>