<?php



require_once("../classes/spn_user_account.php");

require_once("../config/app.config.php");

require_once("../classes/spn_authentication.php");



/*

configuration for the detail table to be shown on screen

the $baseurl & $detailpage will be used to create the "View Details" link in the table

*/

$baseurl = appconfig::GetBaseURL();



if (session_status() == PHP_SESSION_NONE)

  session_start();



$auth = new spn_authentication();



$u = $auth->TokenExistsInDatabase($_POST["token"]);



if ($u == 1) {
  print $auth->ChangePassword($_POST["username"], $_POST["password"], false);
} else {
  print 0;
}
