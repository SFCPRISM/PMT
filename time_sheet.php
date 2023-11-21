<?php include'db_connect.php';
$Startdate = isset($_GET['Start_date']);
$enddate = isset($_GET['End_date']);
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
		<form method="POST" id="myForm">
			<div class="card-header">
				<div class="card-header_inner">
					<div class="row">
							<div class="col-4">
								<label>Project :</label>
								<select name="Project_id" id="Project_id" required>
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
									
									if ($row['id'] == isset($Project_id)) {
										echo '<option selected value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
									} else {
										echo '<option value="' . $row['id'] . '"><span class="ellipsis">' . $name . '</span></option>';
									}
									}
									?>
								</select>
							</div>
							<div class="col-4">
								<label>Start Date:</label>
								<?php
									if(isset($_GET['Start_date'])){ ?>
										<input type="date" name="Start_date" id="Start_date" value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" required>
								<?php } else { ?>
									<input type="date" name="Start_date" id="Start_date" value="<?php echo isset($Start_date) ? $Start_date : ''; ?>" required>

								<?php }  ?>
							</div>
							<div class="col-4">
								<label>End Date:</label>
								<?php
									if(isset($_GET['End_date'])){ ?>
									<input type="date" name="End_date" id="End_date" value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" required>
									<?php } else { ?>
									<input type="date" name="End_date" id="End_date" value="<?php echo isset($End_date) ? $End_date : ''; ?>" required>
									<?php }  ?>
							</div>
						</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col">
						<div class="day-input-container">
							<div class="day-input-container_inner">
								<div class="day-label">Monday:</div>
								<input type="number" class="day-input" id="Monday" value="<?php echo isset($Monday) ? intval($Monday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Tuesday:</div>
								<input type="number" class="day-input" id="Tuesday" value="<?php echo isset($Tuesday) ? intval($Tuesday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Wednesday:</div>
								<input type="number" class="day-input" id="Wednesday" value="<?php echo isset($Wednesday) ? intval($Wednesday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Thursday:</div>
								<input type="number" class="day-input" id="Thursday" value="<?php echo isset($Thursday) ? intval($Thursday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Friday:</div>
								<input type="number" class="day-input" id="Friday" value="<?php echo isset($Friday) ? intval($Friday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Saturday:</div>
								<input type="number" class="day-input" id="Saturday" value="<?php echo isset($Saturday) ? intval($Saturday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
                            <div class="day-input-container_inner">
								<div class="day-label">Sunday:</div>
								<input type="number" class="day-input" id="Sunday" value="<?php echo isset($Sunday) ? intval($Sunday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top : 20px;">
					<div class="col-3">
					<div style="font-size:20px; font-weight:500">Total Hours:
						
							<span id="totalHours"><?php echo isset($Total_hour) ? intval($Total_hour) : 0 ; ?> Hours</span>
					
					</div>
					</div>
                    <div class="col-3"> 
                        <input type="hidden" name="User_id" id="User_id" value="<?php echo  isset($User_id) ? $User_id : $_SESSION['login_id']?>">
						<input type="hidden" name="id" id="id" value="<?php echo isset($Id) ? $Id : '' ?>">

                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                    </div>
                    <div class="col-6">
                    </div>
				</div>
			</div>
		</form>
    </div>
</div>


<script>

function updateTotalHours() {
    // Get the values from the day input fields
    var sundayHours = parseFloat(document.getElementById("Sunday").value) || 0;
    var mondayHours = parseFloat(document.getElementById("Monday").value) || 0;
    var tuesdayHours = parseFloat(document.getElementById("Tuesday").value) || 0;
    var wednesdayHours = parseFloat(document.getElementById("Wednesday").value) || 0;
    var thursdayHours = parseFloat(document.getElementById("Thursday").value) || 0;
    var fridayHours = parseFloat(document.getElementById("Friday").value) || 0;
    var saturdayHours = parseFloat(document.getElementById("Saturday").value) || 0;

    // Calculate the total hours
    var totalHours = sundayHours + mondayHours + tuesdayHours + wednesdayHours + thursdayHours + fridayHours + saturdayHours;

    // Update the "Total Hours" element
    document.getElementById("totalHours").textContent = totalHours;
}

document.getElementById("myForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        var Project_id = document.getElementById("Project_id").value;
        var Start_date = document.getElementById("Start_date").value;
        var End_date = document.getElementById("End_date").value;
        var Sunday = parseFloat(document.getElementById("Sunday").value) || 0;
        var Monday = parseFloat(document.getElementById("Monday").value) || 0;
        var Tuesday = parseFloat(document.getElementById("Tuesday").value) || 0;
        var Wednesday = parseFloat(document.getElementById("Wednesday").value) || 0;
        var Thursday = parseFloat(document.getElementById("Thursday").value) || 0;
        var Friday = parseFloat(document.getElementById("Friday").value) || 0;
        var Saturday = parseFloat(document.getElementById("Saturday").value) || 0;
        var User_id = document.getElementById('User_id').value;
        var Total_hour = document.getElementById("totalHours").textContent;
		var Id = document.getElementById('id').value;
        $.ajax({
        url: 'ajax.php?action=save_timesheet',
        data: { Project_id: Project_id , Start_date:Start_date, End_date:End_date, Sunday:Sunday,Monday:Monday, Tuesday:Tuesday, Wednesday:Wednesday,Thursday:Thursday, Friday:Friday, Saturday:Saturday ,User_id:User_id, Total_hour:Total_hour, Id:Id},        
        method: 'POST',
        dataType: 'json', // Set the expected response data type to JSON
        success: function(resp) {
            if (resp === 1) {
                alert_toast('Data successfully saved.', "success");
                setTimeout(function() {
					window.location.href = 'index.php?page=view_timesheet';
                }, 750);
            }else {
                alert_toast('Data already exists.', "warning");
                setTimeout(function() {
                }, 750);
            }
            
        }
        }); 
});


</script>