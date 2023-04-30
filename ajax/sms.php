<?php

//Enviar SMS
$to = "584122352773,2975928522";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://smpp2.routesms.com:8080/bulksms/bulksms?username=smartext&password=s8k7k7g5&type=0&dlr=1&destination=".$to."&source=scolpanos&message=Logon%20user%20".$_REQUEST['user']);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec ($ch);
curl_close ($ch);

echo $remote_server_output;

?>
