<?php
require_once("../classes/spn_authentication.php");
require_once("../classes/spn_cijfers.php");
require_once("../classes/spn_utils.php");
$i = new spn_cijfers();
$u = new spn_utils();
// if(session_status() == PHP_SESSION_NONE)
// {
 // session_start();
// }
if(session_status() == PHP_SESSION_NONE){
session_start();
}


print  $i->list_school_name_by_teacher($_SESSION["UserGUID"], false);
// }
?>
