<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_cijfers.php");
require_once("../classes/spn_utils.php");
	$i = new spn_cijfers();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE){
		session_start();
}

if ($_SESSION["SchoolType"] == 1 && $_GET["schoolid"] != 8 && $_GET["schoolid"] != 18) {
	print  $i->list_vak_by_teacher_ps($_GET["schoolid"],$_SESSION["UserGUID"],$_GET["teacher_class"], false);

} else {
	print  $i->list_vak_by_teacher($_GET["schoolid"],$_SESSION["UserGUID"],$_GET["teacher_class"], false);
}
