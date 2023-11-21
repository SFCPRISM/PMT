<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
            <?php if($_SESSION['login_type'] != 3 && $_SESSION['login_type'] != 5 && $_SESSION['login_type'] != 7 && $_SESSION['login_type'] != 6 && $_SESSION['login_type'] != 8): ?>
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_sprint"><i class="fa fa-plus"></i> Add New Sprint</a>
			</div>
            <?php endif; ?>
		</div> 
		<div class="card-body">
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
                <th>Sprint</th>
                <th>Project</th>
                <th style="white-space: nowrap;">Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                
                <?php if($_SESSION['login_type'] != 6){ ?>
                <th>Action</th>
                <?php } ?>
            </thead>
				<tbody>
					<?php
					$i = 1;
					$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
					$where = "";
					if($_SESSION['login_type'] == 2){
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
                    $sprint_list = $conn->query("SELECT * FROM sprint_list
                    JOIN project_list ON Project_Id = project_list.id
                    $where
                    ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
                    $i=1;
							foreach($sprint_list as $row){
								echo "<tr>";
									echo'<td class="text-center">'. $i .'</td>';
									echo '<td class=""><a class="dropdown-item view_sprint" href="javascript:void(0)" data-id="' . $row['Sprint_Id'] . '" data-title="' . $row['Sprint_Title'] . '"><b>' . $row['Sprint_Title'] . '</b></a></td>';
									echo '<td class=""><p class="">'.$row['name'].'</p></td>';
                                    echo '<td class=""><p>' . date("M d, Y", strtotime($row['Sprint_Start_Date'])) . '</p></td>';
                                    echo '<td class=""><p>' . date("M d, Y", strtotime($row['Sprint_End_Date'])) . '</p></td>';
									echo '<td>';
											if ($row['Sprint_Status'] == 'Open') {
												echo "<span class='badge badge-primary'>Open</span>";
											} elseif ($row['Sprint_Status'] == 'Closed') {
												echo "<span class='badge badge-warning'>Closed</span>";
											}
									echo '</td>';
									echo '<td class="text-center">';
									if ($_SESSION['login_type'] != 6) {
										echo '<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>';
									}
									echo '<div class="dropdown-menu" style="">';
									echo '<a class="dropdown-item view_sprint" href="javascript:void(0)" data-id="' . $row['Sprint_Id'] . '" data-title="' . $row['Sprint_Title'] . '">View</a>';
									echo '<div class="dropdown-divider"></div>';
									if ($_SESSION['login_type'] != 3 || $_SESSION['login_type'] != 5) {
										echo '<div class="dropdown-divider"></div>';
										echo '<a class="dropdown-item edit_sprint" href="./index.php?page=edit_sprint&id=' . $row['Sprint_Id'] . '" data-title="' . $row['Sprint_Title'] . '">Edit</a>';
										echo '<div class="dropdown-divider"></div>';
										echo '<a class="dropdown-item delete_sprint" href="javascript:void(0)" data-id="' . $row['Sprint_Id'] . '">Delete</a>';
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

	$('#list').on('click', '.view_sprint', function () {
		uni_modal("<i class='fa fa-id-card'></i> Sprint Details", "view_sprint.php?id=" + $(this).attr('data-id'));
	});
	$('.delete_sprint').click(function(){
	_conf("Are you sure to delete this project?","delete_sprint",[$(this).attr('data-id')])
	})
	})
	function delete_sprint($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_sprint',
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