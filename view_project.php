<?php
include 'db_connect.php';
$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
$id = $_GET['id']; // Ensure to sanitize user input or use prepared statements to prevent SQL injection

$qry = $conn->query("SELECT * FROM project_list 
                    JOIN sprint_list ON sprint_list.Project_Id = project_list.id 
                    WHERE id = $id AND sprint_list.Sprint_Status = 'Open'")->fetch_array();
$project_id = isset($_GET['id']) ? $_GET['id'] : null;
if ($qry == null) {
    echo 'Create a sprint for this project!';
    echo '<a href="./index.php?page=new_sprint&id=' . $_GET['id'] . '"
	class="nav-link  nav-new_sprint  nav-View_Sprint">
          <p>
           Click Here
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>';
    die;
}

foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list join sprint_list ON sprint_list.Sprint_Id = task_list.Sprint_Id  where task_list.project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list join sprint_list ON sprint_list.Sprint_Id = task_list.Sprint_Id  where task_list.project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;

?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Project Name</b></dt>
								<dd style="overflow:auto;"><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Sprint Name</b></dt>
								<dd style="overflow:auto;"><?php echo ucwords($Sprint_Title) ?></dd>
								<dt><b class="border-bottom border-primary">Sprint Desc</b></dt>
								<dd style="overflow:auto;"><?php echo ucwords($Sprint_Description) ?></dd>
								<dt><b class="border-bottom border-primary">Sprint Start Date</b></dt>
								<dd style="overflow:auto;"><?php echo date("F d, Y",strtotime($Sprint_Start_Date)) ?></dd>
								<dt><b class="border-bottom border-primary">Sprint End Date</b></dt>
								<dd style="overflow:auto;"><?php echo date("F d, Y",strtotime($Sprint_Start_Date)) ?></dd>
								<dt><b class="border-bottom border-primary">Description</b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
								<dt><b class="border-bottom border-primary">Guest User</b></dt>
								<dd>
								<?php 
									if(!empty($guest_id)):
										$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($guest_id) order by concat(firstname,' ',lastname) asc");
										while($row=$members->fetch_assoc()):
									?>
												<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="Avatar">
												<p class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></p>
												
									<?php 
										endwhile;
									endif;
								?>
								</dd>
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Start Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">End Date</b></dt>
								<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Status</b></dt>
								<dd>
									<?php
									if($stat[$status] =='Pending'){
									echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									}elseif($stat[$status] =='Started'){
									echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									}elseif($stat[$status] =='On-Progress'){
									echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									}elseif($stat[$status] =='On-Hold'){
									echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									}elseif($stat[$status] =='Over Due'){
									echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									}elseif($stat[$status] =='Done'){
									echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									}
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Project Manager</b></dt>
								<dd>
								<?php 
									if(!empty($manager_id)):
										$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($manager_id) order by concat(firstname,' ',lastname) asc");
										while($row=$members->fetch_assoc()):
									?>
												<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="Avatar">
												<p class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></p>
												
									<?php 
										endwhile;
									endif;
								?>
								</dd>
								<dt><b class="border-bottom border-primary">Tech Lead</b></dt>
								<dd>
								<?php 
									if(!empty($tech_lead_id)):
										$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($tech_lead_id) order by concat(firstname,' ',lastname) asc");
										while($row=$members->fetch_assoc()):
									?>
												<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="Avatar">
												<p class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></p>
												
									<?php 
										endwhile;
									endif;
								?>
								</dd>
								<dt><b class="border-bottom border-primary">Team Lead</b></dt>
								<dd>
								<?php 
									if(!empty($team_lead)):
										$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($team_lead) order by concat(firstname,' ',lastname) asc");
										while($row=$members->fetch_assoc()):
									?>
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="Avatar">	
										<p class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></p>
												
									<?php 
										endwhile;
									endif;
								?>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Team Member/s:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <p class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></p>
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-6"></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Task List:</b></span>
					<?php if ($_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 8): ?>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
					</div>
				<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
					<table class="table tabe-hover table-condensed" id="list">
						<colgroup>
							<col width="5%">
							<col width="20%"> 
							<col width="25%"> 
							<col width="20%">
							<col width="12%"> 
							<col width="12%">
							<?php if ($_SESSION['login_type'] != 6) { ?>
								<col width="12%">
							<?php } ?>

						</colgroup>
						<thead>
							<th>#</th>
							<th>Task</th>
							<th>Description</th>
							<th style="white-space: nowrap;">Estimated Time</th>
							<th>Sub task</th>
							<th>Status</th>
							
							<?php if($_SESSION['login_type'] != 6){ ?>
							<th>Action</th>
							<?php } ?>
						</thead>
						<tbody>
							<?php 
							$i = 1;
							$sessionId = $_SESSION['login_id'];
							if($_SESSION['login_type'] == 3 || $_SESSION['login_type'] == 8){
								$tasks = $conn->query("SELECT * FROM task_list where project_id = {$id} AND user_id = $sessionId order by task asc")->fetch_all(MYSQLI_ASSOC);
							}else if($_SESSION['login_type'] == 5 ){
								$tasks = $conn->query("SELECT * FROM task_list where project_id = {$id} AND tester_id = $sessionId order by task asc")-fetch_all(MYSQLI_ASSOC);

							}
							else {
								$tasks = $conn->query("
    SELECT *
    FROM task_list
    JOIN sprint_list ON task_list.Sprint_Id = sprint_list.Sprint_Id
    WHERE task_list.project_id = {$id} 
    AND sprint_list.Sprint_Status = 'Open'
    ORDER BY task ASC
")->fetch_all(MYSQLI_ASSOC);

							}
								$subtask = $conn->query("SELECT * FROM sub_task_list where project_id = {$id} order by sub_task asc")->fetch_all(MYSQLI_ASSOC);
								$SubtaskDetail = [];
								foreach ($subtask as $row) {
									$task_id = $row['task_id'];
									if (isset($SubtaskDetail[$task_id])) {
										$SubtaskDetail[$task_id]['subtask_count']++;
									} else {
										$SubtaskDetail[$task_id] = [
											'task_id' => $task_id,
											'subtask_count' => 1
										];
									}
								}
								foreach ($tasks as &$data) {
									$task_id = $data['id'];
									if (isset($SubtaskDetail[$task_id])) {
										$data['count'] = $SubtaskDetail[$task_id]['subtask_count'];
									} else {
										$data['count'] = 0;
									}
								}
							$i=1;
							foreach($tasks as $row){
								echo "<tr>";
									echo'<td class="text-center">'. $i .'</td>';
									echo '<td class=""><a class="dropdown-item view_task" href="javascript:void(0)" data-id="' . $row['id'] . '" data-task="' . $row['task'] . '"><b>' . $row['task'] . '</b></a></td>';
									echo '<td class=""><p class="truncate">'.$row['description'].'</p></td>';
									echo '<td class=""><b>' . ($row['estimated_time'] ? $row['estimated_time'] . ' hrs' : '0 hrs') . '</b></td>';
									echo '<td class=""><p>'.$row['count'].'</p></td>';
									echo '<td>';
											if ($row['status'] == 1) {
												echo "<span class='badge badge-secondary'>Pending</span>";
											} elseif ($row['status'] == 2) {
												echo "<span class='badge badge-primary'>In-Progress</span>";
											} elseif ($row['status'] == 3) {
												echo "<span class='badge badge-success'>Done</span>";
											} elseif ($row['status'] == 4) {
												echo "<span class='badge badge-warning'>Testing</span>";
											} elseif ($row['status'] == 5) {
												echo "<span class='badge badge-warning'>On-Hold</span>";
											} elseif ($row['status'] == 6) {
												echo "<span class='badge badge-info'>Ready To Deploy</span>";
											}
									echo '</td>';
									echo '<td class="text-center">';
									if ($_SESSION['login_type'] != 6) {
										echo '<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>';
									}
									echo '<div class="dropdown-menu" style="">';
									echo '<a class="dropdown-item view_task" href="javascript:void(0)" data-id="' . $row['id'] . '" data-task="' . $row['task'] . '">View</a>';
									echo '<div class="dropdown-divider"></div>';
									if ($_SESSION['login_type'] != 3 || $_SESSION['login_type'] != 5) {
										echo '<a class="dropdown-item sub_task" href="javascript:void(0)" data-id="' . $row['id'] . '" data-task="' . $row['task'] . '">Create Sub Task</a>';
										echo '<div class="dropdown-divider"></div>';
										echo '<a class="dropdown-item edit_task" href="javascript:void(0)" data-id="' . $row['id'] . '" data-task="' . $row['task'] . '">Edit</a>';
										echo '<div class="dropdown-divider"></div>';
										echo '<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="' . $row['id'] . '">Delete</a>';
									}
									echo '</div>';
									echo '</td>';
								echo "</tr>";
								$i++;
							}
							?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
$(document).ready(function(){
		$('#list').dataTable()
	$('.new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New Progress for: "+$(this).attr('data-task'),"manage_progress.php?pid="+$(this).attr('data-pid')+"&tid="+$(this).attr('data-tid'),'large')
	})
	})
	$('#new_task').click(function(){
		uni_modal("New Task For <?php echo ucwords($name) ?>","manage_task.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.sub_task').click(function(){
		uni_modal("New Sub Task: " + $(this).attr('data-task'), "sub_task.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id') + "&event=new", "mid-large");
	})
	$('.edit_task').click(function(){
		uni_modal("Edit Task: "+$(this).attr('data-task'),"manage_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.delete_task').click(function(){
	_conf("Are you sure to delete this task?","delete_task",[$(this).attr('data-id')])
	})
	$('.view_task').click(function(){
    	var pid = <?php echo $id ?>;
    	var dataId = $(this).attr('data-id');
    	uni_modal_view("Task Details", "view_task.php?pid=" + pid + "&id=" + dataId, "mid-large");
	});

	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> New Progress","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Edit Progress","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_progress').click(function(){
	_conf("Are you sure to delete this progress?","delete_progress",[$(this).attr('data-id')])
	})
	function delete_progress($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	function delete_task($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_task',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	
</script>