<?php
require_once("../classes/spn_authentication.php");

$m= new spn_authentication();

if(session_status() == PHP_SESSION_NONE)
	session_start();

if(isset($_SESSION["SchoolID"]))
{
	$school_id = $_SESSION["SchoolID"];
	$json_name_config = "../assets/js/" . $school_id . "_avi_level.json";
	$htmlcontrol="";

	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$json_string = file_get_contents($json_name_config);
	$data = json_decode($json_string);

	$htmlcontrol .= "<select id=\"level\" name=\"level\" class=\"form-control\">";

	foreach ($data as $name => $values) {
			// $htmlcontrol .=  "<tr><th>LUIS</th>";
			foreach ($values as $k => $v) {
				$htmlcontrol .= "<option value=". htmlentities($v) .">". htmlentities($v) ."</option>";
			}
			$htmlcontrol .= "</select>";
	}


	echo  $htmlcontrol;
}

?>
