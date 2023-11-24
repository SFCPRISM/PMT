<?php if(!isset($conn)){ include 'db_connect.php'; }
$currentDateTime = date('Y-m-d H:i:s');

?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="updated_at" value="<?php echo $currentDateTime; ?>">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Name</label>
					<input type="text" class="form-control form-control-sm" name="name" value="<?php echo isset($name) ? $name : '' ?>">
				</div>
			</div>
        <div class="col-md-6">
				<div class="form-group">
					<label for="">Status</label>
					<select name="status" id="status" class="custom-select custom-select-sm">
						<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Pending</option>
						<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>On-Hold</option>
						<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Done</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Start Date</label>
              <input type="date" class="form-control form-control-sm" autocomplete="off" name="start_date" value="<?php echo isset($start_date) ? date("Y-m-d",strtotime($start_date)) : '' ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">End Date</label>
              <input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : '' ?>">
            </div>
          </div>
		</div>
        <div class="row">
           <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Project Manager</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="manager_id[]">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 2 AND Status = 2 order by concat(firstname,' ',lastname) asc  ");
              	while($row= $managers->fetch_assoc()):
              	?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && in_array($row['id'],explode(',',$manager_id)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>

              	<?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Project Team Members</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
              	<option></option>
              	<?php 
              	$employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type IN (3, 5,8) AND Status = 2 order by concat(firstname,' ',lastname) asc ");
              	while($row= $employees->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'],explode(',',$user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
		<div class="row">
		<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Guest Users</label>
              <select class="form-control form-control-sm select2" name="guest_id">
              	<option></option>
              	<?php 
              	$guest = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 6 AND Status = 2 order by concat(firstname,' ',lastname) asc ");
              	while($row= $guest->fetch_assoc()):
              	?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($guest_id) && $guest_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
		  <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Tech Lead</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="tech_lead_id[]">
              	<option></option>
              	<?php 
              	$techlead = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 4 AND Status = 2 order by concat(firstname,' ',lastname) asc ");
              	while($row= $techlead->fetch_assoc()):
              	?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($tech_lead_id) && in_array($row['id'],explode(',',$tech_lead_id)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
		</div>
		<div class="row">
		<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Team Lead</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="team_lead[]">
              	<option></option>
              	<?php 
              	$teamlead = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 7 AND Status = 2 order by concat(firstname,' ',lastname) asc ");
              	while($row= $teamlead->fetch_assoc()):
              	?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($team_lead) && in_array($row['id'],explode(',',$team_lead)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>

              	<?php endwhile; ?>
              </select>
            </div>
        </div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="">Project Type</label>
					<select required name="type" id="type" class="custom-select custom-select-sm">
						<option value="" <?php echo isset($type) && $type == 0 ? 'selected' : '' ?>>--None--</option>
						<option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Marketing</option>
						<option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Development</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10">
				<div class="form-group">
					<label for="" class="control-label">Description</label>
					<textarea name="description" cols="30" rows="3"   class="form-control">
						<?php echo isset($description) ? $description : '' ?>
					</textarea>
				</div>
			</div>
		</div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project">Save</button>
    			<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-project').submit(function(e){
		e.preventDefault();
		if (isFormDataEmpty()) {
            return;
        }
		start_load()
		$.ajax({
			url:'ajax.php?action=save_project',
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
						location.href = 'index.php?page=project_list'
					},2000)
				}
			}
		})
	})
function isFormDataEmpty() {
        var formData = new FormData($('#manage-project')[0]);
        var startDate = new Date(formData.get('start_date'));
        var endDate = new Date(formData.get('end_date'));
        var projectname = formData.get('name');

        if (startDate.toString() === 'Invalid Date' || endDate.toString() === 'Invalid Date') {
            alert_toast('Please enter valid dates.', 'warning');
            return true;
        }

        if (startDate > endDate) {
            alert_toast('Start date should be less than the end date.', 'warning');
            return true;
        }

        if (projectname.trim() === '') {
            alert_toast('Please fill in all the required fields.', 'warning');
            return true;
        }

        return false;
}

</script>