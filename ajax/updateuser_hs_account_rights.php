<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

$a = new spn_user_hs_account();
$u = new spn_utils();

if(session_status() == PHP_SESSION_NONE)
session_start();
print $a->update_user_hs_account_rights($_POST["user_hs_GUID"], $_POST["user_hs_rights"], appconfig::GetDummy());
?>
