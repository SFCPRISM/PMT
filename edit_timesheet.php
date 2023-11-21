<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM timesheet_list where Id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'time_sheet.php';
?>