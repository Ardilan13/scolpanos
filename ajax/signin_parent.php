<?php

require_once("../classes/spn_authentication.php");
require_once("../config/app.config.php");

$a = new spn_authentication();

$studentnumber = $_POST["studentnumber_signin"];
$securepin = $_POST["securepin_signin"];
$schoolID = $_POST["schools"];
$baseurl = appconfig::GetBaseURL();

print  $a->signin_parent($studentnumber, $securepin, $baseurl,$schoolID,false);

?>
