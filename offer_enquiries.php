<?php 

$to = "urbanwood.in@gmail.com";
         $subject = "Enquiry form Offer";
         
         $message = "<b>Lead Details.</b><br>";
         $message .= "Name:".$_POST["name"]."<br>"."EMail:".$_POST['email']."<br>Mobile:".$_POST['mobile']."<br>Source:".$_SERVER['HTTP_REFERER'];
    
         
         $header = "From:info@urbanwood.in \r\n";
         $header .= "Cc:accounts@urbanwood.in \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to,$subject,$message,$header);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent...";
         }
         
?>