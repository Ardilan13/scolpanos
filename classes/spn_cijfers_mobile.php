<?php
require_once("spn_audit.php");
require_once("spn_setting_mobile.php");
class spn_cijfers_mobile
{
  public $tablename_cijfers = "le_cijfers";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = true;
  public $sp_update_le_cijfersextra = "sp_update_le_cijfersextra";
  public $sp_read_le_cijfersextra = "sp_read_le_cijfersextra";
  public $sp_read_le_cijferswaarde = "sp_read_le_cijferswaarde";
  public $sp_get_cijfers_by_student = "sp_get_cijfers_by_student";
  public $sp_get_cijfers_by_student_parent = "sp_get_cijfers_by_student_parent";
  public $sp_read_le_cijfer_graph = "sp_read_le_cijfer_graph";
  public $sp_read_le_cijfer_graph_by_student = "sp_read_le_cijfer_graph_by_student";
  public $sp_read_le_cijfer_graph_by_class = "sp_read_le_cijfer_graph_by_class";

  function list_Last_cijfers_by_student($schooljaar, $studentid, $schoolID, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $data_cijfer_extra = "";
    $result = 0;
    $i = 0;

    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

        $query = "SELECT 	
        l.rapnummer,
        c.vak,
        (select v.volledigenaamvak from le_vakken v where v.ID = c.vak) as volledigenaamvak,
        c.oc1,
        c.oc2,
        c.oc3,
        c.oc4,
        c.oc5,
        c.oc6,
        c.oc7,
        c.oc8,
        c.oc9,
        c.oc10,
        c.oc11,
        c.oc12,
        c.oc13,
        c.oc14,
        c.oc15,
        c.oc16,
        c.oc17,
        c.oc18,
        c.oc19,
        c.oc20,
        c.dc1,
        c.dc2,
        c.dc3,
        c.dc4,
        c.dc5,
        c.dc6,
        c.dc7,
        c.dc8,
        c.dc9,
        c.dc10,
        c.dc11,
        c.dc12,
        c.dc13,
        c.dc14,
        c.dc15,
        c.dc16,
        c.dc17,
        c.dc18,
        c.dc19,
        c.dc20,
        l.c1,
        l.c2,
        l.c3,
        l.c4,
        l.c5,
        l.c6,
        l.c7,
        l.c8,
        l.c9,
        l.c10,
        l.c11,
        l.c12,
        l.c13,
        l.c14,
        l.c15,
        l.c16,
        l.c17,
        l.c18,
        l.c19,
        l.c20
    FROM le_cijfersextra c join le_cijfers l on c.vak = l.vak
    and  c.rapnummer = l.rapnummer
    and (l.c1 >=0 or l.c2 >=0 or l.c3 >=0 or l.c4 >= 0 or l.c5 >= 0 or l.c6 >= 0 or l.c7 >= 0 or l.c8 >= 0 or l.c9 >= 0 or l.c10 >= 0 or l.c11 >= 0 or l.c12 >= 0 or l.c13 >= 0 or l.c14 >= 0 or l.c15 >= 0 or l.c16 >= 0 or l.c17 >= 0 or l.c18 >= 0 or l.c19 >= 0 or l.c20 >= 0)
    and c.klas = l.klas
    and c.schooljaar = l.schooljaar
    and c.schooljaar = '$schooljaar' 
    where l.studentid = (select id from students where uuid = '$studentid') and c.schoolid = $schoolID
    order by c.dc20 DESC,c.dc19 DESC,c.dc18 DESC,c.dc17 DESC, c.dc16 DESC, c.dc15 DESC, c.dc14 DESC, c.dc13 DESC, c.dc12 DESC, c.dc11 DESC, c.dc10 DESC, c.dc9 DESC, c.dc8 DESC, c.dc7 DESC, c.dc6 DESC, c.dc5 DESC, c.dc4 DESC, c.dc3 DESC, c.dc2 DESC, c.dc1 DESC";
        $resultado = mysqli_query($mysqli, $query);
        $i = 0;
        $list = [];
        if ($resultado->num_rows > 0) {
          $htmlcontrol .= "<div class=\"col-xs-12 table-responsive no-border\">";
          $htmlcontrol .= "<table id=\"c\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
          $htmlcontrol .= "<thead><tr><th>Datum&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th><th>Vak</th><th>Rapport#</th><th>Cijfer</th><th>Toets opmerking</th></tr></thead>";
          $htmlcontrol .= "<tbody>";
          while ($row = mysqli_fetch_assoc($resultado)) {
            for ($j = 20; $j >= 1; $j = $j - 1) {
              if ($row["c" . $j] >= 0 && $row["c" . $j] != null) {
                $lista[$i] = [
                  "dc" => $row["dc" . $j],
                  "oc" => $row["oc" . $j],
                  "vo" => $row["volledigenaamvak"],
                  "ra" => $row["rapnummer"],
                  "c" => $row["c" . $j]
                ];
                $i++;
              }
            }
          }
          usort($lista, function ($a, $b) {
            return strcmp($a["dc"], $b["dc"]);
          });
          $lista = array_reverse($lista);
          $lista = array_slice($lista, 0, 10);
          $l = count($lista);
          for ($x = 0; $x < $l; $x++) {
            $htmlcontrol .= $this->fill_html_control($lista[$x]['dc'], $lista[$x]['oc'], $lista[$x]['vo'], $lista[$x]['ra'], $lista[$x]['c']);
          }
          $htmlcontrol .= "</tbody>";
          $htmlcontrol .= "</table>";
          $htmlcontrol .= "<button class='boton_alternado' id='mostrar'>Show more</button>";
          $htmlcontrol .= "<button class='boton_alternado ocultar' id='ocultar'>Show less</button>";
          $htmlcontrol .= "</div>";
        } else {
          $htmlcontrol .= "No results to show.";
        }
        /* if ($stmt = $mysqli->prepare("CALL sp_read_le_cijfer_extra_parent_by_student (?,?,?)")) {
          if ($stmt->bind_param("ssi", $schooljaar, $studentid, $schoolID)) {
            if ($stmt->execute()) {

              
              $result = 1;
              $stmt->bind_result(
                $rap_cijfer_extra,
                $vak_cijfer_extra,
                $volledigenaamvak,
                $Oc1,
                $Oc2,
                $Oc3,
                $Oc4,
                $Oc5,
                $Oc6,
                $Oc7,
                $Oc8,
                $Oc9,
                $Oc10,
                $Oc11,
                $Oc12,
                $Oc13,
                $Oc14,
                $Oc15,
                $Oc16,
                $Oc17,
                $Oc18,
                $Oc19,
                $Oc20,
                $Dc1,
                $Dc2,
                $Dc3,
                $Dc4,
                $Dc5,
                $Dc6,
                $Dc7,
                $Dc8,
                $Dc9,
                $Dc10,
                $Dc11,
                $Dc12,
                $Dc13,
                $Dc14,
                $Dc15,
                $Dc16,
                $Dc17,
                $Dc18,
                $Dc19,
                $Dc20,
                $c1,
                $c2,
                $c3,
                $c4,
                $c5,
                $c6,
                $c7,
                $c8,
                $c9,
                $c10,
                $c11,
                $c12,
                $c13,
                $c14,
                $c15,
                $c16,
                $c17,
                $c18,
                $c19,
                $c20
              );
              $stmt->store_result();
              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<div class=\"col-xs-12 table-responsive\">";
                $htmlcontrol .= "<table id=\"c\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                $htmlcontrol .= "<thead><tr><th>Datum&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th><th>Vak</th><th>Rapport#</th><th>Cijfer</th><th>Toets opmerking</th><th>Chat</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {

                  //$htmlcontrol .= "<tr><td>" . $studentid . "</td><td>" . $_SESSION["Class"] . "</td><td>". $_SESSION["SchoolJaar"] ."</td><td></td><td></td></tr>";
                  if ($i <= 10) {
                    $data_cijfer = "1";
                    // *******************************************

                    for ($j = 1; $j <= 20; $j++) {
                      $cxx = "C" . $j;
                      //$htmlcontrol .= "<tr><td>ok " . $Oc3 . "</td><td>" . $Dc3 . "</td><td>". $_SESSION["SchoolJaar"] ."</td><td></td><td></td></tr>";
                      //$data_cijfer = $this->get_le_cijfer_extra_by_vak_and_klas($cxx, $studentid, $vak_cijfer_extra, $rap_cijfer_extra);
                      if (!empty($data_cijfer) && $data_cijfer != '0.0') {

                        if ($cxx == "C20" && !empty($Oc20)) {
                          $htmlcontrol .= $this->fill_html_control($Dc20, $Oc20, $volledigenaamvak, $rap_cijfer_extra, $c20);
                        }
                        if ($cxx == "C19" && !empty($Oc19)) {
                          $htmlcontrol .= $this->fill_html_control($Dc19, $Oc19, $volledigenaamvak, $rap_cijfer_extra, $c19);
                        }
                        if ($cxx == "C18" && !empty($Oc18)) {
                          $htmlcontrol .= $this->fill_html_control($Dc18, $Oc18, $volledigenaamvak, $rap_cijfer_extra, $c18);
                        }
                        if ($cxx == "C17" && !empty($Oc17)) {
                          $htmlcontrol .= $this->fill_html_control($Dc17, $Oc20, $volledigenaamvak, $rap_cijfer_extra, $c17);
                        }
                        if ($cxx == "C16" && !empty($Oc16)) {
                          $htmlcontrol .= $this->fill_html_control($Dc16, $Oc16, $volledigenaamvak, $rap_cijfer_extra, $c16);
                        }
                        if ($cxx == "C15" && !empty($Oc15)) {
                          $htmlcontrol .= $this->fill_html_control($Dc15, $Oc15, $volledigenaamvak, $rap_cijfer_extra, $c15);
                        }
                        if ($cxx == "C14" && !empty($Oc14)) {
                          $htmlcontrol .= $this->fill_html_control($Dc14, $Oc14, $volledigenaamvak, $rap_cijfer_extra, $c14);
                        }
                        if ($cxx == "C13" && !empty($Oc13)) {
                          $htmlcontrol .= $this->fill_html_control($Dc13, $Oc13, $volledigenaamvak, $rap_cijfer_extra, $c13);
                        }
                        if ($cxx == "C12" && !empty($Oc12)) {
                          $htmlcontrol .= $this->fill_html_control($Dc12, $Oc12, $volledigenaamvak, $rap_cijfer_extra, $c12);
                        }
                        if ($cxx == "C11" && !empty($Oc11)) {
                          $htmlcontrol .= $this->fill_html_control($Dc11, $Oc11, $volledigenaamvak, $rap_cijfer_extra, $c11);
                        }
                        if ($cxx == "C10" && !empty($Oc10)) {
                          $htmlcontrol .= $this->fill_html_control($Dc10, $Oc10, $volledigenaamvak, $rap_cijfer_extra, $c10);
                        }
                        if ($cxx == "C9" && !empty($Oc9)) {
                          $htmlcontrol .= $this->fill_html_control($Dc9, $Oc9, $volledigenaamvak, $rap_cijfer_extra, $c9);
                        }
                        if ($cxx == "C8" && !empty($Oc8)) {
                          $htmlcontrol .= $this->fill_html_control($Dc8, $Oc8, $volledigenaamvak, $rap_cijfer_extra, $c8);
                        }
                        if ($cxx == "C7" && !empty($Oc7)) {
                          $htmlcontrol .= $this->fill_html_control($Dc7, $Oc7, $volledigenaamvak, $rap_cijfer_extra, $c7);
                        }
                        if ($cxx == "C6" && !empty($Oc6)) {
                          $htmlcontrol .= $this->fill_html_control($Dc6, $Oc6, $volledigenaamvak, $rap_cijfer_extra, $c6);
                        }
                        if ($cxx == "C5" && !empty($Oc5)) {
                          $htmlcontrol .= $this->fill_html_control($Dc5, $Oc5, $volledigenaamvak, $rap_cijfer_extra, $c5);
                        }
                        if ($cxx == "C4" && !empty($Oc4)) {
                          $htmlcontrol .= $this->fill_html_control($Dc4, $Oc4, $volledigenaamvak, $rap_cijfer_extra, $c4);
                        }
                        if ($cxx == "C3" && !empty($Oc3)) {
                          $htmlcontrol .= $this->fill_html_control($Dc3, $Oc3, $volledigenaamvak, $rap_cijfer_extra, $c3);
                        }
                        if ($cxx == "C2" && !empty($Oc2)) {
                          $htmlcontrol .= $this->fill_html_control($Dc2, $Oc2, $volledigenaamvak, $rap_cijfer_extra, $c2);
                        }
                        if ($cxx == "C1" && !empty($Oc1)) {
                          $htmlcontrol .= $this->fill_html_control($Dc1, $Oc1, $volledigenaamvak, $rap_cijfer_extra, $c1);
                        }

                        $i++;
                      }

                      $data_cijfer = "0.0";
                    }
                  } else {
                    break;
                  }
                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        } */
      } catch (Exception $e) {
        $result = -2;
      }
      return $htmlcontrol;
    }
  }

  function list_Last_cijfers_by_student_ps($schooljaar, $studentid, $schoolID, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $data_cijfer_extra = "";
    $result = 0;
    $i = 0;

    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

        $query = "SELECT 	
        l.rapnummer,
        c.vak,
        (select v.vak_naam from le_vakken_ps v where v.id = c.vak) as volledigenaamvak,
        c.oc1,
        c.oc2,
        c.oc3,
        c.oc4,
        c.oc5,
        c.oc6,
        c.oc7,
        c.oc8,
        c.oc9,
        c.oc10,
        c.oc11,
        c.oc12,
        c.oc13,
        c.oc14,
        c.oc15,
        c.oc16,
        c.oc17,
        c.oc18,
        c.oc19,
        c.oc20,
        c.dc1,
        c.dc2,
        c.dc3,
        c.dc4,
        c.dc5,
        c.dc6,
        c.dc7,
        c.dc8,
        c.dc9,
        c.dc10,
        c.dc11,
        c.dc12,
        c.dc13,
        c.dc14,
        c.dc15,
        c.dc16,
        c.dc17,
        c.dc18,
        c.dc19,
        c.dc20,
        l.c1,
        l.c2,
        l.c3,
        l.c4,
        l.c5,
        l.c6,
        l.c7,
        l.c8,
        l.c9,
        l.c10,
        l.c11,
        l.c12,
        l.c13,
        l.c14,
        l.c15,
        l.c16,
        l.c17,
        l.c18,
        l.c19,
        l.c20
    FROM le_cijfersextra c join le_cijfers_ps l on c.vak = l.vak
    and  c.rapnummer = l.rapnummer
    and (l.c1 >=0 or l.c2 >=0 or l.c3 >=0 or l.c4 >= 0 or l.c5 >= 0 or l.c6 >= 0 or l.c7 >= 0 or l.c8 >= 0 or l.c9 >= 0 or l.c10 >= 0 or l.c11 >= 0 or l.c12 >= 0 or l.c13 >= 0 or l.c14 >= 0 or l.c15 >= 0 or l.c16 >= 0 or l.c17 >= 0 or l.c18 >= 0 or l.c19 >= 0 or l.c20 >= 0)
    and c.klas = l.klas
    and c.schooljaar = l.schooljaar
    and c.schooljaar = '$schooljaar' 
    where l.studentid = (select id from students where uuid = '$studentid') and c.schoolid = $schoolID
    order by c.dc20 DESC,c.dc19 DESC,c.dc18 DESC,c.dc17 DESC, c.dc16 DESC, c.dc15 DESC, c.dc14 DESC, c.dc13 DESC, c.dc12 DESC, c.dc11 DESC, c.dc10 DESC, c.dc9 DESC, c.dc8 DESC, c.dc7 DESC, c.dc6 DESC, c.dc5 DESC, c.dc4 DESC, c.dc3 DESC, c.dc2 DESC, c.dc1 DESC";
        $resultado = mysqli_query($mysqli, $query);
        $i = 0;
        $list = [];
        if ($resultado->num_rows > 0) {
          $htmlcontrol .= "<div class=\"col-xs-12 table-responsive no-border\">";
          $htmlcontrol .= "<table id=\"c\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
          $htmlcontrol .= "<thead><tr><th>Datum&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th><th>Vak</th><th>Rapport#</th><th>Cijfer</th><th>Toets opmerking</th></tr></thead>";
          $htmlcontrol .= "<tbody>";
          while ($row = mysqli_fetch_assoc($resultado)) {
            for ($j = 20; $j >= 1; $j = $j - 1) {
              if ($row["c" . $j] >= 0 && $row["c" . $j] != null) {
                $lista[$i] = [
                  "dc" => $row["dc" . $j],
                  "oc" => $row["oc" . $j],
                  "vo" => $row["volledigenaamvak"],
                  "ra" => $row["rapnummer"],
                  "c" => $row["c" . $j]
                ];
                $i++;
              }
            }
          }
          usort($lista, function ($a, $b) {
            return strcmp($a["dc"], $b["dc"]);
          });
          $lista = array_reverse($lista);
          $lista = array_slice($lista, 0, 10);
          $l = count($lista);
          for ($x = 0; $x < $l; $x++) {
            $htmlcontrol .= $this->fill_html_control($lista[$x]['dc'], $lista[$x]['oc'], $lista[$x]['vo'], $lista[$x]['ra'], $lista[$x]['c']);
          }
          $htmlcontrol .= "</tbody>";
          $htmlcontrol .= "</table>";
          $htmlcontrol .= "<button class='boton_alternado' id='mostrar'>Show more</button>";
          $htmlcontrol .= "<button class='boton_alternado ocultar' id='ocultar'>Show less</button>";
          $htmlcontrol .= "</div>";
        } else {
          $htmlcontrol .= "No results to show.";
        }
        /* if ($stmt = $mysqli->prepare($query)) {
          if ($stmt->bind_param("ssi", $schooljaar, $studentid, $schoolID)) {
            if ($stmt->execute()) {

              
              $result = 1;
              $stmt->bind_result(
                $rap_cijfer_extra,
                $vak_cijfer_extra,
                $volledigenaamvak,
                $Oc1,
                $Oc2,
                $Oc3,
                $Oc4,
                $Oc5,
                $Oc6,
                $Oc7,
                $Oc8,
                $Oc9,
                $Oc10,
                $Oc11,
                $Oc12,
                $Oc13,
                $Oc14,
                $Oc15,
                $Oc16,
                $Oc17,
                $Oc18,
                $Oc19,
                $Oc20,
                $Dc1,
                $Dc2,
                $Dc3,
                $Dc4,
                $Dc5,
                $Dc6,
                $Dc7,
                $Dc8,
                $Dc9,
                $Dc10,
                $Dc11,
                $Dc12,
                $Dc13,
                $Dc14,
                $Dc15,
                $Dc16,
                $Dc17,
                $Dc18,
                $Dc19,
                $Dc20,
                $c1,
                $c2,
                $c3,
                $c4,
                $c5,
                $c6,
                $c7,
                $c8,
                $c9,
                $c10,
                $c11,
                $c12,
                $c13,
                $c14,
                $c15,
                $c16,
                $c17,
                $c18,
                $c19,
                $c20
              );
              $stmt->store_result();
              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<div class=\"col-xs-12 table-responsive\">";
                $htmlcontrol .= "<table id=\"c\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                $htmlcontrol .= "<thead><tr><th>Datum&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th><th>Vak</th><th>Rapport#</th><th>Cijfer</th><th>Toets opmerking</th><th>Chat</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {
                  $i = 0;
                  $list = [];
                  for ($j = 20; $j >= 1; $j = $j - 1) {
                    if ($c . $j >= 0 && $c . $j != null) {
                      $lista[$i] = [
                        "dc" => $Dc . $j,
                        "oc" => $Oc . $j,
                        "vo" => $volledigenaamvak,
                        "ra" => $rap_cijfer_extra,
                        "c" => $c . $j
                      ];
                      $i++;
                    }
                  }


                  /*  //$htmlcontrol .= "<tr><td>" . $studentid . "</td><td>" . $_SESSION["Class"] . "</td><td>". $_SESSION["SchoolJaar"] ."</td><td></td><td></td></tr>";
                  if ($i <= 10) {
                    $data_cijfer = "1";
                    // *******************************************

                    for ($j = 20; $j >= 1; $j = $j - 1) {
                      $cxx = "C" . $j;
                      //$htmlcontrol .= "<tr><td>ok " . $Oc3 . "</td><td>" . $Dc3 . "</td><td>". $_SESSION["SchoolJaar"] ."</td><td></td><td></td></tr>";
                      //$data_cijfer = $this->get_le_cijfer_extra_by_vak_and_klas($cxx, $studentid, $vak_cijfer_extra, $rap_cijfer_extra);
                      if (!empty($data_cijfer) && $data_cijfer != '0.0') {
                        if ($cxx == "C20" && !empty($Oc20) && !empty($c20)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc20, $Oc20, $volledigenaamvak, $rap_cijfer_extra, $c20);
                        }
                        if ($cxx == "C19" && !empty($Oc19) && !empty($c19)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc19, $Oc19, $volledigenaamvak, $rap_cijfer_extra, $c19);
                        }
                        if ($cxx == "C18" && !empty($Oc18) && !empty($c18)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc18, $Oc18, $volledigenaamvak, $rap_cijfer_extra, $c18);
                        }
                        if ($cxx == "C17" && !empty($Oc17) && !empty($c17)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc17, $Oc20, $volledigenaamvak, $rap_cijfer_extra, $c17);
                        }
                        if ($cxx == "C16" && !empty($Oc16) && !empty($c16)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc16, $Oc16, $volledigenaamvak, $rap_cijfer_extra, $c16);
                        }
                        if ($cxx == "C15" && !empty($Oc15) && !empty($c15)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc15, $Oc15, $volledigenaamvak, $rap_cijfer_extra, $c15);
                        }
                        if ($cxx == "C14" && !empty($Oc14) && !empty($c14)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc14, $Oc14, $volledigenaamvak, $rap_cijfer_extra, $c14);
                        }
                        if ($cxx == "C13" && !empty($Oc13) && !empty($c13)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc13, $Oc13, $volledigenaamvak, $rap_cijfer_extra, $c13);
                        }
                        if ($cxx == "C12" && !empty($Oc12) && !empty($c12)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc12, $Oc12, $volledigenaamvak, $rap_cijfer_extra, $c12);
                        }
                        if ($cxx == "C11" && !empty($Oc11) && !empty($c11)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc11, $Oc11, $volledigenaamvak, $rap_cijfer_extra, $c11);
                        }
                        if ($cxx == "C10" && !empty($Oc10) && !empty($c10)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc10, $Oc10, $volledigenaamvak, $rap_cijfer_extra, $c10);
                        }
                        if ($cxx == "C9" && !empty($Oc9) && !empty($c9)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc9, $Oc9, $volledigenaamvak, $rap_cijfer_extra, $c9);
                        }
                        if ($cxx == "C8" && !empty($Oc8) && !empty($c8)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc8, $Oc8, $volledigenaamvak, $rap_cijfer_extra, $c8);
                        }
                        if ($cxx == "C7" && !empty($Oc7) && !empty($c7)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc7, $Oc7, $volledigenaamvak, $rap_cijfer_extra, $c7);
                        }
                        if ($cxx == "C6" && !empty($Oc6) && !empty($c6)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc6, $Oc6, $volledigenaamvak, $rap_cijfer_extra, $c6);
                        }
                        if ($cxx == "C5" && !empty($Oc5) && !empty($c5)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc5, $Oc5, $volledigenaamvak, $rap_cijfer_extra, $c5);
                        }
                        if ($cxx == "C4" && !empty($Oc4) && !empty($c4)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc4, $Oc4, $volledigenaamvak, $rap_cijfer_extra, $c4);
                        }
                        if ($cxx == "C3" && !empty($Oc3) && !empty($c3)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc3, $Oc3, $volledigenaamvak, $rap_cijfer_extra, $c3);
                        }
                        if ($cxx == "C2" && !empty($Oc2) && !empty($c2)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc2, $Oc2, $volledigenaamvak, $rap_cijfer_extra, $c2);
                        }
                        if ($cxx == "C1" && !empty($Oc1) && !empty($c1)) {
                          $data_cijfer = "0.0";
                          $i++;
                          $htmlcontrol .= $this->fill_html_control($Dc1, $Oc1, $volledigenaamvak, $rap_cijfer_extra, $c1);
                        }
                      }
                    }
                  } else {
                    break;
                  }
                 
                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        } */
      } catch (Exception $e) {
        $result = -2;
      }
      return $htmlcontrol;
    }
  }

  function fill_html_control($date_extra, $observation_extra, $volledigenaamvak, $rap_cijfer_extra, $data_cijfer_extra)
  {
    $htlm_helper = "";
    $dtime = new DateTime($date_extra);
    $htlm_helper .= "<tr>";
    $htlm_helper .= "<td data-titulo='Datum:'>" . htmlentities($dtime->format("d-m-Y")) . "</td>";
    $htlm_helper .= "<td data-titulo='Vak:'>" . htmlentities($volledigenaamvak) . "</td>";
    $htlm_helper .= "<td data-titulo='Rapport:'>" . htmlentities($rap_cijfer_extra) . "</td>";
    if ($data_cijfer_extra < 5.5) {
      $htlm_helper .= "<td data-titulo='Cijfer:' style='color: red;'>" . htmlentities($data_cijfer_extra) . "</td>";
    } else {
      $htlm_helper .= "<td data-titulo='Cijfer:'>" . htmlentities($data_cijfer_extra) . "</td>";
    }
    $htlm_helper .= "<td data-titulo='Opmerking:'>" . htmlentities($observation_extra) . "</td>";
    /*     $htlm_helper .= "<td> <button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($volledigenaamvak) . "\",\"" . htmlentities($rap_cijfer_extra) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Virtuele klas</button> </td>";
 */
    $htlm_helper .= "</tr>";
    return $htlm_helper;
  }

  function get_oc_commnets($vak, $rapp, $klas)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    $jsons = "";
    $schooljaar = $_SESSION["SchoolJaar"];
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "SELECT * FROM le_cijfersextra WHERE vak = $vak AND klas = '$klas' AND rapnummer = $rapp AND schooljaar = '$schooljaar'";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->execute()) {
          /*  */
          $result = 1;
          $select->bind_result($Id, $klas, $schooljaar, $schoolid, $rapnummer, $vak, $oc1, $oc2, $oc3, $oc4, $oc5, $oc6, $oc7, $oc8, $oc9, $oc10, $oc11, $oc12, $oc13, $oc14, $oc15, $oc16, $oc17, $oc18, $oc19, $oc20, $dc1, $dc2, $dc3, $dc4, $dc5, $dc6, $dc7, $dc8, $dc9, $dc10, $dc11, $dc12, $dc13, $dc14, $dc15, $dc16, $dc17, $dc18, $dc19, $dc20);
          $select->store_result();

          if ($select->num_rows > 0) {
            while ($select->fetch()) {
              $jsons = array($Id, $klas, $schooljaar, $rapnummer, $vak, $oc1, $oc2, $oc3, $oc4, $oc5, $oc6, $oc7, $oc8, $oc9, $oc10, $oc11, $oc12, $oc13, $oc14, $oc15, $oc16, $oc17, $oc18, $oc19, $oc20, $dc1, $dc2, $dc3, $dc4, $dc5, $dc6, $dc7, $dc8, $dc9, $dc10, $dc11, $dc12, $dc13, $dc14, $dc15, $dc16, $dc17, $dc18, $dc19, $dc20);
            }
          }
        } else {
          /* error executing query */
          /* 
           */
          $result = 0;
          if ($this->debug) {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }
      } else {
        /* error preparing query */
        /* 
         */
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $result = json_encode($jsons);
      return $result;
    } catch (Exception $e) {
      /* 
       */
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }

  function get_le_cijfer_extra_by_vak_and_klas($field_cijfer, $studentid, $vak, $rap)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    $cijferExtra =  "";
    $schooljaar = $_SESSION["SchoolJaar"];
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "SELECT c.$field_cijfer FROM le_cijfers c join le_vakken v on c.vak = v.id join students s on s.id = c.studentid
    and s.uuid = '$studentid' and c.schooljaar = '$schooljaar' and c.vak= $vak and c.rapnummer = $rap";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->execute()) {

          $result = 1;
          $select->bind_result($field_cijfer_extra);
          $select->store_result();

          if ($select->num_rows > 0) {
            while ($select->fetch()) {
              $cijferExtra = $field_cijfer_extra;
            }
          } else {
            $cijferExtra = "";
          }
        } else {
          /* error executing query */


          $result = 0;
          if ($this->debug) {
            print "error executing query" . "<br />";
            print "error" . $mysqli->error;
          }
        }
      } else {
        /* error preparing query */


        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $cijferExtra;
      return $returnvalue;
    } catch (Exception $e) {


      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }

  function createcijfers($studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_cijfers . "(created,studentid,schooljaar,rapnummer,vak,klas,user,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,c15,c16,c17,c18,c19,c20,gemiddelde) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiii", $_DateTime, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

            $result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function editcijfers($cijfersid, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("update " . $this->tablename_cijfers . " set lastchanged = ?, studentid = ?, schooljaar = ?, rapnummer = ?, vak = ?, klas = ?, user =?, c1 = ?, c2 = ?, c3 = ?, c4 = ?, c5 = ?, c6 = ?, c7 = ?, c8 = ?, c9 = ?, c10 = ?, c11 = ?, c12 = ?, c13 = ?, c14 = ?, c15 = ?, c16 = ?, c17 = ?, c18 = ?, c19 = ?, c20 = ?, gemiddelde = ? where id = ?")) {
        if ($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiiii", $_DateTime, $studentid, $schooljaar, $rapnummer, $vak, $klas, $user, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $cijfersid)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            if ($mysqli->affected_rows >= 1) {
              $result = 1;
            }
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
      }
    } catch (Exception $e) {
      $result = -2;
      //    $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function listcijfers($schoolid, $klas_in, $vak_in, $rap_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcijferswaarde = "";

    // if($this->debug)
    // {
    //   print "schoolid: " . $schoolid . "<br />";
    //   print "klas_in: " . $klas_in . "<br />";
    //   print "vak_in: " . $vak_in . "<br />";
    //   print "rap_in: " . $rap_in . "<br />";
    //   print "sort_order: " . $sort_order . "<br />";
    // }

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting_mobile();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers c ON s.id = c.studentid LEFT JOIN le_vakken v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? ORDER BY ";

    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)


    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      require_once("spn_utils.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sis", $klas_in, $vak_in, $rap_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $firstname, $lastname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();

            if ($select->num_rows > 0) {

              //BEGIN le_cijferswaarde (ejaspe@caribedev)
              $u = new spn_utils();
              if ($stmtcijferswaarde = $mysqli->prepare("CALL " . $this->sp_read_le_cijferswaarde . " (?,?,?)")) {
                if ($stmtcijferswaarde->bind_param("ssi", $klas_in, $vak_in, $rap_in)) {
                  if ($stmtcijferswaarde->execute()) {
                    $stmtcijferswaarde->bind_result($cijferswaardeid, $klas, $schooljaar, $rapnummer, $vak, $cijferswaarde1, $cijferswaarde2, $cijferswaarde3, $cijferswaarde4, $cijferswaarde5, $cijferswaarde6, $cijferswaarde7, $cijferswaarde8, $cijferswaarde9, $cijferswaarde10, $cijferswaarde11, $cijferswaarde12, $cijferswaarde13, $cijferswaarde14, $cijferswaarde15, $cijferswaarde16, $cijferswaarde17, $cijferswaarde18, $cijferswaarde19, $cijferswaarde20);
                    $stmtcijferswaarde->store_result();

                    if ($stmtcijferswaarde->num_rows > 0) {
                      if ($rap_in == 1) {
                        if ($s->_setting_rapnumber_1) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 2) {
                        if ($s->_setting_rapnumber_2) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 3) {
                        if ($s->_setting_rapnumber_3) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      }
                    } else {
                      // Changes settings (ladalan@caribedev)

                      if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                      } else {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                      }
                    }

                    // End changes settings (laldana@caribedev)
                  } else {
                    $result = 0;
                    $this->mysqlierror = $mysqli->error;
                    $this->mysqlierrornumber = $mysqli->errno;
                    $result = $mysqli->error;
                  }
                } else {
                  $result = 0;
                  $this->mysqlierror = $mysqli->error;
                  $this->mysqlierrornumber = $mysqli->errno;

                  $result = $mysqli->error;
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END le_cijferswaarde


              /* begin drawing table */
              $htmlcontrol .= "<div class=\"table-responsive\">
              <table id=\"vak\" class=\"table table-striped table-vak\">
              <thead>
              <tr>
              <th></th>
              <th class=\"btn-m-w\"></th>";


              $htmlcontrol .= $htmlcijferswaarde;

              // Changes settings (ladalan@caribedev)

              if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {

                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"1\">1 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"2\">2 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"3\">3 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"4\">4 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"5\">5 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"6\">6 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"7\">7 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"8\">8 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"9\">9 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"10\">10 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"11\">11 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"12\">12 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"13\">13 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"14\">14 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"15\">15 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"16\">16 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"17\">17 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"18\">18 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"19\">19 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"20\">20 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              } else {
                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                1
                </th>
                <th>
                2
                </th>
                <th>
                3
                </th>
                <th>
                4
                </th>
                <th>
                5
                </th>
                <th>
                6
                </th>
                <th>
                7
                </th>
                <th>
                8
                </th>
                <th>
                9
                </th>
                <th>
                10
                </th>
                <th>
                11
                </th>
                <th>
                12
                </th>
                <th>
                13
                </th>
                <th>
                14
                </th>
                <th>
                15
                </th>
                <th>
                16
                </th>
                <th>
                17
                </th>
                <th>
                18
                </th>
                <th>
                19
                </th>
                <th>
                20
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              }

              // End changes settings (ladalan@caribedev)

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-cijfer attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                $htmlcontrol .= "<tr><td>$x</td><td>" . utf8_encode($firstname) . chr(32) . utf8_encode($lastname) . "</td>";

                for ($y = 1; $y <= 20; $y++) {
                  $htmlcontrol .= "";

                  $_cijfer_number = 0;
                  /* check dimension of the cijfers */
                  switch ($y) {
                    case 1:
                      $_cijfer_number = $c1;
                      break;

                    case 2:
                      $_cijfer_number = $c2;
                      break;

                    case 3:
                      $_cijfer_number = $c3;
                      break;

                    case 4:
                      $_cijfer_number = $c4;
                      break;

                    case 5:
                      $_cijfer_number = $c5;
                      break;

                    case 6:
                      $_cijfer_number = $c6;
                      break;

                    case 7:
                      $_cijfer_number = $c7;
                      break;

                    case 8:
                      $_cijfer_number = $c8;
                      break;

                    case 9:
                      $_cijfer_number = $c9;
                      break;

                    case 10:
                      $_cijfer_number = $c10;
                      break;

                    case 11:
                      $_cijfer_number = $c11;
                      break;

                    case 12:
                      $_cijfer_number = $c12;
                      break;

                    case 13:
                      $_cijfer_number = $c13;
                      break;

                    case 14:
                      $_cijfer_number = $c14;
                      break;

                    case 15:
                      $_cijfer_number = $c15;
                      break;

                    case 16:
                      $_cijfer_number = $c16;
                      break;

                    case 17:
                      $_cijfer_number = $c17;
                      break;

                    case 18:
                      $_cijfer_number = $c18;
                      break;

                    case 19:
                      $_cijfer_number = $c19;
                      break;

                    case 20:
                      $_cijfer_number = $c20;
                      break;

                    default:
                      $_cijfer_number = 0;
                      break;
                  }

                  // Changes settings (ladalan@caribedev)

                  if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\" class=\"editable\">$_cijfer_number</span></td>";
                  } else {
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\">$_cijfer_number</span></td>";
                  }

                  // End changes settings (ladalan@caribedev)

                  $xx++;
                }

                $htmlcontrol .= "<td>$gemiddelde</td></tr>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
              //BEGIN CaribeDevelopers - Janio Acero
              $u = new spn_utils();
              $mysqli->close();
              $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
              if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_le_cijfersextra . " (?,?,?)")) {
                if ($stmt->bind_param("ssi", $klas_in, $vak_in, $rap_in)) {
                  if ($stmt->execute()) {
                    $stmt->bind_result(
                      $cijfersextraid,
                      $klas,
                      $schooljaar,
                      $rapnummer,
                      $vak,
                      $oc1,
                      $oc2,
                      $oc3,
                      $oc4,
                      $oc5,
                      $oc6,
                      $oc7,
                      $oc8,
                      $oc9,
                      $oc10,
                      $oc11,
                      $oc12,
                      $oc13,
                      $oc14,
                      $oc15,
                      $oc16,
                      $oc17,
                      $oc18,
                      $oc19,
                      $oc20,
                      $dc1,
                      $dc2,
                      $dc3,
                      $dc4,
                      $dc5,
                      $dc6,
                      $dc7,
                      $dc8,
                      $dc9,
                      $dc10,
                      $dc11,
                      $dc12,
                      $dc13,
                      $dc14,
                      $dc15,
                      $dc16,
                      $dc17,
                      $dc18,
                      $dc19,
                      $dc20
                    );
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<input type=\"hidden\" id=\"id_cijfersextra\" name=\"id_cijfersextra\" value=\"" . htmlentities($cijfersextraid) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijfersextra\" name=\"klas_cijfersextra\" value=\"" . htmlentities($klas) . "\">";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijfersextra\" name=\"schooljaar_cijfersextra\" value=\"" . htmlentities($schooljaar) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijfersextra\" name=\"rapnummer_cijfersextra\" value=\"" . htmlentities($rapnummer) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijfersextra\" name=\"vak_cijfersextra\" value=\"" . htmlentities($vak) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc1_cijfersextra\" name=\"oc1_cijfersextra\" value=\"" . htmlentities($oc1) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc2_cijfersextra\" name=\"oc2_cijfersextra\" value=\"" . htmlentities($oc2) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc3_cijfersextra\" name=\"oc3_cijfersextra\" value=\"" . htmlentities($oc3) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc4_cijfersextra\" name=\"oc4_cijfersextra\" value=\"" . htmlentities($oc4) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc5_cijfersextra\" name=\"oc5_cijfersextra\" value=\"" . htmlentities($oc5) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc6_cijfersextra\" name=\"oc6_cijfersextra\" value=\"" . htmlentities($oc6) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc7_cijfersextra\" name=\"oc7_cijfersextra\" value=\"" . htmlentities($oc7) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc8_cijfersextra\" name=\"oc8_cijfersextra\" value=\"" . htmlentities($oc8) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc9_cijfersextra\" name=\"oc9_cijfersextra\" value=\"" . htmlentities($oc9) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc10_cijfersextra\" name=\"oc10_cijfersextra\" value=\"" . htmlentities($oc10) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc11_cijfersextra\" name=\"oc11_cijfersextra\" value=\"" . htmlentities($oc11) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc12_cijfersextra\" name=\"oc12_cijfersextra\" value=\"" . htmlentities($oc12) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc13_cijfersextra\" name=\"oc13_cijfersextra\" value=\"" . htmlentities($oc13) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc14_cijfersextra\" name=\"oc14_cijfersextra\" value=\"" . htmlentities($oc14) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc15_cijfersextra\" name=\"oc15_cijfersextra\" value=\"" . htmlentities($oc15) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc16_cijfersextra\" name=\"oc16_cijfersextra\" value=\"" . htmlentities($oc16) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc17_cijfersextra\" name=\"oc17_cijfersextra\" value=\"" . htmlentities($oc17) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc18_cijfersextra\" name=\"oc18_cijfersextra\" value=\"" . htmlentities($oc18) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc19_cijfersextra\" name=\"oc19_cijfersextra\" value=\"" . htmlentities($oc19) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc20_cijfersextra\" name=\"oc20_cijfersextra\" value=\"" . htmlentities($oc20) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc1_cijfersextra\" name=\"dc1_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc1)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc2_cijfersextra\" name=\"dc2_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc2)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc3_cijfersextra\" name=\"dc3_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc3)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc4_cijfersextra\" name=\"dc4_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc4)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc5_cijfersextra\" name=\"dc5_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc5)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc6_cijfersextra\" name=\"dc6_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc6)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc7_cijfersextra\" name=\"dc7_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc7)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc8_cijfersextra\" name=\"dc8_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc8)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc9_cijfersextra\" name=\"dc9_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc9)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc10_cijfersextra\" name=\"dc10_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc10)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc11_cijfersextra\" name=\"dc11_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc11)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc12_cijfersextra\" name=\"dc12_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc12)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc13_cijfersextra\" name=\"dc13_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc13)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc14_cijfersextra\" name=\"dc14_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc14)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc15_cijfersextra\" name=\"dc15_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc15)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc16_cijfersextra\" name=\"dc16_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc16)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc17_cijfersextra\" name=\"dc17_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc17)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc18_cijfersextra\" name=\"dc18_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc18)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc19_cijfersextra\" name=\"dc19_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc19)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc20_cijfersextra\" name=\"dc20_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc20)) . "\"/>";
                      }
                    } else {
                      //POR AHORA NADA
                    }
                  } else {
                    $result = 0;
                    $this->mysqlierror = $mysqli->error;
                    $this->mysqlierrornumber = $mysqli->errno;
                    $result = $mysqli->error;
                  }
                } else {
                  $result = 0;
                  $this->mysqlierror = $mysqli->error;
                  $this->mysqlierrornumber = $mysqli->errno;

                  $result = $mysqli->error;
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END CaribeDevelopers - Janio Acero
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }


      // $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijferswaarde\" name=\"klas_cijferswaarde\" value=\"" . htmlentities($klas_in) . "\">";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijferswaarde\" name=\"schooljaar_cijferswaarde\" value=\"" . htmlentities($schooljaar) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijferswaarde\" name=\"rapnummer_cijferswaarde\" value=\"" . htmlentities($rap_in) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijferswaarde\" name=\"vak_cijferswaarde\" value=\"" . htmlentities($vak_in) . "\"/>";



      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        //print "exception: " . $e->getMessage();
        $result = 0;
        return $result;
      }
    }

    return $returnvalue;
  }

  function listcijfers_mobile($schoolid, $klas_in, $vak_in, $rap_in, $sort_order, $schooljaar)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcijferswaarde = "";

    // if($this->debug)
    // {
    //   print "schoolid: " . $schoolid . "<br />";
    //   print "klas_in: " . $klas_in . "<br />";
    //   print "vak_in: " . $vak_in . "<br />";
    //   print "rap_in: " . $rap_in . "<br />";
    //   print "sort_order: " . $sort_order . "<br />";
    // }

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting_mobile();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde FROM students s LEFT JOIN le_cijfers c ON s.id = c.studentid LEFT JOIN le_vakken v ON c.vak = v.id WHERE s.class = ? AND v.id = ? AND c.rapnummer = ? AND c.schooljaar = ?  ORDER BY ";

    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)


    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {

      require_once("DBCreds.php");
      require_once("spn_utils.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siss", $klas_in, $vak_in, $rap_in, $schooljaar)) {
          if ($select->execute()) {

            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $firstname, $lastname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();

            if ($select->num_rows > 0) {


              //BEGIN le_cijferswaarde (ejaspe@caribedev)
              $u = new spn_utils();
              if ($stmtcijferswaarde = $mysqli->prepare("CALL " . $this->sp_read_le_cijferswaarde . " (?,?,?)")) {
                if ($stmtcijferswaarde->bind_param("ssi", $klas_in, $vak_in, $rap_in)) {
                  if ($stmtcijferswaarde->execute()) {
                    $stmtcijferswaarde->bind_result($cijferswaardeid, $klas, $schooljaar, $rapnummer, $vak, $cijferswaarde1, $cijferswaarde2, $cijferswaarde3, $cijferswaarde4, $cijferswaarde5, $cijferswaarde6, $cijferswaarde7, $cijferswaarde8, $cijferswaarde9, $cijferswaarde10, $cijferswaarde11, $cijferswaarde12, $cijferswaarde13, $cijferswaarde14, $cijferswaarde15, $cijferswaarde16, $cijferswaarde17, $cijferswaarde18, $cijferswaarde19, $cijferswaarde20);
                    $stmtcijferswaarde->store_result();



                    if ($stmtcijferswaarde->num_rows > 0) {
                      if ($rap_in == 1) {
                        if ($s->_setting_rapnumber_1) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 2) {
                        if ($s->_setting_rapnumber_2) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      } elseif ($rap_in == 3) {
                        if ($s->_setting_rapnumber_3) {
                          // Edit mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\"/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\"/></th>";
                          }
                        } else {
                          // View mode
                          while ($stmtcijferswaarde->fetch()) {
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde1) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde2) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde3) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde4) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde5) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde6) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde7) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde8) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde9) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde10) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde11) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde12) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde13) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde14) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde15) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde16) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde17) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde18) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde19) . "\" disabled/></th>";
                            $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"" . htmlentities($cijferswaarde20) . "\" disabled/></th>";
                          }
                        }
                      }
                    } else {
                      // Changes settings (ladalan@caribedev)

                      if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" onblur=\"save_cijferswaarde();\" class=\"form-control\" value=\"\"/></th>";
                      } else {
                        //NO DATA FIELDS
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde1\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde2\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde3\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde4\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde5\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde6\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde7\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde8\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde9\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde10\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde11\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde12\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde13\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde14\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde15\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde16\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde17\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde18\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde19\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                        $htmlcijferswaarde .= "<th><input id=\"cijferswaarde20\" type=\"text\" class=\"form-control\" value=\"\" disabled/></th>";
                      }
                    }

                    // End changes settings (laldana@caribedev)
                  } else {
                    $result = 0;
                    $this->mysqlierror = $mysqli->error;
                    $this->mysqlierrornumber = $mysqli->errno;
                    $result = $mysqli->error;
                  }
                } else {
                  $result = 0;
                  $this->mysqlierror = $mysqli->error;
                  $this->mysqlierrornumber = $mysqli->errno;

                  $result = $mysqli->error;
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END le_cijferswaarde


              /* begin drawing table */
              $htmlcontrol .= "<div class=\"table-responsive\">
              <table id=\"vak\" class=\"table table-striped table-bordered table-colored table-vak col-md-3 col-xs-6 col-sm-4\">
              <thead>
              <tr>
              <th></th>
              <th class=\"btn-m-w\"></th>";


              $htmlcontrol .= $htmlcijferswaarde;

              // Changes settings (ladalan@caribedev)

              if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {

                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"1\">1 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"2\">2 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"3\">3 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"4\">4 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"5\">5 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"6\">6 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"7\">7 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"8\">8 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"9\">9 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"10\">10 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"11\">11 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"12\">12 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"13\">13 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"14\">14 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"15\">15 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"16\">16 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"17\">17 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"18\">18 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"19\">19 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                <a class=\"modal-btn\" href=\"#\" data-toggle-tooltip=\"tooltip\" data-placement=\"top\" title=\"{title}\" data-toggle=\"modal\" data-target=\"#modalinfo\" data-id=\"20\">20 <i class=\"fa fa-edit\"></i></a>
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              } else {
                $htmlcontrol .= "<th></th>
                </tr>
                <tr class=\"text-align-center\">
                <th>#</th>
                <th class=\"btn-m-w\">Naam</th>
                <th>
                1
                </th>
                <th>
                2
                </th>
                <th>
                3
                </th>
                <th>
                4
                </th>
                <th>
                5
                </th>
                <th>
                6
                </th>
                <th>
                7
                </th>
                <th>
                8
                </th>
                <th>
                9
                </th>
                <th>
                10
                </th>
                <th>
                11
                </th>
                <th>
                12
                </th>
                <th>
                13
                </th>
                <th>
                14
                </th>
                <th>
                15
                </th>
                <th>
                16
                </th>
                <th>
                17
                </th>
                <th>
                18
                </th>
                <th>
                19
                </th>
                <th>
                20
                </th>
                <th>
                Gemiddeld
                </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                <td></td>
                <td class=\"btn-w-m align-right\">Gemiddeld</td>
                <td><div id=\"gemiddeld_1\">0</div></td>
                <td><div id=\"gemiddeld_2\">0</div></td>
                <td><div id=\"gemiddeld_3\">0</div></td>
                <td><div id=\"gemiddeld_4\">0</div></td>
                <td><div id=\"gemiddeld_5\">0</div></td>
                <td><div id=\"gemiddeld_6\">0</div></td>
                <td><div id=\"gemiddeld_7\">0</div></td>
                <td><div id=\"gemiddeld_8\">0</div></td>
                <td><div id=\"gemiddeld_9\">0</div></td>
                <td><div id=\"gemiddeld_10\">0</div></td>
                <td><div id=\"gemiddeld_11\">0</div></td>
                <td><div id=\"gemiddeld_12\">0</div></td>
                <td><div id=\"gemiddeld_13\">0</div></td>
                <td><div id=\"gemiddeld_14\">0</div></td>
                <td><div id=\"gemiddeld_15\">0</div></td>
                <td><div id=\"gemiddeld_16\">0</div></td>
                <td><div id=\"gemiddeld_17\">0</div></td>
                <td><div id=\"gemiddeld_18\">0</div></td>
                <td><div id=\"gemiddeld_19\">0</div></td>
                <td><div id=\"gemiddeld_20\">0</div></td>
                <td><div id=\"gemiddeld_total\">0</div></td>
                </tr>
                </tfoot>
                <tbody>";
              }

              // End changes settings (ladalan@caribedev)

              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-cijfer attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {

                $htmlcontrol .= "<tr><td>$x</td><td>" . utf8_encode($firstname) . chr(32) . utf8_encode($lastname) . "</td>";

                for ($y = 1; $y <= 20; $y++) {
                  $htmlcontrol .= "";

                  $_cijfer_number = 0;
                  /* check dimension of the cijfers */
                  switch ($y) {
                    case 1:
                      $_cijfer_number = $c1;
                      break;

                    case 2:
                      $_cijfer_number = $c2;
                      break;

                    case 3:
                      $_cijfer_number = $c3;
                      break;

                    case 4:
                      $_cijfer_number = $c4;
                      break;

                    case 5:
                      $_cijfer_number = $c5;
                      break;

                    case 6:
                      $_cijfer_number = $c6;
                      break;

                    case 7:
                      $_cijfer_number = $c7;
                      break;

                    case 8:
                      $_cijfer_number = $c8;
                      break;

                    case 9:
                      $_cijfer_number = $c9;
                      break;

                    case 10:
                      $_cijfer_number = $c10;
                      break;

                    case 11:
                      $_cijfer_number = $c11;
                      break;

                    case 12:
                      $_cijfer_number = $c12;
                      break;

                    case 13:
                      $_cijfer_number = $c13;
                      break;

                    case 14:
                      $_cijfer_number = $c14;
                      break;

                    case 15:
                      $_cijfer_number = $c15;
                      break;

                    case 16:
                      $_cijfer_number = $c16;
                      break;

                    case 17:
                      $_cijfer_number = $c17;
                      break;

                    case 18:
                      $_cijfer_number = $c18;
                      break;

                    case 19:
                      $_cijfer_number = $c19;
                      break;

                    case 20:
                      $_cijfer_number = $c20;
                      break;

                    default:
                      $_cijfer_number = 0;
                      break;
                  }

                  // Changes settings (ladalan@caribedev)

                  if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\" class=\"editable\">$_cijfer_number</span></td>";
                  } else {
                    $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"$studentid\" data-cijfer=\"c$y\" data-klas=\"$klas_in\" data-vak=\"$vak_in\" data-rapport=\"$rap_in\">$_cijfer_number</span></td>";
                  }

                  // End changes settings (ladalan@caribedev)

                  $xx++;
                }

                $htmlcontrol .= "<td>$gemiddelde</td></tr>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
              $htmlcontrol .= "</div>";
              //BEGIN CaribeDevelopers - Janio Acero
              $u = new spn_utils();
              $mysqli->close();
              $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
              if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_le_cijfersextra . " (?,?,?)")) {
                if ($stmt->bind_param("ssi", $klas_in, $vak_in, $rap_in)) {
                  if ($stmt->execute()) {
                    $stmt->bind_result(
                      $cijfersextraid,
                      $klas,
                      $schooljaar,
                      $rapnummer,
                      $vak,
                      $oc1,
                      $oc2,
                      $oc3,
                      $oc4,
                      $oc5,
                      $oc6,
                      $oc7,
                      $oc8,
                      $oc9,
                      $oc10,
                      $oc11,
                      $oc12,
                      $oc13,
                      $oc14,
                      $oc15,
                      $oc16,
                      $oc17,
                      $oc18,
                      $oc19,
                      $oc20,
                      $dc1,
                      $dc2,
                      $dc3,
                      $dc4,
                      $dc5,
                      $dc6,
                      $dc7,
                      $dc8,
                      $dc9,
                      $dc10,
                      $dc11,
                      $dc12,
                      $dc13,
                      $dc14,
                      $dc15,
                      $dc16,
                      $dc17,
                      $dc18,
                      $dc19,
                      $dc20
                    );
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<input type=\"hidden\" id=\"id_cijfersextra\" name=\"id_cijfersextra\" value=\"" . htmlentities($cijfersextraid) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijfersextra\" name=\"klas_cijfersextra\" value=\"" . htmlentities($klas) . "\">";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijfersextra\" name=\"schooljaar_cijfersextra\" value=\"" . htmlentities($schooljaar) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijfersextra\" name=\"rapnummer_cijfersextra\" value=\"" . htmlentities($rapnummer) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijfersextra\" name=\"vak_cijfersextra\" value=\"" . htmlentities($vak) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc1_cijfersextra\" name=\"oc1_cijfersextra\" value=\"" . htmlentities($oc1) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc2_cijfersextra\" name=\"oc2_cijfersextra\" value=\"" . htmlentities($oc2) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc3_cijfersextra\" name=\"oc3_cijfersextra\" value=\"" . htmlentities($oc3) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc4_cijfersextra\" name=\"oc4_cijfersextra\" value=\"" . htmlentities($oc4) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc5_cijfersextra\" name=\"oc5_cijfersextra\" value=\"" . htmlentities($oc5) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc6_cijfersextra\" name=\"oc6_cijfersextra\" value=\"" . htmlentities($oc6) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc7_cijfersextra\" name=\"oc7_cijfersextra\" value=\"" . htmlentities($oc7) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc8_cijfersextra\" name=\"oc8_cijfersextra\" value=\"" . htmlentities($oc8) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc9_cijfersextra\" name=\"oc9_cijfersextra\" value=\"" . htmlentities($oc9) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc10_cijfersextra\" name=\"oc10_cijfersextra\" value=\"" . htmlentities($oc10) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc11_cijfersextra\" name=\"oc11_cijfersextra\" value=\"" . htmlentities($oc11) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc12_cijfersextra\" name=\"oc12_cijfersextra\" value=\"" . htmlentities($oc12) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc13_cijfersextra\" name=\"oc13_cijfersextra\" value=\"" . htmlentities($oc13) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc14_cijfersextra\" name=\"oc14_cijfersextra\" value=\"" . htmlentities($oc14) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc15_cijfersextra\" name=\"oc15_cijfersextra\" value=\"" . htmlentities($oc15) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc16_cijfersextra\" name=\"oc16_cijfersextra\" value=\"" . htmlentities($oc16) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc17_cijfersextra\" name=\"oc17_cijfersextra\" value=\"" . htmlentities($oc17) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc18_cijfersextra\" name=\"oc18_cijfersextra\" value=\"" . htmlentities($oc18) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc19_cijfersextra\" name=\"oc19_cijfersextra\" value=\"" . htmlentities($oc19) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"oc20_cijfersextra\" name=\"oc20_cijfersextra\" value=\"" . htmlentities($oc20) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc1_cijfersextra\" name=\"dc1_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc1)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc2_cijfersextra\" name=\"dc2_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc2)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc3_cijfersextra\" name=\"dc3_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc3)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc4_cijfersextra\" name=\"dc4_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc4)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc5_cijfersextra\" name=\"dc5_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc5)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc6_cijfersextra\" name=\"dc6_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc6)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc7_cijfersextra\" name=\"dc7_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc7)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc8_cijfersextra\" name=\"dc8_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc8)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc9_cijfersextra\" name=\"dc9_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc9)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc10_cijfersextra\" name=\"dc10_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc10)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc11_cijfersextra\" name=\"dc11_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc11)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc12_cijfersextra\" name=\"dc12_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc12)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc13_cijfersextra\" name=\"dc13_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc13)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc14_cijfersextra\" name=\"dc14_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc14)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc15_cijfersextra\" name=\"dc15_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc15)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc16_cijfersextra\" name=\"dc16_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc16)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc17_cijfersextra\" name=\"dc17_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc17)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc18_cijfersextra\" name=\"dc18_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc18)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc19_cijfersextra\" name=\"dc19_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc19)) . "\"/>";
                        $htmlcontrol .= "<input type=\"hidden\" id=\"dc20_cijfersextra\" name=\"dc20_cijfersextra\" value=\"" . $u->convertfrommysqldate(htmlentities($dc20)) . "\"/>";
                      }
                    } else {
                      //POR AHORA NADA
                    }
                  } else {
                    $result = 0;
                    $this->mysqlierror = $mysqli->error;
                    $this->mysqlierrornumber = $mysqli->errno;
                    $result = $mysqli->error;
                  }
                } else {
                  $result = 0;
                  $this->mysqlierror = $mysqli->error;
                  $this->mysqlierrornumber = $mysqli->errno;

                  $result = $mysqli->error;
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;

                $result = $mysqli->error;
              }
              //END CaribeDevelopers - Janio Acero
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */

            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }


      // $htmlcontrol .= "<input type=\"hidden\" id=\"klas_cijferswaarde\" name=\"klas_cijferswaarde\" value=\"" . htmlentities($klas_in) . "\">";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"schooljaar_cijferswaarde\" name=\"schooljaar_cijferswaarde\" value=\"" . htmlentities($schooljaar) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"rapnummer_cijferswaarde\" name=\"rapnummer_cijferswaarde\" value=\"" . htmlentities($rap_in) . "\"/>";
      // $htmlcontrol .= "<input type=\"hidden\" id=\"vak_cijferswaarde\" name=\"vak_cijferswaarde\" value=\"" . htmlentities($vak_in) . "\"/>";



      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {

      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        //print "exception: " . $e->getMessage();
        $result = 0;
        return $result;
      }
    }

    return $returnvalue;
  }


  function savecijfer($schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {

    $cijfer_count = $this->_getcijfercount("", $studentid_in, $klas_in, $rap_in, $vak_in);
    if ($cijfer_count == 1) {

      /* TODO: Validate  */
      if ($cijfer_number_in == "c1" || $cijfer_number_in == "c2" || $cijfer_number_in == "c3" ||  $cijfer_number_in == "c4" || $cijfer_number_in == "c5" || $cijfer_number_in == "c6" || $cijfer_number_in == "c7" || $cijfer_number_in == "c8" || $cijfer_number_in == "c9" || $cijfer_number_in == "c10" ||  $cijfer_number_in == "c11" || $cijfer_number_in == "c12" || $cijfer_number_in == "c13" || $cijfer_number_in == "c14" || $cijfer_number_in == "c15" || $cijfer_number_in == "c16" || $cijfer_number_in == "c17" ||  $cijfer_number_in == "c18" || $cijfer_number_in == "c19" || $cijfer_number_in == "c20") {


        return $this->_updatecijfer($schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
      }
    } else {

      /* TODO: Validate  */
      if ($cijfer_number_in == "c1" || $cijfer_number_in == "c2" || $cijfer_number_in == "c3" ||  $cijfer_number_in == "c4" || $cijfer_number_in == "c5" || $cijfer_number_in == "c6" || $cijfer_number_in == "c7" || $cijfer_number_in == "c8" || $cijfer_number_in == "c9" || $cijfer_number_in == "c10" ||  $cijfer_number_in == "c11" || $cijfer_number_in == "c12" || $cijfer_number_in == "c13" || $cijfer_number_in == "c14" || $cijfer_number_in == "c15" || $cijfer_number_in == "c16" || $cijfer_number_in == "c17" ||  $cijfer_number_in == "c18" || $cijfer_number_in == "c19" || $cijfer_number_in == "c20") {


        return $this->_insertcijfer($schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in);
      }
    }
  }

  function _getcijfercount($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.id from le_cijfers c where c.studentid = ? and c.rapnummer = ? and c.vak = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $rap_in, $vak_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'read cijfers count', appconfig::GetDummy());

            $result = 1;

            $select->store_result();

            $returnvalue = $select->num_rows;
          } else {
            /* error executing query */

            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {

      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        //print "exception: " . $e->getMessage();
        $result = 0;
        return $result;
      }
    }

    return $returnvalue;
  }

  function getgemiddelde($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.gemiddelde from le_cijfers c where c.studentid = ? and c.rapnummer = ? and c.vak = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("sss", $studentid_in, $rap_in, $vak_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'read cijfers middelde', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($gemiddelde);
            $select->store_result();

            while ($select->fetch()) {
              $returnvalue = $gemiddelde;
            }
          } else {
            /* error executing query */

            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {

      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        //print "exception: " . $e->getMessage();
        $result = 0;
        return $result;
      }
    }

    return $returnvalue;
  }

  function _updatecijfer($schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {

    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      //update le_cijfer h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      if ($stmt = $mysqli->prepare("update" . chr(32) . $this->tablename_cijfers . chr(32) . "c join students s on c.studentid = s.id set" . chr(32) . "c." . $cijfer_number_in . chr(32) . " = ? where s.id = ? and s.class = ? and c.rapnummer = ? and c.vak = ?;")) {
        if ($stmt->bind_param("sssss", $cijfer_value_in, $studentid_in, $klas_in, $rap_in, $vak_in)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            // function _savecijfersgemiddelde($schoolid,$studentid_in,$klas_in,$rap_in,$vak_in)
            $result = $this->_savecijfersgemiddelde($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in);
            if ($this->debug) {
              print "result = " . $result . "</br>";
              print "sudentid = " . $studentid_in . "</br>";
              print "klas = " . $klas_in . "</br>";
              print "rap = " . $rap_in . "</br>";
              print "vak = " . $vak_in . "</br>";
              print "cijfer = " . $cijfer_value_in . "</br>";
            }
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function _savecijfersgemiddelde($schoolid, $studentid_in, $klas_in, $rap_in, $vak_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select s.id, c.id, s.class, s.firstname, s.lastname, s.sex, c.c1, c.c2, c.c3, c.c4, c.c5, c.c6, c.c7, c.c8, c.c9, c.c10, c.c11, c.c12, c.c13, c.c14, c.c15, c.c16, c.c17, c.c18, c.c19, c.c20, c.gemiddelde from students s left join le_cijfers c on s.id = c.studentid left join le_vakken v on c.vak = v.id where s.class = ? and v.id = ? and c.rapnummer = ? and c.studentid = ? order by s.firstname asc;";
    /* TODO: QUERY NEEDS TO BE FIXED , EL */


    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas_in, $vak_in, $rap_in, $studentid_in)) {
          if ($select->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers middelde', appconfig::GetDummy());

            $result = 1;

            $select->bind_result($studentid, $cijferid, $klas, $firstname, $lastname, $sex, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde);
            $select->store_result();


            if ($select->num_rows == 1) {

              while ($select->fetch()) {
                $gemid = $this->_gemiddeldebasis($c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);



                //$mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                $sql_querygemid = "update  le_cijfers set gemiddelde =  ?  where id = ?";

                if ($update = $mysqli->prepare($sql_querygemid)) {
                  if ($update->bind_param("si", $gemid, $cijferid)) {
                    if ($update->execute()) {
                      $returnvalue = 1;
                    } else {
                      /* error executing query */

                      $this->mysqlierror = $mysqli->error;
                      $result = 0;

                      if ($this->debug) {
                        print "error executing query" . "<br />";
                        print "error" . $mysqli->error;
                      }
                    }
                  } else {
                    /* error binding parameters */

                    $this->mysqlierror = $mysqli->error;
                    $result = 0;

                    if ($this->debug) {
                      print "error binding parameters";
                    }
                  }
                } else {
                  /* error preparing query */

                  $this->mysqlierror = $mysqli->error;
                  $result = 0;

                  if ($this->debug) {
                    print "error preparing query";
                  }
                }
              }
            } else {
              $returnvalue = 0;
            }
          } else {
            /* error executing query */

            $this->mysqlierror = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */

          $this->mysqlierror = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */

        $this->mysqlierror = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {

      $this->mysqlierror = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }




  function _gemiddeldebasis(
    $c1,
    $c2,
    $c3,
    $c4,
    $c5,
    $c6,
    $c7,
    $c8,
    $c9,
    $c10,
    $c11,
    $c12,
    $c13,
    $c14,
    $c15,
    $c16,
    $c17,
    $c18,
    $c19,
    $c20,
    $w1,
    $w2,
    $w3,
    $w4,
    $w5,
    $w6,
    $w7,
    $w8,
    $w9,
    $w10,
    $w11,
    $w12,
    $w13,
    $w14,
    $w15,
    $w16,
    $w17,
    $w18,
    $w19,
    $w20
  ) {

    $index = 0;
    $samen  = 0;

    for ($i = 1; $i < 21; $i++) {
      if (${'c' . $i} <> 0) {
        $samen = $samen + (${'c' . $i} * ${'w' . $i});
        $index = $index + ${'w' . $i};
      }
    }
    if ($this->debug) {
      print 'c1 =' . $c1 . 'w1=' . $w1 . '<br>';
      print "index" . $index . " samen " . $samen;
    }
    if ($index > 0 && $samen > 0) {

      $Xgemid = ($samen / $index);

      if ($Xgemid == 10) {
        $result = $Xgemid;
      } else {
        $result = substr($Xgemid, 0, 3);
      }
    } else {
      $result = 0;
    }

    return $result;
  }

  function _insertcijfer($schoolid, $studentid_in, $cijfer_number_in, $cijfer_value_in, $klas_in, $rap_in, $vak_in)
  {

    $schooljaar_temp = "2016-2017";
    $user_temp = "test_user";

    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      //update le_cijfer h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      if ($stmt = $mysqli->prepare("insert into" . chr(32) . $this->tablename_cijfers . chr(32) . "(studentid, created, schooljaar, rapnummer, klas, user," . chr(32) . $cijfer_number_in . ") values (?,?,?,?,?,?,?);")) {
        if ($stmt->bind_param("sssssss", $studentid_in, $_DateTime, $schooljaar_temp, $rap_in, $klas_in, $user_temp, $cijfer_value_in)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

            $result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }

      //print "mysqli error: " . $this->mysqlierror;


    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  //BEGIN CaribeDevelopers - Janio Acero
  function updatecijfersextra($idcijfersextra, $klas, $schooljaar, $rapnummer, $vak, $index, $oc, $dc)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("CALL " . $this->sp_update_le_cijfersextra . " (?,?,?,?,?,?,?,?)")) {
        $result = 0;
        if ($stmt->bind_param(
          "issisiss",
          $idcijfersextra,
          $klas,
          $schooljaar,
          $rapnummer,
          $vak,
          $index,
          $oc,
          $dc
        )) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'update cijfers extra', appconfig::GetDummy());
            $result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $result = $mysqli->error;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $result = $mysqli->error;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;

        $result = $mysqli->error;
      }
    } catch (Exception $e) {
      $result = -2;
      //  $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  function _getcijfersocdcvalue($index, $i, $value)
  {
    if ($index == $i) {
      return $value;
    } else {
      return "";
    }
  }
  //END CaribeDevelopers - Janio Acero

  //CREATE CIJFERSWAARDEE (ejaspe@caribedev)
  function createcijferswaarde($klas, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($stmt = $mysqli->prepare("CALL sp_create_le_cijferswaarde (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("ssisiiiiiiiiiiiiiiiiiiii", $klas, $schooljaar, $rapnummer, $vak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers waarde', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */

            $result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        // $this->mysqlierror = $mysqli->error;
        //$this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }
  //Caribe Dev
  function create_le_cijfer_student($schooljaar, $rapnummer, $klass, $schoolID)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("CALL sp_create_le_cijfers_student (?,?,?,?)")) {
        if ($stmt->bind_param("ssss", $schooljaar, $rapnummer, $klass, $schoolID)) {
          if ($stmt->execute()) {
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'cijfers', 'create cijfers student', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            //$result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " .  $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " .       $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        echo "# " .   $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      //$this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }


  function list_cijfers_by_student($schooljaar, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $sp_get_cijfers_by_student_parent = "sp_get_cijfers_by_student_parent";

        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_cijfers_by_student_parent . "(?,?)")) {
          if ($stmt->bind_param("ss", $schooljaar, $studentid)) {
            if ($stmt->execute()) {
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers by student', appconfig::GetDummy());

              $result = 1;
              $stmt->bind_result($klas, $id_studente, $lastchanged, $schooljaar, $rapnummer, $vak, $volledigenaamvak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $hergemiddelde);
              //$stmt->bind_result($klas,$id_studente,$lastchanged,$schooljaar,$rapnummer,$vak,$volledigenaamvak,$gemiddelde,$hergemiddelde);
              $stmt->store_result();



              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<div class=\"col-xs-11 table-responsive\">";

                $htmlcontrol .= "<div class='radio_buttons'>";
                $htmlcontrol .= "<div>";
                $htmlcontrol .= "<input type='radio' id='rapport_1' name='rapport' value='1'>";
                $htmlcontrol .= "<label for='rapport_1'>R1</label>";
                $htmlcontrol .= "</div>";

                $htmlcontrol .= "<div>";
                $htmlcontrol .= "<input type='radio' id='rapport_2' name='rapport' value='2'>";
                $htmlcontrol .= "<label for='rapport_2'>R2</label>";
                $htmlcontrol .= "</div>";

                $htmlcontrol .= "<div>";
                $htmlcontrol .= "<input type='radio' id='rapport_3' name='rapport' value='3'>";
                $htmlcontrol .= "<label for='rapport_3'>R3</label>";
                $htmlcontrol .= "</div>";

                $htmlcontrol .= "</div>";

                $htmlcontrol .= "<table id=\"tbl_cijfers_by_student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                $htmlcontrol .= "<thead><tr><th class='mobile'>Schooljaar</th><th class='mobile'>Klass</th><th>Rapport#</th><th>Vak</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>Gem</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr  class=" . htmlentities($rapnummer) . " >";
                  $htmlcontrol .= "<td class='mobile'>" . htmlentities($schooljaar) . "</td>";
                  $htmlcontrol .= "<td class='mobile'>" . htmlentities($klas) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($rapnummer) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($volledigenaamvak) . "</td>";
                  /*                   $htmlcontrol .= "<td> <button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($volledigenaamvak) . "\",\"" . htmlentities($rapnummer) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Virtuele klas</button> </td>"; */
                  // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"quaternary-bg-color default-secondary-color\"": "") ."</td>";

                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc1')\" title=\" \" name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc2')\" title=\" \" name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc3')\" title=\" \" name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc4')\" title=\" \" name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc5')\" title=\" \" name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc6')\" title=\" \" name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc7')\" title=\" \" name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc8')\" title=\" \" name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc9')\" title=\" \" name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                  $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc10')\" title=\" \" name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";

                  $htmlcontrol .= "<td name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                  // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                }

                /* mobile while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr  class=" . htmlentities($rapnummer) . ">";
                  $htmlcontrol .= "<td data-titulo='Schooljaar:'>" . htmlentities($schooljaar) . "</td>";
                  $htmlcontrol .= "<td data-titulo='Klas:'>" . htmlentities($klas) . "</td>";
                  $htmlcontrol .= "<td data-titulo='Rapnummer:'>" . htmlentities($rapnummer) . "</td>";
                  $htmlcontrol .= "<td data-titulo='Vak:'>" . htmlentities($volledigenaamvak) . "</td>";
                  $htmlcontrol .= "<td class='cijfers'> <button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($volledigenaamvak) . "\",\"" . htmlentities($rapnummer) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Virtuele klas</button> </td>";

                  // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"quaternary-bg-color default-secondary-color\"": "") ."</td>";

                  $htmlcontrol .= "<td" . ($c1 == null ? " class='cijfers'" : "") . " data-titulo='C1:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc1')\" title=\" \" name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                  $htmlcontrol .= "<td" . ($c2 == null ? " class='cijfers'" : "") . " data-titulo='C2:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc2')\" title=\" \" name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                  $htmlcontrol .= "<td" . ($c3 == null ? " class='cijfers'" : "") . " data-titulo='C3:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc3')\" title=\" \" name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                  $htmlcontrol .= "<td" . ($c4 == null ? " class='cijfers'" : "") . " data-titulo='C4:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc4')\" title=\" \" name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                  $htmlcontrol .= "<td" . ($c5 == null ? " class='cijfers'" : "") . " data-titulo='C5:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc5')\" title=\" \" name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                  $htmlcontrol .= "<td" . ($c6 == null ? " class='cijfers'" : "") . " data-titulo='C6:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc6')\" title=\" \" name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                  $htmlcontrol .= "<td" . ($c7 == null ? " class='cijfers'" : "") . " data-titulo='C7:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc7')\" title=\" \" name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                  $htmlcontrol .= "<td" . ($c8 == null ? " class='cijfers'" : "") . " data-titulo='C8:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc8')\" title=\" \" name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                  $htmlcontrol .= "<td" . ($c9 == null ? " class='cijfers'" : "") . " data-titulo='C9:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc9')\" title=\" \" name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                  $htmlcontrol .= "<td" . ($c10 == null ? " class='cijfers'" : "") . " data-titulo='C10:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc10')\" title=\" \" name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";
                  $htmlcontrol .= "<td" . ($c11 == null ? " class='cijfers'" : "") . " data-titulo='C11:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc11')\" title=\" \" name =\"c1\"" . ($c11 >= 1 && $c11 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c11 == 0.0 ? "" : htmlentities($c11)) . "</td>";
                  $htmlcontrol .= "<td" . ($c12 == null ? " class='cijfers'" : "") . " data-titulo='C12:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc12')\" title=\" \" name =\"c1\"" . ($c12 >= 1 && $c12 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c12 == 0.0 ? "" : htmlentities($c12)) . "</td>";
                  $htmlcontrol .= "<td" . ($c13 == null ? " class='cijfers'" : "") . " data-titulo='C13:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc13')\" title=\" \" name =\"c1\"" . ($c13 >= 1 && $c13 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c13 == 0.0 ? "" : htmlentities($c13)) . "</td>";
                  $htmlcontrol .= "<td" . ($c14 == null ? " class='cijfers'" : "") . " data-titulo='C14:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc14')\" title=\" \" name =\"c1\"" . ($c14 >= 1 && $c14 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c14 == 0.0 ? "" : htmlentities($c14)) . "</td>";
                  $htmlcontrol .= "<td" . ($c15 == null ? " class='cijfers'" : "") . " data-titulo='C15:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc15')\" title=\" \" name =\"c1\"" . ($c15 >= 1 && $c15 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c15 == 0.0 ? "" : htmlentities($c15)) . "</td>";
                  $htmlcontrol .= "<td" . ($c16 == null ? " class='cijfers'" : "") . " data-titulo='C16:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc16')\" title=\" \" name =\"c1\"" . ($c16 >= 1 && $c16 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c16 == 0.0 ? "" : htmlentities($c16)) . "</td>";
                  $htmlcontrol .= "<td" . ($c17 == null ? " class='cijfers'" : "") . " data-titulo='C17:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc17')\" title=\" \" name =\"c1\"" . ($c17 >= 1 && $c17 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c17 == 0.0 ? "" : htmlentities($c17)) . "</td>";
                  $htmlcontrol .= "<td" . ($c18 == null ? " class='cijfers'" : "") . " data-titulo='C18:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc18')\" title=\" \" name =\"c1\"" . ($c18 >= 1 && $c18 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c18 == 0.0 ? "" : htmlentities($c18)) . "</td>";
                  $htmlcontrol .= "<td" . ($c19 == null ? " class='cijfers'" : "") . " data-titulo='C19:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc19')\" title=\" \" name =\"c1\"" . ($c19 >= 1 && $c19 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c19 == 0.0 ? "" : htmlentities($c19)) . "</td>";
                  $htmlcontrol .= "<td" . ($c20 == null ? " class='cijfers'" : "") . " data-titulo='C20:' onclick=\"seeadd('$vak','$rapnummer','$klas','oc20')\" title=\" \" name =\"c1\"" . ($c20 >= 1 && $c20 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c20 == 0.0 ? "" : htmlentities($c20)) . "</td>";

                  $htmlcontrol .= "<td data-titulo='gemiddelde' name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                  // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                } */

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                /* $htmlcontrol .= "<button class='boton_alternado' id='mostrar_cijfer'>Show more</button>";
                $htmlcontrol .= "<button class='boton_alternado ocultar' id='ocultar_cijfer'>Show less</button>"; */
                $htmlcontrol .= "</div>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        // $this->exceptionvalue = $e->getMessage();
        //$result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }

  function list_cijfers_by_student_ps($schooljaar, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $sp_get_cijfers_by_student_parent = "SELECT id FROM students where uuid = ? limit 1;";

        if ($stmt = $mysqli->prepare($sp_get_cijfers_by_student_parent)) {
          if ($stmt->bind_param("s", $studentid)) {
            if ($stmt->execute()) {
              $stmt->bind_result($studentid);
              $stmt->store_result();
              if ($stmt->num_rows > 0) {
                while ($stmt->fetch()) {
                  echo "</br>";
                }
              }
              $sp_get_cijfers_by_student = "SELECT DISTINCT
              le_cijfers_ps.klas,
              le_cijfers_ps.studentid,
              le_cijfers_ps.lastchanged,
              le_cijfers_ps.schooljaar,
              le_cijfers_ps.rapnummer,
              le_cijfers_ps.vak,
              le_vakken_ps.vak_naam, 
              le_cijfers_ps.c1,
              le_cijfers_ps.c2,
              le_cijfers_ps.c3,
              le_cijfers_ps.c4,
              le_cijfers_ps.c5,
              le_cijfers_ps.c6,
              le_cijfers_ps.c7,
              le_cijfers_ps.c8,
              le_cijfers_ps.c9,
              le_cijfers_ps.c10,
              le_cijfers_ps.c11,
              le_cijfers_ps.c12,
              le_cijfers_ps.c13,
              le_cijfers_ps.c14,
              le_cijfers_ps.c15,
              le_cijfers_ps.c16,
              le_cijfers_ps.c17,
              le_cijfers_ps.c18,
              le_cijfers_ps.c19,
              le_cijfers_ps.c20,
              le_cijfers_ps.gemiddelde,
              le_cijfers_ps.hergemiddelde
              
              FROM
              le_cijfers_ps
              INNER JOIN le_vakken_ps ON le_vakken_ps.ID = le_cijfers_ps.vak
              left join le_cijfersextra on le_vakken_ps.ID = le_cijfersextra.vak
              where le_cijfers_ps.studentid = ? and le_cijfers_ps.schooljaar = ?
              and
              (le_cijfers_ps.c1 is not null or
              le_cijfers_ps.c2 is not null or
              le_cijfers_ps.c3 is not null or
              le_cijfers_ps.c4 is not null or
              le_cijfers_ps.c5 is not null or
              le_cijfers_ps.c6 is not null or
              le_cijfers_ps.c7 is not null or
              le_cijfers_ps.c8 is not null or
              le_cijfers_ps.c9 is not null or
              le_cijfers_ps.c10 is not null or
              le_cijfers_ps.c11 is not null or
              le_cijfers_ps.c12 is not null or
              le_cijfers_ps.c13 is not null or
              le_cijfers_ps.c14 is not null or
              le_cijfers_ps.c15 is not null or
              le_cijfers_ps.c16 is not null or
              le_cijfers_ps.c17 is not null or
              le_cijfers_ps.c18 is not null or
              le_cijfers_ps.c19 is not null or
              le_cijfers_ps.c20 is not null)
              GROUP BY klas,vak,rapnummer order by le_cijfers_ps.schooljaar desc, le_cijfers_ps.rapnummer desc,le_cijfers_ps.vak desc;";
              if ($stmt = $mysqli->prepare($sp_get_cijfers_by_student)) {
                if ($stmt->bind_param("ss", $studentid, $schooljaar)) {
                  if ($stmt->execute()) {
                    $spn_audit = new spn_audit();
                    $UserGUID = $_SESSION['UserGUID'];
                    $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers by student', appconfig::GetDummy());

                    $result = 1;
                    $stmt->bind_result($klas, $id_studente, $lastchanged, $schooljaar, $rapnummer, $vak, $volledigenaamvak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $hergemiddelde);
                    //$stmt->bind_result($klas,$id_studente,$lastchanged,$schooljaar,$rapnummer,$vak,$volledigenaamvak,$gemiddelde,$hergemiddelde);
                    $stmt->store_result();



                    if ($stmt->num_rows > 0) {
                      $htmlcontrol .= "<div class=\"col-xs-11 table-responsive\">";
                      /* $htmlcontrol .= "<div class='radio_buttons'>";

                      $htmlcontrol .= "<div>";
                      $htmlcontrol .= "<input type='radio' id='rapport_1' name='rapport' value='1'>";
                      $htmlcontrol .= "<label for='rapport_1'>R1</label>";
                      $htmlcontrol .= "</div>";

                      $htmlcontrol .= "<div>";
                      $htmlcontrol .= "<input type='radio' id='rapport_2' name='rapport' value='2'>";
                      $htmlcontrol .= "<label for='rapport_2'>R2</label>";
                      $htmlcontrol .= "</div>";

                      $htmlcontrol .= "<div>";
                      $htmlcontrol .= "<input type='radio' id='rapport_3' name='rapport' value='3'>";
                      $htmlcontrol .= "<label for='rapport_3'>R3</label>";
                      $htmlcontrol .= "</div>";

                      $htmlcontrol .= "</div>"; */
                      $htmlcontrol .= "<table id=\"tbl_cijfers_by_student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                      $htmlcontrol .= "<thead><tr><th>Schooljaar</th><th>Klass</th><th>Rapport#</th><th>Vak</th><th>Chat</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>Gemiddeld</th></tr></thead>";
                      $htmlcontrol .= "<tbody>";

                      while ($stmt->fetch()) {
                        $htmlcontrol .= "<tr>";
                        $htmlcontrol .= "<td>" . htmlentities($schooljaar) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($rapnummer) . "</td>";
                        $htmlcontrol .= "<td>" . htmlentities($volledigenaamvak) . "</td>";
                        $htmlcontrol .= "<td> <button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($volledigenaamvak) . "\",\"" . htmlentities($rapnummer) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Virtuele klas</button> </td>";

                        // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"quaternary-bg-color default-secondary-color\"": "") ."</td>";

                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc1')\" title=\" \" name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc2')\" title=\" \" name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc3')\" title=\" \" name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc4')\" title=\" \" name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc5')\" title=\" \" name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc6')\" title=\" \" name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc7')\" title=\" \" name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc8')\" title=\" \" name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc9')\" title=\" \" name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc10')\" title=\" \" name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc11')\" title=\" \" name =\"c1\"" . ($c11 >= 1 && $c11 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c11 == 0.0 ? "" : htmlentities($c11)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc12')\" title=\" \" name =\"c1\"" . ($c12 >= 1 && $c12 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c12 == 0.0 ? "" : htmlentities($c12)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc13')\" title=\" \" name =\"c1\"" . ($c13 >= 1 && $c13 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c13 == 0.0 ? "" : htmlentities($c13)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc14')\" title=\" \" name =\"c1\"" . ($c14 >= 1 && $c14 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c14 == 0.0 ? "" : htmlentities($c14)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc15')\" title=\" \" name =\"c1\"" . ($c15 >= 1 && $c15 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c15 == 0.0 ? "" : htmlentities($c15)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc16')\" title=\" \" name =\"c1\"" . ($c16 >= 1 && $c16 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c16 == 0.0 ? "" : htmlentities($c16)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc17')\" title=\" \" name =\"c1\"" . ($c17 >= 1 && $c17 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c17 == 0.0 ? "" : htmlentities($c17)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc18')\" title=\" \" name =\"c1\"" . ($c18 >= 1 && $c18 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c18 == 0.0 ? "" : htmlentities($c18)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc19')\" title=\" \" name =\"c1\"" . ($c19 >= 1 && $c19 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c19 == 0.0 ? "" : htmlentities($c19)) . "</td>";
                        $htmlcontrol .= "<td onclick=\"seeadd('$vak','$rapnummer','$klas','oc20')\" title=\" \" name =\"c1\"" . ($c20 >= 1 && $c20 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c20 == 0.0 ? "" : htmlentities($c20)) . "</td>";

                        $htmlcontrol .= "<td name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                        // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                      }

                      $htmlcontrol .= "</tbody>";
                      $htmlcontrol .= "</table>";
                      /* $htmlcontrol .= "<button class='boton_alternado' id='mostrar_cijfer'>Show more</button>";
                      $htmlcontrol .= "<button class='boton_alternado ocultar' id='ocultar_cijfer'>Show less</button>"; */
                      $htmlcontrol .= "</div>";
                    } else {
                      $htmlcontrol .= "No results to show";
                    }
                  }
                }
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        // $this->exceptionvalue = $e->getMessage();
        //$result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }


  function list_cijfers_by_student_by_id($schooljaar, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $sp_get_cijfers_by_student = "sp_get_cijfers_by_student";

        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_cijfers_by_student . "(?,?)")) {
          if ($stmt->bind_param("si", $schooljaar, $studentid)) {
            if ($stmt->execute()) {
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'cijfers', 'list cijfers by student', appconfig::GetDummy());

              $result = 1;
              $stmt->bind_result($firstname, $lastname, $klas, $id_studente, $lastchanged, $schooljaar, $rapnummer, $vak, $volledigenaamvak, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13, $c14, $c15, $c16, $c17, $c18, $c19, $c20, $gemiddelde, $hergemiddelde);
              $stmt->store_result();



              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<div class=\"col-xs-11 table-responsive\">";
                $htmlcontrol .= "<table id=\"tbl_cijfers_by_student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";

                $htmlcontrol .= "<thead><tr><th>Schooljaar</th><th>Klass</th><th>Rapport#</th><th>Vak</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th><th>11</th><th>12</th><th>13</th><th>14</th><th>15</th><th>16</th><th>17</th><th>18</th><th>19</th><th>20</th><th>Gemiddeld</th></tr></thead>";
                $htmlcontrol .= "<tbody>";

                while ($stmt->fetch()) {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>" . htmlentities($schooljaar) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($rapnummer) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($volledigenaamvak) . "</td>";
                  // $htmlcontrol .= "<td>". ($c1 >= 1 && $c1 <= 5.5 ? "class=\"quaternary-bg-color default-secondary-color\"": "") ."</td>";

                  $htmlcontrol .= "<td name =\"c1\"" . ($c1 >= 1 && $c1 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c1 == 0.0 ? "" : htmlentities($c1)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c2 >= 1 && $c2 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c2 == 0.0 ? "" : htmlentities($c2)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c3 >= 1 && $c3 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c3 == 0.0 ? "" : htmlentities($c3)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c4 >= 1 && $c4 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c4 == 0.0 ? "" : htmlentities($c4)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c5 >= 1 && $c5 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c5 == 0.0 ? "" : htmlentities($c5)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c6 >= 1 && $c6 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c6 == 0.0 ? "" : htmlentities($c6)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c7 >= 1 && $c7 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c7 == 0.0 ? "" : htmlentities($c7)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c8 >= 1 && $c8 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c8 == 0.0 ? "" : htmlentities($c8)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c9 >= 1 && $c9 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c9 == 0.0 ? "" : htmlentities($c9)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c10 >= 1 && $c10 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c10 == 0.0 ? "" : htmlentities($c10)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c11 >= 1 && $c11 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c11 == 0.0 ? "" : htmlentities($c11)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c12 >= 1 && $c12 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c12 == 0.0 ? "" : htmlentities($c12)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c13 >= 1 && $c13 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c13 == 0.0 ? "" : htmlentities($c13)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c14 >= 1 && $c14 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c14 == 0.0 ? "" : htmlentities($c14)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c15 >= 1 && $c15 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c15 == 0.0 ? "" : htmlentities($c15)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c16 >= 1 && $c16 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c16 == 0.0 ? "" : htmlentities($c16)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c17 >= 1 && $c17 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c17 == 0.0 ? "" : htmlentities($c17)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c18 >= 1 && $c18 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c18 == 0.0 ? "" : htmlentities($c18)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c19 >= 1 && $c19 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c19 == 0.0 ? "" : htmlentities($c19)) . "</td>";
                  $htmlcontrol .= "<td name =\"c1\"" . ($c20 >= 1 && $c20 <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . ($c20 == 0.0 ? "" : htmlentities($c20)) . "</td>";

                  $htmlcontrol .= "<td name =\"gemiddelde\"" . ($gemiddelde >= 1 && $gemiddelde <= 5.5 ?  "class=\"quaternary-bg-color default-secondary-color\"" : "") . ">" . htmlentities($gemiddelde) . "</td>";
                  // $htmlcontrol .= "<td>". htmlentities($gemiddelde) ."</td>";

                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        // $this->exceptionvalue = $e->getMessage();
        //$result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }

  function get_cijfers_graph($schoolid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph . " (?)")) {
          if ($select->bind_param("s", $schoolid)) {
            if ($select->execute()) {
              require_once("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'cijfers', 'get cijfers graph', appconfig::GetDummy());

              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                  } else
                    $json_result .= ",{";

                  $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                  $json_result .= "\"unit\": \"Cijfers\",";
                  $json_result .= "\"periode2\": \"" . $period2 . "\",";
                  $json_result .= "\"periode3\": \"" . $period3 . "\",";
                  $json_result .= "\"periode1\": \"" . $period1 . "\"";
                  // $json_result .= "\"periode2\": \"". 4 . "\",";
                  // $json_result .= "\"periode3\": \"". 5 . "\",";
                  // $json_result .= "\"periode1\": \"". 6 . "\"";

                  $json_result .= "}";
                }

                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $json_result = $result;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }


  function get_cijfers_graph_by_class($schoolid, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $inloop = false;
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph_by_class . " (?,?)")) {
          if ($select->bind_param("si", $schoolid, $studentid)) {
            if ($select->execute()) {

              // require_once ("spn_audit.php");
              // // $spn_audit = new spn_audit();
              // // $UserGUID = $_SESSION['UserGUID'];
              // // $spn_audit->create_audit($UserGUID, 'cijfers','get cijfers graph',appconfig::GetDummy());

              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                  } else
                    $json_result .= ",{";

                  $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                  $json_result .= "\"unit\": \"Cijfers\",";
                  $json_result .= "\"periode2\": \"" . $period2 . "\",";
                  $json_result .= "\"periode3\": \"" . $period3 . "\",";
                  $json_result .= "\"periode1\": \"" . $period1 . "\"";
                  $json_result .= "}";
                }

                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $json_result = $result;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }


  function get_cijfers_graph_by_student($schoolid, $studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $json_result = "";
    $indexvaluetoshow = 3;
    $inloop = false;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL " . $this->sp_read_le_cijfer_graph_by_student . " (?,?)")) {
          if ($select->bind_param("si", $schoolid, $studentid)) {
            if ($select->execute()) {
              // require_once ("spn_audit.php");
              // // $spn_audit = new spn_audit();
              // // $UserGUID = $_SESSION['UserGUID'];
              // // $spn_audit->create_audit($UserGUID, 'cijfers','get cijfers graph',appconfig::GetDummy());

              $result = 1;
              $select->bind_result($volledigenaamvak, $period1, $period2, $period3);
              $select->store_result();

              if ($select->num_rows > 0) {
                $json_result .= "[";

                while ($select->fetch()) {
                  if (!$inloop) {
                    $json_result .= "{";
                    $inloop = true;
                  } else
                    $json_result .= ",{";

                  $json_result .= "\"vakken\": \"" . $volledigenaamvak . "\",";
                  $json_result .= "\"unit\": \"Cijfers\",";
                  $json_result .= "\"periode2\": \"" . $period2 . "\",";
                  $json_result .= "\"periode3\": \"" . $period3 . "\",";
                  $json_result .= "\"periode1\": \"" . $period1 . "\"";
                  // $json_result .= "\"periode2\": \"". 4 . "\",";
                  // $json_result .= "\"periode3\": \"". 5 . "\",";
                  // $json_result .= "\"periode1\": \"". 6 . "\"";

                  $json_result .= "}";
                }

                $json_result .= "]";
              } else {
                $json_result .= "{ [] }";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;

              $json_result = $result;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;

            $json_result = $result;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;

          $json_result = $result;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();

        $json_result = $result;
      }

      return $json_result;
    }
  }
}
