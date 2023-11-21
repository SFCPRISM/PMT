<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
					<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=calendar"><i class="fa fa-plus"></i> Create New Timesheet</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="35%">
					<col width="25%">
					<col width="20%">
					<col width="20%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Date</th>
						<th>Project</th>
						<th>Hours</th>
						<?php 
						if ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 6) {
						?>
						<th>Developer</th>
						<?php
						}
						?>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $i = 1;
                        $where = "";
                        $rows = array();
                        
                        if ($_SESSION['login_type'] != 2 && $_SESSION['login_type'] != 1 && $_SESSION['login_type'] != 6) {
							$userId = $_SESSION['login_id'];
                            $where = "User_id = '$userId'";
							$result = $conn->query("SELECT timesheet_list.*, project_list.name, CONCAT(users.firstname, ' ', users.lastname) as Developer
							FROM timesheet_list
							JOIN project_list ON timesheet_list.Project_id = project_list.id
							JOIN users ON timesheet_list.User_id = users.id
							WHERE $where
							ORDER BY timesheet_list.Created_at ASC");
							
                        }else {
							$result = $conn->query("SELECT timesheet_list.*, project_list.name, CONCAT(users.firstname, ' ', users.lastname) as Developer
							FROM timesheet_list
							JOIN project_list ON timesheet_list.Project_id = project_list.id
							JOIN users ON timesheet_list.User_id = users.id
							ORDER BY timesheet_list.Created_at ASC");

							
                        }
                        if ($result) {
                            $data = $result->fetch_all(MYSQLI_ASSOC);
                        }
                        $timesheet = array();
                        foreach ($data as $row) {
                            $startDate = $row['Start_date'];
                            $endDate = $row['End_date'];
                            $row['Date'] = $startDate . ' - ' . $endDate;
                            $timesheet[] = $row;
                        }
                    
                        // Output the new array
                        foreach($timesheet as $row){
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $row['Date'] . '</td>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['Total_hour'] . '</td>';
							if ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 6) {
							echo '<td>' . $row['Developer'] . '</td>';
							}
							?>
                            <td class="text-center">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                Action
                                </button>
                                <div class="dropdown-menu" style="">
                                <a class="dropdown-item view_timesheet_detail" href="./index.php?page=view_timesheet_detail&id=<?php echo $row['Id'] ?>" data-id="<?php echo $row['Id'] ?>">View</a>
                                <div class="dropdown-divider"></div>
                                <?php if($_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 8 && $_SESSION['login_type'] != 6): ?>
                                <a class="dropdown-item" href="./index.php?page=edit_timesheet&id=<?php echo $row['Id'] ?>">Edit</a>
                                <div class="dropdown-divider"></div>
                                <?php if($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2){ ?>
                                <a class="dropdown-item delete_timesheet" href="javascript:void(0)" data-id="<?php echo $row['Id'] ?>">Delete</a>
                                    <?php }?>
                            <?php endif; ?>
                                </div>
                            </td>

                            <?php
                            echo '</tr>';
                            $i++;
                        
                        }
                        
					?>	
				</tbody>
			</table>
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
	$(document).ready(function(){
		$('#list').dataTable()

		$('#list').on('click', '.view_timesheet_detail', function () {
			uni_modal("<i class='fa fa-id-card'></i> Time Sheet", "view_timesheet_detail.php?id=" + $(this).attr('data-id'));
		});
	
	$('.delete_timesheet').click(function(){
	_conf("Are you sure to delete this project?","delete_project",[$(this).attr('data-id')])
	})
	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_timesheet',
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