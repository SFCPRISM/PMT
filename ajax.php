<?php
session_start();
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}

if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'update_user'){
	$save = $crud->update_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'save_project'){
	$save = $crud->save_project();
	if($save)
		echo $save;
}
if($action == 'delete_project'){
	$save = $crud->delete_project();
	if($save)
		echo $save;
}
if($action == 'save_task'){
	$save = $crud->save_task();
	if($save)
		echo $save;
}
if($action == 'save_sub_task'){
	$save = $crud->save_sub_task();
	if($save)
		echo $save;
}
if($action == 'delete_task'){
	$save = $crud->delete_task();
	if($save)
		echo $save;
}
if($action == 'save_progress'){
	$save = $crud->save_progress();
	if($save)
		echo $save;
}
if($action == 'delete_progress'){
	$save = $crud->delete_progress();
	if($save)
		echo $save;
}
if($action == 'get_report'){
	$get = $crud->get_report();
	if($get)
		echo $get;
}
if($action == 'upload_attchament'){
	$save = $crud->upload_attchament();
	if($save)
		echo $save;
}
if($action == 'get_attchament'){
	$get = $crud->get_attchament();
	if($get)
		echo $get;
}
if($action == 'delete_attchament'){
	$save = $crud->delete_attchament();
	if($save)
		echo $save;
}
if($action == 'save_comment'){
	$save = $crud->save_comment();
	if($save)
		echo $save;
}
if($action == 'get_comment'){
	$get = $crud->get_comment();
	if($get)
		echo $get;
}
if($action =='delete_comment'){
	$save = $crud->delete_comment();
	if($save)
		echo $save;
}
if($action == 'delete_sub_task'){
	$save = $crud->delete_sub_task();
	if($save)
	echo $save;
}
if($action == 'get_tasklist'){
	$get = $crud->get_tasklist();
	if($get)
	echo $get;
}
if($action == 'get_taskdetail'){
	$get = $crud->get_taskdetail();
	if($get)
	echo $get;
}
if($action == 'get_productivity'){
	$get = $crud->get_productivity();
	if($get)
	echo $get;
}
if($action == 'save_sprint'){
	$save = $crud->save_sprint();
	if($save)
	echo $save;
}
if($action == 'delete_sprint'){
	$save = $crud->delete_sprint();
	if($save)
	echo $save;
}
if($action == 'get_log_activity'){
	$get = $crud->get_log_activity();
	if($get)
	echo $get;
}
if($action == 'save_timesheet'){
	$save = $crud->save_timesheet();
	if($save)
	echo $save;
}
if($action == 'delete_timesheet'){
	$save = $crud->delete_timesheet();
	if($save)
	echo $save;
}

ob_end_flush();
?>
