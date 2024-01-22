<?php
require_once("spn_audit.php");
require_once("spn_setting.php");

class spn_houding
{
  public $tablename_houding = "le_houding";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $debug = false;


  function createhouding($studentnumber, $schooljaar, $rapnummer, $klas, $user, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25)
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
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_houding . "(created,studentid,schooljaar,rapnummer,klas,user,h1,h2,h3,h4,h5,h6,h7,h8,h9,h10,h11,h12,h13,h14,h15,h16,h17,h18,h19,h20,h21,h22,h23,h24,h25) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisissiiiiiiiiiiiiiiiiiiiiiiiii", $_DateTime, $studentnumber, $schooljaar, $rapnummer, $klas, $user, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'create houding', appconfig::GetDummy());
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function edithouding($houdingid, $studentnumber, $schooljaar, $rapnummer, $klas, $user, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15)
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
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("update " . $this->tablename_houding . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, klas = ?, user =?, h1 = ?, h2 = ?, h3 = ?, h4 = ?, h5 = ?, h6 = ?, h7 = ?, h8 = ?, h9 = ?, h10 = ?, h11 = ?, h12 = ?, h13 = ?, h14 = ?, h15 = ? where id = ?")) {
        if ($stmt->bind_param("sisissiiiiiiiiiiiiiiii", $_DateTime, $studentnumber, $schooljaar, $rapnummer, $klas, $user, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $houdingid)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'update houding', appconfig::GetDummy());
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function listhouding($schooljaar, $schoolid, $klas_in, $rap_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();


    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    //print $schoolid.' '.$klas_in.' '.$rap_in.' '.$sort_order;

    // Changes settings (ladalan@caribedev)

    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex,
    h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding h ON s.id = h.studentid
    WHERE s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ?
    ORDER BY ";

    $sql_order = " s.lastname , s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas_in, $rap_in, $schoolid, $schooljaar)) {
          if ($select->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'list houding', appconfig::GetDummy());
            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);


            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" style=\"overflow-x:auto\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  array_push($houding_name, $v);
                  $index_colum++;
                }
                $htmlcontrol .=  "</tr>";
              }

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";



                for ($y = 1; $y <= $index_colum; $y++) {
                  $htmlcontrol .= "<td class=\"text-center\">";

                  if (($s->_setting_rapnumber_1 && $rap_in == 1 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_2 && $rap_in == 2 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_3 && $rap_in == 3 && $_SESSION["UserRights"] != "ASSISTENT")) {
                    $htmlcontrol .= "<select id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h$y\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\" class=\"form-control editable-select\" data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: " . $houding_name[$y - 1] . "\"  style=\"width: 60px\">";
                  }

                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;


                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if (($s->_setting_rapnumber_1 && $rap_in == 1 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_2 && $rap_in == 2 && $_SESSION["UserRights"] != "ASSISTENT") || ($s->_setting_rapnumber_3 && $rap_in == 3 && $_SESSION["UserRights"] != "ASSISTENT")) {
                    // Edit mode

                    if ($y == 9) {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">A</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">B</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">C</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">D</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">E</option><option value=\"6\"" . ($_houding_number == 6 ? "selected" : "") . ">F</option><option value=\"7\"" . ($_houding_number == 7 ? "selected" : "") . ">G</option><option value=\"8\"" . ($_houding_number == 8 ? "selected" : "") . ">H</option></select></td> ";
                    } else if (is_null($_houding_number) || $_houding_number == 0) {
                      /*
                      no data in table for this houding
                      use the goed default
                      */

                      $htmlcontrol .= "<option value=\"1\" selected>A</option>
                      <option value=\"2\">B</option>
                      <option value=\"3\">C</option>
                      <option value=\"4\">D</option>
                      <option value=\"5\">E</option>
                      <option value=\"6\">F</option>
                      </select>
                      </td> ";
                    } else {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">A</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">B</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">C</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">D</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">E</option><option value=\"6\"" . ($_houding_number == 6 ? "selected" : "") . ">F</option></select></td> ";
                    }
                  } else {

                    if (!is_null($_houding_number) || $_houding_number != 0 || $_houding_number != "") {
                      // View mode
                      switch ($_houding_number) {
                        case 1: {
                            $htmlcontrol .= "<span align=\"center\" >A</span>";
                            break;
                          }
                        case 2: {
                            $htmlcontrol .= "<span align=\"center\" >B</span>";
                            break;
                          }
                        case 3: {
                            $htmlcontrol .= "<span align=\"center\" >C</span>";
                            break;
                          }
                        case 4: {
                            $htmlcontrol .= "<span align=\"center\" >D</span>";
                            break;
                          }
                        case 5: {
                            $htmlcontrol .= "<span align=\"center\" >E</span>";
                            break;
                          }
                        case 6: {
                            $htmlcontrol .= "<span align=\"center\" >F</span>";
                            break;
                          }
                      }
                    } else {
                      $htmlcontrol .= "<span align=\"center\" >A</span>";
                    }
                    $htmlcontrol .= "</td>";
                  }

                  $xx++;
                }

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listhoudingbystudent($schooljaar, $studentid)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";

    $result = 0;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sp_get_cijfers_by_student = "";
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("CALL sp_get_houding_by_student (?,?)")) {
        if ($stmt->bind_param("ss", $schooljaar, $studentid)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers

            $this->error = false;
            $result = 1;
            $stmt->bind_result($houdingid, $studentid, $schooljaar, $klas, $rapnummer, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th>Schooljaar</th><th>Klas</th><th>Rapnr</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                // $htmlcontrol .=  "<tr><th>LUIS</th>";
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  //array_push($houding_name,$v);
                  $index_colum++;
                }
                $htmlcontrol .=  "</tr>";
              }

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;
              //print $select->num_rows;

              while ($stmt->fetch()) {


                $htmlcontrol .= "<tr><td>$houdingid</td><td>$schooljaar</td><td>$klas</td><td>$rapnummer</td>";
                $level_klas = substr($klas, 0, 1);



                for ($y = 1; $y <= $index_colum; $y++) {
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $htmlcontrol .= "<td>";
                      if ($h1 == 1 || $h1 == 0 || is_null($h1)) {

                        // $c1=($c1 == 0.0 ? "" : $c1 );
                        $htmlcontrol .= "A";
                      } elseif ($h1 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h1 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h1 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h1 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h1 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h1 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h1 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h1 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 2:
                      $htmlcontrol .= "<td>";
                      if ($h2 == 1 || $h2 == 0 || is_null($h2)) {
                        $htmlcontrol .= "A";
                      } elseif ($h2 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h2 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h2 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h2 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h2 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h2 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h2 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h2 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 3:
                      $htmlcontrol .= "<td>";
                      if ($h3 == 1 || $h3 == 0 || is_null($h3)) {
                        $htmlcontrol .= "A";
                      } elseif ($h3 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h3 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h3 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h3 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h3 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h3 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h3 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h3 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 4:
                      $htmlcontrol .= "<td>";
                      if ($h4 == 1 || $h4 == 0 || is_null($h4)) {
                        $htmlcontrol .= "A";
                      } elseif ($h4 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h4 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h4 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h4 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h4 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h4 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h4 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h4 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 5:
                      $htmlcontrol .= "<td>";
                      if ($h5 == 1 || $h5 == 0 || is_null($h5)) {
                        $htmlcontrol .= "A";
                      } elseif ($h5 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h5 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h5 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h5 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h5 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h5 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h5 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h5 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 6:
                      $htmlcontrol .= "<td>";
                      if ($h6 == 1 || $h6 == 0 || is_null($h6)) {
                        $htmlcontrol .= "A";
                      } elseif ($h6 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h6 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h6 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h6 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h6 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h6 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h6 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h6 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;

                    case 7:
                      $htmlcontrol .= "<td>";
                      if ($h7 == 1 || $h7 == 0 || is_null($h7)) {
                        $htmlcontrol .= "A";
                      } elseif ($h7 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h7 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h7 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h7 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h7 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h7 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h7 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h7 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 8:
                      $htmlcontrol .= "<td>";
                      if ($h8 == 1 || $h8 == 0 || is_null($h8)) {
                        $htmlcontrol .= "A";
                      } elseif ($h8 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h8 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h8 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h8 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h8 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h8 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h8 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h8 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 9:
                      $htmlcontrol .= "<td>";
                      if ($h9 == 1 || $h9 == 0 || is_null($h9)) {
                        $htmlcontrol .= "A";
                      } elseif ($h9 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h9 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h9 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h9 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h9 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h9 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h9 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h9 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 10:
                      $htmlcontrol .= "<td>";
                      if ($h10 == 1 || $h10 == 0 || is_null($h10)) {
                        $htmlcontrol .= "A";
                      } elseif ($h10 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h10 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h10 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h10 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h10 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h10 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h10 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h10 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 11:
                      $htmlcontrol .= "<td>";
                      if ($h11 == 1 || $h11 == 0 || is_null($h11)) {
                        $htmlcontrol .= "A";
                      } elseif ($h11 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h11 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h11 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h11 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h11 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h11 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h11 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h11 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 12:
                      $htmlcontrol .= "<td>";
                      if ($h12 == 1 || $h12 == 0 || is_null($h12)) {
                        $htmlcontrol .= "A";
                      } elseif ($h12 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h12 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h12 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h12 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h12 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h12 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h12 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h12 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 13:
                      $htmlcontrol .= "<td>";
                      if ($h13 == 1 || $h13 == 0 || is_null($h13)) {
                        $htmlcontrol .= "A";
                      } elseif ($h13 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h13 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h13 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h13 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h13 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h13 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h13 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h13 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 14:
                      $htmlcontrol .= "<td>";
                      if ($h14 == 1 || $h14 == 0 || is_null($h14)) {
                        $htmlcontrol .= "A";
                      } elseif ($h14 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h14 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h14 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h14 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h14 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h14 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h14 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h14 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 15:
                      $htmlcontrol .= "<td>";
                      if ($h15 == 1 || $h15 == 0 || is_null($h15)) {
                        $htmlcontrol .= "A";
                      } elseif ($h15 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h15 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h15 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h15 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h15 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h15 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h15 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h15 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 16:
                      $htmlcontrol .= "<td>";
                      if ($h16 == 1 || $h16 == 0 || is_null($h16)) {
                        $htmlcontrol .= "A";
                      } elseif ($h16 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h16 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h16 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h16 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h16 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h16 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h16 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h16 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 17:
                      $htmlcontrol .= "<td>";
                      if ($h17 == 1 || $h17 == 0 || is_null($h17)) {
                        $htmlcontrol .= "A";
                      } elseif ($h17 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h17 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h17 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h17 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h17 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h17 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h17 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h17 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 18:
                      $htmlcontrol .= "<td>";
                      if ($h18 == 1 || $h18 == 0 || is_null($h18)) {
                        $htmlcontrol .= "A";
                      } elseif ($h18 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h18 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h18 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h18 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h18 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h18 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h18 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h18 == 12) {
                        $htmlcontrol .= "I";
                      }
                    case 19:
                      $htmlcontrol .= "<td>";
                      if ($h19 == 1 || $h19 == 0 || is_null($h19)) {
                        $htmlcontrol .= "A";
                      } elseif ($h19 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h19 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h19 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h19 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h19 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h19 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h19 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h19 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 20:
                      $htmlcontrol .= "<td>";
                      if ($h20 == 1 || $h20 == 0 || is_null($h20)) {
                        $htmlcontrol .= "A";
                      } elseif ($h20 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h20 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h20 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h20 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h20 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h20 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h20 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h20 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 21:
                      $htmlcontrol .= "<td>";
                      if ($h21 == 1 || $h21 == 0 || is_null($h21)) {
                        $htmlcontrol .= "A";
                      } elseif ($h21 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h21 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h21 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h21 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h21 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h21 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h21 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h21 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 22:
                      $htmlcontrol .= "<td>";
                      if ($h22 == 1 || $h22 == 0 || is_null($h22)) {
                        $htmlcontrol .= "A";
                      } elseif ($h22 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h22 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h22 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h22 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h22 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h22 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h22 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h22 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 23:
                      $htmlcontrol .= "<td>";
                      if ($h23 == 1 || $h23 == 0 || is_null($h23)) {
                        $htmlcontrol .= "A";
                      } elseif ($h23 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h23 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h23 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h23 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h23 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h23 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h23 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h23 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 24:
                      $htmlcontrol .= "<td>";
                      if ($h24 == 1 || $h24 == 0 || is_null($h15)) {
                        $htmlcontrol .= "A";
                      } elseif ($h24 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h24 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h24 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h24 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h24 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h24 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h24 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h24 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 25:
                      $htmlcontrol .= "<td>";
                      if ($h25 == 1 || $h25 == 0 || is_null($h25)) {
                        $htmlcontrol .= "A";
                      } elseif ($h25 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h25 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h25 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h25 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h25 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h25 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h25 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h25 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    default:
                      break;
                  }


                  $htmlcontrol .= "</td>";
                }
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
            $result = $this->mysqlierror;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $result = $this->mysqlierror;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
        $result = $this->mysqlierror;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();
    }
    return $htmlcontrol;
  }

  function savehouding($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in)
  {

    $houding_count = $this->_gethoudingcount("", $studentid_in, $klas_in, $rap_in);
    if ($houding_count == 1) {
      //echo "entre al if de == 1";
      /* TODO: Validate  */
      if ($houding_number_in == "h1" || $houding_number_in == "h2" || $houding_number_in == "h3" ||  $houding_number_in == "h4" || $houding_number_in == "h5" || $houding_number_in == "h6" || $houding_number_in == "h7" || $houding_number_in == "h8" || $houding_number_in == "h9" || $houding_number_in == "h10" || $houding_number_in == "h11" || $houding_number_in == "h12" || $houding_number_in == "h13" || $houding_number_in == "h14" || $houding_number_in == "h15"); {
        //echo "entre de update";

        return $this->_updatehouding($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in);
      }
    } else {

      /* TODO: Validate  */
      if ($houding_number_in == "h1" || $houding_number_in == "h2" || $houding_number_in == "h3" ||  $houding_number_in == "h4" || $houding_number_in == "h5" || $houding_number_in == "h6" || $houding_number_in == "h7" || $houding_number_in == "h8" || $houding_number_in == "h9" || $houding_number_in == "h10" || $houding_number_in == "h11" || $houding_number_in == "h12" || $houding_number_in == "h13" || $houding_number_in == "h14" || $houding_number_in == "h15"); {


        //return $this->_inserthouding($schooljaar,$schoolid,$studentid_in,$houding_number_in,$houding_value_in,$klas_in,$rap_in);
        return $this->_updatehouding($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in);
      }
    }
  }

  function _gethoudingcount($schoolid, $studentid_in, $klas_in, $rap_in)
  {
    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select h.id from le_houding h where h.studentid = ? and h.rapnummer = ?";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("ss", $studentid_in, $rap_in)) {
          if ($select->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'read houding count', appconfig::GetDummy());
            $this->error = false;
            $result = 1;

            $select->store_result();

            $returnvalue = $select->num_rows;
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function _updatehouding($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in)
  {

    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    if ($_SESSION['SchoolType'] >= 2) {
      $this->tablename_houding  = 'le_houding_hs';
    } else {
      $this->tablename_houding  = 'le_houding';
    }


    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      //update le_houding h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1

      $sql_query = "update" . chr(32) . $this->tablename_houding . chr(32) . "h join students s on h.studentid = s.id set" . chr(32) . "  h." . $houding_number_in . chr(32) . " = ? where s.id = ? and h.rapnummer = ? and h.id = ?;";

      if ($stmt = $mysqli->prepare($sql_query)) {

        if ($stmt->bind_param("sssi", $houding_value_in, $studentid_in, $rap_in, $houding_id)) {

          if ($stmt->execute()) {
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
      $this->exceptionvalue = $e->getMessage();
    }


    return $result;
  }

  function _inserthouding($schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in)
  {

    //$schooljaar_temp = "2016-2017";
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
      //update le_houding h inner join students s on h.studentid = s.id set h.h1 = 3 where s.id = 24 and s.class = "1A" and h.rapnummer = 1
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("insert into" . chr(32) . $this->tablename_houding . chr(32) . "(studentid, created, schooljaar, rapnummer, klas, user," . chr(32) . $houding_number_in . ") values (?,?,?,?,?,?,?);")) {
        if ($stmt->bind_param("sssssss", $studentid_in, $_DateTime, $schooljaar, $rap_in, $klas_in, $user_temp, $houding_value_in)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'insert houding', appconfig::GetDummy());
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
      $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }

  //Caribe Dev
  function create_le_houding_student($schoolID, $rapnummer, $klass, $schooljaar)
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
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("CALL sp_create_le_houding_student (?,?,?,?)")) {
        if ($stmt->bind_param("ssss", $schoolID, $rapnummer, $klass, $schooljaar)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'create houding student', appconfig::GetDummy());
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            //$result = 1;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " . $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " . $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        echo "# " . $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }


  function listhouding_skoa($schooljaar, $schoolid, $klas_in, $rap_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    //print $schoolid.' '.$klas_in.' '.$rap_in.' '.$sort_order;

    // Changes settings (ladalan@caribedev)

    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname,
    s.sex, h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding h ON s.id = h.studentid WHERE s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ?
    ORDER BY ";

    $sql_order = " s.lastname , s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas_in, $rap_in, $schoolid, $schooljaar)) {
          if ($select->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'list houding', appconfig::GetDummy());
            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);


            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/ps_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\" style=\"overflow-x:auto\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                // $htmlcontrol .=  "<tr><th>LUIS</th>";
                foreach ($values as $k => $v) {
                  if ($k != "h12" && $k != "h13") {
                    if ($sort_order == 1) {
                      $v = substr($v, 0, 5);
                    }
                    $columns[$k] = $k;
                    array_push($houding_name, $v);
                    switch ($k) {
                      case "h1":
                        $v = "CD";
                        break;
                      case "h2":
                        $v = "CL";
                        break;
                      case "h3":
                        $v = "SG";
                        break;
                      case "h4":
                        $v = "ZN";
                        break;
                      case "h5":
                        $v = "ND";
                        break;
                      case "h6":
                        $v = "DN";
                        break;
                      case "h7":
                        $v = "ZD";
                        break;
                      case "h8":
                        $v = "WO";
                        break;
                      case "h9":
                        $v = "WG";
                        break;
                      case "h10":
                        $v = "CE";
                        break;
                      case "h11":
                        $v = "HK";
                        break;
                      case "h14":
                        $v = "ZU";
                        break;
                    }
                    $htmlcontrol .=  "<th>$v</th>";
                    $index_colum++;
                  }
                }
                $htmlcontrol .=  "</tr>";
              }

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";



                for ($y = 1; $y <= $index_colum; $y++) {
                  $y = $y == 12 ? 14 : $y;
                  $htmlcontrol .= "<td class=\"text-center\">";

                  if (($rap_in == 1) || ($rap_in == 2) || ($rap_in == 3)) {
                    $htmlcontrol .= "<select id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h$y\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\" class=\"form-control editable-select\" data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: " . $houding_name[$y - 1] . "\"  style=\"width: 60px\">";
                  }

                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;



                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if (($rap_in == 1) || ($rap_in == 2) || ($rap_in == 3)) {
                    // Edit mode
                    if ($y == 14) {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">A</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">B</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">C</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">D</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">E</option><option value=\"6\"" . ($_houding_number == 6 ? "selected" : "") . ">F</option><option value=\"7\"" . ($_houding_number == 7 ? "selected" : "") . ">G</option><option value=\"8\"" . ($_houding_number == 8 ? "selected" : "") . ">H</option></select></td> ";
                    } else if (is_null($_houding_number) || $_houding_number == 0) {
                      /*
                      no data in table for this houding
                      use the goed default
                      */

                      $htmlcontrol .= "<option value=\"1\" selected>A</option>
                      <option value=\"2\">B</option>
                      <option value=\"3\">C</option>
                      <option value=\"4\">D</option>
                      <option value=\"5\">E</option>
                      <option value=\"5\">F</option>
                      </select>
                      </td> ";
                    } else {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">A</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">B</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">C</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">D</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">E</option><option value=\"6\"" . ($_houding_number == 6 ? "selected" : "") . ">F</option></select></td> ";
                    }
                  } else {
                    if (!is_null($_houding_number) || $_houding_number != 0) {
                      // View mode
                      switch ($_houding_number) {
                        case 1: {
                            break;
                            $htmlcontrol .= "<span align=\"center\" >A</span>";
                          }
                        case 2: {
                            $htmlcontrol .= "<span align=\"center\" >B</span>";
                            break;
                          }
                        case 3: {
                            $htmlcontrol .= "<span align=\"center\" >C</span>";
                            break;
                          }
                        case 4: {
                            $htmlcontrol .= "<span align=\"center\" >D</span>";
                            break;
                          }
                        case 5: {
                            $htmlcontrol .= "<span align=\"center\" >E</span>";
                            break;
                          }
                        case 6: {
                            $htmlcontrol .= "<span align=\"center\" >F</span>";
                            break;
                          }
                      }

                      $htmlcontrol .= "</td>";
                    } else {
                      $htmlcontrol .= "";
                    }
                  }

                  $xx++;
                }

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listhouding_school_8_klas_5($schooljaar, $schoolid, $klas_in, $rap_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();

    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    //print $schoolid.' '.$klas_in.' '.$rap_in.' '.$sort_order;

    // Changes settings (ladalan@caribedev)

    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex, h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding h ON s.id = h.studentid WHERE s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ?
    ORDER BY ";

    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas_in, $rap_in, $schoolid, $schooljaar)) {
          if ($select->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'list houding', appconfig::GetDummy());
            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);


            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config_klas1-5.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\" style=\"overflow-x:auto\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                // $htmlcontrol .=  "<tr><th>LUIS</th>";
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  array_push($houding_name, $v);
                  $index_colum++;
                }
                $htmlcontrol .=  "</tr>";
              }

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";



                for ($y = 1; $y <= $index_colum; $y++) {
                  $htmlcontrol .= "<td class=\"text-center\">";

                  if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                    $htmlcontrol .= "<select id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h$y\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\" class=\"form-control editable-select\" data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: " . $houding_name[$y - 1] . "\"  style=\"width: 60px\">";
                  }

                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;



                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if (($s->_setting_rapnumber_1 && $rap_in == 1) || ($s->_setting_rapnumber_2 && $rap_in == 2) || ($s->_setting_rapnumber_3 && $rap_in == 3)) {
                    // Edit mode

                    if (is_null($_houding_number) || $_houding_number == 0) {
                      /*
                      no data in table for this houding
                      use the goed default
                      */

                      $htmlcontrol .= "<option value=\"10\" selected>B</option>
                      <option value=\"11\">S</option>
                      <option value=\"12\">I</option>
                      </select>
                      </td> ";
                    } else {
                      $htmlcontrol .= "<option value=\"10\"" . ($_houding_number == 10 ? "selected" : "") . ">B</option><option value=\"11\"" . ($_houding_number == 11 ? "selected" : "") . ">S</option><option value=\"12\"" . ($_houding_number == 12 ? "selected" : "") . ">I</option></select></td> ";
                    }
                  } else {
                    if (!is_null($_houding_number) || $_houding_number != 0) {
                      // View mode
                      switch ($_houding_number) {
                        case 1: {
                            break;
                            $htmlcontrol .= "<span align=\"center\" >B</span>";
                          }
                        case 2: {
                            $htmlcontrol .= "<span align=\"center\" >S</span>";
                            break;
                          }
                        case 3: {
                            $htmlcontrol .= "<span align=\"center\" >I</span>";
                            break;
                          }
                      }

                      $htmlcontrol .= "</td>";
                    } else {
                      $htmlcontrol .= "";
                    }
                  }

                  $xx++;
                }

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listhouding_tutor($schooljaar, $schoolid, $klas_in, $rap_in, $sort_order)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex, h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,";
    $sql_query .= " h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25 ";
    $sql_query .= " FROM students s LEFT JOIN le_houding h ON s.id = h.studentid WHERE s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ? ORDER BY ";

    $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas_in, $rap_in, $schoolid, $schooljaar)) {
          if ($select->execute()) {
            // Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $UserGUID = $_SESSION['UserGUID'];
            $spn_audit->create_audit($UserGUID, 'houding', 'list houding', appconfig::GetDummy());
            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);

            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" style=\"overflow-x:auto\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              $columns = array();
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                // $htmlcontrol .=  "<tr><th>LUIS</th>";
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  array_push($houding_name, $v);
                  $index_colum++;
                }
                $htmlcontrol .=  "</tr>";
              }
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";
              $x = 1;
              $xx = 1;
              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";
                for ($y = 1; $y <= $index_colum; $y++) {
                  $htmlcontrol .= "<td class=\"text-center\">";
                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;


                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if (!is_null($_houding_number) || $_houding_number != 0 || $_houding_number != "") {
                    // View mode
                    switch ($_houding_number) {
                      case 1: {
                          $htmlcontrol .= "<span align=\"center\" >A</span>";
                          break;
                        }
                      case 2: {
                          $htmlcontrol .= "<span align=\"center\" >B</span>";
                          break;
                        }
                      case 3: {
                          $htmlcontrol .= "<span align=\"center\" >C</span>";
                          break;
                        }
                      case 4: {
                          $htmlcontrol .= "<span align=\"center\" >D</span>";
                          break;
                        }
                      case 5: {
                          $htmlcontrol .= "<span align=\"center\" >E</span>";
                          break;
                        }
                      case 6: {
                          $htmlcontrol .= "<span align=\"center\" >F</span>";
                          break;
                        }
                    }
                  } else {
                    $htmlcontrol .= "<span align=\"center\" >A</span>";
                  }
                  $htmlcontrol .= "</td>";

                  $xx++;
                }

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listhouding_hs($schooljaar, $schoolid, $klas_in, $rap_in, $vak_id)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();


    mysqli_report(MYSQLI_REPORT_STRICT);

    // Changes settings (ladalan@caribedev)

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    // End change settings (laldana@caribedev)

    //print $schoolid.' '.$klas_in.' '.$rap_in.' '.$sort_order;

    // Changes settings (ladalan@caribedev)


    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex,
    h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding_hs h ON s.id = h.studentid
    WHERE s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ? and h.vakid= ?
    ORDER BY ";



    /*
    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex,
    h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding_hs h ON s.id = h.studentid
    WHERE h.created = (SELECT MAX(created) FROM le_houding_hs)
    AND s.class = ? AND h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ? and h.vakid= ?
    ORDER BY ";
    */

    $sql_order = " s.lastname, s.firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siisi", $klas_in, $rap_in, $schoolid, $schooljaar, $vak_id)) {
          if ($select->execute()) {
            // Audit by Caribe Developers

            $UserGUID = $_SESSION['UserGUID'];

            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);


            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" style=\"overflow-x:auto\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  array_push($houding_name, $v);
                  $index_colum++;
                }
              }

              $htmlcontrol .=  "</tr>";

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";



                for ($y = 1; $y <= 6; $y++) {
                  $htmlcontrol .= "<td class=\"text-center\">";

                  if ($rap_in > 0) {
                    $htmlcontrol .= "<select id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h$y\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\" class=\"form-control editable-select\" data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: " . $houding_name[$y - 1] . "\"  style=\"width: 60px\">";
                  }


                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;


                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if ($rap_in > 0) {
                    // Edit mode

                    if (is_null($_houding_number) || $_houding_number == 0) {
                      /*
                      no data in table for this houding
                      use the goed default
                      */

                      $htmlcontrol .= "<option value=\"\" selected></option>
                      <option value=\"1\" >1</option>
                      <option value=\"2\">2</option>
                      <option value=\"3\">3</option>
                      <option value=\"4\" selected>4</option>
                      <option value=\"5\" >5</option>
                      </select>
                      </td> ";
                    } else {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">1</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">2</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">3</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">4</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">5</option></select></td> ";
                    }
                  } else {

                    if (!is_null($_houding_number) || $_houding_number != 0 || $_houding_number != "") {
                      // View mode
                      // print('ENTRE EN VIEW MODEL');
                      switch ($_houding_number) {
                        case 1: {
                            $htmlcontrol .= "<span align=\"center\" >A</span>";
                            break;
                          }
                        case 2: {
                            $htmlcontrol .= "<span align=\"center\" >B</span>";
                            break;
                          }
                        case 3: {
                            $htmlcontrol .= "<span align=\"center\" >C</span>";
                            break;
                          }
                        case 4: {
                            $htmlcontrol .= "<span align=\"center\" >D</span>";
                            break;
                          }
                        case 5: {
                            $htmlcontrol .= "<span align=\"center\" >E</span>";
                            break;
                          }
                        case 6: {
                            $htmlcontrol .= "<span align=\"center\" >F</span>";
                            break;
                          }
                      }
                    } else {
                      $htmlcontrol .= "<span align=\"center\" >A</span>";
                    }
                    $htmlcontrol .= "</td>";
                  }

                  $xx++;
                }
                $htmlcontrol .= "<td width =\"300px\"><input id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h7\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\"  class = 'lblhouding form-control opmerking-input' type = 'text'   data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: Leerhouding toelichten\"  style = 'display:block' value='$h7'/></td>";
                $htmlcontrol .= "<td width =\"300px\"><input id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h8\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\"  class = 'lblhouding form-control opmerking-input' type = 'text'   data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: Gedrag toelichten\"  style = 'display:block' value='$h8'/></td>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function listhouding_hs_group($schooljaar, $schoolid, $klas_in, $rap_in, $vak_id, $klas_groep)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";
    $houding_name = array();

    require_once("DBCreds.php");
    require_once("spn_utils.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli->set_charset('utf8');

    $get_vak = "SELECT vak FROM groups WHERE id = $vak_id";
    $vak_result = $mysqli->query($get_vak);
    $result = $vak_result->fetch_assoc();

    $vak_row = $result['vak'];
    $name_row = substr($result['name'], 0, 2);
    $klas = $klas_in . $klas_groep;

    mysqli_report(MYSQLI_REPORT_STRICT);

    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);

    $sql_query = "SELECT s.id, h.id, s.class, s.firstname, s.lastname, s.sex,
    h.h1, h.h2, h.h3, h.h4, h.h5, h.h6, h.h7, h.h8, h.h9, h.h10, h.h11, h.h12, h.h13, h.h14, h.h15,
    h.h16,h.h17,h.h18,h.h19,h.h20,h.h21,h.h22,h.h23,h.h24,h.h25
    FROM students s LEFT JOIN le_houding_hs h ON s.id = h.studentid
    WHERE h.rapnummer = ? AND s.schoolid = ? and h.schooljaar = ? and h.vakid= ? and s.class like '$klas%'
    ORDER BY ";

    $sql_order = " s.lastname, s.firstname ";
    if ($s->_setting_mj) {
      $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }
    // End change settings (laldana@caribedev)

    try {
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("iisi", $rap_in, $schoolid, $schooljaar, $vak_row)) {
          if ($select->execute()) {
            // Audit by Caribe Developers

            $UserGUID = $_SESSION['UserGUID'];

            $this->error = false;
            $result = 1;
            $select->store_result();
            $select->bind_result($studentid, $houdingid, $klas, $firstname, $lastname, $sex, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);


            if ($select->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" style=\"overflow-x:auto\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  array_push($houding_name, $v);
                  $index_colum++;
                }
              }

              $htmlcontrol .=  "</tr>";

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;

              //print $select->num_rows;

              while ($select->fetch()) {
                $htmlcontrol .= "<tr><td>$x</td><td>" . $firstname . chr(32) . $lastname . "</td>";



                for ($y = 1; $y <= 6; $y++) {
                  $htmlcontrol .= "<td class=\"text-center\">";

                  if ($rap_in > 0) {
                    $htmlcontrol .= "<select id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h$y\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\" class=\"form-control editable-select\" data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: " . $houding_name[$y - 1] . "\"  style=\"width: 60px\">";
                  }


                  $_houding_number = 0;
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $_houding_number = $h1;
                      break;

                    case 2:
                      $_houding_number = $h2;
                      break;

                    case 3:
                      $_houding_number = $h3;
                      break;

                    case 4:
                      $_houding_number = $h4;
                      break;

                    case 5:
                      $_houding_number = $h5;
                      break;

                    case 6:
                      $_houding_number = $h6;
                      break;

                    case 7:
                      $_houding_number = $h7;
                      break;
                    case 8:
                      $_houding_number = $h8;
                      break;
                    case 9:
                      $_houding_number = $h9;
                      break;
                    case 10:
                      $_houding_number = $h10;
                      break;
                    case 11:
                      $_houding_number = $h11;
                      break;
                    case 12:
                      $_houding_number = $h12;
                      break;
                    case 13:
                      $_houding_number = $h13;
                      break;
                    case 14:
                      $_houding_number = $h14;
                      break;
                    case 15:
                      $_houding_number = $h15;
                      break;
                    case 16:
                      $_houding_number = $h16;
                      break;
                    case 17:
                      $_houding_number = $h17;
                      break;
                    case 18:
                      $_houding_number = $h18;
                      break;
                    case 19:
                      $_houding_number = $h19;
                      break;
                    case 20:
                      $_houding_number = $h20;
                      break;
                    case 21:
                      $_houding_number = $h21;
                      break;
                    case 22:
                      $_houding_number = $h22;
                      break;
                    case 23:
                      $_houding_number = $h23;
                      break;
                    case 24:
                      $_houding_number = $h24;
                      break;
                    case 25:
                      $_houding_number = $h25;
                      break;


                    default:
                      $_houding_number = 0;
                      break;
                  }

                  if ($rap_in > 0) {
                    // Edit mode

                    if (is_null($_houding_number) || $_houding_number == 0) {
                      /*
                      no data in table for this houding
                      use the goed default
                      */

                      $htmlcontrol .= "<option value=\"\" selected></option>
                      <option value=\"1\" >1</option>
                      <option value=\"2\">2</option>
                      <option value=\"3\">3</option>
                      <option value=\"4\" selected>4</option>
                      <option value=\"5\" >5</option>
                      </select>
                      </td> ";
                    } else {
                      $htmlcontrol .= "<option value=\"1\"" . ($_houding_number == 1 ? "selected" : "") . ">1</option><option value=\"2\"" . ($_houding_number == 2 ? "selected" : "") . ">2</option><option value=\"3\"" . ($_houding_number == 3 ? "selected" : "") . ">3</option><option value=\"4\"" . ($_houding_number == 4 ? "selected" : "") . ">4</option><option value=\"5\"" . ($_houding_number == 5 ? "selected" : "") . ">5</option></select></td> ";
                    }
                  } else {

                    if (!is_null($_houding_number) || $_houding_number != 0 || $_houding_number != "") {
                      // View mode
                      // print('ENTRE EN VIEW MODEL');
                      switch ($_houding_number) {
                        case 1: {
                            $htmlcontrol .= "<span align=\"center\" >A</span>";
                            break;
                          }
                        case 2: {
                            $htmlcontrol .= "<span align=\"center\" >B</span>";
                            break;
                          }
                        case 3: {
                            $htmlcontrol .= "<span align=\"center\" >C</span>";
                            break;
                          }
                        case 4: {
                            $htmlcontrol .= "<span align=\"center\" >D</span>";
                            break;
                          }
                        case 5: {
                            $htmlcontrol .= "<span align=\"center\" >E</span>";
                            break;
                          }
                        case 6: {
                            $htmlcontrol .= "<span align=\"center\" >F</span>";
                            break;
                          }
                      }
                    } else {
                      $htmlcontrol .= "<span align=\"center\" >A</span>";
                    }
                    $htmlcontrol .= "</td>";
                  }

                  $xx++;
                }
                $htmlcontrol .= "<td width =\"300px\"><input id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h7\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\"  class = 'lblhouding form-control opmerking-input' type = 'text'   data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: Leerhouding toelichten\"  style = 'display:block' value='$h7'/></td>";
                $htmlcontrol .= "<td width =\"300px\"><input id=\"lblName$xx\" id_houding_table=\"$houdingid\"data-student-id=\"$studentid\" data-houding=\"h8\" data-klas=\"$klas_in\" data-rapport=\"$rap_in\"  class = 'lblhouding form-control opmerking-input' type = 'text'   data-toggle=\"tooltip\"  title=\"Leerling: " . $firstname . chr(32) . $lastname . chr(13) . "Houding: Gedrag toelichten\"  style = 'display:block' value='$h8'/></td>";

                /* increment variable with one */
                $x++;
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;

        if ($this->debug) {
          print "error preparing query";
        }
      }

      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }

    return $returnvalue;
  }

  function create_le_houding_student_hs($schoolID, $rapnummer, $klass, $schooljaar, $vak_id)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      echo $schoolID;
      echo $rapnummer;
      echo $klass;
      echo $schooljaar;
      echo $vak_id;
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("CALL sp_create_le_houding_student_hs (?,?,?,?,?)")) {
        if ($stmt->bind_param("ssssi", $schoolID, $rapnummer, $klass, $schooljaar, $vak_id)) {
          if ($stmt->execute()) {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            //$result = 1;

            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            echo $this->mysqlierror = $mysqli->error;
            echo "# " . $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          echo $this->mysqlierror = $mysqli->error;
          echo "# " . $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        echo $this->mysqlierror = $mysqli->error;
        echo "# " . $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function create_le_houding_student_hs_group($schoolID, $rapnummer, $klass, $schooljaar, $group)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      echo $schoolID;
      echo $rapnummer;
      echo $klass;
      echo $schooljaar;
      echo $group;
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sql = "SELECT s.id,s.class,gr.vak FROM students s INNER JOIN group_student g ON s.id = g.student_id INNER JOIN groups gr ON g.group_id = gr.id WHERE s.schoolid = '$schoolID' AND g.group_id = $group AND g.schooljaar = '$schooljaar';";
      $consult = mysqli_query($mysqli, $sql);
      if ($consult->num_rows > 0) {
        while ($row = $consult->fetch_assoc()) {
          $id = $row['id'];
          $klas = $row['class'];
          $vakid = $row['vak'];
          $sql1 = "SELECT lc.studentid
                    FROM 
                      le_houding_hs lc 
                    INNER JOIN 
                      students s on lc.studentid = s.id 
                    WHERE s.schoolid = '$schoolID'
                    AND lc.studentid = '$id'
                    AND lc.schooljaar = '$schooljaar'
                    AND lc.rapnummer = '$rapnummer'
                    AND lc.klas = '$klas' AND lc.vakid = '$vakid';";
          $consulta = mysqli_query($mysqli, $sql1);
          if ($consulta->num_rows == 0) {
            $sqlI = "INSERT INTO
        le_houding_hs
        (`studentid`,`lastchanged`,`created`,`schooljaar`,`rapnummer`,`vakid`,`klas`,`user`) values ($id,null,now(),'$schooljaar','$rapnummer','$vakid','$klas','SPN')";
            $resultado123 = mysqli_query($mysqli, $sqlI);
            if (!$resultado123) {
              $mysqli->close();
              $result = $sql1;
            }
          } else {
            $result = 0;
          }
        }
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function savehouding_hs($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in)
  {
    return $this->_updatehouding($houding_id, $schooljaar, $schoolid, $studentid_in, $houding_number_in, $houding_value_in, $klas_in, $rap_in);
  }
  function listhoudingbystudent_hs($schooljaar, $studentid)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $index_colum = 0;
    $json_name_config = "";

    $result = 0;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sp_get_cijfers_by_student = "";
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("CALL sp_get_houding_by_student_hs (?,?)")) {
        if ($stmt->bind_param("ss", $schooljaar, $studentid)) {
          if ($stmt->execute()) {
            // Audit by Caribe Developers

            $this->error = false;
            $result = 1;
            $stmt->bind_result($volledigenaamvak, $houdingid, $studentid, $schooljaar, $klas, $rapnummer, $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9, $h10, $h11, $h12, $h13, $h14, $h15, $h16, $h17, $h18, $h19, $h20, $h21, $h22, $h23, $h24, $h25);
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
              $json_name_config = appconfig::GetBaseURL() . "/assets/js/" . $_SESSION["SchoolID"] . "_houding_config.json";

              $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              $htmlcontrol .= "<thead>";
              $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th>Schooljaar</th><th>Vak</th><th>Klas</th><th>Rapnr</th>";

              //$json_string =file_get_contents($json_name_config, TRUE);
              //$data = json_decode($json_string);
              $columns = array();

              $ch = curl_init();
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_URL, $json_name_config);
              $result = curl_exec($ch);
              curl_close($ch);

              $data = json_decode($result);

              foreach ($data as $name => $values) {
                // $htmlcontrol .=  "<tr><th>LUIS</th>";
                foreach ($values as $k => $v) {

                  $htmlcontrol .=  "<th>$v</th>";
                  $columns[$k] = $k;
                  //array_push($houding_name,$v);
                  $index_colum++;
                }
                $htmlcontrol .=  "</tr>";
              }

              // $htmlcontrol .= "<table id=\"dataRequest-houding\" class=\"table table-bordered table-colored table-houding\" data-table=\"yes\">";
              // $htmlcontrol .= "<thead>";
              // $htmlcontrol .= "<tr class=\"text-align-center\"><th>ID</th><th class=\"btn-m-w\">Naam</th><th>Gedrag</th><th>Concentratie</th><th>Werkverzorging</th><th>Motivatie</th><th>Godsdient</th><th>Huiswerk</th><th>Expr</th></tr>";
              $htmlcontrol .= "</thead>";
              $htmlcontrol .= "<tbody>";


              /* initialize counter variable, this variable is used to display student count number */
              $x = 1;

              /* initialize counter variable, this variable is used to set the data-houding attribute */
              $xx = 1;
              //print $select->num_rows;

              while ($stmt->fetch()) {


                $htmlcontrol .= "<tr><td>$houdingid</td><td>$schooljaar</td><td>$volledigenaamvak</td><td>$klas</td><td>$rapnummer</td>";
                $level_klas = substr($klas, 0, 1);



                for ($y = 1; $y <= $index_colum; $y++) {
                  /* check dimension of the dropdowns */
                  switch ($y) {
                    case 1:
                      $htmlcontrol .= "<td>";
                      if ($h1 == 1) {
                        // $c1=($c1 == 0.0 ? "" : $c1 );
                        $htmlcontrol .= "1";
                      } elseif ($h1 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h1 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h1 == 4 || $h1 == 0 || is_null($h1)) {
                        $htmlcontrol .= "4";
                      } elseif ($h1 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h1 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h1 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h1 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h1 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 2:
                      $htmlcontrol .= "<td>";
                      if ($h2 == 1) {
                        $htmlcontrol .= "1";
                      } elseif ($h2 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h2 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h2 == 4  || $h2 == 0 || is_null($h2)) {
                        $htmlcontrol .= "4";
                      } elseif ($h2 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h2 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h2 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h2 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h2 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 3:
                      $htmlcontrol .= "<td>";
                      if ($h3 == 1) {
                        $htmlcontrol .= "1";
                      } elseif ($h3 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h3 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h3 == 4 || $h3 == 0 || is_null($h3)) {
                        $htmlcontrol .= "4";
                      } elseif ($h3 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h3 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h3 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h3 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h3 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 4:
                      $htmlcontrol .= "<td>";
                      if ($h4 == 1) {
                        $htmlcontrol .= "4";
                      } elseif ($h4 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h4 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h4 == 4 || $h4 == 0 || is_null($h4)) {
                        $htmlcontrol .= "4";
                      } elseif ($h4 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h4 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h4 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h4 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h4 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 5:
                      $htmlcontrol .= "<td>";
                      if ($h5 == 1) {
                        $htmlcontrol .= "1";
                      } elseif ($h5 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h5 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h5 == 4 || $h5 == 0 || is_null($h5)) {
                        $htmlcontrol .= "4";
                      } elseif ($h5 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h5 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h5 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h5 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h5 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 6:
                      $htmlcontrol .= "<td>";
                      if ($h6 == 1) {
                        $htmlcontrol .= "1";
                      } elseif ($h6 == 2) {
                        $htmlcontrol .= "2";
                      } elseif ($h6 == 3) {
                        $htmlcontrol .= "3";
                      } elseif ($h6 == 4 || $h6 == 0 || is_null($h6)) {
                        $htmlcontrol .= "4";
                      } elseif ($h6 == 5) {
                        $htmlcontrol .= "5";
                      } elseif ($h6 == 6) {
                        $htmlcontrol .= "6";
                      } elseif ($h6 == 10) {
                        $htmlcontrol .= "7";
                      } elseif ($h6 == 11) {
                        $htmlcontrol .= "8";
                      } elseif ($h6 == 12) {
                        $htmlcontrol .= "9";
                      }
                      break;

                    case 7:
                      $htmlcontrol .= "<td>";
                      $htmlcontrol .= $h7;
                      break;
                    case 8:
                      $htmlcontrol .= "<td>";
                      $htmlcontrol .= $h8;

                      break;
                    case 9:
                      $htmlcontrol .= "<td>";
                      if ($h9 == 1 || $h9 == 0 || is_null($h9)) {
                        $htmlcontrol .= "A";
                      } elseif ($h9 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h9 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h9 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h9 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h9 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h9 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h9 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h9 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 10:
                      $htmlcontrol .= "<td>";
                      if ($h10 == 1 || $h10 == 0 || is_null($h10)) {
                        $htmlcontrol .= "A";
                      } elseif ($h10 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h10 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h10 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h10 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h10 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h10 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h10 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h10 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 11:
                      $htmlcontrol .= "<td>";
                      if ($h11 == 1 || $h11 == 0 || is_null($h11)) {
                        $htmlcontrol .= "A";
                      } elseif ($h11 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h11 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h11 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h11 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h11 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h11 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h11 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h11 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 12:
                      $htmlcontrol .= "<td>";
                      if ($h12 == 1 || $h12 == 0 || is_null($h12)) {
                        $htmlcontrol .= "A";
                      } elseif ($h12 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h12 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h12 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h12 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h12 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h12 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h12 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h12 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 13:
                      $htmlcontrol .= "<td>";
                      if ($h13 == 1 || $h13 == 0 || is_null($h13)) {
                        $htmlcontrol .= "A";
                      } elseif ($h13 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h13 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h13 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h13 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h13 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h13 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h13 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h13 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 14:
                      $htmlcontrol .= "<td>";
                      if ($h14 == 1 || $h14 == 0 || is_null($h14)) {
                        $htmlcontrol .= "A";
                      } elseif ($h14 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h14 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h14 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h14 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h14 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h14 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h14 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h14 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 15:
                      $htmlcontrol .= "<td>";
                      if ($h15 == 1 || $h15 == 0 || is_null($h15)) {
                        $htmlcontrol .= "A";
                      } elseif ($h15 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h15 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h15 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h15 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h15 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h15 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h15 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h15 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 16:
                      $htmlcontrol .= "<td>";
                      if ($h16 == 1 || $h16 == 0 || is_null($h16)) {
                        $htmlcontrol .= "A";
                      } elseif ($h16 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h16 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h16 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h16 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h16 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h16 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h16 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h16 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 17:
                      $htmlcontrol .= "<td>";
                      if ($h17 == 1 || $h17 == 0 || is_null($h17)) {
                        $htmlcontrol .= "A";
                      } elseif ($h17 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h17 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h17 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h17 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h17 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h17 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h17 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h17 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 18:
                      $htmlcontrol .= "<td>";
                      if ($h18 == 1 || $h18 == 0 || is_null($h18)) {
                        $htmlcontrol .= "A";
                      } elseif ($h18 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h18 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h18 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h18 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h18 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h18 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h18 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h18 == 12) {
                        $htmlcontrol .= "I";
                      }
                    case 19:
                      $htmlcontrol .= "<td>";
                      if ($h19 == 1 || $h19 == 0 || is_null($h19)) {
                        $htmlcontrol .= "A";
                      } elseif ($h19 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h19 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h19 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h19 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h19 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h19 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h19 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h19 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 20:
                      $htmlcontrol .= "<td>";
                      if ($h20 == 1 || $h20 == 0 || is_null($h20)) {
                        $htmlcontrol .= "A";
                      } elseif ($h20 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h20 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h20 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h20 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h20 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h20 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h20 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h20 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 21:
                      $htmlcontrol .= "<td>";
                      if ($h21 == 1 || $h21 == 0 || is_null($h21)) {
                        $htmlcontrol .= "A";
                      } elseif ($h21 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h21 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h21 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h21 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h21 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h21 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h21 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h21 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 22:
                      $htmlcontrol .= "<td>";
                      if ($h22 == 1 || $h22 == 0 || is_null($h22)) {
                        $htmlcontrol .= "A";
                      } elseif ($h22 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h22 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h22 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h22 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h22 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h22 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h22 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h22 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 23:
                      $htmlcontrol .= "<td>";
                      if ($h23 == 1 || $h23 == 0 || is_null($h23)) {
                        $htmlcontrol .= "A";
                      } elseif ($h23 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h23 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h23 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h23 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h23 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h23 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h23 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h23 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 24:
                      $htmlcontrol .= "<td>";
                      if ($h24 == 1 || $h24 == 0 || is_null($h15)) {
                        $htmlcontrol .= "A";
                      } elseif ($h24 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h24 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h24 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h24 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h24 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h24 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h24 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h24 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    case 25:
                      $htmlcontrol .= "<td>";
                      if ($h25 == 1 || $h25 == 0 || is_null($h25)) {
                        $htmlcontrol .= "A";
                      } elseif ($h25 == 2) {
                        $htmlcontrol .= "B";
                      } elseif ($h25 == 3) {
                        $htmlcontrol .= "C";
                      } elseif ($h25 == 4) {
                        $htmlcontrol .= "D";
                      } elseif ($h25 == 5) {
                        $htmlcontrol .= "E";
                      } elseif ($h25 == 6) {
                        $htmlcontrol .= "F";
                      } elseif ($h25 == 10) {
                        $htmlcontrol .= "B";
                      } elseif ($h25 == 11) {
                        $htmlcontrol .= "S";
                      } elseif ($h25 == 12) {
                        $htmlcontrol .= "I";
                      }
                      break;
                    default:
                      break;
                  }


                  $htmlcontrol .= "</td>";
                }
              }

              $htmlcontrol .= "</tbody>";
              $htmlcontrol .= "</table>";
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
            $result = $this->mysqlierror;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $result = $this->mysqlierror;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
        $result = $this->mysqlierror;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();
    }
    return $htmlcontrol;
  }
}
