
 <?php
 if ($_SERVER['REQUEST_METHOD']=="POST"){
  
  // $to = "girish.sembara46@gmail.com ";
   $to="nitish.urbanwood07@gmail.com";
   $subject="Request Callback enquiry";
   $senderName = 'Urbanwood Furniture';
   $senderEmail = 'support@urbanwood.in';

   $from = stripslashes($senderName)."<".stripslashes($senderEmail).">";
 
   $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

   
   $headers = "From: $from\r\n" . 
   "Cc: urbanwood.in@gmail.com\r\n".
   "MIME-Version: 1.0\r\n" .
   "Content-Type: multipart/mixed;\r\n" .
  " boundary=\"{$mime_boundary}\"";
    $message="Enquiry For Custom Furniture\n\n";
    $message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\n"."City:".$_POST['city']."\nPincode:".$_POST['postcode']."\nMessage:".$_POST["detail"]."\nSource:".$_SERVER['HTTP_REFERER'];
    $message = "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
     $message . "\n\n";

   $i=0;
  
  
 $message.="--{$mime_boundary}--\n";
  if (@mail($to, $subject, $message, $headers))
     echo "Service Working as 87wfbewjf8hlfwkvwvivhwlnv8f3";
  else
     echo "Service Working as keufwf23n9h2fnekfefnihfnfenf";
 } else {
     echo 'Access Forbidden';exit;
 }
 
 ?>
 