<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT timesheet_list.*, project_list.name, CONCAT(users.firstname, ' ', users.lastname) as Developer
        FROM timesheet_list
        JOIN project_list ON timesheet_list.Project_id = project_list.id
        JOIN users ON timesheet_list.User_id = users.id
        WHERE timesheet_list.Id = " . (int)$_GET['id'] . "
        ORDER BY timesheet_list.Created_at ASC")->fetch_array();
//print_r($qry);die;
foreach($qry as $k => $v){
	$$k = $v;
}
}
?>
<div class="col-lg-12">
    <div class="card card-outline card-success">
		<form method="POST" id="myForm">
			<div class="card-header">
				<div class="card-header_inner">
					<div class="row">
							<div class="col-4">
								<label>Project :</label>
								<select name="Project_id" id="Project_id" disabled required>
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
										<input type="date" name="Start_date" id="Start_date" disabled value="<?php echo isset($_GET['Start_date']) ? $_GET['Start_date'] : ''; ?>" required>
								<?php } else { ?>
									<input type="date" name="Start_date" id="Start_date" disabled value="<?php echo isset($Start_date) ? $Start_date : ''; ?>" required>

								<?php }  ?>
							</div>
							<div class="col-4">
								<label>End Date:</label>
								<?php
									if(isset($_GET['End_date'])){ ?>
									<input type="date" name="End_date" id="End_date" disabled value="<?php echo isset($_GET['End_date']) ? $_GET['End_date'] : ''; ?>" required>
									<?php } else { ?>
									<input type="date" name="End_date" id="End_date" disabled value="<?php echo isset($End_date) ? $End_date : ''; ?>" required>
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
								<input type="number" class="day-input" id="Monday" disabled value="<?php echo isset($Monday) ? intval($Monday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Tuesday:</div>
								<input type="number" class="day-input" id="Tuesday" disabled value="<?php echo isset($Tuesday) ? intval($Tuesday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Wednesday:</div>
								<input type="number" class="day-input" id="Wednesday" disabled value="<?php echo isset($Wednesday) ? intval($Wednesday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Thursday:</div>
								<input type="number" class="day-input" id="Thursday" disabled value="<?php echo isset($Thursday) ? intval($Thursday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Friday:</div>
								<input type="number" class="day-input" id="Friday" disabled value="<?php echo isset($Friday) ? intval($Friday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
							<div class="day-input-container_inner">
								<div class="day-label">Saturday:</div>
								<input type="number" class="day-input" id="Saturday" disabled value="<?php echo isset($Saturday) ? intval($Saturday) : ''; ?>" onkeyup ="updateTotalHours()">
							</div>
                            <div class="day-input-container_inner">
								<div class="day-label">Sunday:</div>
								<input type="number" class="day-input" id="Sunday" disabled value="<?php echo isset($Sunday) ? intval($Sunday) : ''; ?>" onkeyup ="updateTotalHours()">
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
                    <div style="font-size:20px; font-weight:500">Developer :
						
                        <span id=""><?php echo isset($Developer) ? ($Developer) : '' ; ?></span>
                
                </div>
                    </div>
                    <div class="col-6">
                    </div>
				</div>
			</div>
		</form>
    </div>
</div>