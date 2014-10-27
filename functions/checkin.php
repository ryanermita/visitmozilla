<?php
//Connect to the database
require_once '../config/config.php';
date_default_timezone_set("Asia/Manila");

$id=$_POST['id'];

//Select record in walkin_attendees using email
$select_visitor_log_query="SELECT * FROM visitors_log WHERE log_id = '$id'";
$execute_select_visitor_log_query=mysqli_query($db_connection,$select_visitor_log_query) or die(mysqli_error($db_connection));

$result = mysqli_fetch_assoc($execute_select_visitor_log_query);
$status = $result['check_in_status'];
$email = $result['email_address'];
$datetime_checked_in = date('Y/m/d H:i:s');
$checked_in = date("M d, Y - g:i a", strtotime($datetime_checked_in));
$value='';

if($status == true){
  $value = false;
}
else{
  $value = true;
}

$checkin_query="UPDATE visitors_log SET check_in_status='$value', datetime_checked_in='$datetime_checked_in' WHERE log_id = '$id'";
$execute_checkin_query=mysqli_query($db_connection, $checkin_query) or die(mysqli_error($db_connection));

if ($value){
  $response = array('success' => true, 'check_in_date' => $checked_in);
}
else{
  $response = array('success' => false, 'check_in_date' => '');
}
echo json_encode($response);
?>



