<?php

require_once("../classes/spn_verzuim_tracker.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

/*
configuration for the detail table to be shown on screen
the $baseurl & $detailpage will be used to create the "View Details" link in the table
*/
if(session_status() == PHP_SESSION_NONE)
session_start();

$datum = '';
$_datum_in = null;



$utils = new spn_utils();

if (isset($_GET['datum'])) {
  $datum = $_GET['datum'];



  if($utils->converttomysqldate($datum) != false)
  {
    $_datum_in = $utils->converttomysqldate($datum);
  }
  else
  {
    $_datum_in = null;
  }
}
else{
  $_datum_in = date('Y-m-d');
}

$a = new spn_verzuim_tracker();
print $a->list_verzuim_tracker_by_date($_datum_in,$_SESSION["SchoolJaar"], $_SESSION["SchoolID"], appconfig::GetDummy());

?>
