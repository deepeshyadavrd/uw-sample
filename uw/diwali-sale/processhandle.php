<?php
// Assuming you have a database connection established.
$servername = "localhost";
$username = "urbanwood_user";
$password = "Wood@263327";
$dbname = "beta_uw";

// Create a database connection.
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data.
$name = $_POST["name"];
$email = $_POST["email"];
$mobile = $_POST["mobile"];
$message = $_POST["message"];
$state = $_POST["state"];

date_default_timezone_set("Asia/Kolkata");
$date =   date("y-m-d H:i:s");

if($_POST['name'] != ' ' && $_POST['name'] != NULL && $_POST['email'] != ' ' && $_POST['email'] != NULL){
    $sql = "INSERT INTO oc_request_callback SET name = '" . $name . "', email = '" . $email . "', mobile = '" . $mobile . "', state = '" . $state . "', message = '" . mysqli_real_escape_string($conn,$message) . "', date_added = '" . $date . "'";

    $conn->query($sql);
$json = array();
    $to="urbanwoodindia.in@gmail.com";
   $subject="UW festive landing page Callback enquiry";
   $senderName = 'Urbanwood Furniture';
   $senderEmail = 'support@urbanwood.in';

   $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
 
   $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   
   $headers = "From: $from\r\n" . 
   "Cc: urbanwood.in@gmail.com"."\r\n".
   "MIME-Version: 1.0\r\n" .
   "Content-Type: multipart/mixed;\r\n" .
  " boundary=\"{$mime_boundary}\"";
	if(isset($_POST["time_slot"]) || isset($_POST["date"])){
		$message="Enquiry For Furniture (Request Callback)\n\n";
		$message .= "Name: ".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nMobile: ".$_POST['mobile']."\nTime Slot:".$_POST["time_slot"]."\nDate:".$_POST["date"]."\nLooking For:".$_POST["message"];
	}else{
		$message="Call Back Request\n\n";
		$message .= "Name: ".$_POST["name"]."\n"."EMail: ".$_POST['email']."\nState: ".$_POST['state']."\nMobile: ".$_POST['mobile']."\nLooking For: ".$_POST["message"];
	}
    $message = "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
     $message . "\n\n";

   $i=0;
 $message.="--{$mime_boundary}--\n";
  if (mail($to, $subject, $message, $headers)){
	 $json['success'] = 'Service Working';//exit;
  }else{
     $json['error'] = 'error';//.error_get_last()['message'];//'Service Error';exit;
  }
        //$json['data'] = $this->request->post;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
        
    }else{
        $json['error'] = 'empty data';
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }
?>