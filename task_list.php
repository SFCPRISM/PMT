<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
			<?php if($_SESSION['login_type'] == 1 ): ?>
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>

        <?php elseif($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 4): ?>
			<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>
			<?php else: ?>

				<?php endif; ?>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="10%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Project</th>
						<th>Task</th>
						<th>Project Started</th>
						<th>Project Due Date</th>
						<th>Project Status</th>
						<th>Task Status</th>
					
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if($_SESSION['login_type'] == 2){
						//$where = " where p.manager_id = '{$_SESSION['login_id']}' ";
						$where = " where concat('[',REPLACE(p.manager_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

					}elseif($_SESSION['login_type'] == 4){
						//$where = " where p.tech_lead_id = '{$_SESSION['login_id']}' ";
						$where = " where concat('[',REPLACE(p.tech_lead_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

					}elseif($_SESSION['login_type'] == 6){
						$where = " where p.guest_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3  || $_SESSION['login_type'] == 8){
						$where = " where concat('[',REPLACE(t.user_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'  ";
					}elseif($_SESSION['login_type'] == 5 ){
						$where = " where concat('[',REPLACE(t.tester_id,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'  ";
					}elseif($_SESSION['login_type'] == 7){
						//$where = " where p.team_lead = '{$_SESSION['login_id']}' ";
						$where = " where concat('[',REPLACE(p.team_lead,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";

					}
					
					$stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
					$qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
						$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['pid']}")->num_rows;
		                if($row['pstatus'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
		                if($prod  > 0  || $cprog > 0)
		                  $row['pstatus'] = 2;
		                else
		                  $row['pstatus'] = 1;
		                elseif($row['pstatus'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
		                $row['pstatus'] = 4;
		                endif;


					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
						<p><a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['pid'] ?>" data-id="<?php echo $row['pid'] ?>"><b><?php echo ucwords($row['pname']) ?></b></a></p>
						</td>
						<td>
							<p><b><?php echo ucwords($row['task']) ?></b></p>
							<p class="truncate"><?php echo strip_tags($desc) ?></p>
						</td>
						<td><b><?php echo date("M d, Y",strtotime($row['start_date'])) ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
						<td class="text-center">
							<?php
							  if($stat[$row['pstatus']] =='Pending'){
							  	echo "<span class='badge badge-secondary'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Started'){
							  	echo "<span class='badge badge-primary'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='On-Progress'){
							  	echo "<span class='badge badge-info'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='On-Hold'){
							  	echo "<span class='badge badge-warning'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Over Due'){
							  	echo "<span class='badge badge-danger'>{$stat[$row['pstatus']]}</span>";
							  }elseif($stat[$row['pstatus']] =='Done'){
							  	echo "<span class='badge badge-success'>{$stat[$row['pstatus']]}</span>";
							  }
							?>
						</td>
						<td>
                        	<?php 
                        	if($row['status'] == 1){
						  		echo "<span class='badge badge-secondary'>Pending</span>";
                        	}elseif($row['status'] == 2){
						  		echo "<span class='badge badge-primary'>On-Progress</span>";
                        	}elseif($row['status'] == 3){
						  		echo "<span class='badge badge-success'>Done</span>";
                        	}elseif($row['status'] == 4){
								echo "<span class='badge badge-warning'>Testing</span>";
						  	}elseif($row['status'] == 5){
								echo "<span class='badge badge-warning'>On-Hold</span>";
						  	}elseif($row['status'] == 6){
								echo "<span class='badge badge-info'>Ready To Deploy</span>";
						  	}
                        	?>
                        </td>
						
					</tr>	
				<?php endwhile; ?>
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

	})
	function delete_project($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_project',
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