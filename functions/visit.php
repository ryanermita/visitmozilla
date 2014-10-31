<?php
include("../config/config.php");
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{

// Visitors Info.
$email_address = $_REQUEST['email_address'];
$visit_date = $_REQUEST['visit_date'];
$visit_time = date("h:i", strtotime($_REQUEST['visit_time']));
$v_date = date("F d, Y", strtotime($visit_date));
$select_visitor_query="SELECT * FROM visitors_info WHERE email_address='$email_address'";
$execute_select_visitor_query=mysqli_query($db_connection, $select_visitor_query) or die(mysqli_error($db_connection));
$info = mysqli_fetch_assoc($execute_select_visitor_query);
if (mysqli_num_rows($execute_select_visitor_query) == 0){
      $response = array('success' => true, 'reason' => 'We detect that you dont have an account yet. Please register <a href="guest.php">here</a> first.');
      echo json_encode($response);
      exit();
}

$insert_visitors_log_query = "INSERT INTO visitors_log(email_address, date_of_arrival, time_of_arrival) VALUES('$email_address', '$visit_date','$visit_time')";
$execute_insert_visitors_log_query = mysqli_query($db_connection, $insert_visitors_log_query) or die(mysqli_error($db_connection));

/* EMAIL */
// multiple recipients

$to = $email_address;
$to .= "info@mozillaphilippines.org";

// subject
$subject = '[Mozilla Space Manila] Visitor Registration Confirmed';

$message = "
 <html>
  <head>
    <title>RSVP Confirmed</title>
    <style>
    *
    {
      font-family: 'Open Sans', sans-serif;
    }
	p
	{
		font-size:1em;
	}
    </style>
  </head>
  <body>
  <p>Hi there!
This is to confirm that we have received your appointment request on $v_date $visit_time at the Mozilla Community Space Manila. Thank you for using our online appointment service! <br />We are excited to see you!</p>
  <br />
  <p>- Mozilla Community Space Manila Management</p>
</body>
</html>
   ";


// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To:' . $info['first_name'] . ' ' . $info['last_name'] . '<' . $_REQUEST['email_address'] . '>' . "\r\n";
$headers .= "Cc: Mozilla Philippines <info@mozillaphilippines.org>" . "\r\n";
$headers .= "From: Mozilla Philippines <info@mozillaphilippines.org>" . "\r\n";
// Mail it
$retval = mail($to, $subject, $message, $headers);
if( $retval == true )
   {
      $response = array('success' => true, 'reason' => 'Appoinment has been sent!');
      echo json_encode($response);
   }
   else
   {
      $response = array('success' => true, 'reason' => 'Some problem with internet connection!');
      echo json_encode($response);
   }


/* EMAIL END */

mysqli_close($db_connection);
}
else
{
	echo "Who lives in a pineapple under the sea?";
}
?>
