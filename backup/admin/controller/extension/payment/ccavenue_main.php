<?php 
/*Payment Name    : CCAvenue MCPG
Description	  : Payment with CCAvenue MCPG.
Module Version    : 3.0.2
Author		  : CCAvenue 
 */

class Ccavenue_main 
{   
		
	private  $_default_currency="INR";
	private  $_default_language="en";
	private  $_pg_live_url='https://secure.ccavenue.com/transaction.do?command=initiateTransaction';
	private  $_pg_test_url='https://test.ccavenue.com/transaction.do?command=initiateTransaction';
		
 
	public function getfrontform($passdata)
	{
		$getdata = json_decode($passdata,true);
		$customer_info_array = array();
		foreach ($getdata['merchantdata'] as $key => $value)
		{
			$customer_info_array[] = $key.'='.urlencode($value);
		}		
		$customer_info = implode("&",$customer_info_array);
		$encrypted_data = $this->encrypt($customer_info,$getdata['encryptkey']);		
		$access_code = $getdata['data']['access_code'] ;
		if(!isset($getdata['data']['action']))
		{
			$getdata['data']['action'] = $this->getPaymentGatewayUrl();
		}
		
		return '<form action="'.$getdata['data']['action'].'" method="post" id="ccavenuepay_standard_checkout" name="redirect">
					<input type="hidden" name="encRequest" id="encRequest" value="'.$encrypted_data.'" />
					<input type="hidden" name="access_code" id="access_code" value="'.$access_code.'" />
				</form>';	
	}
	
   	public function getAllowedCurrency($payment_currency) {
		$allowedCurrencies = $this->AllowedCurrency();

		if (in_array($payment_currency, $allowedCurrencies)) {
			return $payment_currency;			
		} 
		return false;
	}

	public function AllowedCurrency() {
		$allowedCurrencies = array(
			'AUD','CAD','EUR','GBP','JPY','USD','NZD','CHF','HKD','SGD','SEK',
			'DKK','PLN','NOK','HUF','CZK','ILS','MXN','MYR','BRL','PHP','TWD',
			'THB','TRY','INR', 'AED','USD','GBP','EUR','QAR','SAR','KWD','BHD','OMR'
		);
						
		return $allowedCurrencies;
	}

    public function getAllowedLanguage($req_lang='en') {		
		$allowedLanguages = array('en', 'hi', 'mr', 'gu', 'bn', 'kn', 'pa', 'ta', 'te', 'es', 'ar', 'fr', 'de', 'it', 'ja', 'pt', 'zhCN', 'zhTW');		
		if(in_array($req_lang, $allowedLanguages)) {
			return $req_lang;
		}
		return $this->_default_language;
	}
	
	public function getPaymentGatewayUrl($test_mode)
	{		
		$pg_gateway_url='';
		if($test_mode == 1)
		{
			$pg_gateway_url =$this->_pg_test_url;
		}
		else
		{
			$pg_gateway_url=$this->_pg_live_url;
		}
		return $pg_gateway_url;
	}	

   	public function encrypt($plainText,$key)
	{
		$encryptionMethod = "AES-128-CBC";
		$secretKey        = $this->hextobin(md5($key));
		$initVector       = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText    = openssl_encrypt($plainText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
		return bin2hex($encryptedText);

	}

	public 	function decrypt($encryptedText,$key)
	{
		$encryptionMethod 	= "AES-128-CBC";
		$secretKey 			=  $this->hextobin(md5($key));
		$initVector 		=  pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText  	=  $this->hextobin($encryptedText);
		$decryptedText 		=  openssl_decrypt($encryptedText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}
  
   	public function hextobin($hexString) 
	{ 
		$length = strlen($hexString); 
		$binString="";   
		$count=0; 
		while($count<$length) 
		{       
			$subString =substr($hexString,$count,2);           
			$packedString = pack("H*",$subString); 
			if ($count==0)
			{
				$binString=$packedString;
			} 
			else 
			{
				$binString.=$packedString;
			} 
			$count+=2; 
		} 
		return $binString; 
	}
	  
}