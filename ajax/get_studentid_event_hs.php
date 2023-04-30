<?php
if (!empty($_POST)) {
    if($_POST["reason"] != null && $_POST["reason"] != ''){
        require_once "../classes/spn_event.php";
        require_once "../config/app.config.php";
        require_once "../classes/spn_utils.php";

        $utils = new spn_utils();
        $ev = new spn_event();
        $event_date =  $utils->converttomysqldate( $_POST['event_date']);
        $due_date =  $utils->converttomysqldate( $_POST['due_date']);   
        $event_detail = $ev->create_event($event_date,$due_date, $_POST['reason'], $_POST['involved'],$_POST['observations'],$_POST['private'],$_POST['important'],$_POST['listevent'],$_SESSION["SchoolJaar"], appconfig::GetDummy());
    }
    else if ($_POST["event"] != null && $_POST["event"] != 0) {
        $studentid = $_POST["event"];
        echo $studentid;
        exit;
    } else if ($_POST["listevent"] != null && $_POST["listevent"] != 0) {
        require_once "../classes/spn_event.php";
        require_once "../config/app.config.php";
        $ev = new spn_event();
        $studentid1 = $_POST["listevent"];
        $event_detail = $ev->list_event($_SESSION['SchoolJaar'], null, $studentid1, appconfig::GetDummy());
        echo $event_detail;
        exit;
    }
}
