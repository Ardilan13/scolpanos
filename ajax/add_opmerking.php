<?php
/**
 * Created by PhpStorm.
 * User: Fogo
 * Date: 03-Mar-16
 * Time: 2:02 PM
 */


require_once("../classes/spn_opmerking.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");


$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$e = new spn_opmerking();
$u = new spn_utils();

print $e->save_opmerking($_POST["opmerking_student_name"],$_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_POST["houding_klassen_lijst"], $_POST["opmerking_1"], $_POST["opmerking_2"], $_POST["opmerking_3"], appconfig::GetDummy());

?>
