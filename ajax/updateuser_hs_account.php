<?php

require_once("../classes/spn_user_hs_account.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

$a = new spn_user_hs_account();
$u = new spn_utils();

if (session_status() == PHP_SESSION_NONE)
	session_start();

$vak = $_POST["cijfers_vakken_lijst"] == "" || !isset($_POST["cijfers_vakken_lijst"]) || $_POST['cijfers_vakken_lijst'] == 0 ? $_POST['group'] : $_POST["cijfers_vakken_lijst"];
$klas = $_POST['group'] == "" ? $_POST["cijfers_klassen_lijst"] : substr($_POST["cijfers_klassen_lijst"], 0, 1);

print $a->update_klas_vak_user_hs_account($_POST["user_access_id"], $vak, $klas, $_POST["is_tutor_hidden"], appconfig::GetDummy());
