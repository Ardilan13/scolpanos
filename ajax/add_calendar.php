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
$ip = $_SERVER['REMOTE_ADDR'];
$id = date("YmdHis");

if ($_FILES["fileToUpload"]["error"] == 4 || !isset($_FILES["fileToUpload"])) {
  if ($_SESSION["SchoolType"] == 2 && ($_POST["calendar_subject"] == "Exam" || $_POST["calendar_subject"] == "Test")) {
    if ($c->get_exams($_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["schoolid"]) <= 4) {
      $spn_audit->create_audit($UserGUID, 'Calendar', 'Create calendar:' . $u->converttomysqldate($_POST["calendar_date"]) . ' ip:' . $ip . ' s:' . $_POST["calendar_observation"], appconfig::GetDummy());
      print $c->create_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"], $_POST["schoolid"], $_POST["schooljaar"], appconfig::GetDummy(), $_POST["verzuim_vakken_lijst"]);
    } else {
      print "exams";
    }
  } else {
    $spn_audit->create_audit($UserGUID, 'Calendar', 'Create calendar:' . $u->converttomysqldate($_POST["calendar_date"]) . ' ip:' . $ip . ' s:' . $_POST["calendar_observation"], appconfig::GetDummy());
    print $c->create_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"], $_POST["schoolid"], $_POST["schooljaar"], appconfig::GetDummy(), $_POST["verzuim_vakken_lijst"]);
  }
} else {
  // YES HAVE FILE

  chdir("..");
  $target_dir = getcwd() . "/upload/Calendar/" . $_POST["schoolid"] . "_" . $_SESSION["schoolname"] . "/" . $_POST["cijfers_klassen_lijst"] . "/";
  $document_id = $id . '_' . basename($_FILES["fileToUpload"]["name"]);
  $target_file = $target_dir . $document_id;


  if (!is_dir($target_dir)) {

    mkdir($target_dir, 0755, true);
  }

  if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
    $spn_audit->create_audit($UserGUID, 'Calendar', 'Create calendar:' . $u->converttomysqldate($_POST["calendar_date"]) . ' ip:' . $ip . ' s:' . $_POST["calendar_observation"], appconfig::GetDummy());
    $calendar_inserted = $c->create_calendar($_POST["list_docent"], $_POST["cijfers_klassen_lijst"],  $u->converttomysqldate($_POST["calendar_date"]), $_POST["calendar_subject"], $_POST["calendar_observation"], $_POST["schoolid"], $_POST["schooljaar"], appconfig::GetDummy(), $_POST["verzuim_vakken_lijst"]);
    print $s->createDocument($calendar_inserted, $document_id, $_POST["calendar_observation"], $_POST["schoolid"], $_POST["cijfers_klassen_lijst"], "Calendar_Document");
  }
}
