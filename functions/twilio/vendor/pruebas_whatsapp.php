<?php 
 
	// Update the path below to your autoload.php, 
	// see https://getcomposer.org/doc/01-basic-usage.md 
	require_once 'autoload.php'; 
	 
	use Twilio\Rest\Client; 
	 
	//$sid    = "***"; 
	$token  = "d0133ca5069721bd00f018b71846ecba"; 
	$twilio = new Client($sid, $token); 
	 
	$message = $twilio->messages 
	                  ->create("whatsapp:+34681660826", // to 
	                           array( 
	                               "from" => "whatsapp:+14155238886",       
	                               "body" => "Tu puta madre" 
	                           ) 
	                  ); 
	 
	print($message->sid);

?>