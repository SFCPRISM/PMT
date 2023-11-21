<?php
if(isset($_SESSION['login_id'])){
	$userId = $_SESSION['login_id'];
    $conn = new mysqli('localhost', 'root', '', 'pms_db') or die("Could not connect to mysql" . mysqli_error($conn));
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    $query = "SET @current_user_id = " . $userId;
    $conn->query($query);
}else {
    $conn = new mysqli('localhost', 'root', '', 'pms_db') or die("Could not connect to mysql" . mysqli_error($conn));

}

