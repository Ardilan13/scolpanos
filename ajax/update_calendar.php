<?php
session_start();

require_once("../classes/spn_calendar.php");
require_once("../classes/spn_utils.php");
require_once("../classes/spn_audit.php");
require_once("../config/app.config.php");
require_once("../classes/spn_documents.php");

$c = new spn_calendar();
$u = new spn_utils();
$s = new spn_documents();
$spn_audit = new spn_audit();

$UserGUID = $_SESSION['UserGUID'];
$id_calendar = $_POST["id_calendar"];
$ip = $_SERVER['REMOTE_ADDR'];
$id = date("YmdHis");

if ($_POST["calendar_subject"] == "Exam" || $_POST["calendar_subject"] == "Test") {
    if ($c->get_exams($_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["schoolid"]) <= 4) {
        $spn_audit->create_audit($UserGUID, 'Calendar', 'Update calendar:' . $id_calendar . ' ip:' . $ip . ' s:' . $_POST["calendar_observation"], appconfig::GetDummy());
        print $c->update_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"]);
    } else {
        print "exams";
    }
} else {
    $spn_audit->create_audit($UserGUID, 'Calendar', 'Update calendar:' . $id_calendar . ' ip:' . $ip . ' s:' . $_POST["calendar_observation"], appconfig::GetDummy());
    print $c->update_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"]);
}
