<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$type_arr = array('',"Admin","Project Manager","Developer","Tech Lead","Tester","Guest","Team Lead","Marketing Associate");
    $qry = $conn->query("
    SELECT *
    FROM sprint_list
    JOIN project_list ON sprint_list.Project_Id = project_list.id
    WHERE Sprint_Id = " . $_GET['id']
)->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
}
?>
<div class="container-fluid">
    <div class="card card-widget widget-user">
        <div class="card-header">
           <b><h3 class="card-title"><?php echo ucwords($Sprint_Title) ?></h3></b>
        </div>
        <div class="card-body">
            <div class="sprint_details">
                <div class="sprint_details_inner">
                    <b><span> Project : </span></b>
                    <span><?php echo $name ?></span>
                <div>
                <div class="sprint_details_inner">
                    <b><span>Description :  </span></b>
                    <span><?php echo $Sprint_Description ?></span>
                <div>
                <div class="sprint_details_inner">
                    <b><span>Start Date :  </span></b>
                    <span><?php echo $Sprint_Start_Date ?></span>
                </div>
                <div class="sprint_details_inner">
                    <b><span>End Date : </span></b>
                    <span><?php echo $Sprint_End_Date ?></span>
                </div>
                <div class="sprint_details_inner">
                    <b><span>Status : </span></b>
                    <span><?php echo $Sprint_Status ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer display p-0 m-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>