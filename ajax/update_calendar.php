<?php
session_start();

require_once("../classes/spn_calendar.php");
require_once("../classes/spn_utils.php");
require_once("../config/app.config.php");
require_once("../classes/spn_documents.php");

$c = new spn_calendar();
$u = new spn_utils();
$s = new spn_documents();
$id = date("YmdHis");

if ($_POST["calendar_subject"] == "Exam" || $_POST["calendar_subject"] == "Test") {
    if ($c->get_exams($_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["schoolid"]) <= 4) {
        print $c->update_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"]);
    } else {
        print "exams";
    }
} else {
    print $c->update_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"]);
}
