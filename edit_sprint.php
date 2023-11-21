<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM sprint_list where Sprint_Id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'new_sprint.php';
?>