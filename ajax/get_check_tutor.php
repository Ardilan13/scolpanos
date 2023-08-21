<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
require_once("../classes/spn_user_hs_account.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
$baseurl = appconfig::GetBaseURL();

$a = new spn_user_hs_account();
print $a->check_mentor_in_klas($_GET["klas"], $_GET["userGUID"], $_GET["type_check"], appconfig::GetDummy());
