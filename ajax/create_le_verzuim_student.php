<?php

require_once "../classes/spn_user_hs_account.php";
require_once "../classes/spn_verzuim.php";
require_once "../config/app.config.php";


$s = new spn_verzuim();

$baseurl = appconfig::GetBaseURL();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo " fecha en metodo ";
echo $_POST["datum"];
echo $_POST["p1"];
echo $_POST["p2"];
echo $_POST["p3"];
echo $_POST["p4"];
echo $_POST["p5"];
echo $_POST["p6"];
echo $_POST["p7"];
echo $_POST["p8"];
echo $_POST["p9"];
echo $_POST["p10"];

$create_verzuim = $s->create_le_verzuim_student_new($_POST["school_id"],$_POST["schooljaar"],$_POST["klas"],$_POST["datum"],'',$_POST["studentid"],$_POST["p1"],$_POST["p2"],$_POST["p3"],$_POST["p4"],$_POST["p5"],$_POST["p6"],$_POST["p7"],$_POST["p8"],$_POST["p9"],$_POST["p10"]);

echo $create_verzuim;
