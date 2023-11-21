<?php 
include 'db_connect.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT tl.*, pl.manager_id, user.firstname, user.lastname, concat(user.firstname,' ',user.lastname) as username 
                        FROM task_list tl 
                        INNER JOIN project_list pl ON tl.project_id = pl.id
						INNER JOIN users user ON tl.user_id = user.id 
                        WHERE tl.id = ".$_GET['id'])->fetch_array();

    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}
$subtasks = $conn->query("SELECT * FROM sub_task_list WHERE task_id =" .$_GET['id'])->fetch_all(MYSQLI_ASSOC);
$allusers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3")->fetch_all(MYSQLI_ASSOC);
//print_r($allusers);die;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type IN (2, 4)")->fetch_all(MYSQLI_ASSOC);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-6">
			<dl>
				<dt><b class="border-bottom border-primary">Task</b></dt>
				<dd><?php echo ucwords($task) ?></dd>
			</dl>
			<dl>
		<dt><b class="border-bottom border-primary">Status</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-secondary'>Pending</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-primary'>On-Progress</span>";
        	}elseif($status == 3){
		  		echo "<span class='badge badge-success'>Done</span>";
        	}else{
				echo "<span class='badge badge-warning'>Testing</span>";
		  	}
        	?>
		</dd>
			</dl>
			<dl>
		<dt><b class="border-bottom border-primary">Description</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
			</dl>
			<dl>
		<dt><b class="border-bottom border-primary">Developer</b></dt>
		<dd><?php echo html_entity_decode($username) ?></dd>
			</dl>
			<dl>
  				<dt><b class="border-bottom border-primary">Start Date</b></dt>
  				<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
			</dl>

			<dl>
		<dt><b class="border-bottom border-primary">End Date</b></dt>
		<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
			</dl>
			<dl>
		<dt><b class="border-bottom border-primary">Estimated Time</b></dt>
		<dd><?php echo html_entity_decode($estimated_time) ?></dd>
			</dl>
			<dl>
		<dt><b class="border-bottom border-primary">Project Manager</b></dt>
		<?php 
	
			$managerFound = false; // Flag to check if manager is found

			foreach ($manager as $data) {
		if ($data['id'] == $manager_id) {
			echo '<dd>'. $data['name'] . '</dd>';
			$managerFound = true; // Set flag to true if manager is found
		}
			}

			// If no managers are found, print the message
			if (!$managerFound) {
		echo '<dd><small><i>Manager Deleted from Database</i></small></dd>';
			}
	
		?>
			<dl>
			<dt><b class="border-bottom border-primary">Actual Time</b></dt>
			<dd><?php echo (strtotime($end_date) - strtotime($start_date)) / 3600; ?> hours</dd>
			</dl>
			<dl>
			<dt><b class="border-bottom border-primary">Task Type</b></dt>
			<?php 
			if($task_type == 1)
			{
				echo "<span>Story</span>"; 
			}
			else
			{
				echo "<span>Bug</span>"; 

			}
			
			?>
			</dl>

	</div>
	<div class="col-6">
		<dl>
			<dt><b class="border-bottom border-primary">Sub Task</b></dt>
			
			<?php
				$i = 1;
				foreach($subtasks as $subtask){
					echo '<dd><a class="dropdown-item view_sub_task" href="javascript:void(0)" data-id="'.$subtask['id'].'" data-task="'.$subtask['sub_task'].'" style="color: black;">'.$i.". ".$subtask['sub_task']. '</a></dd>';
					$i = $i + 1;
					}
				?>
			
		</dl>
	</div>
	</div>
</div>
<script>
$('.view_sub_task').click(function(){
		uni_modal("Sub Task Details","view_sub_task.php?id="+$(this).attr('data-id'),"mid-large")
	})
</script>