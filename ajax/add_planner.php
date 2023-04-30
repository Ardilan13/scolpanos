<?php

  require_once("../classes/spn_planner.php");
	require_once("../classes/spn_utils.php");
	require_once("../config/app.config.php");

	$p = new spn_planner();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	print  $p->create_planner($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],	$u->converttomysqldate($_POST["planner_week"]),$_POST["planner_subject"],$_POST["planner_observation"],appconfig::GetDummy());
	// print  $m->create_social_work("2016",	$u->getdatetime("UTC"),"Fight","8B",	"Observacion desde el modelo",1,2,false);

?>
