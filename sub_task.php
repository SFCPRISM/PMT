<?php 
include 'db_connect.php';
session_start();
$project = $conn->query("SELECT project_list.type FROM project_list WHERE project_list.id =".$_GET['pid'])->fetch_array();
$projectType = $project['type'];
if(isset($_GET['event'])){
    if($_GET['event'] == 'edit'){       
        $qry = $conn->query("SELECT * FROM sub_task_list where id = ".$_GET['id'])->fetch_array();
        $project_id = $qry['project_id'];
        foreach($qry as $k => $v){
            $$k = $v;
        }
    }
}
if($_GET['event'] == 'new'){
   
    $assignedusers = $conn->query("SELECT CONCAT(`user_ids`, ',', `team_lead`, ',', `tech_lead_id`) AS users FROM project_list WHERE id = " . $_GET['pid'])->fetch_array();
}else{
  
    $assignedusers = $conn->query("SELECT CONCAT(`user_ids`, ',', `team_lead`, ',', `tech_lead_id`) AS users FROM project_list WHERE id = " . $_GET['pid'])->fetch_array();

}
$userIds = $assignedusers[0];
$userIdsArray = explode(',', $userIds);

$usersArray = array();
foreach ($userIdsArray as $userId) {
    $usersArray[] = array(
        'user_id' => $userId
    );
}
$currentDateTime = date('Y-m-d H:i:s');

//print_r($usersArray);die;
$allusers = $conn->query("SELECT * FROM users WHERE type IN (3,7,8,4)")->fetch_all(MYSQLI_ASSOC);
//print_r($allusers);die;
$testers = $conn->query("SELECT * FROM users WHERE type IN (5)")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid">
    <form action="" id="manage-task">
    <div class="row">
        <div class="col-md-6 border-right">
             <input type="hidden" name="Updated_At" value="<?php echo $currentDateTime; ?>">

        <?php 
       if($_GET['event'] == 'new'){ ?>
             <input type="hidden" name="task_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
      <?php } else{ ?>
             <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
             <input type="hidden" name="task_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
             <input type="hidden" name="project_id" value="<?php echo isset($project_id) ? $project_id: '' ?>">

        <?php }?>
        
        <div class="form-group">
            <label for="">Sub Task</label>
            <input type="text" class="form-control form-control-sm" name="sub_task" value="<?php echo isset($sub_task) ? $sub_task : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" id="" cols="30" rows="5" class=" form-control">
                <?php echo isset($description) ? $description : '' ?>
            </textarea>
        </div>
        <div class="form-group">
        <label for="">Assign To Developer</label>
            <select required name="user_id" id="user_id" class="custom-select custom-select-sm">
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
        <?php
		if($projectType == 2){?>
			<div class="form-group">
		<label for="">Assign To Tester</label>
			<select required name="tester_id" id="tester_id" class="custom-select custom-select-sm">
				<?php
				foreach ($usersArray as $data) {
					foreach ($testers as $tester) {
						if ($tester['id'] == $data['user_id']) {
							if(isset($tester_id) && $tester_id == $tester['id'] ){
								echo '<option selected value="' . $tester['id'] . '">' . $tester['firstname'] . ' ' . $tester['lastname'] . '</option>';

							}else {
								echo '<option  value="' . $tester['id'] . '">' . $tester['firstname'] . ' ' . $tester['lastname'] . '</option>';

							}
						}
					}
				}
				?>

			</select>
		</div>
		<?php } ?>
        <div class="form-group">
            <label for="">Status</label>
            <select name="status" id="status" class="custom-select custom-select-sm">
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pending</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>In-Progress</option>
				<option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>Testing</option>
				<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>On-Hold</option>
				<option value="6" <?php echo isset($status) && $status == 6 ? 'selected' : '' ?>>Ready To Deploy</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Done</option>
            </select>
        </div>
            </div>
            <div class="col-6">
        <div class="form-group">
            <label for="">Start Date</label>
            <input type="date" class="form-control form-control-sm" name="start_date" value="<?php echo isset($start_date) ? date("Y-m-d",strtotime($start_date)) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="">Estimated End Date</label>
            <input type="date" class="form-control form-control-sm" name="end_date" value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="">Estimated Time</label>
            <input type="text" onkeypress="return onlyNumberKey(event)" class="form-control form-control-sm" name="estimated_time" value="<?php echo isset($estimated_time) ? $estimated_time : '' ?>" required>
        </div>
        <div class="form-group">
			<label for="">Actual End Date</label>
			<?php if(isset($approve_by_lead) && $approve_by_lead == 1) {?>
			<input type="date" disabled  class="form-control form-control-sm" name="actual_end_date" value="<?php echo isset($actual_end_date) ? date("Y-m-d",strtotime($actual_end_date)) : '' ?>" >
			<?php }else { ?>
				<input type="date"  class="form-control form-control-sm" name="actual_end_date" value="<?php echo isset($actual_end_date) ? date("Y-m-d",strtotime($actual_end_date)) : '' ?>" >

			<?php } ?>
		</div>
        <?php if($_SESSION['login_type'] == 4 || $_SESSION['login_type'] == 7 || $_SESSION['login_type'] == 1) { ?>
		<div class="form-group">
			<label for="">End Date Approval</label>
			<select name="approve_by_lead" id="approve_by_lead" class="custom-select custom-select-sm">
				<option value="0" <?php echo isset($approve_by_lead) && $approve_by_lead == 0 ? 'selected' : '' ?>>Open</option>
				<option value="1" <?php echo isset($approve_by_lead) && $approve_by_lead == 1 ? 'selected' : '' ?>>Close</option>
			</select>
		</div>
		<?php } ?>
        <!-- <div class="form-group">
            <label for="" class="control-label">Attachment</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
        <div class="form-group d-flex justify-content-center align-items-center">
            <img src="<?php echo isset($attachment_name ) ? 'assets/attachment/'.$attachment_name    :'' ?>" alt="Attachment" id="cimg" class="img-fluid img-thumbnail ">
        </div> -->
        </div>   
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
        e.preventDefault();
        if (isFormDataEmpty()) {
            return;
        }
        start_load()
        $.ajax({
            url:'ajax.php?action=save_sub_task',
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

    function isFormDataEmpty() {
    var formData = new FormData($('#manage-task')[0]);
	var endDate = formData.get('end_date');
    var startDate = formData.get('start_date');
    var actualEndDate = formData.get('actual_end_date');
    var task = formData.get('sub_task');



    if (task.trim() === '') {
        alert_toast('Please fill Sub Task title .', 'warning');
        return true;
    }
    
    if (startDate == '') {
        alert_toast('Please fill in Start date.', 'warning');
        return true;
    }
	if (endDate == '') {
        alert_toast('Please fill in  End date .', 'warning');
        return true;
    }
    if (startDate.toString() === 'Invalid Date' || endDate.toString() === 'Invalid Date') {
        alert_toast('Please enter valid dates.', 'warning');
        return true;
    }
    if (actualEndDate == '') {
        alert_toast('Please fill in Actual End date .', 'warning');
        return true;
    }
    if (startDate > endDate) {
        alert_toast('Start date should be less than the end date.', 'warning');
        return true;
    }

    return false;
}

</script>
