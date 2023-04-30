
<?php

require_once("../classes/spn_audit.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");

	$i = new spn_audit();
	$u = new spn_utils();

	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}

	$spn_audit = new spn_audit();
	$spn_audit->create_audit($_SESSION['UserGUID'], 'Documents','CREATE Document',false);


?>
