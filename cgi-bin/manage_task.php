<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
$assignedusers = $conn->query("SELECT `user_ids` FROM project_list where id = ".$_GET['pid'])->fetch_array();
$userIds = $assignedusers[0];

$userIdsArray = explode(',', $userIds);

$usersArray = array();
foreach ($userIdsArray as $userId) {
    $usersArray[] = array(
        'user_id' => $userId
    );
}

//print_r($usersArray);die;
$allusers = $conn->query("SELECT * FROM users WHERE type = 3")->fetch_all(MYSQLI_ASSOC);
//print_r($allusers);die;
?>

<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Task</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Description</label>
			<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
				<?php echo isset($description) ? $description : '' ?>
			</textarea>
		</div>
		<div class="form-group">
		<label for="">Developer</label>
			<select name="user_id" id="user_id" class="custom-select custom-select-sm">
				<?php
				foreach ($usersArray as $data) {
					foreach ($allusers as $alluser) {
						if ($alluser['id'] == $data['user_id']) {
							if(isset($user_id) && $user_id == $alluser['id'] ){
								echo '<option selected value="' . $alluser['id'] . '">' . $alluser['firstname'] . ' ' . $alluser['lastname'] . '</option>';

							}else {
								echo '<option  value="' . $alluser['id'] . '">' . $alluser['firstname'] . ' ' . $alluser['lastname'] . '</option>';

							}
						}
					}
				}
				?>

			</select>
		</div>
		<div class="form-group">
			<label for="">Status</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pending</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
				<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>Testing</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Done</option>
			</select>
		</div>
		<div class="form-group">
			<label for="">Start Date</label>
			<input type="date" class="form-control form-control-sm" name="start_date" value="<?php echo isset($start_date) ? date("Y-m-d",strtotime($start_date)) : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">End Date</label>
			<input type="date" class="form-control form-control-sm" name="end_date" value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Estimated Time</label>
			<input type="text" class="form-control form-control-sm" name="estimated_time" value="<?php echo isset($estimated_time) ? $estimated_time : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Attachment</label>
			<div class="custom-file">
		        <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
		        <label class="custom-file-label" for="customFile">Choose file</label>
		    </div>
		</div>
		<div class="form-group d-flex justify-content-center align-items-center">
			<img src="<?php echo isset($attachment_name	) ? 'assets/attachment/'.$attachment_name	 :'' ?>" alt="Attachment" id="cimg" class="img-fluid img-thumbnail ">
		</div>
		
	</form>
</div>

<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>

<script>
	$(document).ready(function(){


	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    })
     })

	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>