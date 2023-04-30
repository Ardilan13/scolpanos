<?php
/**
 * Created by PhpStorm.
 * User: Fogo
 * Date: 03-Mar-16
 * Time: 2:02 PM
 */


require_once("../classes/spn_event.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");


$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$e = new spn_event();
$u = new spn_utils();



print $e->update_event(
        $_POST["id_event"],
        $u->converttomysqldate($_POST["event_date"]),
        $u->converttomysqldate($_POST["due_date"]),
        $_POST["reason"],
        $_POST["involved"],
        $_POST["observation_event"],
        $_POST["event_private_selected"],
        $_POST["important_notice_selected"],
        appconfig::GetDummy()
        );
