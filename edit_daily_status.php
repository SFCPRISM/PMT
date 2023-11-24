<?php
include 'db_connect.php';
$qry = $conn->query("SELECT user_productivity.* FROM user_productivity WHERE user_productivity.id = " . $_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'daily_status.php';
?>