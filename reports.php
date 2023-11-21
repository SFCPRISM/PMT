<?php include 'db_connect.php' ?>
 <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-header">
            <b>Project Progress</b>
            <div class="card-tools">
            	<button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive" id="printable">
              <table class="table m-0 table-bordered">
              <colgroup>
                  <col width="5%">
                  <col width="15%">
                  <col width="15%">
                  <col width="10%">
                  <col width="10%">
                  <col width="10%">
                  <col width="10%">
                  <col width="10%">
                  <col width="10%">
                  <col width="12%">
                  <col width="10%">
                  <col width="8%">
              </colgroup>
                <thead>
                  <th>#</th>
                  <th>Project</th>
                  <th>Task</th>
                  <th>Bugs</th>
                  <th>Story</th>
                  <th>Sub Task</th>
                  <th>Pending</th>
                  <th>On progress</th>
                  <th>Testing</th>
                  <th>Done</th>
                  <th>Work Duration</th>
                  <th>Progress</th>
                  <th>Status</th>
                </thead>
                <tbody>
                <?php
                $i = 1;
                $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
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
                $qry = $conn->query("SELECT * FROM project_list $where order by name asc");
                while($row= $qry->fetch_assoc()):
                $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                $tbugprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND task_type = 2")->num_rows;
                $tstoryprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND task_type = 1")->num_rows;
                $tsubtaskprog = $conn->query("SELECT * FROM sub_task_list where project_id = {$row['id']} ")->num_rows;
                $tpendingprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND status = 1 ")->num_rows;
                $tonprogressprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND status = 2 ")->num_rows;
                $ttestingprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND status = 4 ")->num_rows;

                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                $prod = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} AND task_type = 1 ")->num_rows;
                $devdur = $conn->query("SELECT sum(estimated_time) as devduration FROM task_list where project_id = {$row['id']}");
                $testdur = $conn->query("SELECT sum(testing_time) as testduration FROM task_list where project_id = {$row['id']}");
                $devdur_sub = $conn->query("SELECT sum(estimated_time) as devduration FROM sub_task_list where project_id = {$row['id']}");
                $testdur_sub = $conn->query("SELECT sum(testing_time) as testduration FROM sub_task_list where project_id = {$row['id']}");

                $devdur = $devdur->num_rows > 0 ? $devdur->fetch_assoc()['devduration'] : 0;
                $testdur = $testdur->num_rows > 0 ? $testdur->fetch_assoc()['testduration'] : 0;
                $devdur_sub = $devdur_sub->num_rows > 0 ? $devdur_sub->fetch_assoc()['devduration'] : 0;
                $testdur_sub = $testdur_sub->num_rows > 0 ? $testdur_sub->fetch_assoc()['testduration'] : 0;
                if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
                if($prod  > 0  || $cprog > 0)
                  $row['status'] = 2;
                else
                  $row['status'] = 1;
                elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
                $row['status'] = 4;
                endif;
                  ?>
                  <tr>
                      <td>
                         <?php echo $i++ ?>
                      </td>
                      <td>
                      <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><b><?php echo ucwords($row['name']) ?></b></a>
                          <small>
                              Due: <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
                          </small>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_task" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_bug" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tbugprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_story" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tstoryprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_subtask" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tsubtaskprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_pending" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tpendingprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_onprogress" data-id="<?php echo $row['id'] ?>"><?php echo number_format($tonprogressprog) ?></a>
                      </td>
                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_testing" data-id="<?php echo $row['id'] ?>"><?php echo number_format($ttestingprog) ?></a>
                      </td>

                      <td class="text-center">
                      <a class="dropdown-item list_view" href="./index.php?page=list_view&id=<?php echo $row['id']; ?>&type=total_done" data-id="<?php echo $row['id'] ?>"><?php echo number_format($cprog) ?></a>
                      </td>
                      <td class="text-center">
                      	Total :<?php echo number_format($devdur + $testdur +$devdur_sub +$testdur_sub).' Hr/s.' ?>
                        <small><small>
                              Devlopment:<?php echo number_format($devdur+$devdur_sub).' Hr/s.' ?>
                          </small></small>
                          <small><small>
                              Testing: <?php echo number_format($testdur+$testdur_sub).' Hr/s.' ?>
                          </small></small>
                      </td>
                      <td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                              </div>
                          </div>
                          <small>
                              <?php echo $prog ?>% Complete
                          </small>
                      </td>
                      <td class="project-state">
                          <?php
                            if($stat[$row['status']] =='Pending'){
                              echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Started'){
                              echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='On-Progress'){
                              echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='On-Hold'){
                              echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Over Due'){
                              echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                            }elseif($stat[$row['status']] =='Done'){
                              echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
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
<script>
	$(document).ready(function() {
		$('#print').click(function(){

			var _h = $('head').clone();
			var _p = $('#printable').clone();
			var _d = "<p class='text-center'><b>Project Progress Report as of (" + (new Date().toLocaleDateString()) + ")</b></p>";
			_p.prepend(_d);
			_p.prepend(_h);
			var nw = window.open("","","width=1200,height=600");
			nw.document.write(_p.html());
			nw.document.close();

			// Add a delay before printing
			setTimeout(function() {
				nw.print();
				setTimeout(function(){
					nw.close();
				}, 750);
			}, 500);
		});
	});
</script>

