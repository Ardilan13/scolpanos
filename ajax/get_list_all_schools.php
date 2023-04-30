<?php

require_once("../classes/spn_le_vakken.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
    session_start();

$v = new spn_le_vakken();

print $v->list_all_schools($_SESSION["SchoolAdmin"], false);

?>
