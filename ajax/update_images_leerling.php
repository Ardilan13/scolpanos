<?php

require_once("../classes/spn_leerling.php");
require_once("../classes/spn_utils.php");

$l = new spn_leerling();
$u = new spn_utils();

if (session_status() == PHP_SESSION_NONE)
	session_start();

if ($_FILES["file"] != "") {
	$Image = pathinfo($_FILES["file"]["name"]);
	$check_student = $l->check_is_student_by_studentnumber($Image['filename'], false);

	if ($check_student > 0) {

		chdir("..");
		$target_dir = getcwd() . "/profile_students/";
		// $document_id = $Image['filename'];
		$target_file = $target_dir . $Image['filename'] . "-" . $_SESSION["SchoolID"];
		print('Target File' . $target_file . '<br>');
		$res = move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		print($res);
	} else {
		print('error');
		header("HTTP/1.1 500 Internal Server Error");
		http_response_code(500);
	}
	// print(basename($_FILES["file"]["name"]));
} else {
	print("No files found to upload"); //No file upload message
	http_response_code(500);
}
