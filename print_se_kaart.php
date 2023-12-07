<?php

ob_start();
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

require_once("config/app.config.php");
require_once("classes/spn_authentication.php");
require_once("classes/spn_rapport_school_12.php");
require_once("classes/spn_leerling.php");
require_once("classes/spn_see_kaart.php");
require_once "classes/DBCreds.php";
require_once("classes/spn_setting.php");
require_once("classes/spn_utils.php");
$u = new spn_utils();

$schoolId = $_SESSION['SchoolID'];
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$s = new spn_setting();
$s->getsetting_info($schoolId, false);
$user = $_SESSION['UserGUID'];
$klas = $_GET["klas"];
$level_klas = substr($klas, 0, 1);
$studentid = $_GET["studentid"];
$query = "SELECT FirstName, LastName FROM app_useraccounts WHERE Class = '$klas' AND SchoolID = $schoolId AND UserRights = 'DOCENT' LIMIT 1";
$resultado = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_assoc($resultado)) {
  $teacher = $row["FirstName"] . " " . $row["LastName"];
}
switch ($schoolId) {
  case 4:
    $img = "kudawecha.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
    $cabesante = "Xiomara Berkel";
    break;
  case 6:
    $img = "washington.jpg";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
    $cabesante = "Sharine Curiel";
    break;
  case 9:
    $img = "reina.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
    $cabesante = "Rosalinda Granger";
    break;
  case 10:
    $img = "xander.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
    $cabesante = "Nadia Maduro";
    break;
  case 11:
    $img = "angela.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
    $cabesante = "Cornelia Aventurin Connor";
    break;
  case 13:
    $img = "Abrahamdeveer.jpeg";
    $titleP = "Commandant&nbsp&nbsp&nbspGeneraal&nbsp&nbsp&nbspAbraham&nbsp&nbsp&nbspde&nbsp&nbsp&nbspVeer School";
    $titleD = "Commandant Generaal Abraham de Veer School";
    break;
  case 12:
    $img = "ceque_logo.png";
    $titleP = "Ceque&nbsp&nbsp&nbsp&nbspCollege";
    $titleD = "Ceque College";
    break;
  case 17:
    $img = "monplaisir.png";
    $titleP = "Mon&nbsp&nbsp&nbspPlaisir&nbsp&nbsp&nbspCollege&nbsp&nbsp&nbspHAVO";
    $titleD = "Mon Plaisir College HAVO";
    break;
  case 18:
    $img = "futuro.jpg";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = "Scol Paso Pa Futuro";
    $cabesante = "Doris Franken";
    break;
  default:
    $img = "logo_spn.png";
    $titleP = "Scol&nbsp&nbsp&nbspPa&nbsp&nbsp&nbspNos";
    $titleD = $s->_setting_school_name;
}

$page_html = "";
$t = new spn_rapport_school_12();
if ($_SESSION["SchoolType"] == 2 && substr($_GET["klas"], 0, 1) == 4) {
  if ($_SESSION["SchoolID"] == 13) {
    switch ($_GET["klas"]) {
      case "4A":
        $tutor = "Minoushka Chin-Sie-Jen";
        break;
      case "4B":
        $tutor = "Celio Vrolijk";
        break;
      case "4C":
        $tutor = "Luwen Sam";
        break;
      case "4D":
        $tutor = "Roy Broekman";
        break;
    }
  } else {
    $tutor = $t->_writetutorName(4, $schoolId);
  }
} else {
  $tutor = $t->_writetutorName($_GET["klas"], $schoolId);
}

$array = array();
$returnarr = array();

$l = new spn_leerling();
if ($studentid == 'all') {
  $array_leerling = $l->get_all_students_array_by_klas($_GET["klas"], $_GET["schoolJaar"], $_GET['rap']);
} else {
  $select = "SELECT id,studentnumber,firstname,lastname,sex,dob,class,profiel FROM students where id = '$studentid' and status = 1";
  $resultado1 = mysqli_query($mysqli, $select);
  while ($row = mysqli_fetch_assoc($resultado1)) {
    $returnarr["studentid"] = $studentid;
    $returnarr["studentnumber"] = $row["studentnumber"];
    $returnarr["voornamen"] = $row["firstname"];
    $returnarr["achternaam"] = $row["lastname"];
    $returnarr["geslacht"] = $row["sex"];
    $returnarr["geboortedatum"] = $row["dob"];
    $returnarr["klas"] = $row["class"];
    $returnarr["profiel"] = $row["profiel"];
    array_push($array, $returnarr);
  }
  $array_leerling = $array;
}
$table_cijfers = "";
$c = new spn_see_kaart();
// $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_SESSION["SchoolJaar"], 5307,1,false);
// echo($table_cijfers);


//
foreach ($array_leerling as $item) {
  $opmerking1 = null;
  $opmerking2 = null;
  $opmerking3 = null;
  $advies = null;
  $student = $item['studentid'];
  $schooljaar = $_GET["schoolJaar"];
  $query = "SELECT opmerking1, opmerking2, opmerking3, advies FROM opmerking WHERE klas = '$klas' AND SchoolID = $schoolId AND studentid = $student AND schooljaar = '$schooljaar'";
  $resultado = mysqli_query($mysqli, $query);
  if ($resultado->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($resultado)) {
      $opmerking1 = $row["opmerking1"];
      $opmerking2 = $row["opmerking2"];
      $opmerking3 = $row["opmerking3"];
      $advies = $row["advies"];
    }
  }


  switch (substr($item["profiel"], 0, 2)) {
    case 'MM':
      $paket = "<b class='p-1 ml-2 bg-warning text-white'>" . $item["profiel"] . "</b>";
      $color = "bg-warning";
      break;
    case 'NW':
      $paket = "<b class='p-1 ml-2 bg-primary text-white'>" . $item["profiel"] . "</b>";
      $color = "bg-primary";
      break;
    case 'HU':
      $paket = "<b class='p-1 ml-2 bg-success text-white'>" . $item["profiel"] . "</b>";
      $color = "bg-success";
      break;
    default:
      $paket = "";
      $color = "";
      break;
  }
  $profiel = $item["profiel"];

  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
    $table_cijfers = "";
  } else if ($_SESSION["SchoolID"] != 18) {
    $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_GET["schoolJaar"], $item['studentid'], $_GET["rap"], $klas, $profiel, $color, false);
  }
  // $table_cijfers = $c->list_cijfers_by_student_se_kaart_rapport($_SESSION["SchoolJaar"], 5291,2,false);

  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8) {
    if ($_SESSION["SchoolID"] != 18) {
      $page_html .= "<div style='display: flex; justify-content: space-evenly;'>";
      $page_html .= "<div style=' display: flex; align-items: center; justify-content: space-evenly; flex-direction: column;'>";
    } else {
      $page_html .= "<div style='margin-top:5%;display: flex; justify-content: space-evenly;'>";
      $page_html .= "<div style='display: flex; align-items: center; justify-content: space-evenly; flex-direction: column;'>";
    }
    $page_html .= "<div style='display: flex; align-items: center;'>";

    if ($_SESSION["SchoolID"] != 18) {
      $page_html .= "<label style='margin-right: 20px;'>R1</label>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking1) . "</textarea>";
    } else {
      $page_html .= "<label style=' max-width: 100px;'>Comentario Rapport 1</label>";
      $page_html .= "<div style='display: flex; flex-direction: column;'><div><input type='radio'><label style='margin-right: 15px; margin-left:10px;'>Suficiente</label>";
      $page_html .= "<input type='radio'><label style='margin-left:10px;'>Insuficiente</label></div>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking1) . "</textarea></div>";
    }

    $page_html .= "</div>";
    $page_html .= "<div style='display: flex; align-items: center;'>";

    if ($_SESSION["SchoolID"] != 18) {
      $page_html .= "<label style='margin-right: 20px;'>R2</label>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking2) . "</textarea>";
    } else {
      $page_html .= "<label style=' max-width: 100px;'>Comentario Rapport 2</label>";
      $page_html .= "<div style='display: flex; flex-direction: column;'><div><input type='radio'><label style='margin-right: 15px; margin-left:10px;'>Suficiente</label>";
      $page_html .= "<input type='radio'><label style='margin-left:10px;'>Insuficiente</label></div>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking2) . "</textarea></div>";
    }

    $page_html .= "</div>";
    $page_html .= "<div style='display: flex; align-items: center;'>";

    if ($_SESSION["SchoolID"] != 18) {
      $page_html .= "<label style='margin-right: 20px;'>R3</label>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking3) . "</textarea>";
    } else {
      $page_html .= "<label style=' max-width: 100px;'>Comentario Rapport 3</label>";
      $page_html .= "<div style='display: flex; flex-direction: column;'><div><input type='radio'><label style='margin-right: 15px; margin-left:10px;'>Suficiente</label>";
      $page_html .= "<input type='radio'><label style='margin-left:10px;'>Insuficiente</label></div>";
      $page_html .= "<textarea style='resize: none;overflow: hidden;width: 300px;height: 100px;font-size: 11px;'>" . utf8_decode($opmerking3) . "</textarea></div>";
    }

    $page_html .= "</div>";
    $page_html .= "</div>";
    if ($schoolId == 10) {
      $page_html .= "<div style='margin: 0px; margin-top:3%; display: flex; align-items: center; flex-direction: column;' >";
    } else {
      $page_html .= "<div style='margin: 0px; display: flex; align-items: center; flex-direction: column; margin-left: 10%;' >";
    }
  } else if (substr($_GET["klas"], 0, 1) > 2) {
    $page_html .= "<div style='display: flex; justify-content: space-evenly;'>";
    $page_html .= "<div style=' display: flex; align-items: center; justify-content: space-evenly;'>";
    $page_html .= "<img style='margin-right: 100px !important;'  width='450px' height:'400px' src='" . appconfig::GetBaseURL() . "/assets/img/profiels.jpeg' class='mx-auto d-block'>";
    $page_html .= "<div style='width: 35rem; margin: 0px; display: flex; align-items: center; flex-direction: column;' >";
  } else {
    $page_html .= "<div class='row justify-content-end' style=''>";
    $page_html .= "<div class='col-6' style='display: flex; align-items: center;'>";
    $page_html .= "</br>";
    $page_html .= "</br>";
    $page_html .= "</br>";
    $page_html .= "</br>";
    $page_html .= "</br>";
    $page_html .= "<div style='width: 50rem; margin: 0px; display: flex; align-items: center; flex-direction: column;' >";
  }


  if ($schoolId != 17 && $schoolId != 10) {
    if ($schoolId == 18) {
      $page_html .= "</br>";
      $page_html .= "<h4 style='width:auto; max-width: 25rem; margin-bottom: 10%;'>" . $titleD . "</h4>";
    } else {
      $page_html .= "</br>";
      $page_html .= "<h4 style='width:auto; max-width: 25rem; margin-bottom: 15%; font-size: 1.14rem !important;'>" . $titleD . "</h4>";
    }
  } else {
    $page_html .= "</br>";
    $page_html .= "</br>";
    $page_html .= "</br>";
  }
  if ($schoolId == 10) {
    $page_html .= "<img  width='200px' style='padding-left: 50px;' height='150px' src='" . appconfig::GetBaseURL() . "/assets/img/" . $img . "' class='mx-auto d-block'>";
  } else if ($schoolId == 13) {
    $page_html .= "<img style='margin-right: 24% !important' width='200px' height:'200px' marin src='" . appconfig::GetBaseURL() . "/assets/img/" . $img . "' class='mx-auto d-block'>";
  } else {
    $page_html .= "<img  width='200px' height:'200px' src='" . appconfig::GetBaseURL() . "/assets/img/" . $img . "' class='mx-auto d-block'>";
  }
  if ($schoolId == 18) {
    $page_html .= "<div class='card-body' style='padding-bottom: 0px;  margin-top: 5%;'>";
  } else {
    $page_html .= "<div class='card-body'>";
  }
  if ($schoolId != 18) {
    $page_html .= "</br>";
    $page_html .= "</br>";
  }
  $page_html .= "<row class='justify-content-end'>";
  $page_html .= "<div class='col-2'></div>";
  $page_html .= "<div style='width:25rem;' class='col-10 float-right'>";
  $page_html .= "<table style='text-align: center;'>";
  $page_html .= "<tbody>";
  $page_html .= "<tr>";
  if ($_SESSION["SchoolID"] == 18) {
    $page_html .= "<td width='50%' height='40'><b>Nomber Alumno: </b></td>";
  } else {
    if ($level_klas == 4 && $_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='50%' height='50'><b>SE KAART: </b></td>";
    } else {
      $page_html .= "<td width='50%' height='50'><b>Rapport van: </b></td>";
    }
  }
  $page_html .= "<td ><b><i>" . $item['voornamen'] . " " . $item['achternaam'] . "</i></b></td>";
  $page_html .= "</tr>";
  $page_html .= "<tr>";
  if ($_SESSION["SchoolID"] == 18) {
    $page_html .= "<td width='40%' height='40'><b>Grupo: </b></td>";
  } else {
    $page_html .= "<td width='40%' height='50'><b>Klas: </b></td>";
  }
  $page_html .= "<td><b><i>" . $item["klas"] . "</i></b>";
  $page_html .= "</td>";
  $page_html .= "</tr>";
  $page_html .= "<tr>";
  if ($_SESSION["SchoolID"] == 18) {
    $page_html .= "<td width='40%' height='40'><b>Aña Escolar: </b></td>";
  } else {
    $page_html .= "<td width='40%' height='50'><b>Schooljaar: </b></td>";
  }
  $page_html .= "<td><b><i>" . $_GET["schoolJaar"] . "</i></b></td>";
  if ($_SESSION["SchoolID"] == 18) {
    $page_html .= "<tr>";
    $page_html .= "<td width='40%' height='40'><b>Maestro/a: </b></td>";
    $page_html .= "<td><b><i>" . $teacher . "</i></b></td>";
    $page_html .= "</tr>";
    $page_html .= "<tr>";
    $page_html .= "<td width='40%' height='40'><b>Cabesante: </b></td>";
    $page_html .= "<td><b><i>" . $cabesante . "</i></b></td>";
    $page_html .= "</tr>";
  }
  $page_html .= "</tr>";
  $page_html .= "</tbody>";
  $page_html .= "</table>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "<style>.table{margin-bottom: .7rem !important;}</style>";


  $page_html .= "<div class='page-break'></div>";
  $page_html .= "<div class='card-deck border-5 printable' style='font-size: 0.70rem; margin-top: 3rem;  margin-left:auto; margin-right:auto; max-width:1140px; width: 100%;' >";
  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8) {
    $page_html .= "<div class='card border-0' style='margin-left: 0; margin-right: 40px;'>";
  } else {
    $page_html .= "<div class='card border-0' >";
  }
  $page_html .= "<div class='row'>";
  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
    $page_html .= "<div style='display: flex; flex-direction: row; align-items: center;' class='card'>";
    $page_html .= "<img  width='100px' style='padding:0 20px; flex: 1.5;' src='" . appconfig::GetBaseURL() . "/assets/img/" . $img . "' class='mx-auto d-block'>";
    $page_html .= "<div style='flex: 8.5;'>";
    if ($schoolId != 10) {
      $page_html .= "<h5 class='card-title text-center'>" . $titleD . "</h5>";
    } else {
      $page_html .= "<h5 class='card-title text-center'></h5>";
    }
    $page_html .= "<h6 class='card-subtitle mb-2 text-muted text-center'>Schooljaar: " . $_GET["schoolJaar"] . "</h6>";
    $page_html .= "</div>";
  } else {
    $page_html .= "<div class='card'>";
    $page_html .= "<h5 class='card-title text-center'>" . $titleD . "</h5>";
    if ($_SESSION["SchoolID"] == 18) {
      $page_html .= "<h6 class='card-subtitle mb-2 text-muted text-center'>Aña escolar: " . $_GET["schoolJaar"] . "</h6>";
    } else {
      $page_html .= "<h6 class='card-subtitle mb-2 text-muted text-center'>Schooljaar: " . $_GET["schoolJaar"] . "</h6>";
    }
  }

  $page_html .= "</div>";
  $page_html .= "</div>";
  if ($_SESSION['SchoolType'] == 2) {
    $page_html .= "<hr>";
  }
  $page_html .= "<div class='row' style='margin-left: 1%; justify-content: space-between; flex-direction: column;'>";
  $page_html .= "<div>";
  if ($level_klas == 4 && $_SESSION["SchoolType"] == 2) {
    $page_html .= "<p style='margin-bottom: 0rem;'><b>SE KAART: </b><span>" . $item['voornamen'] . " " . $item['achternaam'] . "</span></p>";
  } else if ($_SESSION["SchoolID"] == 18) {
    $page_html .= "<p style='margin-bottom: 0rem;'><b>Rapport di: </b><span>" . $item['voornamen'] . " " . $item['achternaam'] . "</span></p>";
  } else {
    $page_html .= "<p style='margin-bottom: 0rem;'><b>Rapport van: </b><span>" . $item['voornamen'] . " " . $item['achternaam'] . "</span></p>";
  }
  $page_html .= "</div>";
  //$page_html .="<p style='margin-bottom: 0rem;'><b>Mentor: </b><span>".$tutor."</span></p>";
  if ($_SESSION['SchoolType'] == 2) {
    $page_html .= "<div style='padding-right:0px;'>";
    $page_html .= "<p style='margin-bottom: 0rem;'><b>Mentor: </b><span>" . $tutor . "</span></p>";
    $page_html .= "</div>";
  }
  $page_html .= "<div>";
  $page_html .= "<p style='margin-bottom: 0rem;'><b>Klas: </b><span>" . $_GET["klas"] . "</span></p>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "<div class='row'>";
  $page_html .= "<div class='col-md-12'>";
  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
  } else {
    $page_html .= $table_cijfers;
  }

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
  $page_html .= "</div>";
  $page_html .= "</div>";
  //$page_html .="<br>";



  ////////////////////////////////////// NEW HOUDING ///////////////////////

  $page_html .= "<div class='row mt-2'>";
  $page_html .= "<div class='col-md-12'>";
  $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";
  $page_html .= "<thead>";
  if ($_SESSION["SchoolType"] == 2) {
    $page_html .= "<style>th, tbody td{min-width: 45px;}</style>";
    $klas = substr($_GET["klas"], 0, 1);
    if ($_SESSION["SchoolType"] == 1) {
      $page_html .= "<th>Gedrag, leer- en werkhouding</th>";
    } else {
      $page_html .= "<th>Persoonlijke kwaliteiten</th>";
    }

    if ($_GET["rap"] == '1') {
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 4) {
        $page_html .= "<th>Eind</th>";
      }
    }
    if ($_GET["rap"] == '2') {
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 4) {
        $page_html .= "<th>Eind</th>";
      }
    }
    if ($_GET["rap"] == '3') {
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 4) {
        $page_html .= "<th>Eind</th>";
      }
    }

    $page_html .= "</thead>";
    $page_html .= "<tbody>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Concentratie</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Contact met leerkracht</td>";
    }

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {
        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {
        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {
        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }

    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Inzet / Motivatie</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Contact met leerling</td>";
    }


    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {
        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }

    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Werkverzorging</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag</td>";
    }

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {

        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }


    $page_html .= "</tr>";

    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Huiswerk attitude</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Zelfvertrouwen</td>";
    }

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {

        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }


    $page_html .= "</tr>";

    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - docent</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Nauwkeurigheid</td>";
    }

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {

        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }


    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - leerling</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Doorzettingsvermogen</td>";
    }

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $page_html .= "<td>" . $_h1 . " </td>";

      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 4) {

        $page_html .= "<td>" . (int)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 4, $schooljaar);

      $page_html .= "<td>" . $_h1_1 . " </td>";

      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 4) {

        $page_html .= "<td>" . number_format($avg_h, 0) . " </td>";
      }
    }
    $page_html .= "</tr>";

    $id = $item['studentid'];
    $schooljaar = $_GET["schoolJaar"];
    $klas_in = $_GET["klas"];
    $schoolid = $_SESSION["SchoolID"];
    $cont_verzuim1 = 0;
    $cont_verzuim2 = 0;
    $cont_verzuim3 = 0;
    $cont_laat1 = 0;
    $cont_laat2 = 0;
    $cont_laat3 = 0;
    $cont_spi1 = 0;
    $cont_spi2 = 0;
    $cont_spi3 = 0;
    $fecha1 = $s->_setting_begin_rap_1;
    $fecha2 = $s->_setting_end_rap_1;
    $fecha3 = $s->_setting_begin_rap_2;
    $fecha4 = $s->_setting_end_rap_2;
    $fecha5 = $s->_setting_begin_rap_3;
    $fecha6 = $s->_setting_end_rap_3;
    $sql_query_verzuim = "SELECT s.id as studentid,v.p1,v.p2,v.p3,v.p4,v.p5,v.p6,v.p7,v.p8,v.p9,v.p10, v.datum
            from students s inner join le_verzuim_hs v
            where s.class = '$klas_in'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
    $resultado = mysqli_query($mysqli, $sql_query_verzuim);
    while ($row1 = mysqli_fetch_assoc($resultado)) {
      $datum = $u->convertfrommysqldate_new($row1["datum"]);
      if ($datum >= $fecha1 && $datum <= $fecha2) {
        if ($row1["p10"] == 'A') {
          $cont_verzuim1++;
        } else {
        }
        for ($y = 1; $y <= 9; $y++) {
          if ($row1["p" . $y] == 'L') {
            $cont_laat1++;
          }
          if ($row1["p" . $y] == 'S') {
            $cont_spi1++;
          }
        }
      } else if ($datum >= $fecha3 && $datum <= $fecha4 && $_GET["rap"] > 1) {
        if ($row1["p10"] == 'A') {
          $cont_verzuim2++;
        }
        for ($y = 1; $y <= 9; $y++) {
          if ($row1["p" . $y] == 'L') {
            $cont_laat2++;
          }
          if ($row1["p" . $y] == 'S') {
            $cont_spi2++;
          }
        }
      } else if ($datum >= $fecha5 && $datum <= $fecha6 && $_GET["rap"] > 2) {
        if ($row1["p10"] == 'A') {
          $cont_verzuim3++;
        }
        for ($y = 1; $y <= 9; $y++) {
          if ($row1["p" . $y] == 'L') {
            $cont_laat3++;
          }
          if ($row1["p" . $y] == 'S') {
            $cont_spi3++;
          }
        }
      }
    }
    $page_html .= "<tr>";
    $page_html .= "<td style='padding-top: 20px;' width='65%'>&nbsp;&nbsp;Verzuim</td>";
    $page_html .= "<td style='padding-top: 20px;'>" . $cont_verzuim1 . " </td>";
    $page_html .= "<td style='padding-top: 20px;'>" . $cont_verzuim2 . " </td>";
    $page_html .= "<td style='padding-top: 20px;'>" . $cont_verzuim3 . "</td>";
    if ($klas != 4) {

      $page_html .= "<td style='padding-top: 20px;'>" . ($cont_verzuim1 + $cont_verzuim2 + $cont_verzuim3) . "</td>";
    }
    $page_html .= "</tr>";

    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Laat</td>";
    $page_html .= "<td>" . $cont_laat1 . " </td>";
    $page_html .= "<td>" . $cont_laat2 . " </td>";
    $page_html .= "<td>" . $cont_laat3 . "</td>";
    if ($klas != 4) {

      $page_html .= "<td>" . ($cont_laat1 + $cont_laat2 + $cont_laat3) . "</td>";
    }
    $page_html .= "</tr>";

    $page_html .= "</tr>";

    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Ongeoorloofd verzuim (spijbelen)</td>";
    $page_html .= "<td>" . $cont_spi1 . " </td>";
    $page_html .= "<td>" . $cont_spi2 . " </td>";
    $page_html .= "<td>" . $cont_spi3 . "</td>";
    if ($klas != 4) {

      $page_html .= "<td>" . ($cont_spi1 + $cont_spi2 + $cont_spi3) . "</td>";
    }
    $page_html .= "</tr>";
  }

  if ($_SESSION["SchoolType"] == 1) {

    if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
      $klas = substr($_GET["klas"], 0, 1);
      $page_html .= "<style>th, tbody td{min-width: 35px;}</style>";

      $page_html .= "<div class='row'>";
      $page_html .= "<div class='col-md-12'>";
      $page_html .= "<style>.table{margin-bottom: 1rem;}</style>";
      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Lezen</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th >1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
        if ($klas != 1) {
          $page_html .= "<th>Eind</th>";
        }
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th >1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
        if ($klas != 1) {
          $page_html .= "<th>Eind</th>";
        }
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th >1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
        if ($klas != 1) {
          $page_html .= "<th>Eind</th>";
        }
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Technisch lezen</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;

        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . " </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }

        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h1_2 <= 5.4 && $_h1_2 ? " class=\"bg-danger\"" : "") . ">" . $_h1_2 . " </td>";
        $page_html .= "<td" . ((float)$_h1_3 <= 5.4 && $_h1_3 ? " class=\"bg-danger\"" : "") . ">" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Begrijpend lezen</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . " </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";


        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;

        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Gemiddeld</b></td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        if ($h1_beg > 0 && $h1_tec > 0) {
          $_h1 = round(($h1_beg + $h1_tec) / 2, 1);
        } else if ($h1_beg > 0) {
          $_h1 = $h1_beg;
        } else if ($h1_tec > 0) {
          $_h1 = $h1_tec;
        }

        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . " </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {
        if ($h1_beg > 0 && $h1_tec > 0) {
          $_h1_1 = round(($h1_beg + $h1_tec) / 2, 1);
        } else if ($h1_beg > 0) {
          $_h1_1 = $h1_beg;
        } else if ($h1_tec > 0) {
          $_h1_1 = $h1_tec;
        }

        if ($h2_beg > 0 && $h2_tec > 0) {
          $_h2_1 = round(($h2_beg + $h2_tec) / 2, 1);
        } else if ($h2_beg > 0) {
          $_h2_1 = $h2_beg;
        } else if ($h2_tec > 0) {
          $_h2_1 = $h2_tec;
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        if ($h1_beg > 0 && $h1_tec > 0) {
          $_h1_1 = round(($h1_beg + $h1_tec) / 2, 1);
        } else if ($h1_beg > 0) {
          $_h1_1 = $h1_beg;
        } else if ($h1_tec > 0) {
          $_h1_1 = $h1_tec;
        }

        if ($h2_beg > 0 && $h2_tec > 0) {
          $_h1_2 = round(($h2_beg + $h2_tec) / 2, 1);
        } else if ($h2_beg > 0) {
          $_h1_2 = $h2_beg;
        } else if ($h2_tec > 0) {
          $_h1_2 = $h2_tec;
        }

        if ($h3_beg > 0 && $h3_tec > 0) {
          $_h1_3 = round(($h3_beg + $h3_tec) / 2, 1);
        } else if ($h3_beg > 0) {
          $_h1_3 = $h3_beg;
        } else if ($h3_tec > 0) {
          $_h1_3 = $h3_tec;
        }

        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1 && $_h1_1 > 0) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $h1_beg = 0;
      $h1_tec = 0;

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";


      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";
      $page_html .= "<tbody>";

      $page_html .= "<tr>";
      $page_html .= "<td width='65%' ><b>Schrijven</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . "</td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . "    </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);


        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";



      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Rekenen</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Rekenen</td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . "</td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . "    </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }


        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Inzicht</td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        if ($klas != 1) {
          if ($item['studentid'] != 4966 && $item['studentid'] != 5017) {
            $page_html .= "<td>" . $_h1 . " </td>";
          } else {
            $page_html .= "<td> </td>";
          }
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 2, $schooljaar);


        if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
          $page_html .= "<td>" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 4, $schooljaar);


        if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
          $page_html .= "<td>" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Nederlandse Taal</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th  ></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th ></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th ></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Taal</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td>" . $_h1  . " </td>";
        } else {
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Mondeling taalgebruik</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        if ($klas != 1  && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 2, $schooljaar);

        if ($klas != 1  && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
          $page_html .= "<td>" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 4, $schooljaar);

        if ($klas != 1  && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
          $page_html .= "<td>" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Wereldoriëntatie</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td> </td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1  . " </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Expressie</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $c1 = '';
        $_h1 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1 >= 9) {
          $c1 = 'A';
        } else if ($_h1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1 != null && $_h1 <= 4.4) {
          $c1 = 'F';
        }
        if ($klas != 1) {
          $page_html .= "<td>" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /* $page_html .= "<td>" . $c1  . " </td>"; */
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $c1 = '';
        $c2 = '';
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h2_1 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h2_1 >= 9) {
          $c2 = 'A';
        } else if ($_h2_1 >= 7.5) {
          $c2 = 'B';
        } else if ($_h2_1 >= 6.5) {
          $c2 = 'C';
        } else if ($_h2_1 >= 5.5) {
          $c2 = 'D';
        } else if ($_h2_1 >= 4.5) {
          $c2 = 'E';
        } else if ($_h2_1 != null && $_h2_1 <= 4.4) {
          $c2 = 'F';
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $c2 . " </td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /* if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
if($avg_h == 0.0){$avg_h = null;}
        $page_html .= "<td>" . number_format($avg_h,2) . " </td>"; */
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $c1 = '';
        $c2 = '';
        $c3 = '';

        $_h1_1 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h1_2 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h1_2 >= 9) {
          $c2 = 'A';
        } else if ($_h1_2 >= 7.5) {
          $c2 = 'B';
        } else if ($_h1_2 >= 6.5) {
          $c2 = 'C';
        } else if ($_h1_2 >= 5.5) {
          $c2 = 'D';
        } else if ($_h1_2 >= 4.5) {
          $c2 = 'E';
        } else if ($_h1_2 != null && $_h1_2 <= 4.4) {
          $c2 = 'F';
        }
        $_h1_3 =  $c->_writerapportdata_cijfers(8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_3 >= 9) {
          $c3 = 'A';
        } else if ($_h1_3 >= 7.5) {
          $c3 = 'B';
        } else if ($_h1_3 >= 6.5) {
          $c3 = 'C';
        } else if ($_h1_3 >= 5.5) {
          $c3 = 'D';
        } else if ($_h1_3 >= 4.5) {
          $c3 = 'E';
        } else if ($_h1_3 != null && $_h1 <= 4.4) {
          $c3 = 'F';
        }
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $c2 . " </td>";
        $page_html .= "<td>" . $c3 . " </td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /*         $page_html .= "<td>" . number_format($avg_h,2) . " </td>";
 */
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Lichamelijke opvoeding</td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $c1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1 >= 9) {
          $c1 = 'A';
        } else if ($_h1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1 != null && $_h1 <= 4.4) {
          $c1 = 'F';
        }
        if ($klas != 1) {
          $page_html .= "<td>" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /*         $page_html .= "<td>" . $_h1  . " </td>";
 */
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $c1 = "";
        $c2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h2_1 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h2_1 >= 9) {
          $c2 = 'A';
        } else if ($_h2_1 >= 7.5) {
          $c2 = 'B';
        } else if ($_h2_1 >= 6.5) {
          $c2 = 'C';
        } else if ($_h2_1 >= 5.5) {
          $c2 = 'D';
        } else if ($_h2_1 >= 4.5) {
          $c2 = 'E';
        } else if ($_h2_1 != null && $_h2_1 <= 4.4) {
          $c2 = 'F';
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $c2 . " </td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /* if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
if($avg_h == 0.0){$avg_h = null;}
        $page_html .= "<td>" . number_format($avg_h,2) . " </td>"; */
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $c1 = "";
        $c2 = "";
        $c3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h1_2 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h1_2 >= 9) {
          $c2 = 'A';
        } else if ($_h1_2 >= 7.5) {
          $c2 = 'B';
        } else if ($_h1_2 >= 6.5) {
          $c2 = 'C';
        } else if ($_h1_2 >= 5.5) {
          $c2 = 'D';
        } else if ($_h1_2 >= 4.5) {
          $c2 = 'E';
        } else if ($_h1_2 != null && $_h1_2 <= 4.4) {
          $c2 = 'F';
        }
        $_h1_3 =  $c->_writerapportdata_cijfers(9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_3 >= 9) {
          $c3 = 'A';
        } else if ($_h1_3 >= 7.5) {
          $c3 = 'B';
        } else if ($_h1_3 >= 6.5) {
          $c3 = 'C';
        } else if ($_h1_3 >= 5.5) {
          $c3 = 'D';
        } else if ($_h1_3 >= 4.5) {
          $c3 = 'E';
        } else if ($_h1_3 != null && $_h1_3 <= 4.4) {
          $c3 = 'F';
        }
        /*         if ($_h1_1 != null && $_h1_2 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3)/3;
        } else if ($_h1_1 != null && $_h1_2 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }
 */
        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $c2 . " </td>";
        $page_html .= "<td>" . $c3 . " </td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /*         $page_html .= "<td>" . number_format($avg_h,2) . " </td>";
 */
      }

      $page_html .= "</tr>";

      if ($_GET["klas"] == '3A' || $_GET["klas"] == '3B' || $_GET["klas"] == '3C' || $_GET["klas"] == '3D' || $_GET["klas"] == '3') {
        $page_html .= "<tr>";

        $page_html .= "<td width='65%'>Zwemniveau</td>";
        $page_html .= "<td style='width: 75px;'></td>";
        if ($_GET["rap"] == '1') {
          $_h1 = "";
          $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
          $page_html .= "<td >" . $_h1 . " </td>";
          $page_html .= "<td></td>";
          $page_html .= "<td></td>";
          $page_html .= "<td></td>";
        }
        if ($_GET["rap"] == '2') {

          $_h1_1 = "";
          $_h1_2 = "";
          $avg_h = 0;

          $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
          $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 2, $schooljaar);


          $page_html .= "<td>" . $_h1_1 . " </td>";
          $page_html .= "<td>" . $_h2_1 . " </td>";
          $page_html .= "<td> </td>";
          $page_html .= "<td> </td>";

          if ($_h1_1 > 0 && $_h2_1 > 0) {
            $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
          } else if ($_h1_1 > 0) {
            $avg_h = $_h1_1;
          } else if ($_h2_1 > 0) {
            $avg_h = $_h2_1;
          }
          if ($avg_h == 0.0) {
            $avg_h = null;
          }
        }
        if ($_GET["rap"] == '3') {

          $_h1_1 = "";
          $_h1_2 = "";
          $_h1_3 = "";
          $avg_h = "";

          $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
          $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 2, $schooljaar);
          $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 3, $schooljaar);
          $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 4, $schooljaar);


          $page_html .= "<td>" . $_h1_1 . " </td>";
          $page_html .= "<td>" . $_h1_2 . " </td>";
          $page_html .= "<td>" . $_h1_3 . " </td>";
          $page_html .= "<td> </td>";
        }

        $page_html .= "</tr>";
      }

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Talen</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th ></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th ></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        if ($klas != 1) {
          $page_html .= "<th></th>";
        }
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Engels</td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td> </td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        } else if ($klas != 1) {
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        } else if ($klas != 1) {
          $page_html .= "<td> </td>";
        }
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>&nbsp;&nbsp;Spaans</td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td> </td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1  . " </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        } else if ($klas != 1) {
          $page_html .= "<td> </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td " . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        } else if ($klas != 1) {
          $page_html .= "<td> </td>";
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Maatschappijleer</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $c1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1 >= 9) {
          $c1 = 'A';
        } else if ($_h1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1 != null && $_h1 <= 4.4) {
          $c1 = 'F';
        }
        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1 <= 5.4 && $_h1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td> </td>";
        }
        /*         $page_html .= "<td>" . $_h1  . " </td>";
 */
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $c1 = "";
        $c2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h2_1 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h2_1 >= 9) {
          $c2 = 'A';
        } else if ($_h2_1 >= 7.5) {
          $c2 = 'B';
        } else if ($_h2_1 >= 6.5) {
          $c2 = 'C';
        } else if ($_h2_1 >= 5.5) {
          $c2 = 'D';
        } else if ($_h2_1 >= 4.5) {
          $c2 = 'E';
        } else if ($_h1_2 != null && $_h1_2 <= 4.4) {
          $c2 = 'F';
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $c2 . " </td>";
        $page_html .= "<td></td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /* if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
if($avg_h == 0.0){$avg_h = null;}
        $page_html .= "<td>" . number_format($avg_h,2) . " </td>"; */
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $c1 = "";
        $c2 = "";
        $c3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($_h1_1 >= 9) {
          $c1 = 'A';
        } else if ($_h1_1 >= 7.5) {
          $c1 = 'B';
        } else if ($_h1_1 >= 6.5) {
          $c1 = 'C';
        } else if ($_h1_1 >= 5.5) {
          $c1 = 'D';
        } else if ($_h1_1 >= 4.5) {
          $c1 = 'E';
        } else if ($_h1_1 != null && $_h1_1 <= 4.4) {
          $c1 = 'F';
        }
        $_h1_2 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        if ($_h1_2 >= 9) {
          $c2 = 'A';
        } else if ($_h1_2 >= 7.5) {
          $c2 = 'B';
        } else if ($_h1_2 >= 6.5) {
          $c2 = 'C';
        } else if ($_h1_2 >= 5.5) {
          $c2 = 'D';
        } else if ($_h1_2 >= 4.5) {
          $c2 = 'E';
        } else if ($_h1_2 != null && $_h1_2 <= 4.4) {
          $c2 = 'F';
        }
        $_h1_3 =  $c->_writerapportdata_cijfers(27, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_3 >= 9) {
          $c3 = 'A';
        } else if ($_h1_3 >= 7.5) {
          $c3 = 'B';
        } else if ($_h1_3 >= 6.5) {
          $c3 = 'C';
        } else if ($_h1_3 >= 5.5) {
          $c3 = 'D';
        } else if ($_h1_3 >= 4.5) {
          $c3 = 'E';
        } else if ($_h1_3 != null && $_h1_3 <= 4.4) {
          $c3 = 'F';
        }
        /*         if ($_h1_1 != null && $_h1_2 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3)/3;
        } else if ($_h1_1 != null && $_h1_2 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 != null && $_h1_3 != null) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }
 */

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $c1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $c2 . " </td>";
        $page_html .= "<td>" . $c3 . " </td>";
        if ($klas != 1) {
          $page_html .= "<td></td>";
        }
        /*         $page_html .= "<td>" . number_format($avg_h,2) . " </td>";
 */
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Verkeer</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1 <= 5.4 && $_h1 ? " class=\"bg-danger\"" : "") . ">" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td> </td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1  . " </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);


        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";

      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%' ><b>Boekbespreking/spreekbeurt</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1 <= 5.4 && $_h1 ? " class=\"bg-danger\"" : "") . ">" . $_h1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td> </td>";
        $page_html .= "<td> </td>";
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1  . " </td>";
        }
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td> </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers(28, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);
        if ($_h1_1 > 0 && $_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3) / 3;
        } else if ($_h1_1 > 0 && $_h1_2 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_2) / 2;
        } else if ($_h1_1 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h1_3) / 2;
        } else if ($_h1_2 > 0 && $_h1_3 > 0) {
          $avg_h = ((float)$_h1_2 + (float)$_h1_3) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h1_2 > 0) {
          $avg_h = $_h1_2;
        } else if ($_h1_3 > 0) {
          $avg_h = $_h1_3;
        }

        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td> </td>";
        }
        $page_html .= "<td>" . $_h1_2 > 0.0 ? $_h1_2 : '' . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
        if ($avg_h > 0.0) {
          if ($klas != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";
    } else if ($_SESSION['SchoolID'] != 18) {
      $page_html .= "<tr>";
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Inzicht</td>";

      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        $page_html .= "<td>" . $_h1 . " </td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 2, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h2_1 . " </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h12', $item['studentid'], 4, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }
      $page_html .= "</tr>";


      $page_html .= "<tr>";
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Mondeling taalgebrui</td>";

      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        $page_html .= "<td>" . $_h1 . " </td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 2, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h2_1 . " </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h13', $item['studentid'], 4, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }
      $page_html .= "</tr>";


      $page_html .= "<tr>";
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Zwem niveau</td>";

      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
        $page_html .= "<td>" . $_h1 . " </td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 2, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h2_1 . " </td>";

        if ($_h1_1 > 0 && $_h2_1 > 0) {
          $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
        } else if ($_h1_1 > 0) {
          $avg_h = $_h1_1;
        } else if ($_h2_1 > 0) {
          $avg_h = $_h2_1;
        }
        if ($avg_h > 0.0) {
          $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h14', $item['studentid'], 4, $schooljaar);


        $page_html .= "<td>" . $_h1_1 . " </td>";
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }
      $page_html .= "</tr>";
    } else {
      $klas_1 = substr($_GET["klas"], 0, 1);
      $page_html .= "<style>th, tbody td{min-width: 35px;}</style>";
      $page_html .= "<div class='row'>";
      $page_html .= "<div class='col-md-12'>";
      $page_html .= "<style>.table{margin-bottom: 1rem;}</style>";
      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Idioma Oral</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th>1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th>1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th>1</th>";
        $page_html .= "<th>2</th>";
        $page_html .= "<th>3</th>";
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Combersacion</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 1, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Comprension</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";


        if ($avg_h > 0.0) {
          if ($klas_1 != 1) {
            $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
          }
        }
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 2, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";


      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Idioma Skirbi</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Ehercicio</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 3, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Composicion</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 4, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Dictado</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 5, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";


      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Lesa</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Tecnica</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;


        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 6, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Entonacion</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 7, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Comprension</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 50, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";


      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<thead>";

      $page_html .= "<th>Som</th>";
      if ($_GET["rap"] == '1') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '2') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }
      if ($_GET["rap"] == '3') {
        $page_html .= "<th style='width: 75px;'></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
        $page_html .= "<th></th>";
      }

      $page_html .= "</thead>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Traha som</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;


        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 8, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Rek for di cabes</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 9, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Tafels</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 10, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Redaccion</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 11, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Placa</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 12, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Midi</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 13, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Holoshi</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 14, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Kalender</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 15, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";


      $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

      $page_html .= "<tbody>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'><b>Skirbi</b></td>";
      $page_html .= "<td style='width: 75px;'></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;


        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 26, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'><b>Trafico</b></td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_tec = $_h1;


        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

        $h1_tec = $_h1_1;
        $h2_tec = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 24, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);


        $h1_tec = $_h1_1;
        $h2_tec = $_h1_2;
        $h3_tec = $_h1_3;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'><b>Proyecto</b></td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1;

        if ($klas_1 != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

        $h1_beg = $_h1_1;
        $h2_beg = $_h2_1;

        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";

        $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
        $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
        $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 25, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

        $h1_beg = $_h1_1;
        $h2_beg = $_h1_2;
        $h3_beg = $_h1_3;



        if ($klas_1 != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";

      $page_html .= "</tbody>";

      $page_html .= "</table>";
    }
  }
  $page_html .= "</tbody>";
  $page_html .= "</table>";
  $page_html .= "</div>";
  $page_html .= "</div>";

  ///////////////////////////////////////END HOUDING ///////////////////////
  $page_html .= "</div>";
  $page_html .= "<div class='card border-0'>";

  if ($_SESSION["SchoolType"] == 2) {
    /* if ($klas == 3) { */
    $opmerking = array();
    $defi = array();
    $rapport = $_GET["rap"];
    $id = $item['studentid'];
    $klas_o = $_GET["klas"];
    for ($y = 0; $rapport >= $y; $y++) {
      $y++;
      $get_opmerking = "SELECT
      (SELECT opmerking1 FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas_o' AND studentid = '$id' AND rapport = $y AND schooljaar = '$schooljaar' LIMIT 1) as opmerking1,
      opmerking2,
      (SELECT opmerking3 FROM opmerking WHERE SchoolID = '$schoolid' AND klas = '$klas_o' AND studentid = '$id' AND rapport = $y AND schooljaar = '$schooljaar' LIMIT 1) as opmerking3,
      advies,
      ciclo
    FROM opmerking
    WHERE schoolid = '$schoolid'
      AND klas = '$klas_o'
      AND studentid = '$id'
      AND schooljaar = '$schooljaar'
      AND rapport = $y
    LIMIT 1;
    ;";
      $result1 = mysqli_query($mysqli, $get_opmerking);
      if ($result1->num_rows > 0) {
        $row2 = mysqli_fetch_assoc($result1);
        $opmerking[$y] = $row2["opmerking1"];
        $radio1 = $row2["opmerking2"];
        $opmerking3 = $row2["opmerking3"];
        $radio2 = $row2["advies"];
        $radio3 = $row2["ciclo"];
      } else {
        $opmerking[$y] = null;
        $opmerking3 = null;
        $radio1 = "";
        $radio2 = "";
        $radio3 = "";
      }
      $radio1 = $radio1 == 1 ? "checked" : "";
      $radio2 = $radio2 == 1 ? "checked" : "";
      $radio3 = $radio3 == 1 ? "checked" : "";
      $defi[$y] = null;
      if (($opmerking3 == null || $opmerking3 == "") && $level_klas != 4) {
        $cuenta_pri = 0;
        $cuenta = 0;
        $cijfers = 0;
        if ($y == 4) {
          $get_cijfers = "SELECT v.volledigenaamvak as vak,ROUND(SUM(c.gemiddelde)/COUNT(c.rapnummer)) as gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE v.volgorde > 0 AND c.studentid = '$id' AND c.klas = '$klas_o' AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL GROUP BY vak ORDER BY c.studentid,vak;";
        } else {
          $get_cijfers = "SELECT (SELECT volledigenaamvak FROM le_vakken WHERE ID = c.vak AND volgorde > 0) as vak,c.gemiddelde FROM le_cijfers c WHERE c.studentid = '$id' AND c.klas = '$klas_o' AND c.rapnummer = $y AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL;";
        }
        $result2 = mysqli_query($mysqli, $get_cijfers);
        if ($result2->num_rows > 0) {
          while ($row3 = mysqli_fetch_assoc($result2)) {
            if ($row3["vak"] != "rk" && $row3["vak"] != null) {
              $cijfers = $cijfers + $row3["gemiddelde"];
            }
            if ($row3["vak"] == "ne" || $row3["vak"] == "en" || $row3["vak"] == "wi") {
              if ($row3["gemiddelde"] == 0 || $row3["gemiddelde"] >= 5.5 || $row3["gemiddelde"] == NULL) {
                $cuenta_pri = $cuenta_pri + 0;
              } else if ($row3["gemiddelde"] < 1) {
                $cuenta_pri = $cuenta_pri + 6;
              } else if ($row3["gemiddelde"] < 2) {
                $cuenta_pri = $cuenta_pri + 5;
              } else if ($row3["gemiddelde"] < 3) {
                $cuenta_pri = $cuenta_pri + 4;
              } else if ($row3["gemiddelde"] < 4) {
                $cuenta_pri = $cuenta_pri + 3;
              } else if ($row3["gemiddelde"] < 5) {
                $cuenta_pri = $cuenta_pri + 2;
              } else if ($row3["gemiddelde"] < 5.5) {
                $cuenta_pri = $cuenta_pri + 1;
              }
            } else if ($row3["vak"] != "rk" && $row3["vak"] != NULL) {
              if ($row3["gemiddelde"] == 0 || $row3["gemiddelde"] >= 5.5 || $row3["gemiddelde"] == NULL) {
                $cuenta = $cuenta + 0;
              } else if ($row3["gemiddelde"] < 1) {
                $cuenta = $cuenta + 6;
              } else if ($row3["gemiddelde"] < 2) {
                $cuenta = $cuenta + 5;
              } else if ($row3["gemiddelde"] < 3) {
                $cuenta = $cuenta + 4;
              } else if ($row3["gemiddelde"] < 4) {
                $cuenta = $cuenta + 3;
              } else if ($row3["gemiddelde"] < 5) {
                $cuenta = $cuenta + 2;
              } else if ($row3["gemiddelde"] < 5.5) {
                $cuenta = $cuenta + 1;
              }
            }
          }
        }

        if ($cijfers < 71 || $cuenta_pri > 2 || ($cuenta + $cuenta_pri) > 3) {
          $defi[$y] = 0;
        } else {
          $defi[$y] = 1;
        }
      } else {
        $opmerking3 = strtoupper($opmerking3);
        if ($opmerking3 == "O" || $opmerking3 == "A") {
          $defi[$y] = 0;
        } else if ($opmerking3 == "V" || $opmerking3 == "G") {
          $defi[$y] = 1;
        } else {
          $defi[$y] = null;
        }
      }
      $y--;
    }

    $page_html .= "<div class='row'>";
    $page_html .= "<div class='card'>";
    $page_html .= "<div class='card-body' style='padding-bottom: 0px;'>";
    if ($level_klas != 4) {
      $page_html .= "<h6 class='card-title'>Opmerking bij het eerste rapport</h6>";
    } else {
      $page_html .= "<h6 class='card-title'>Opmerking bij het SE KAART 1</h6>";
    }
    $page_html .= "<textarea style='width: 100%;'>" . $opmerking[1] . "</textarea>";
    $page_html .= "<div class='row' style='justify-content: space-evenly;'>";
    if ($defi[1] === null) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[1] == 1) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[1] == 0) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    }
    $page_html .= "</div>";
    if ($level_klas != 4) {
      $page_html .= "<h6 class='card-title'>Opmerking bij het tweede rapport</h6>";
    } else {
      $page_html .= "<h6 class='card-title'>Opmerking bij het SE KAART 2</h6>";
    }
    $page_html .= "<textarea style='width: 100%;'>" . $opmerking[2] . "</textarea>";
    $page_html .= "<div class='row' style='justify-content: space-evenly;'>";
    if ($defi[2] === null || $rapport < 2) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[2] == 1 && $rapport >= 2) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[2] == 0 && $rapport >= 2) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    }
    $page_html .= "</div>";
    if ($level_klas != 4) {
      $page_html .= "<h6 class='card-title'>Opmerking bij het eindrapport</h6>";
      $page_html .= "<textarea style='width: 100%;'>" .  $opmerking[4] . "</textarea>";
    } else {
      $page_html .= "<h6 class='card-title'>Opmerking bij het SE KAART 3</h6>";
      $page_html .= "<textarea style='width: 100%;'>" .  $opmerking[3] . "</textarea>";
    }
    $page_html .= "<div class='row' style='justify-content: space-evenly;'>";
    if ($defi[4] === null) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[4] == 1 && $rapport == 3) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    } else if ($defi[4] == 0 && $rapport == 3) {
      $page_html .= "<div>";
      $page_html .= "<input type='radio' style='margin-right: 5px;'><label>Voldoende</label>";
      $page_html .= "</div>";
      $page_html .= "<div>";
      $page_html .= "<input type='radio' checked style='margin-right: 5px;'><label>Onvoldoende</label>";
      $page_html .= "</div>";
    }
    $page_html .= "</div>";
    $page_html .= "<br>";
    $page_html .= "<div class='column'>";
    $page_html .= "<div>";
    switch (substr($_GET["klas"], 0, 1)) {
      case 1:
        $klas_var = "<label>Over naar ciclo basico 2</label>";
        break;
      case 2:
        $klas_var = "<label>Over naar ciclo avansa 1</label>";
        break;
      case 3:
        // case 4:
        $klas_var = "<label>Over naar ciclo avansa 2 met pakket</label>" . $paket;
        break;
      case 4:
        $klas_var = $paket;
        break;
    }

    if ($klas != 4) {
      $page_html .= "<input " . $radio2 . " type='radio' style='margin-right: 5px;'>" . $klas_var;
      $page_html .= "</div>";
      $page_html .= "<br>";
      $page_html .= "<div>";
      $page_html .= "<input " . $radio3 . " type='radio' style='margin-right: 5px;'><label>Niet over</label>";
      $page_html .= "</div>";
      $page_html .= "<br>";
      $page_html .= "<div>";
      $page_html .= "<input " . $radio1 . " type='radio' style='margin-right: 5px;'><label>Verwezen naar ander schooltype</label>";
      $page_html .= "</div>";
    } else {
      $page_html .= "Pakket: " . $klas_var;
      $page_html .= "</div>";
    }

    $page_html .= "</div>";

    $page_html .= "</div>";
    $page_html .= "</div>";
    $page_html .= "</div>";
    /* }  else {
      $page_html .= "<div class='row'>";
      $page_html .= "<div class='card'>";
      $page_html .= "<div class='card-body' style='padding-bottom: 0px;'>";
      $page_html .= "<h6 class='card-title'>Opmerking bij het eerste rapport</h6>";
      $page_html .= "<hr style='border-top: dotted 2px; margin-top: 2.1rem; margin-bottom: .3rem' />";
      $page_html .= "<h6>O Voldoende:</h6>";
      $page_html .= "<h6>O Onvoldoende:</h6>";
      $page_html .= "<br>";
      $page_html .= "</div>";
      $page_html .= "</div>";
      $page_html .= "</div>";


      $page_html .= "<div class='row'>";
      $page_html .= "<div class='card'>";
      $page_html .= "<div class='card-body'>";
      $page_html .= "<h6 class='card-title'>Opmerking bij het tweede rapport</h6>";
      $page_html .= "<hr style='border-top: dotted 2px; margin-top: 2.1rem; margin-bottom: .3rem' />";
      $page_html .= "<h6>O Voldoende:</h6>";
      $page_html .= "<h6>O Onvoldoende:</h6>";
      $page_html .= "</div>";
      $page_html .= "</div>";
      $page_html .= "</div>";


      $page_html .= "<div class='row'>";
      $page_html .= "<div class='card'>";
      $page_html .= "<div class='card-body'>";
      $page_html .= "<h6 class='card-title'>Opmerking bij het derde rapport</h6>";
      $page_html .= "<hr style='border-top: dotted 2px; margin-top: 2.1rem; margin-bottom: .3rem' />";
      if ($schoolId != 17 && $_SESSION["SchoolType"] != 2) {
        $page_html .= "<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Basico 2</h6>";
      } else if ($_SESSION["SchoolType"] == 2) {
        switch ($level_klas) {
          case 1:
            $page_html .= "<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Basico 2</h6>";
            break;
          case 2:
            $page_html .= "<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Avansa 1</h6>";
            break;
          case 3:
            $page_html .= "<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Avansa 2</h6>";
            break;
        }
      } else {
        $page_html .= "<h6 class='classovernar' id='idovernar'>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar</h6>";
      }
      $page_html .= "<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNiet over</h6>";
      $page_html .= "<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspVerwezen naar:.................................................................</h6>";
      $page_html .= "<h6>O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOpmerking:.....................................................................</h6>";
      $page_html .= "</div>";
      $page_html .= "</div>";
      $page_html .= "</div>";
    } */
  }

  $page_html .= "<div class='row' style='margin: 0 !important;'>";

  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
    $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm' style='margin-top: 3%;'>";
    $page_html .= "<thead>";
    $page_html .= "<th>Gedrag, leer- en werkhouding</th>";


    if ($_GET["rap"] == '1') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 1) {
        $page_html .= "<th>Eind</th>";
      }
    }
    if ($_GET["rap"] == '2') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 1) {
        $page_html .= "<th>Eind</th>";
      }
    }
    if ($_GET["rap"] == '3') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
      if ($klas != 1) {
        $page_html .= "<th>Eind</th>";
      }
    }

    $page_html .= "</thead>";
    $page_html .= "<tbody>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Concentratie</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Contact met leerkracht</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . (float)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar);

      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 3, $schooljaar, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 4, $schooljaar, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }

    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Inzet / Motivatie</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Contact met leerling</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";


    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . (float)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }

    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Werkverzorging</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . (float)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }


    $page_html .= "</tr>";

    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Huiswerk attitude</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Zelfvertrouwen</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }


    $page_html .= "</tr>";

    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - docent</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Nauwkeurigheid</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);

      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }


    $page_html .= "</tr>";
    $page_html .= "<tr>";
    if ($_SESSION["SchoolType"] == 2) {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Sociaal gedrag - leerling</td>";
    } else {
      $page_html .= "<td width='65%'>&nbsp;&nbsp;Doorzettingsvermogen</td>";
    }
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . (float)$_h1  . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_SESSION["SchoolType"] == 2) {
        $page_html .= "<td>" . number_format($avg_h, 1) . " </td>";
      }
    }


    $page_html .= "</tr>";
    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Zelfstandigheid</td>";
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    $page_html .= "</tr>";


    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Werktempo</td>";
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    $page_html .= "</tr>";


    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Werkverzorging</td>";
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    $page_html .= "</tr>";


    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Concentratie</td>";
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    $page_html .= "</tr>";


    $page_html .= "<tr>";
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Huiswerk</td>";
    $page_html .= "<td style='width: 75px;'></td>";

    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 1, $schooljaar);
      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 2, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }

      if ($_h1_1 > 0 && $_h2_1 > 0) {
        $avg_h = ((float)$_h1_1 + (float)$_h2_1) / 2;
      } else if ($_h1_1 > 0) {
        $avg_h = $_h1_1;
      } else if ($_h2_1 > 0) {
        $avg_h = $_h2_1;
      }
      if ($avg_h == 0.0) {
        $avg_h = null;
      }
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h11', $item['studentid'], 4, $schooljaar);


      if ($klas != 1 && $item['studentid'] != 4966 && $item['studentid'] != 5017 && $item['studentid'] != 8702 && $item['studentid'] != 8701 && $item['studentid'] != 5711 && $item['studentid'] != 5708 && $item['studentid'] != 5712 && $item['studentid'] != 8656 && $item['studentid'] != 8656 && $item['studentid'] != 8693 && $item['studentid'] != 8692 && $item['studentid'] != 8696 && $item['studentid'] != 8674) {
        $page_html .= "<td>" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
      if ($klas != 1) {
        $page_html .= "<td></td>";
      }
    }
    $page_html .= "</tr>";
    $page_html .= "</tbody>";
    $page_html .= "</table>";

    $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";

    $page_html .= "<tbody>";

    $page_html .= "<tr>";
    $schoolid = $_SESSION["SchoolID"];
    $page_html .= "<td width='65%'>&nbsp;&nbsp;Te laat</td>";
    $page_html .= "<td style='width: 75px;'></td>";
    $cont_laat = 0;
    $cont_verzuim = 0;
    $klas = $_GET["klas"];
    if ($_GET["rap"] == '1') {
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {
      $cont_laat1 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['telaat'] > 0) {
            $cont_laat1++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $promedio = $cont_laat1 + $cont_laat;
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td>" . $promedio . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {
      $cont_laat1 = 0;
      $cont_laat2 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $fecha5 = $s->_setting_begin_rap_3;
      $fecha6 = $s->_setting_end_rap_3;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['telaat'] > 0) {
            $cont_laat1++;
          }
        }
        if ($datum >= $fecha5 && $datum <= $fecha6) {
          if ($row1['telaat'] > 0) {
            $cont_laat2++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td>" . $cont_laat2 . " </td>";
      $promedio = $cont_laat + $cont_laat1 + $cont_laat2;
      if ($klas != 1) {
        $page_html .= "<td>" . $promedio . " </td>";
      }
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>&nbsp;&nbsp;Verzuim</td>";
    $page_html .= "<td style='width: 75px;'></td>";
    if ($_GET["rap"] == '1') {
      $cont_laat = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      }
    }
    if ($_GET["rap"] == '2') {
      $cont_laat = 0;
      $cont_laat1 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['absentie'] > 0) {
            $cont_laat1++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td></td>";
      $promedio = $cont_laat1 + $cont_laat;
      if ($klas != 1) {
        $page_html .= "<td>" . $promedio . " </td>";
      }
    }
    if ($_GET["rap"] == '3') {
      $cont_laat = 0;
      $cont_laat1 = 0;
      $cont_laat2 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $fecha5 = $s->_setting_begin_rap_3;
      $fecha6 = $s->_setting_end_rap_3;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['absentie'] > 0) {
            $cont_laat1++;
          }
        }
        if ($datum >= $fecha5 && $datum <= $fecha6) {
          if ($row1['absentie'] > 0) {
            $cont_laat2++;
          }
        }
      }
      if ($klas != 1) {
        $page_html .= "<td>" . $cont_laat . " </td>";
      } else {
        $page_html .= "<td> </td>";
      }
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td>" . $cont_laat2 . " </td>";
      $promedio = $cont_laat + $cont_laat1 + $cont_laat2;
      if ($klas != 1) {
        $page_html .= "<td>" . $promedio . " </td>";
      }
    }

    $page_html .= "</tr>";

    $page_html .= "</tbody>";

    $page_html .= "</table>";
  } else if ($_SESSION["SchoolID"] == 18) {
    $klas = substr($_GET["klas"], 0, 1);
    $page_html .= "<style>th, tbody td{min-width: 35px;}</style>";
    /*     $page_html .= "<style>.table1 td{padding: .15rem;} .table1 th{padding: .15rem;}</style>";
 */
    $page_html .= "<table align='center'   cellpadding='1' cellspacing='1' class='table table1 table-sm'>";

    $page_html .= "<thead>";

    $page_html .= "<th></th>";
    if ($_GET["rap"] == '1') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
    }
    if ($_GET["rap"] == '2') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
    }
    if ($_GET["rap"] == '3') {
      $page_html .= "<th style='width: 75px;'>Rapport</th>";
      $page_html .= "<th>1</th>";
      $page_html .= "<th>2</th>";
      $page_html .= "<th>3</th>";
    }

    $page_html .= "</thead>";

    $page_html .= "<tbody>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Obra di man</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_tec = $_h1;


      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);

      $h1_tec = $_h1_1;
      $h2_tec = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 16, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_tec = $_h1_1;
      $h2_tec = $_h1_2;
      $h3_tec = $_h1_3;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Trabou cu tela</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 17, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Pinta</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 18, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Carpinteria</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 19, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Musica</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 20, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Educacion Fisico</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 21, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    if ($_GET["klas"] == "3A" || $_GET["klas"] == "3B") {
      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Landa</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 2, $schooljaar);


        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h9', $item['studentid'], 4, $schooljaar);


        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";
    }


    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Cushina</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1;

      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h2_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);

      $h1_beg = $_h1_1;
      $h2_beg = $_h2_1;

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";

      $_h1_1 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 1);
      $_h1_2 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 2);
      $_h1_3 =  $c->_writerapportdata_cijfers_18($_GET["klas"], 23, $item['studentid'], $_GET["schoolJaar"], $schoolId, 3);

      $h1_beg = $_h1_1;
      $h2_beg = $_h1_2;
      $h3_beg = $_h1_3;



      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "</tbody>";

    $page_html .= "</table>";


    $page_html .= "<table align='center'  cellpadding='1' cellspacing='1'  class='table table1 table-sm'>";

    $page_html .= "<tbody>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Comportacion pa cu maestro</td>";
    $page_html .= "<td style='width: 75px;'></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h1', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Comportacion pa cu alumno</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h2', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Motivacion</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h3', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Concentracion</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h4', $item['studentid'], 4, $schooljaar);

      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Tempo</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h5', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Traha independiente</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h6', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Cuido di trabou</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h7', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Actitud pa trabou</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $_h1 = "";
      $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      if ($klas != 1) {
        $page_html .= "<td>" . $_h1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {

      $_h1_1 = "";
      $_h1_2 = "";
      $avg_h = 0;

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 2, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {

      $_h1_1 = "";
      $_h1_2 = "";
      $_h1_3 = "";
      $avg_h = "";

      $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 1, $schooljaar);
      $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 2, $schooljaar);
      $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 3, $schooljaar);
      $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h8', $item['studentid'], 4, $schooljaar);


      if ($klas != 1) {
        $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "<td>" . $_h1_2 . " </td>";
      $page_html .= "<td>" . $_h1_3 . " </td>";
    }

    $page_html .= "</tr>";

    if ($_SESSION["SchoolID"] == 18) {

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Tarea pa cas</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $_h1 = "";
        $_h1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
        if ($klas != 1) {
          $page_html .= "<td>" . $_h1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td></td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '2') {

        $_h1_1 = "";
        $_h1_2 = "";
        $avg_h = 0;

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
        $_h2_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 2, $schooljaar);


        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td" . ((float)$_h2_1 <= 5.4 && $_h2_1 ? " class=\"bg-danger\"" : "") . ">" . $_h2_1 . " </td>";
        $page_html .= "<td></td>";
      }
      if ($_GET["rap"] == '3') {

        $_h1_1 = "";
        $_h1_2 = "";
        $_h1_3 = "";
        $avg_h = "";

        $_h1_1 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 1, $schooljaar);
        $_h1_2 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 2, $schooljaar);
        $_h1_3 =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 3, $schooljaar);
        $avg_h =  $c->_writerapportdata_houding($_GET["klas"], 'h10', $item['studentid'], 4, $schooljaar);


        if ($klas != 1) {
          $page_html .= "<td" . ((float)$_h1_1 <= 5.4 && $_h1_1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
        } else {
          $page_html .= "<td></td>";
        }
        $page_html .= "<td>" . $_h1_2 . " </td>";
        $page_html .= "<td>" . $_h1_3 . " </td>";
      }

      $page_html .= "</tr>";
    }

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Laat</td>";
    $page_html .= "<td></td>";
    $cont_laat = 0;
    $cont_verzuim = 0;
    $klas = $_GET["klas"];
    $schoolid = $_SESSION["SchoolID"];
    if ($_GET["rap"] == '1') {
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {
      $cont_laat1 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['telaat'] > 0) {
            $cont_laat1++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {
      $cont_laat1 = 0;
      $cont_laat2 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $fecha5 = $s->_setting_begin_rap_3;
      $fecha6 = $s->_setting_end_rap_3;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['telaat'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['telaat'] > 0) {
            $cont_laat1++;
          }
        }
        if ($datum >= $fecha5 && $datum <= $fecha6) {
          if ($row1['telaat'] > 0) {
            $cont_laat2++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td>" . $cont_laat2 . " </td>";
    }

    $page_html .= "</tr>";

    $page_html .= "<tr>";

    $page_html .= "<td width='65%'>Ausencia</td>";
    $page_html .= "<td></td>";
    if ($_GET["rap"] == '1') {
      $cont_laat = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td></td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '2') {
      $cont_laat = 0;
      $cont_laat1 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['absentie'] > 0) {
            $cont_laat1++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td></td>";
    }
    if ($_GET["rap"] == '3') {
      $cont_laat = 0;
      $cont_laat1 = 0;
      $cont_laat2 = 0;
      $id = $item['studentid'];
      $schooljaar = $_GET["schoolJaar"];
      $fecha1 = $s->_setting_begin_rap_1;
      $fecha2 = $s->_setting_end_rap_1;
      $fecha3 = $s->_setting_begin_rap_2;
      $fecha4 = $s->_setting_end_rap_2;
      $fecha5 = $s->_setting_begin_rap_3;
      $fecha6 = $s->_setting_end_rap_3;
      $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
      $resultado = mysqli_query($mysqli, $sql_query_verzuim);
      while ($row1 = mysqli_fetch_assoc($resultado)) {
        $datum = $u->convertfrommysqldate_new($row1["datum"]);
        if ($datum >= $fecha1 && $datum <= $fecha2) {
          if ($row1['absentie'] > 0) {
            $cont_laat++;
          }
        }
        if ($datum >= $fecha3 && $datum <= $fecha4) {
          if ($row1['absentie'] > 0) {
            $cont_laat1++;
          }
        }
        if ($datum >= $fecha5 && $datum <= $fecha6) {
          if ($row1['absentie'] > 0) {
            $cont_laat2++;
          }
        }
      }
      $page_html .= "<td>" . $cont_laat . " </td>";
      $page_html .= "<td>" . $cont_laat1 . " </td>";
      $page_html .= "<td>" . $cont_laat2 . " </td>";
    }

    $page_html .= "</tr>";

    if ($_SESSION["SchoolID"] != 18) {

      $page_html .= "<tr>";

      $page_html .= "<td width='65%'>Tarea pa cas</td>";
      $page_html .= "<td></td>";
      if ($_GET["rap"] == '1') {
        $cont_laat = 0;

        $id = $item['studentid'];
        $schooljaar = $_GET["schoolJaar"];
        $fecha1 = $s->_setting_begin_rap_1;
        $fecha2 = $s->_setting_end_rap_1;
        $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
        $resultado = mysqli_query($mysqli, $sql_query_verzuim);
        while ($row1 = mysqli_fetch_assoc($resultado)) {
          $datum = $u->convertfrommysqldate_new($row1["datum"]);
          if ($datum >= $fecha1 && $datum <= $fecha2) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat++;
            }
          }
        }
        $page_html .= "<td>" . $cont_laat . " </td>";
        $page_html .= "<td>" . $cont_laat . " </td>";
      }
      if ($_GET["rap"] == '2') {
        $cont_laat1 = 0;
        $id = $item['studentid'];
        $schooljaar = $_GET["schoolJaar"];
        $fecha1 = $s->_setting_begin_rap_1;
        $fecha2 = $s->_setting_end_rap_1;
        $fecha3 = $s->_setting_begin_rap_2;
        $fecha4 = $s->_setting_end_rap_2;
        $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
        $resultado = mysqli_query($mysqli, $sql_query_verzuim);
        while ($row1 = mysqli_fetch_assoc($resultado)) {
          $datum = $u->convertfrommysqldate_new($row1["datum"]);
          if ($datum >= $fecha1 && $datum <= $fecha2) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat++;
            }
          }
          if ($datum >= $fecha3 && $datum <= $fecha4) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat1++;
            }
          }
        }
        $page_html .= "<td>" . $cont_laat . " </td>";
        $page_html .= "<td>" . $cont_laat1 . " </td>";
        $promedio = $cont_laat1 + $cont_laat;
        $page_html .= "<td>" . $promedio . " </td>";
      }
      if ($_GET["rap"] == '3') {
        $cont_laat1 = 0;
        $cont_laat2 = 0;
        $id = $item['studentid'];
        $schooljaar = $_GET["schoolJaar"];
        $fecha1 = $s->_setting_begin_rap_1;
        $fecha2 = $s->_setting_end_rap_1;
        $fecha3 = $s->_setting_begin_rap_2;
        $fecha4 = $s->_setting_end_rap_2;
        $fecha5 = $s->_setting_begin_rap_3;
        $fecha6 = $s->_setting_end_rap_3;
        $sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk, v.datum
					from students s inner join le_verzuim v
					where s.class = '$klas'  and s.schoolid = $schoolid and v.schooljaar = '$schooljaar' and v.studentid = $id and s.id = $id ORDER BY v.created;";
        $resultado = mysqli_query($mysqli, $sql_query_verzuim);
        while ($row1 = mysqli_fetch_assoc($resultado)) {
          $datum = $u->convertfrommysqldate_new($row1["datum"]);
          if ($datum >= $fecha1 && $datum <= $fecha2) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat++;
            }
          }
          if ($datum >= $fecha3 && $datum <= $fecha4) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat1++;
            }
          }
          if ($datum >= $fecha5 && $datum <= $fecha6) {
            if ($row1['huiswerk'] > 0) {
              $cont_laat2++;
            }
          }
        }
        $page_html .= "<td>" . $cont_laat . " </td>";
        $page_html .= "<td>" . $cont_laat1 . " </td>";
        $page_html .= "<td>" . $cont_laat2 . " </td>";
        $promedio = $cont_laat + $cont_laat1 + $cont_laat2;
        $page_html .= "<td>" . $promedio . " </td>";
      }

      $page_html .= "</tr>";
    }


    $page_html .= "</tbody>";

    $page_html .= "</table>";
  }
  $page_html .= "<div class='card'>";
  if ($_SESSION['SchoolID'] == 18) {
    $page_html .= "<div class='card-body' style='padding-bottom: 0px; padding-top: 0px;'>";
  } else {
    $page_html .= "<div class='card-body' style='padding-bottom: 0px; padding-top: 0.3rem;'>";
  }
  if ($_SESSION['SchoolType'] == 2) {
    $page_html .= "<h6 class='card-title'>Betekenis persoonlijke kwaliteiten:</h6>";
    $page_html .= "<div class='row'>";
    $page_html .= "<div class='row col-12' style='justify-content: center;'>";
    $page_html .= "<p style='margin-bottom: 0.5rem; font-size: 0.75rem; margin-right: 1rem;'>5 = Goed,</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.75rem; margin-right: 1rem;'> 4 = voldoende,</li>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.75rem; margin-right: 1rem;'> 3 = Matig,</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.75rem; margin-right: 1rem;'> 2 = Onvoldoende,</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.75rem;'> 1 = Slecht</p>";
    $page_html .= "</div>";
    $page_html .= "</div>";
  } else if ($_SESSION['SchoolID'] != 18) {
    $page_html .= "<h6>Betekenis cijfers/letters:</h6>";
    $page_html .= "<div class='row' style='height: 110px; justify-content: space-around;'>";
    $page_html .= "<div style='height: fit-content;'>";
    $page_html .= "<p  style='margin-bottom: 0.1rem; font-size: 0.7rem'>10 = Uitmuntend</p>";
    $page_html .= "<p  style='margin-bottom: 0.1rem; font-size: 0.7rem'>9 = Zeer goed</li>";
    $page_html .= "<p  style='margin-bottom: 0.1rem; font-size: 0.7rem'>8 = Goed</p>";
    $page_html .= "<p  style='margin-bottom: 0.1rem; font-size: 0.7rem'>7 = Ruim voldoende</p>";
    $page_html .= "<p  style='margin-bottom: 0.1rem; font-size: 0.7rem'>6 = Voldoende</p>";
    $page_html .= "</div>";
    $page_html .= "<div style='height: fit-content;'>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>5 = Bijna voldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>4 = Onvoldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>3 = Zeer onvoldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>2 = Slecht</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>1 = Zeer slecht</p>";
    $page_html .= "</div>";
    $page_html .= "<div style='height: fit-content;'>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>A = Zeer goed</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>B = Goed</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>C = Ruim voldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>D = Voldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>E = Onvoldoende</p>";
    $page_html .= "<p style='margin-bottom: 0.1rem; font-size: 0.7rem'>F = Slecht</p>";
    $page_html .= "</div>";
    $page_html .= "</div>";
  }/*  else {
    $page_html .= "<label style='margin-right: 20px;'>Comentario Rapport 1</label>";
    $page_html .= "<textarea style='resize: none;overflow: hidden;width: 250px;height: auto;'>" . $opmerking1 . "</textarea>";

    $page_html .= "<label style='margin-right: 20px;'>Comentario Rapport 2</label>";
    $page_html .= "<textarea style='resize: none;overflow: hidden;width: 250px;height: auto;'>" . $opmerking2 . "</textarea>";

    $page_html .= "<label style='margin-right: 20px;'>Comentario Rapport 3</label>";
    $page_html .= "<textarea style='resize: none;overflow: hidden;width: 250px;height: auto;'>" . $opmerking3 . "</textarea>";
  } */
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";


  $page_html .= "<div class='row'>";
  $page_html .= "<div class='card'>";
  $page_html .= "<div class='card-body' style='padding-bottom: 0px; padding-top: 0px;'>";


  if ($_SESSION['SchoolType'] == 2) {
    $page_html .= "<div class='row'>";
    $page_html .= "<div class='col-6' style=''>";
    $page_html .= "<h6 class='card-title'>Handtekening Mentor:</h6>";
    $page_html .= "<br>";
    $page_html .= "<br>";
    $page_html .= "<hr style='border-top: 2px solid rgba(0, 0, 0, 0.34); border-top-style: dotted;'>";
    $page_html .= "</div>";

    $page_html .= "<div class='col-6'style=''>";
    $page_html .= "<h6 class='card-title'>Handtekening Directeur:</h6>";
    $page_html .= "<br>";
    $page_html .= "<br>";
    $page_html .= "<hr style='border-top: 2px solid rgba(0, 0, 0, 0.34); border-top-style: dotted;'>";
    $page_html .= "</div>";
  } else if ($_SESSION['SchoolID'] != 18) {
    $page_html .= "<div class='row' style='margin: 0 !important;'>";
    $page_html .= "<div style='width: 100%;'>";
    $klas_next = substr($klas, 0, 1);
    $klas_next = (int)$klas_next + 1;
    $klas_next = $klas_next > 6 ? null : $klas_next;
    if ($advies == '0') {
      if ($klas_next != null) {
        $page_html .= "<b><p style='margin: .5rem !important; text-align: center; font-size: 14px;'>Bevorderd naar klas " . $klas_next . "</p></b>";
      } else {
        $page_html .= "<b><p style='margin: .5rem !important; text-align: center; font-size: 14px;'>Gaat naar het voortgezet onderwijs</p></b>";
      }
    } else if ($advies == '1') {
      $page_html .= "<b><p style='margin: .5rem !important; text-align: center; font-size: 14px;'>Over wegens leeftijd naar klas " . $klas_next . ".</p></b>";
    } else if ($advies == '2') {
      $page_html .= "<b><p style='margin: .5rem !important; text-align: center; font-size: 14px;'>Niet bevorderd.</p></b>";
    } else if ($advies == null || $advies == '') {
      $page_html .= "<p style='margin-bottom: .5rem !important;'>Ο Bevorderd naar klas: ..............................................................</p>";
      $page_html .= "<p style='margin-bottom: .5rem !important;'>Ο Over wegens leeftijd naar klas: ...............................................</p>";
      $page_html .= "<p style='margin-bottom: .5rem !important;'>Ο Niet bevorderd: ..............................................................</p>";
      $page_html .= "<p style='margin-bottom: .5rem !important;'>Ο Verwezen naar: ..............................................................</p>";
    } else {
      $page_html .= "<b><p style='margin: .5rem !important; text-align: center; font-size: 14px;'>Verwezen naar " . utf8_decode($advies) . "</p></b>";
    }

    $page_html .= "<div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%;'>";
    $page_html .= "<p style='margin-bottom: .5rem !important;display: inline; '>Naam leerkracht: " . $teacher . "</p>";
    $page_html .= "<p>.................................................................</p>";
    $page_html .= "</div>";
    $page_html .= "<div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%;'>";
    $page_html .= "<p style='margin-bottom: .5rem !important;display: inline;'>Naam Schoolhoofd: " . $cabesante . "</p>";
    $page_html .= "<p style='margin-bottom: ;'>.................................................................</p>";
    $page_html .= "</div>";

    $page_html .= "</div>";
  } else {
    $page_html .= "<div class='row' style='justify-content: space-evenly;'>";
    $page_html .= "<div>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>A = Excelente</p>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>B = Hopi bon</li>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>C = Bon</p>";
    $page_html .= "</div>";
    $page_html .= "<div>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>D = Suficiente</p>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>E = Insuficiente</p>";
    $page_html .= "<p style='margin-bottom: 0.2rem; font-size: 0.75rem'>F = Malo</p>";
    $page_html .= "</div>";
    $page_html .= "</div>";
  }
  $page_html .= "</div>";

  $page_html .= "</div>";
  $page_html .= "</div>";

  if ($_SESSION['SchoolID'] == 18) {
    $page_html .= "<br>";
    if ($advies == 0) {
      $page_html .= "<div style='display:flex; flex-direction: column;'>";
      $page_html .= "<div><input type='radio'><label style='margin-left: 10px;'>A pasa e aña</label></div>";
      $page_html .= "<div><input type='radio'><label style='margin-left: 10px;'>No a pasa e aña</label></div>";
      $page_html .= "</div>";
    } else if ($advies == 1) {
      $page_html .= "<div style='display:flex; flex-direction: column;'>";
      $page_html .= "<div><input type='radio'><label style='margin-left: 10px;'>A pasa e aña</label></div>";
      $page_html .= "<div><input type='radio'><label style='margin-left: 10px;'>No a pasa e aña</label></div>";
      $page_html .= "</div>";
    }
    $page_html .= "<br>";
    $page_html .= "<div class='row'>";
    $page_html .= "<div class='card'>";
    $page_html .= "<div class='card-body' style='padding-bottom: 1%; padding-top: 1%;display:flex;justify-content:center; '>";

    $page_html .= "<table style='width:90%;' border='1'>";
    $page_html .= "<tr>";
    $page_html .= "<td colspan='3' style='text-align:center;'><b>Firma</b></td>";
    $page_html .= "</tr>";
    $page_html .= "<tr>";
    $page_html .= "<td>Cabesante</td>";
    $page_html .= "<td>Maestro</td>";
    $page_html .= "<td>Mayor</td>";
    $page_html .= "</tr>";
    $page_html .= "<tr>";
    $page_html .= "<td style='height: 2rem;'></td>";
    $page_html .= "<td></td>";
    $page_html .= "<td></td>";
    $page_html .= "</tr>";
    $page_html .= "</table>";

    $page_html .= "</div>";
    $page_html .= "</div>";
    $page_html .= "</div>";
  }

  $page_html .= "</div>";
  $page_html .= "</div>";





  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "</div>";
  $page_html .= "<div class='page-break'></div>";
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
      .page-break {
        display: none;
      }
    }

    @media print {
      .page-break {
        display: block;
        page-break-before: always;
      }
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
    .table td,
    .table th {
      padding: 0.1rem;
    }

    body {
      font-size: 0.75rem;
    }

    .h6,
    h6 {
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

  <div>
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
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
  </script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
  </script>
  <script type="text/javascript">
    $(document).ready(function() {

      var SchoolID = '<?php echo $_SESSION["SchoolID"]; ?>';
      var Klass = '<?php echo $_GET["klas"]; ?>';
      var pattern = /3/;
      var pattern2 = /2/;
      var contain = pattern.test(Klass);
      var contain2 = pattern2.test(Klass);
      var text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar Ciclo Basico 2";

      if (SchoolID == 12) {
        if (contain) {
          text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar leerjaar 4"
        }

        if (contain2) {
          text = "O&nbsp&nbsp&nbsp&nbsp&nbsp&nbspOver naar leerjaar 3"
        }

        var lerjar4 = document.getElementsByClassName("classovernar");
        for (var i = 0; i < lerjar4.length; i++) {
          lerjar4[i].innerHTML = text;
        }
      }

      setTimeout(function() {
        window.print();
      }, 1000);
    });
  </script>
</body>

</html>