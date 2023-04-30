<?php

require_once("../classes/spn_user_account.php");
require_once("../config/app.config.php");
require_once("../classes/spn_authentication.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();

$a = new spn_user_account();
$auth = new spn_authentication();

$u = $a->check_user_email($_GET["email"],appconfig::GetDummy());

if ($u ==1){
  print $auth->ActivateResetPasswordMode($_GET["email"]);
}
else{
  print(0);
}

?>
