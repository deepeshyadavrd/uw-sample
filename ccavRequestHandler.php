<html>
<head>
<title> CCAvenue Payment Gateway Integration kit</title>
</head>
<style type="text/css">
	.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: #222;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 75px;
    height: 75px;
    display: inline-block;
    border-width: 2px;
    border-color: rgba(255, 255, 255, 0.05);
    border-top-color: #fff;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}
</style>
<body>
	<div class="overlay">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>
<center>

<?php

 include('Crypto.php')?>
<?php require_once "config.php"; ?>
<?php 

	error_reporting(0);
	
	$merchant_data='';
	$working_key = '3F5DDDDCCCE93ED2C4D077F51B940100';
	$access_code = 'AVJO72KF47AB26OJBA';
	
	foreach ($_POST as $key => $value){
        if($key != 'order_id'){
		$merchant_data.=$key.'='.$value.'&';
        }
	}
	$merchant_data .= "order_id=".$_POST['order_id'];
	
	$encrypted_data=encrypt($merchant_data,$working_key);

?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
<input type="submit" name="SUBMIT">
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>