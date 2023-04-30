<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

$m = new spn_user_hs_account();
$u = new spn_utils();
require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$user_hs_email = $_POST["user_hs_email"];

$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
// print('diferente');
$delete = "DELETE FROM app_useraccounts WHERE Email = '$user_hs_email';";
$resultado123 = mysqli_query($mysqli, $delete);
if ($resultado123) {
	print 1;
} else {
	print 0;
}
