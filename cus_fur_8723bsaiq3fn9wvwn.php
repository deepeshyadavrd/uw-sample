
 <?php
 if ($_SERVER['REQUEST_METHOD']=="POST"){
  
  // $to = "girish.sembara46@gmail.com ";
   $to="urbanwoodindia.in@gmail.com";
   $subject="Custom furniture enquiry";
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
    $message .= "Name:".$_POST["name"]."\n"."EMail:".$_POST['email']."\nMobile:".$_POST['mobile']."\n"."City:".$_POST['city']."\nPincode:".$_POST['postcode']."\nMessage:".$_POST["detail"];
    $message = "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
     $message . "\n\n";

   $i=0;
  
   foreach($_FILES['upload_file']['name'] as $userfile){ 
    //  print_r($userfile);
          $tmp_name = $_FILES['upload_file']['tmp_name'][$i];
          $type = $_FILES['upload_file']['type'][$i];
          $name = $_FILES['upload_file']['name'][$i];
          $size = $_FILES['upload_file']['size'][$i];

    $i++;
   
      if (file_exists($tmp_name)){
    
        // echo 'file_exists';
         if(is_uploaded_file($tmp_name)){
    
           
            $file = fopen($tmp_name,'rb');
    
           
            $data = fread($file,filesize($tmp_name));
    
           
            fclose($file);
    
            
            $data = chunk_split(base64_encode($data));
         }
         $message .= "--{$mime_boundary}\n" .
            "Content-Type: {$type};\n" .
            " name=\"{$name}\"\n" .
            "Content-Disposition: attachment;\n" .
            " filename=\"{$fileatt_name}\"\n" .
            "Content-Transfer-Encoding: base64\n\n" .
         $data . "\n\n";
      }
 }
 $message.="--{$mime_boundary}--\n";
  if (@mail($to, $subject, $message, $headers))
     echo "Service Working as 87wfbewjf8hlfwkvwvivhwlnv8f3";
  else
     echo "Service Working as keufwf23n9h2fnekfefnihfnfenf";
 } else {
     echo 'Access Forbidden';exit;
 }
 
 ?>
 