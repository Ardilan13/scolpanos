<?php

ob_start();

require_once("config/app.config.php");
require_once("classes/spn_authentication.php");
require_once("classes/spn_rapport_school_12.php");
require_once("classes/spn_leerling.php");
require_once("classes/spn_see_kaart.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}
$schoolId = $_SESSION['SchoolID'];
switch($schoolId){
  case 13:
    $img = "Abrahamdeveer.jpeg"; 
    $titleP = "Commandant&nbsp&nbsp&nbspGeneraal&nbsp&nbsp&nbspAbraham&nbsp&nbsp&nbspde&nbsp&nbsp&nbspVeerschool";
    $titleD = "Commandant Generaal Abraham Veerschool";
    break;
  case 12:
    $img = "ceque_logo.png";
    $titleP = "Ceque&nbsp&nbsp&nbspde&nbsp&nbsp&nbspCollege";
    $titleD = "Ceque College";
    break;
  default:
    $img = "logo_spn.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = "Scol Pa Nos";
      
}

$page_html = "";
$t = new spn_rapport_school_12();
$tutor = $t->_writetutorName($_GET["klas"]);


$l = new spn_leerling();
$array_leerling = $l->get_all_students_array_by_klas($_GET["klas"]);

$table_cijfers = "";
$c = new spn_see_kaart();
// $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_SESSION["SchoolJaar"], 5307,1,false);
// echo($table_cijfers);


//
foreach($array_leerling as $item) {


  $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_SESSION["SchoolJaar"], $item['studentid'],$_GET["rap"],false);
  // $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_SESSION["SchoolJaar"], 5291,2,false);

  $page_html .="<div class='row justify-content-end'>";
  $page_html .="<div class='col-7'>";
  $page_html .="</br>";
  $page_html .="</br>";
  $page_html .="</br>";
  $page_html .="</br>";
  $page_html .="</br>";
  $page_html .="<div class='card border-0' style='width: 40rem;' >";
  $page_html .="</br>";
  $page_html .="<h3 class='center'>".$titleD."</h3>";
  $page_html .="<img  src='".appconfig::GetBaseURL()."/assets/img/".$img."' class='mx-auto d-block'>";
  $page_html .="<div class='card-body'>";
  $page_html .="</br>";
  $page_html .="</br>";
  $page_html .="<row class='justify-content-end'>";
  $page_html .="<div class='col-2'></div>";
  $page_html .="<div class='col-10 float-right'>";
  $page_html .="<table class=''>";
  $page_html .="<tbody>";
  $page_html .="<tr>";
  $page_html .="<td width='40%' height='50'><b>Rapport van: </b></td>";
  $page_html .="<td ><b><i>".$item['achternaam']." ".$item['voornamen']."</i></b></td>";
  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='40%' height='50'><b>Klas: </b></td>";
  $page_html .="<td><b><i>".$item["klas"]."</i></b>";
  $page_html .="</td>";
  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='40%' height='50'><b>Schooljaar: </b></td>";
  $page_html .="<td><b><i>".$_GET["schoolJaar"]."</i></b></td>";
  $page_html .="</tr>";
  $page_html .="</tbody>";
  $page_html .="</table>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";

  $page_html .="<div class='page-break'></div>";
  $page_html .="<div class='card-deck border-5 printable' style='font-size: 0.70rem;'>";
  $page_html .="<div class='card border-0'>";
  $page_html .="<div class='row'>";
  $page_html .="<div class='card'>";
  $page_html .="<h5 class='card-title text-center'>".$titleP."</h5>";
  $page_html .="<h6 class='card-subtitle mb-2 text-muted text-center'>Schooljaar: ".$_GET["schoolJaar"]."</h6>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="<hr>";
  $page_html .="<div class='row'>";
  $page_html .="<div class='col-md-12'>";
  $page_html .="<p style='margin-bottom: 0rem;'><b>Rapport Van: </b><span>".$item['achternaam']." ".$item['voornamen']."</span></p>";
  $page_html .="</div>";
  $page_html .="<div class='col-md-4'>";
  $page_html .="<p style='margin-bottom: 0rem;'><b>Klas: </b><span>".$item['klas']."</span></p>";
  $page_html .="</div>";
  $page_html .="<div class='col-md-8'>";
  $page_html .="<p style='margin-bottom: 0rem;'><b>Mentor: </b><span>".$tutor."</span></p>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="<hr>";
  $page_html .="<div class='row'>";
  $page_html .="<div class='col-md-12'>";
  $page_html .= $table_cijfers;

  // $page_html .="<table align='center' cellpadding='1' cellspacing='1' class='table'>";
  // $page_html .="<thead>";
  // $page_html .="<th>Vakken</th>";
  // $page_html .="<th>1</th>";
  // $page_html .="<th>2</th>";
  // $page_html .="<th>3</th>";
  // $page_html .="<th>Eind</th>";
  // $page_html .="</thead>";
  // $page_html .="<tbody>";
  // $page_html .="<tr>";
  // $page_html .="<td width='65%'>&nbsp;&nbsp;Kennis van het Geestelijk Leven (KGL)</td>";
  // $page_html .="<td>0.5</td>";
  // $page_html .="<td>2</td>";
  // $page_html .="<td>3</td>";
  // $page_html .="<td>4</td>";
  // $page_html .="</tr>";
  // $page_html .="</tbody>";
  // $page_html .="</table>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="<br>";
  $page_html .="<div class='row'>";
  $page_html .="<div class='col-md-12'>";
  $page_html .="<table align='center' cellpadding='1' cellspacing='1' class='table table-sm' >";
  $page_html .="<thead>";
  $page_html .="<th>Verzuim</th>";

  if ($_GET["rap"] == '1'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>Eind</th>";
  }
  if ($_GET["rap"] == '2'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>2</th>";
    $page_html .="<th>Eind</th>";

  }
  if ($_GET["rap"] == '3'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>2</th>";
    $page_html .="<th>3</th>";
    $page_html .="<th>Eind</th>";
  }

  $page_html .="</thead>";
  $page_html .="<tbody>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Te laat</td>";
  // $page_html .="<td>2</td>";
  // $page_html .="<td>3</td>";
  // $page_html .="<td>4</td>";
  // $page_html .="<td>0</td>";
  if ($_GET["rap"] == '1'){
    $absentie1 = "";
    $absentie_total = "";
    $absentie1 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". (int)$absentie1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $absentie1 = "";
    $absentie2 = "";

    $absentie_total = "";
    $absentie1 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);
    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";

    $absentie_total = (int)$absentie1 + (int)$absentie2;
    $page_html.="<td>". $absentie_total ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $absentie1 = "";
    $absentie2 = "";
    $absentie3 = "";
    $absentie_total = "";
    $absentie1 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);
    $absentie3 =  $c->get_te_laat_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 3, false);

    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";
    $page_html.="<td>". $absentie3." </td>";
    $absentie_total = (int)$absentie1 + (int)$absentie2 +(int)$absentie3;
    $page_html.="<td>". $absentie_total ." </td>";
  }


  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Verzuim</td>";
  // $page_html .="<td>2</td>";
  // $page_html .="<td>3</td>";
  // $page_html .="<td>4</td>";
  // $page_html .="<td>0</td>";


  if ($_GET["rap"] == '1'){
    $absentie1 = "";


    $absentie_total = "";
    $absentie1 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);


    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". (int)$absentie1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $absentie1 = "";
    $absentie2 = "";

    $absentie_total = "";
    $absentie1 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);


    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";

    $absentie_total = (int)$absentie1 + (int)$absentie2;
    $page_html.="<td>". $absentie_total ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $absentie1 = "";
    $absentie2 = "";
    $absentie3 = "";
    $absentie_total = "";
    $absentie1 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);
    $absentie3 =  $c->get_absentie_by_student($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 3, false);

    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";
    $page_html.="<td>". $absentie3." </td>";
    $absentie_total = (int)$absentie1 + (int)$absentie2 +(int)$absentie3;
    $page_html.="<td>". $absentie_total ." </td>";
  }


  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Ongeoorloofd verzuim (Spijbelen)</td>";
  if ($_GET["rap"] == '1'){
    $absentie1 = "";
    $absentie_total = "";
    $absentie1 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);


    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". (int)$absentie1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $absentie1 = "";
    $absentie2 = "";

    $absentie_total = "";
    $absentie1 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);


    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";

    $absentie_total = (int)$absentie1 + (int)$absentie2;
    $page_html.="<td>". $absentie_total ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $absentie1 = "";
    $absentie2 = "";
    $absentie3 = "";
    $absentie_total = "";
    $absentie1 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 1, false);
    $absentie2 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 2, false);
    $absentie3 =  $c->get_uitsturen_by_student_se_kaart($_GET["schoolJaar"], $_SESSION['SchoolID'], $item['studentid'], 3, false);

    $page_html.="<td>".$absentie1." </td>";
    $page_html.="<td>". $absentie2." </td>";
    $page_html.="<td>". $absentie3." </td>";
    $absentie_total = (int)$absentie1 + (int)$absentie2 +(int)$absentie3;
    $page_html.="<td>". $absentie_total ." </td>";
  }

  $page_html .="</tr>";
  $page_html .="</tbody>";
  $page_html .="</table>";
  $page_html .="</div>";
  $page_html .="</div>";




  ////////////////////////////////////// NEW HOUDING ///////////////////////

  $page_html .="<div class='row'>";
  $page_html .="<div class='col-md-12'>";
  $page_html .="<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";
  $page_html .="<thead>";
  $page_html .="<th>Persoonlijke kwaliteiten</th>";

  if ($_GET["rap"] == '1'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>Eind</th>";
  }
  if ($_GET["rap"] == '2'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>2</th>";
    $page_html .="<th>Eind</th>";

  }
  if ($_GET["rap"] == '3'){
    $page_html .="<th style='width: 75px;'>1</th>";
    $page_html .="<th>2</th>";
    $page_html .="<th>3</th>";
    $page_html .="<th>Eind</th>";
  }

  $page_html .="</thead>";
  $page_html .="<tbody>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Concentratie</td>";

  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],2);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h1',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }

  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Inzet / Motivatie</td>";


  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],2);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h2',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }

  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Werkverzorging</td>";

  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],2);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h3',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }


  $page_html .="</tr>";

  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Huiswerk attitude</td>";

  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],2);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h4',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }


  $page_html .="</tr>";

  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - docent</td>";

  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],2);

    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";
  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h5',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }


  $page_html .="</tr>";
  $page_html .="<tr>";
  $page_html .="<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - leerling</td>";

  if ($_GET["rap"] == '1'){
    $_h1 = "";
    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],1);
    $page_html.="<td>".$_h1." </td>";
    $page_html.="<td>". (int)$_h1  ." </td>";
  }
  if ($_GET["rap"] == '2'){

    $_h1_1 = "";
    $_h1_2 = "";
    $avg_h = 0;

    $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],1);
    $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],2);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h2_1." </td>";

    $avg_h = (int)$_h1_1 + (int)$_h2_1/2;
    $page_html.="<td>". $avg_h ." </td>";

  }
  if ($_GET["rap"] == '3'){

    $_h1_1 = "";
    $_h1_2 = "";
    $_h1_3 = "";
    $avg_h = "";

    $_h1 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],1);
    $_h2 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],2);
    $_h3 =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],3);
    $avg_h =  $c->_writerapportdata_houding($_GET["klas"],'h6',$item['studentid'],4);


    $page_html.="<td>".$_h1_1." </td>";
    $page_html.="<td>". $_h1_2." </td>";
    $page_html.="<td>". $_h1_3." </td>";
    $page_html.="<td>". $avg_h." </td>";

  }


  $page_html .="</tr>";
  $page_html .="</tbody>";
  $page_html .="</table>";
  $page_html .="</div>";
  $page_html .="</div>";
  ///////////////////////////////////////END HOUDING ///////////////////////
  $page_html .="</div>";
  $page_html .="<div class='card border-0'>";


  $page_html .="<div class='row'>";
  $page_html .="<div class='card'>";
  $page_html .="<div class='card-body' style='padding-bottom: 0px;'>";
  $page_html .="<h6 class='card-title'>Opmerking bij het eerste rapport</h6>";
  $page_html .="<hr style='border-top: dotted 2px; margin-top: 1rem;' />";
  $page_html .="<h6>O Voldoende:</h6>";
  $page_html .="<h6>O Onvoldoende:</h6>";
  $page_html .="<br>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";


  $page_html .="<div class='row'>";
  $page_html .="<div class='card border-0'>";
  $page_html .="<div class='card-body'>";
  $page_html .="<h6 class='card-title'>Opmerking bij het tweede rapport</h6>";
  $page_html .="<hr style='border-top: dotted 2px; margin-top: 1rem;' />";
  $page_html .="<h6>O Voldoende:</h6>";
  $page_html .="<h6>O Onvoldoende:</h6>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";


  $page_html .="<div class='row'>";
  $page_html .="<div class='card'>";
  $page_html .="<div class='card-body'>";
  $page_html .="<h6 class='card-title'>Opmerking bij het derde rapport</h6>";
  $page_html .="<hr style='border-top: dotted 2px; margin-top: 1rem;' />";
  $page_html .="<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Basico 2</h6>";
  $page_html .="<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNiet over</h6>";
  $page_html .="<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspVerwezen naar:.................................................................</h6>";
  $page_html .="<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOpmerking:.....................................................................</h6>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";


  $page_html .="<div class='row'>";
  $page_html .="<div class='card'>";
  $page_html .="<div class='card-body' style='padding-bottom: 0px;'>";
  $page_html .="<h6 class='card-title'>Betekenis persoonlijke kwaliteiten:</h6>";
  $page_html .="<div class='row' style='height: 130px;'>";
  $page_html .="<div class='col-8'>";
  $page_html .="<p style='margin-bottom: 0.5rem; font-size: 0.75rem'>5 = Goed</p>";
  $page_html .="<p style='margin-bottom: 0.5rem; font-size: 0.75rem'>4 = voldoende</li>";
  $page_html .="<p style='margin-bottom: 0.5rem; font-size: 0.75rem'>3 = Matig</p>";
  $page_html .="<p style='margin-bottom: 0.5rem; font-size: 0.75rem'>2 = Onvoldoende</p>";
  $page_html .="<p style='margin-bottom: 0.1rem; font-size: 0.75rem'>1 = Slecht</p>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";


  $page_html .="<br>";
  $page_html .="<div class='row'>";
  $page_html .="<div class='card'>";
  $page_html .="<div class='card-body' style='padding-bottom: 0px;'>";

  $page_html .="<div class='row'>";
  $page_html .="<div class='col-6' style=''>";
  $page_html .="<h6 class='card-title'>Handtekening Mentor:</h6>";
  $page_html .="<br>";
  $page_html .="<hr style='border-top: 2px solid rgba(0, 0, 0, 0.34); border-top-style: dotted;'>";
  $page_html .="</div>";

  $page_html .="<div class='col-6'style=''>";
  $page_html .="<h6 class='card-title'>Handtekening Directeur:</h6>";
  $page_html .="<br>";
  $page_html .="<hr style='border-top: 2px solid rgba(0, 0, 0, 0.34); border-top-style: dotted;'>";
  $page_html .="</div>";
  $page_html .="</div>";

  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";





  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="</div>";
  $page_html .="<div class='page-break'></div>";

}





//if(appconfig::GetHTTPMode() == "HTTPS")
//{
//    header("HTTP/1.1 301 Moved Permanently");
//   header("Location: " . appconfig::GetBaseURL() . "/" . __FILE__);
//}

// $auth = new spn_authentication();
// $auth->CheckSessionValidity();

// ob_flush();

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style type="text/css" media="print">

  @page {
    size: A4 landscape;
  }
  @media all {
    .page-break	{ display: none; }
  }

  @media print {
    .page-break	{ display: block; page-break-before: always; }
  }

  /* @media print {
  .page-break	{ display: block; page-break-before: always; }
  } */
  @media print {
    .printable {
      width: auto;
    }
  }

  /* @media print {
  .page-break {page-break-after: always;}
  } */
  .table td, .table th {
    padding: 0.1rem;
  }
  body{
    font-size: 0.75rem;
  }
  .h6, h6 {
    font-size: 0.75rem;
  }
  .vl {
    border-left: 6px solid green;
    height: 500px;
  }



  </style>
    <title><?php echo $titleD ?></title>
</head>



<body>

  <div class='container'>
    <?php echo $page_html ?>
    <!-- <div class="row justify-content-end">
    <div class="col-7">
  </br>
</br>
</br>
</br>
</br>
<div class="card border-0" style="width: 40rem;" >
</br>
</br>
</br>
</br>
</br>
<img src='ceque_logo.png'class="mx-auto d-block">
<div class="card-body">
</br>
</br>
<row class="justify-content-end">
<div class="col-2"></div>
<div class="col-10 float-right">
<table class="">
<tbody>
<tr>
<td width='35%' height="50"><b>Reporta van</b></td>
<td >Alvarez Guzman Enrique Antonio</td>
</tr>
<tr>
<td width='35%' height="50"><b>Klas</b></td>
<td>1A
</td>
</tr>
<tr>
<td width='35%' height="50"><b>Cursus jaar</b></td>
<td>2018-2019</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div> -->

<div class='page-break'></div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript">

$( document ).ready(function() {

  var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
  var Klass = '<?php echo $_GET["klas"]; ?>';
  var pattern = /3/;
  var pattern2 = /2/;
  var contain = pattern.test(Klass);
  var contain2 = pattern2.test(Klass);
  var text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Basico 2";

  if(SchoolID == 12){
    if(contain){
      text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar leerjaar 4"
    }

    if(contain2){
      text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar leerjaar 3"
    }

    var lerjar4 = document.getElementsByClassName("classovernar");
    for (var i = 0; i < lerjar4.length; i++) {
      lerjar4[i].innerHTML = text;
    }
  }

  setTimeout(function(){
    window.print();
  }, 1000);
});


</script>
</body>
</html>
