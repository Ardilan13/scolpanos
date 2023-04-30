<?php

require_once("../classes/spn_message.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

$m = new spn_message();
$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
session_start();
print $m->delete_message($_POST["id"], false);

?>
