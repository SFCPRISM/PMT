<?php
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."' and Status= '2' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
		//	print_r($_SESSION);
				return 1;
		}else{
			return 2;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
			$data = "";
			foreach ($_POST as $k => $v) {
                if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
                    if (is_string($v)) {
                        $v = "'" . $this->db->real_escape_string($v) . "'";
                    } elseif (is_array($v)) {
                        // Sanitize and join array elements
                        $v = array_map(array($this->db, 'real_escape_string'), $v);
                        $v = "'" . implode(',', $v) . "'";
                    }           
                    if (empty($data)) {
                        $data .= " $k=$v ";
                    } else {
                        $data .= ", $k=$v ";
                    }
                }
            }
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
				if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{ 
			
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_project(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				// Wrap string values in quotes.
				$v = is_string($v) ? "'".$this->db->real_escape_string($v)."'" : $v;
				if(empty($data)){
					$data .= " $k=$v ";
				}else{
					// Handle array values
					if (is_array($v)) {
						// Sanitize and join array elements
						$v = array_map(array($this->db, 'real_escape_string'), $v);
						$v = "'" . implode(',', $v) . "'";
					}
					$data .= ", $k=$v ";
				}
			}
		}
	
		if(empty($id)){
			$save = $this->db->query("INSERT INTO project_list SET $data");
		}else{
			$save = $this->db->query("UPDATE project_list SET $data WHERE id = $id");
		}
	
		if($save){
			return 1;
		}
	}
	function delete_project(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM project_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_task(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/attachment/'. $fname);
			$data .= ", attachment_name	 = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_list set $data");
            /*$save = "INSERT INTO task_list set $data";
			 if ($this->db->query($save) === TRUE) {
				// Get the newly created ID
				$newlyCreatedId = $this->db->insert_id;
				
			   $query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS developer,users.email AS Email, task_list.*,project_list.name AS Project_Name
						  FROM task_list
						  JOIN users ON task_list.user_id = users.id
						  JOIN project_list ON task_list.project_id = project_list.id
						  WHERE task_list.id = " . $newlyCreatedId;
			
				$data = $this->db->query($query)->fetch_assoc();
			}
			if(!empty($data)){
    			$to = $data['Email'];
    			$subject = "New Task Assigned to You " . $data['task'];
                $txt = "Task Name :- {$data['task']},\n Task description: {$data['description']},\n Project Name : {$data['Project_Name']},\n Task Priority : ";
                
                if ($data['priority'] == 1) {
                    $txt .= "High";
                } elseif ($data['priority'] == 2) {
                    $txt .= "Medium";
                } else {
                    $txt .= "Low";
                }
    			$headers = "From: pmt@cloudprism.in" . "\r\n" . "CC: manhar.shyaam@cloudprism.in";
    			
    			$issent = mail($to, $subject, $txt, $headers);
			}*/
				
		}else{
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
		
	}
	function upload_attchament(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($_FILES['file']) && $_FILES['file']['tmp_name'] != ''){
			
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['file']['name'];
			$move = move_uploaded_file($_FILES['file']['tmp_name'],'assets/attachment/'. $fname);
			$data .= ", url	 = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO attachment_list set $data");
		}
		if($save){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO comment_list set $data");
		}else{
			$save = $this->db->query("UPDATE comment_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_sub_task(){
		extract($_POST);
		
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/attachment/'. $fname);
			$data .= ", attachment_name	 = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO sub_task_list set $data");
		}else{
			$save = $this->db->query("UPDATE sub_task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
		
	}
	function delete_sub_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM sub_task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_progress(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'comment')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
    	$dur = abs(strtotime("1970-01-01 $end_time") - strtotime("1970-01-01 $start_time"));
		$dur = $dur / (60 * 60);
		$data .= ", time_rendered='$dur' ";
		// echo "INSERT INTO user_productivity set $data"; exit;
		if(empty($id)){
			$data .= ", user_id={$_SESSION['login_id']} ";
			$save = $this->db->query("INSERT INTO user_productivity set $data");
		}else{
			$save = $this->db->query("UPDATE user_productivity set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_progress(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM user_productivity where id = $id");
		if($delete){
			return 1;
		}
	}
	function get_report(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where date(t.date_created) between '$date_from' and '$date_to' order by unix_timestamp(t.date_created) desc ");
		while($row= $get->fetch_assoc()){
			$row['date_created'] = date("M d, Y",strtotime($row['date_created']));
			$row['name'] = ucwords($row['name']);
			$row['adult_price'] = number_format($row['adult_price'],2);
			$row['child_price'] = number_format($row['child_price'],2);
			$row['amount'] = number_format($row['amount'],2);
			$data[]=$row;
		}
		return json_encode($data);

	}
	function get_attchament(){
		extract($_POST);
		$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, attachment_list.*
				FROM attachment_list 
				JOIN users ON attachment_list.user_id = users.id 
				WHERE attachment_list.task_id = " . $task_id;
		
		$data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
				
		return json_encode($data);
	}
	function  delete_attchament(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM attachment_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function get_comment(){
		extract($_POST);
		$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, comment_list.*
				FROM comment_list 
				JOIN users ON comment_list.user_id = users.id 
				WHERE comment_list.task_id = " . $task_id;
		
		$data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
				return json_encode($data);
	}
		function  delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM comment_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function get_tasklist() {
		extract($_POST);
		if (isset($where) && !empty($where)) {
		
			$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, task_list.*
			FROM task_list
			JOIN users ON task_list.user_id = users.id
			JOIN sprint_list ON task_list.Sprint_Id = sprint_list.Sprint_Id";
  
			$query .= " WHERE task_list.project_id = " . $id . " AND " . $where;
		} elseif (isset($where_subtask) && !empty($where_subtask)) {
		
			$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, sub_task_list.*
					FROM sub_task_list
					JOIN users ON sub_task_list.user_id = users.id
					JOIN sprint_list ON task_list.Sprint_Id = sprint_list.Sprint_Id";
			$query .= " WHERE sub_task_list.project_id = " . $id;
		} else {
		
			$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, task_list.*
					FROM task_list
					JOIN users ON task_list.user_id = users.id
					JOIN sprint_list ON task_list.Sprint_Id = sprint_list.Sprint_Id";
			$query .= " WHERE task_list.project_id = " . $id;
		}
		
		$data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
				return json_encode($data);
	}
	function get_taskdetail(){
		extract($_POST);
		$query = "SELECT task_list.*
					FROM task_list";
		$query .= " WHERE task_list.project_id = " . $id ;
		$data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
		return json_encode($data);
	}
	function get_productivity(){
		extract($_POST);
		$query = "SELECT CONCAT(users.firstname, ' ', users.lastname) AS user_name, project_list.name AS Project_Name,  user_productivity.* 
		FROM user_productivity
		JOIN users ON user_productivity.user_id = users.id
		JOIN project_list ON user_productivity.project_id = project_list.id";

		// Build query conditions based on different scenarios
		if (empty($user)) {
			if (empty($date_from) && empty($date_to)) {
				// Scenario 1: No user, no date range
				// Retrieve all data
			} elseif (empty($date_to)) {
				// Scenario 2: No user, only date_from
				$query .= " WHERE user_productivity.date = '" . $date_from . "'";
			} else {
				// Scenario 3: No user, both date_from and date_to
				$query .= " WHERE user_productivity.date BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
			}
		}else {
			if (empty($date_from) && empty($date_to)) {
				// Scenario 4: User specified, no date range
				$query .= " WHERE user_productivity.user_id IN (" . $user . ")";
			} elseif (empty($date_to)) {
				// Scenario 5: User specified, only date_from
				$query .= " WHERE user_productivity.user_id IN (" . $user . ") AND user_productivity.date = '" . $date_from . "'";
			} elseif (!empty($date_from) && empty($date_to)) {
				// Scenario 6: User specified, only date_to
				$query .= " WHERE user_productivity.user_id IN (" . $user . ") AND user_productivity.date <= '" . $date_to . "'";
			} else {
				// Scenario 7: User specified, both date_from and date_to
				$query .= " WHERE user_productivity.user_id IN (" . $user . ") AND user_productivity.date BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
			}
		}
		
		$data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
		return json_encode($data);
		
	}
	function save_sprint(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('Sprint_Id')) && !is_numeric($k)){
				if($k == 'Sprint_Description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		// echo "INSERT INTO user_productivity set $data"; exit;
		if(empty($Sprint_Id)){
			$save = $this->db->query("INSERT INTO sprint_list set $data");
		}else{
			$save = $this->db->query("UPDATE sprint_list set $data where Sprint_Id = $Sprint_Id");
		}
		if($save){
			return 1;
		}
	}
	function delete_sprint(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM sprint_list where Sprint_Id = $id");
		if($delete){
			return 1;
		}
	}
	function get_log_activity(){
		extract($_POST);
		$table = isset($_POST['table']) ? $this->db->real_escape_string($_POST['table']) : '';
		$getData = $this->db->query("SELECT Query FROM reportobject WHERE Name = '$table'")->fetch_all(MYSQLI_ASSOC);
		$query = $getData[0]['Query'];
		$changeType = $this->db->real_escape_string($_POST['changeType']);
		$date_from = $this->db->real_escape_string($_POST['dateFrom']);
		$date_to = $this->db->real_escape_string($_POST['dateTo']);
		//print_r($query);die;
		$query .= " WHERE change_type = '$changeType' AND timestamp BETWEEN '$date_from' AND '$date_to'";
		$getData = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
		return json_encode($getData);
	}

	function save_timesheet() {
		extract($_POST);
		// Ensure $id is set and is a valid integer
		if(!isset($Id)){
			$duplicateCheck = $this->db->query("SELECT COUNT(*) as count FROM timesheet_list 
			WHERE User_id = '$User_id' 
			AND Project_id = '$Project_id' 
			AND Start_date = '$Start_date' 
			AND End_date = '$End_date'");
	
			$row = $duplicateCheck->fetch_assoc();
			$count = (int)$row['count'];
	
			if ($count > 0) {
				// A duplicate record exists, handle it here (e.g., show an error message)
				return 2;
			}
		
		}
		if ($Id > 0) {
			// Update existing record
			$set_values = [];
			foreach ($_POST as $column => $value) {
				if ($column !== 'id') {
					$set_values[] = "$column = '$value'";
				}
			}
			$set_values = implode(', ', $set_values);
			
			$save = $this->db->query("UPDATE timesheet_list SET $set_values WHERE Id = $Id");
		} else {
			// Insert a new record
			$columns = implode(', ', array_keys($_POST));
			$values = implode("', '", $_POST);
			$save = $this->db->query("INSERT INTO timesheet_list ($columns) VALUES ('$values')");
		}
	
		if ($save) {
			return 1;
		}
	}
	function delete_timesheet(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM timesheet_list where Id = $id");
		if($delete){
			return 1;
		}
	}
	
}