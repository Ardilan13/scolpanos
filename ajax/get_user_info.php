<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
session_start();

$a = new spn_user_hs_account();
print $a->read_user_account( $_GET["userGUID"],  appconfig::GetDummy());

?>