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
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_sprint">
			<input type="hidden" name="Sprint_Id" value="<?php echo isset($Sprint_Id) ? $Sprint_Id : '' ?>">
				<input type="hidden" name="updated_at" value="<?php echo $currentDateTime; ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Project Name</label>
                            <select name="Project_Id" class="form-control form-control-sm" required id="Project_Id">
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
                                
								if (isset($Project_Id) && $row['id'] == $Project_Id || !isset($Project_Id) && $row['id'] == $_GET['id']) {
									echo '<option selected value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
								} else {
									echo '<option value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
								}
								
								
                                }
                                ?>
                            </select>

						</div>
						<div class="form-group">
							<label for="" class="control-label">Sprint Title</label>
                            <input type="text" name="Sprint_Title" required value="<?php echo isset($Sprint_Title) ? ($Sprint_Title) : ''; ?>" class="form-control required form-control-sm" maxlength="60" />
						</div>
						
						<div class="form-group">
							<label for="" class="control-label">Start Date</label>
                            <input type="date" name="Sprint_Start_Date" required value="<?php echo isset($Sprint_Start_Date) ? date("Y-m-d",strtotime($Sprint_Start_Date)) : ''; ?>" class="form-control required form-control-sm" />
						</div>
						
						<div class="form-group">
							<label for="" class="control-label">End Date</label>
                            <input type="date" name="Sprint_End_Date" required value="<?php echo isset($Sprint_End_Date) ? date("Y-m-d",strtotime($Sprint_End_Date)) : ''; ?>" class="form-control required form-control-sm" />
                        </div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                <textarea class="form-control" required id="Sprint_Description" name="Sprint_Description" rows="6"><?php echo isset($Sprint_Description) ? $Sprint_Description : ''; ?></textarea>
                            </div>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Sprint Status</label>
							<select name="Sprint_Status" class="form-control form-control-sm" required id="Sprint_Status">
								<option value="Open" <?php echo (isset($Sprint_Status) && $Sprint_Status == 'Open') ? 'selected' : '' ?>>Open</option>
								<option value="Closed" <?php echo (isset($Sprint_Status) && $Sprint_Status == 'Closed') ? 'selected' : '' ?>>Closed</option>
							</select>

						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="goBack()">Cancel</button>

				</div>
			</form>
		</div>
	</div>
</div>

<script>

$('#manage_sprint').submit(function(e){
	e.preventDefault();
		if (isFormDataEmpty()) {
            return;
        }
		start_load()
		$.ajax({
			url:'ajax.php?action=save_sprint',
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
						location.href = 'index.php?page=sprint_list';					
					},750)
				}else{
					alert_toast('Please try Again',"warning");
					setTimeout(function(){	
						location.reload();
					},750)
					end_load()
				}
			}
		})
})

function isFormDataEmpty() {
        var formData = new FormData($('#manage_sprint')[0]);
        var startDate = new Date(formData.get('Sprint_Start_Date'));
        var endDate = new Date(formData.get('Sprint_End_Date'));


        if (startDate > endDate) {
            alert_toast('Start date should be less than the end date.', 'warning');
            return true;
        }

        return false;
}

</script>