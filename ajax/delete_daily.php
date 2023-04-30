<?php
/**
 * Created by PhpStorm.
 * User: Fogo
 * Date: 03-Mar-16
 * Time: 2:02 PM
 */


require_once("../classes/spn_daily.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");


$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$e = new spn_daily();
$u = new spn_utils();

print $e->delete_daily($_GET["id_daily"],appconfig::GetDummy());
?>
