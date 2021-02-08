<?php
$resp = array();
    session_start();
    include '../Connect.php';
    $json =file_get_contents('php://input');
    $obj =json_decode($json);
    $DeviceId =$obj->{'DeviceId'};
   $FirstName =$obj->{'FirstName'};
   $LastName =$obj->{'LastName'};
   $Mail =$obj->{'Email'};
   $PhoneNumber =$obj->{'Phone'};
   
   $dataarray =array();
   array_push($dataarray, $FirstName, $LastName, $Mail,$PhoneNumber);
   $count =sizeof($dataarray);
	
   $method ='AES-256-CBC';
   $key=getenv('NAMESPACED_CRYPTO_KEY');
   $length =openssl_cipher_iv_length($method);
   $iv =openssl_random_pseudo_bytes($length);
   
   
   for($i =0;$i<$count;$i++)
   {
   	$ciphertext =openssl_encrypt( $dataarray[$i], $method, $key, OPENSSL_RAW_DATA, $iv);
   	$encode =base64_encode($ciphertext);
   	$decode =base64_decode($encode);
   	$plaintext =openssl_decrypt($decode,$method,$key,OPENSSL_RAW_DATA,$iv);
   	array_push($resp,$plaintext);
	

   }
   echo json_encode($resp);
   
   
   
   
   
    

?>
