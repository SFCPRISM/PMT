<?php
include 'db_connect.php';
$qry = $conn->query("SELECT user_productivity.*, task_list.task FROM user_productivity JOIN task_list ON task_list.id = user_productivity.task_id WHERE user_productivity.id = " . $_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'daily_status.php';
?>