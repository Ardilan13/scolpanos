<?php
require_once("spn_audit.php");
require_once("spn_setting.php");
require_once("spn_utils.php");
require_once("DBCreds.php");
class spn_see_kaart
{
  function list_cijfers_by_student_se_kaart_rapport($schooljaar, $studentid, $rapnummer, $klas, $profiel, $color, $dummy)
  {

    $returnvalue = "";

    $sql_query = "";
    $vrijstelling = ["e1" => 'ne', "e2" => 'en', "e3" => 'sp', "e4" => 'pa', "e5" => 'wi', "e6" => 'na', "e7" => 'sk', "e8" => 'bi', "e9" => 'ec', "e10" => 'ak', "e11" => 'gs', "e12" => 're'];
    $table = "";

    $gemiddelde = "";
    $gemiddelde2 = "";
    $gemiddelde3 = "";
    $total_gemiddele1 = 0;
    $total_gemiddele2 = 0;
    $total_gemiddele3 = 0;
    $total_gemiddele_average = 0;

    $gemiddelde_rap1 = 0;
    $gemiddelde_rap2 = 0;
    $gemiddelde_rap3 = 0;

    $average_divisor = 0;
    $is_ckv = false;
    $average = 0;
    $result = 0;
    $vak_code = "";

    $name_vak = "";

    ############################################

    $ckv_data = "";
    $_rap_out = "";
    $final_avg_CKV = 0;
    $ckv_gemm_array = [];

    $avg_rap1 = 0.0;
    $avg_rap2 = 0.0;
    $avg_rap3 = 0.0;
    $a = '';

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
        if ($_SESSION['SchoolID'] != 18) {
          $level_klas = substr($klas, 0, 1);
          $get_cijfers = $level_klas == 4 ? "sp_get_cijfers_by_student_see_kaart_groups" : "sp_get_cijfers_by_student_see_kaart";
          if ($stmt = $mysqli->prepare("CALL  " . $get_cijfers . " (?,?,?)")) {
            if ($stmt->bind_param("ssi", $schooljaar, $studentid, $rapnummer)) {
              if ($stmt->execute()) {
                $result = 1;
                if ($level_klas == 4) {
                  $stmt->bind_result($vak, $volledigenaamvak, $complete_name,  $gemmindele1, $gemmindele2, $gemmindele3, $po, $e1, $e2, $e3, $e4, $e5, $e6, $e7, $e8, $e9, $e10, $e11, $e12);
                } else {
                  $stmt->bind_result($vak, $volledigenaamvak, $complete_name,  $gemmindele1, $gemmindele2, $gemmindele3);
                }
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                  $table .= "<table align='center' cellpadding='1' cellspacing='1' class='table table-sm' style='border: 1px solid black;'>";
                  if ($rapnummer == 1) {

                    $table .= "<thead>";
                    $table .= "<th style='border-top: 1px solid black;'>Vakken</th>";
                    $table .= "<th style='border-top: 1px solid black;'>1</th>";
                    $table .= "<th style='border-top: 1px solid black;'>2</th>";
                    $table .= "<th style='border-top: 1px solid black;'>3</th>";
                    $table .= $level_klas == 4 ? "<th style='border-top: 1px solid black;'>GSE</th>" : "<th style='border-top: 1px solid black;'>Eind</th>";
                    $table .= "</thead>";
                  }
                  if ($rapnummer == 2) {

                    $table .= "<thead>";
                    $table .= "<th style='border-top: 1px solid black;'>Vakken</th>";
                    $table .= "<th style='border-top: 1px solid black;'>1</th>";
                    $table .= "<th style='border-top: 1px solid black;'>2</th>";
                    $table .= "<th style='border-top: 1px solid black;'>3</th>";
                    $table .= $level_klas == 4 ? "<th style='border-top: 1px solid black;'>GSE</th>" : "<th style='border-top: 1px solid black;'>Eind</th>";
                    $table .= "</thead>";
                  }
                  if ($rapnummer == 3) {

                    $table .= "<thead>";
                    $table .= "<th style='border-top: 1px solid black;'>Vakken</th>";
                    $table .= "<th style='border-top: 1px solid black;'>1</th>";
                    $table .= "<th style='border-top: 1px solid black;'>2</th>";
                    $table .= "<th style='border-top: 1px solid black;'>3</th>";
                    $table .= $level_klas == 4 ? "<th style='border-top: 1px solid black;'>GSE</th>" : "<th style='border-top: 1px solid black;'>Eind</th>";
                    $table .= "</thead>";
                  }

                  $table .= "<tbody>";
                  $i_array = 0;
                  while ($stmt->fetch()) {
                    $vak_code = strtoupper($volledigenaamvak);
                    $eba = array_search(strtolower($vak_code), $vrijstelling);
                    $pos = $$eba;
                    $table .= "<tr>";
                    if (strpos($complete_name, 'CKV')) {
                      $result_cvk_array = array();
                      if (!$is_ckv) {
                        // $table .= $this->list_cijfers_by_student_se_kaart_rapport_ckv($schooljaar, $studentid,$rapnummer, false);
                        $array_vaks_ckv = $this->get_vakken_CKV($studentid, $schooljaar);
                        // print(json_encode($array_vaks_ckv));

                        $table .= "<td width='65%'>$complete_name</td>";

                        // $vak_gemiddelde = new \stdClass();
                        if ($rapnummer == 1) {
                          $gemiddelde_ckv1 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 1);


                          if ($gemiddelde_ckv1 && $gemiddelde_ckv1 > 0.0 && $gemiddelde_ckv1 != '') {
                            $vak_gemiddelde['Rap1'] = $gemiddelde_ckv1;
                          } else {
                            // $vak_gemiddelde->Rap1 =  new \stdClass();
                            $vak_gemiddelde['Rap1'] = '0.0';
                          }
                        }
                        if ($rapnummer == 2) {
                          if ($_SESSION['SchoolID'] == 13 && $klas == "2A") {
                            $gemiddelde_ckv1 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 1);
                            $gemiddelde_ckv2 = $this->get_gemiddelde_ckv_2A($_SESSION['SchoolID'], $schooljaar, $studentid, $klas, 2);

                            if (!is_null($gemiddelde_ckv1) && $gemiddelde_ckv1 > 0.0 && $gemiddelde_ckv1 != '') {

                              $vak_gemiddelde['Rap1'] = $gemiddelde_ckv1;
                            } else {
                              $vak_gemiddelde['Rap1'] = '0.0';
                            }

                            if ($gemiddelde_ckv2 && $gemiddelde_ckv2 > 0.0 && $gemiddelde_ckv2 != '') {
                              $vak_gemiddelde['Rap2'] = $gemiddelde_ckv2;
                            } else {
                              $vak_gemiddelde['Rap2'] = '0.0';
                            }
                          } else {
                            $gemiddelde_ckv1 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 1);
                            $gemiddelde_ckv2 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 2);

                            if (!is_null($gemiddelde_ckv1) && $gemiddelde_ckv1 > 0.0 && $gemiddelde_ckv1 != '') {

                              $vak_gemiddelde['Rap1'] = $gemiddelde_ckv1;
                            } else {
                              $vak_gemiddelde['Rap1'] = '0.0';
                            }

                            if ($gemiddelde_ckv2 && $gemiddelde_ckv2 > 0.0 && $gemiddelde_ckv2 != '') {
                              $vak_gemiddelde['Rap2'] = $gemiddelde_ckv2;
                            } else {
                              $vak_gemiddelde['Rap2'] = '0.0';
                            }
                          }
                        }
                        if ($rapnummer == 3) {
                          $gemiddelde_ckv1 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 1);
                          if ($_SESSION['SchoolID'] == 13 && $klas == "2A") {
                            $gemiddelde_ckv2 = $this->get_gemiddelde_ckv_2A($_SESSION['SchoolID'], $schooljaar, $studentid, $klas, 2);
                          } else {
                            $gemiddelde_ckv2 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 2);
                          }
                          $gemiddelde_ckv3 = $this->get_gemiddelde_ckv($_SESSION['SchoolID'], $schooljaar, $studentid, 3);
                          if ($gemiddelde_ckv1 && $gemiddelde_ckv1 > 0.0 && $gemiddelde_ckv1 != '') {

                            $vak_gemiddelde['Rap1'] = $gemiddelde_ckv1;
                          } else {
                            $vak_gemiddelde['Rap1'] = '0.0';
                          }

                          if ($gemiddelde_ckv2 && $gemiddelde_ckv2 > 0.0 && $gemiddelde_ckv2 != '') {
                            $vak_gemiddelde['Rap2'] = $gemiddelde_ckv2;
                          } else {
                            $vak_gemiddelde['Rap2'] = '0.0';
                          }
                          if ($gemiddelde_ckv3 && $gemiddelde_ckv3 > 0.0 && $gemiddelde_ckv3 != '') {
                            $vak_gemiddelde['Rap3'] = $gemiddelde_ckv3;
                          } else {
                            $vak_gemiddelde['Rap3'] = '0.0';
                          }
                        }

                        array_push($result_cvk_array, $vak_gemiddelde);


                        // print json_encode($result_cvk_array);
                        $average_ckv_divisor = 0;
                        for ($x = 0; $x <= $rapnummer; $x++) {
                          $avg_rap1 = 0.0;
                          $avg_rap2 = 0.0;
                          $avg_rap3 = 0.0;

                          for ($i = 0; $i < count($result_cvk_array); $i++) {

                            if ((float)$result_cvk_array[$i]['Rap1'] > 0) {
                              $avg_rap1 = (float)$result_cvk_array[$i]['Rap1'] + $avg_rap1;
                            }
                            if ($rapnummer == 2) {
                              if ((float)$result_cvk_array[$i]['Rap2'] > 0) {
                                $avg_rap2 = (float)$result_cvk_array[$i]['Rap2'] + $avg_rap2;
                              }
                            }
                            if ($rapnummer == 3) {
                              if ((float)$result_cvk_array[$i]['Rap3'] > 0 || (float)$result_cvk_array[$i]['Rap2'] > 0) {
                                $avg_rap2 = (float)$result_cvk_array[$i]['Rap2'] + $avg_rap2;
                                $avg_rap3 = (float)$result_cvk_array[$i]['Rap3'] + $avg_rap3;
                              }
                            }
                          }

                          if ((float)$avg_rap1 >= 0 && $avg_rap1 != '') {
                            $average_ckv_divisor++;
                          }
                          if ((float)$avg_rap2 >= 0 && $avg_rap2 != '') {
                            $average_ckv_divisor++;
                          }
                          if ((float)$avg_rap3 >= 0 && $avg_rap3 != '') {
                            $average_ckv_divisor++;
                          }

                          $final_avg_CKV = ($avg_rap1 + $avg_rap2 + $avg_rap3) / $average_ckv_divisor;

                          $average_ckv_divisor = 0;
                        }
                        if ($rapnummer == 1) {
                          $table .= "<td " . ((float)$avg_rap1 >= 1 && (float)$avg_rap1 <= 4.4  && (float)$avg_rap1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap1 > 0.0 && $avg_rap1 != null  && $avg_rap1 != "" ? round($avg_rap1, 1) : "") . "</td>";
                          $table .= "<td></td>";
                          $table .= "<td></td>";
                        }

                        if ($rapnummer == 2) {
                          $table .= "<td " . ((float)$avg_rap1 >= 1 && (float)$avg_rap1 <= 4.4  && (float)$avg_rap1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap1 > 0.0 && $avg_rap1 != null  && $avg_rap1 != "" ? round($avg_rap1, 1) : "") . "</td>";
                          $table .= "<td " . ((float)$avg_rap2 >= 1 && (float)$avg_rap2 <= 4.4  && (float)$avg_rap2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap2 > 0.0 && $avg_rap2 != null  && $avg_rap2 != "" ? round($avg_rap2, 1) : "") . "</td>";
                          $table .= "<td></td>";
                        }
                        if ($rapnummer == 3) {
                          $table .= "<td " . ((float)$avg_rap1 >= 1 && (float)$avg_rap1 <= 4.4  && (float)$avg_rap1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap1 > 0.0 && $avg_rap1 != null  && $avg_rap1 != "" ? round($avg_rap1, 1) : "") . "</td>";
                          $table .= "<td " . ((float)$avg_rap2 >= 1 && (float)$avg_rap2 <= 4.4  && (float)$avg_rap2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap2 > 0.0 && $avg_rap2 != null  && $avg_rap2 != "" ? round($avg_rap2, 1) : "") . "</td>";
                          $table .= "<td " . ((float)$avg_rap3 >= 1 && (float)$avg_rap3 <= 4.4  && (float)$avg_rap3 ? "class=\"bg-danger\"" : "") . ">" . ((float)$avg_rap3 > 0.0 && $avg_rap3 != null  && $avg_rap3 != "" ? round($avg_rap3, 1) : "") . "</td>";
                        }

                        $table .= "<td class='" . $color . "' " . ((float)$final_avg_CKV >= 1 && (float)$final_avg_CKV <= 4.4  && (float)$final_avg_CKV ? "class=\"bg-danger\"" : "") . ">" . ((float)$final_avg_CKV > 0.0 && $final_avg_CKV != null  && $final_avg_CKV != "" ? ($final_avg_CKV >= 5.5 && $final_avg_CKV <= 6 ? 6 : round($final_avg_CKV)) : "") . "</td>";
                        $is_ckv = true;
                      }
                    } else if ($complete_name != null && $complete_name != '' && ($level_klas != 4 || $vak_code != "LO") || ($level_klas == 4 && $pos != NULL && $pos != '')) {
                      $average_divisor = 0;
                      $average = 0;
                      $table .= "<td width='65%'>$complete_name ($vak_code)</td>";
                      if ((float)$gemmindele1 > 0.0 && $gemmindele1 != '' && $gemmindele1 != null) {
                        $average_divisor++;
                        $average = ($gemmindele1) / 1;
                      }
                      if ($rapnummer == 2) {
                        if ((float)$gemmindele2 > 0.0 && $gemmindele2 != '' && $gemmindele2 != null) {
                          $average_divisor++;
                        }
                        $average = ($gemmindele1 + $gemmindele2) / ($average_divisor == 0 ? 1 : $average_divisor);
                      }
                      if ($rapnummer == 3) {
                        if ($level_klas == 4) {
                          if ((float)$po > 0.0 && $po != '' && $po != null) {
                            $average_divisor = $average_divisor + 0.5;
                          }
                        }
                        if ((float)$gemmindele3 > 0.0 && $gemmindele3 != null  && $gemmindele3 != "") {
                          $average_divisor++;
                        }
                        if ((float)$gemmindele2 > 0.0 && $gemmindele2 != '' && $gemmindele2 != null) {
                          $average_divisor++;
                        }
                        if ($level_klas == 4) {
                          $average = ($gemmindele1 + $gemmindele2 + $gemmindele3 + ($po / 2)) / ($average_divisor == 0 ? 1 : $average_divisor);
                        } else {
                          $average = ($gemmindele1 + $gemmindele2 + $gemmindele3) / ($average_divisor == 0 ? 1 : $average_divisor);
                        }

                        if ($_SESSION['SchoolID'] == 17 && $schooljaar == "2021-2022" && ($volledigenaamvak == "lo" || $volledigenaamvak == "LO")) {
                          $average = ($gemmindele1 + $gemmindele2 + $gemmindele3) / 2;
                        }

                        if ($_SESSION['SchoolID'] == 12 && $schooljaar == "2019-2020") {
                          if ($gemmindele1 != 0) {

                            if ($gemmindele3 != 0) {
                              $average = (($gemmindele1) + ($gemmindele2) + ($gemmindele3)) / 3;
                            } else {
                              if ($gemmindele2 != 0) {
                                $average = (($gemmindele1) + ($gemmindele2)) / 2;  // code...
                              } else {
                                $average = $gemmindele1;
                              }
                            }
                          } else {
                            $average = (($gemmindele2) + ($gemmindele3)) / 2;
                          }
                        }
                      }
                      $blue = "";
                      if ($level_klas == 4 && $eba !== false && $$eba == "V") {
                        $gemmindele1 = "V";
                        $gemmindele2 = "V";
                        $gemmindele3 = "V";
                        $blue = " style='color: dodgerblue;'";
                      }

                      if ($rapnummer == 1) {
                        $table .= "<td " . $blue . ((float)$gemmindele1 >= 0 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" || $gemmindele1 == "V" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td></td>";
                        $table .= "<td></td>";
                        if ($level_klas == 4) {
                          $average = 0.0;
                          $color = "";
                        }
                      }

                      if ($rapnummer == 2) {
                        $table .= "<td " . $blue . ((float)$gemmindele1 >= 0 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" || $gemmindele1 == "V" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td " . $blue . ((float)$gemmindele2 >= 0 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" || $gemmindele2 == "V" ? $gemmindele2 : "") . "</td>";
                        $table .= "<td></td>";
                        if ($level_klas == 4) {
                          $average = 0.0;
                          $color = "";
                        }
                      }
                      if ($rapnummer == 3) {
                        $table .= "<td " . $blue . ((float)$gemmindele1 >= 0 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" || $gemmindele1 == "V" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td " . $blue . ((float)$gemmindele2 >= 0 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" || $gemmindele2 == "V" ? $gemmindele2 : "") . "</td>";
                        $table .= "<td " . $blue . ((float)$gemmindele3 >= 0 && (float)$gemmindele3 <= 4.4  && (float)$gemmindele3 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele3 > 0.0 && $gemmindele3 != null  && $gemmindele3 != "" || $gemmindele3 == "V" ? $gemmindele3 : "") . "</td>";
                        if ($level_klas == 4) {
                          $color = "";
                        }
                      }

                      if ($level_klas != 4 && $vak_code == "NE" || $vak_code == "EN" || $vak_code == "LO" || ($vak_code == "WI" && $profiel != "HU08")) {
                        $table .= "<td class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                      } else {
                        $paket = substr($profiel, 0, 2);

                        switch ($vak_code) {
                          case 'EC':
                            if ($level_klas != 4 && $profiel != "MM10" && $profiel != "MM11" && $profiel != "MM12" && $profiel != "NW01" && $profiel != "NW02" && $profiel != "NW04" && $profiel != "NW07" && $profiel != "HU07") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'GS':
                            if ($level_klas != 4 && $profiel != "MM04" && $profiel != "MM05" && $profiel != "MM07" && $paket != "NW") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'SP':
                            if ($level_klas != 4 && $profiel != "MM03" && $profiel != "MM07" && $profiel != "MM08" && $profiel != "MM09" && $profiel != "MM12" && $profiel != "NW04" && $profiel != "NW05" && $profiel != "NW06" && $profiel != "NW09") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'PA':
                            if ($level_klas != 4 && $profiel != "MM02" && $profiel != "MM05" && $profiel != "MM06" && $profiel != "MM09" && $profiel != "MM11" && $profiel != "NW02" && $profiel != "NW03" && $profiel != "NW06" && $profiel != "NW08" && $profiel != "HU10") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'BI':
                            if ($level_klas != 4 && $profiel != "MM01" && $profiel != "MM04" && $profiel != "MM06" && $profiel != "MM08" && $profiel != "MM10" && $profiel != "NW01" && $profiel != "NW03" && $profiel != "NW05" && $paket != "HU") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'AK':
                            if ($level_klas != 4 && $profiel != "MM01" && $profiel != "MM02" && $profiel != "MM03" && $paket != "NW") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'NA':
                            if ($level_klas != 4 && $paket != "MM" && $profiel != "NW07" && $profiel != "NW08" && $profiel != "NW09" && $paket != "HU") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'SK':
                            if ($level_klas != 4 && $paket != "MM" && $paket != "HU") {
                              $table .= "<td" . $blue . " class='" . $color . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? " bg-danger" : "") . "'>" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          default:
                            $table .= "<td" . $blue . " " . ((float)$average >= 0 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            break;
                        }
                      }

                      $table .= "</tr>";
                    }
                  }

                  $table .= "</tbody>";
                  $table .= "</table>";
                } else {
                  $table .= "No results to show";
                }
              } else {
                $result = 0;
              }
            } else {
              $result = 0;
            }
          }
        }
      } catch (Exception $e) {
        $result = -2;
        $result = $e->getMessage();
      }
      return $table;
    }
  }

  function get_absentie_by_student($schooljaar, $schoolID, $studentid, $rapnummer, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($_SESSION['SchoolType'] == 1) {
          if ($select = $mysqli->prepare("CALL sp_get_absentie_by_student_se_kaart (?,?,?,?)")) {
            if ($select->bind_param("siii", $schooljaar, $schoolID, $studentid, $rapnummer)) {
              if ($select->execute()) {

                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if ($select->num_rows > 0) {
                  while ($select->fetch()) {
                    $htmlcontrol = $value;
                  }
                } else
                  $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;
              } else {
                /* error executing query */

                $result = 0;
              }
            } else {
              $result = 0;
            }
          } else {
            /* error preparing query */

            $result = 0;

            print "error del sql: ";
          }
        } else {
          if ($select = $mysqli->prepare("CALL sp_get_absentie_by_student_se_kaart (?,?,?,?)")) {
            if ($select->bind_param("siii", $schooljaar, $schoolID, $studentid, $rapnummer)) {
              if ($select->execute()) {

                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if ($select->num_rows > 0) {
                  while ($select->fetch()) {
                    $htmlcontrol = $value;
                  }
                } else
                  $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;
              } else {
                /* error executing query */

                $result = 0;
              }
            } else {
              $result = 0;
            }
          } else {
            /* error preparing query */

            $result = 0;

            print "error del sql: ";
          }
        }

        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {

        $result = 0;
      }
    }

    return $returnvalue;
  }

  function get_te_laat_by_student($schooljaar, $schoolID, $studentid, $rapnummer, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if ($_SESSION['SchoolType'] == 1) {
          if ($select = $mysqli->prepare("SELECT SUM(CASE WHEN telaat = 1 THEN 1 ELSE 0 END)  as total_absentie FROM le_verzuim l 
          INNER JOIN students s ON l.studentid = s.id 
          INNER JOIN setting st ON st.schoolid = s.schoolid
          AND s.schoolid = ? AND s.id=?
          AND l.schooljaar = ?
          AND l.datum >=
          (CASE ?
            WHEN 1 THEN st.br1
            WHEN 2 THEN st.br2
            WHEN 3 THEN st.br3
            END)
            AND l.datum <=
            (CASE ?
              WHEN 1 THEN st.er1
              WHEN 2 THEN st.er2
              WHEN 3 THEN st.er3
              END)
          AND s.status = 1;")) {

            if ($select->bind_param("siiii", $schoolID, $studentid, $schooljaar, $rapnummer, $rapnummer)) {
              if ($select->execute()) {

                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if ($select->num_rows > 0) {
                  while ($select->fetch()) {
                    $htmlcontrol = $value;
                  }
                } else
                  $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;
              } else {
                /* error executing query */

                $result = 0;
              }
            } else {
              $result = 0;
            }
          } else {
            /* error preparing query */

            $result = 0;

            print "error del sql: ";
          }
        } else {
          if ($select = $mysqli->prepare("CALL sp_get_telaat_by_student_see_kart (?,?,?,?)")) {
            if ($select->bind_param("siii", $schooljaar, $schoolID, $studentid, $rapnummer)) {
              if ($select->execute()) {

                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if ($select->num_rows > 0) {
                  while ($select->fetch()) {
                    $htmlcontrol = $value;
                  }
                } else
                  $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;
              } else {
                /* error executing query */

                $result = 0;
              }
            } else {
              $result = 0;
            }
          } else {
            /* error preparing query */

            $result = 0;

            print "error del sql: ";
          }
        }

        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {

        $result = 0;
      }
    }

    return $returnvalue;
  }

  function get_uitsturen_by_student_se_kaart($schooljaar, $schoolID, $studentid, $rapnummer, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_uitsturen_by_student_see_kaart (?,?,?,?)")) {
          if ($select->bind_param("siii", $schooljaar, $schoolID, $studentid, $rapnummer)) {
            if ($select->execute()) {

              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */

              $result = 0;
            }
          } else {
            $result = 0;
          }
        } else {
          /* error preparing query */

          $result = 0;

          print "error del sql: ";
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {

        $result = 0;
      }
    }

    return $returnvalue;
  }

  function list_cijfers_by_student_se_kaart_rapport_ckv($schooljaar, $studentid, $rapnummer, $dummy)
  {

    $returnvalue = "";

    $sql_query = "";

    $ckv = "";

    $gemiddelde = "";
    $gemiddelde2 = "";
    $gemiddelde3 = "";
    $total_gemiddele1 = 0;
    $total_gemiddele2 = 0;
    $total_gemiddele3 = 0;
    $total_gemiddele_average = 0;

    $gemiddelde_rap1 = 0;
    $gemiddelde_rap2 = 0;
    $gemiddelde_rap3 = 0;

    $average_divisor = 0;
    $is_ckv = false;
    $average = 0;
    $result = 0;
    $vak_code = "";

    $name_vak = "";

    ############################################

    $_rap_in = "";
    $_rap_out = "";
    $final_avg_CKV = 0;
    $ckv_gemm_array = [];

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
        if ($stmt = $mysqli->prepare("CALL  sp_get_cijfers_by_student_see_kaart_ckv (?,?,?)")) {
          if ($stmt->bind_param("ssi", $schooljaar, $studentid, $rapnummer)) {
            if ($stmt->execute()) {

              $result = 1;
              $stmt->bind_result($complete_name,  $gemmindele1, $gemmindele2, $gemmindele3);
              $stmt->store_result();
              if ($stmt->num_rows > 0) {
                $i_array = 0;
                while ($stmt->fetch()) {
                  $ckv .= "<td width='65%'>$complete_name</td>";
                  if ((float)$gemmindele1 >= 0 && $gemmindele1 != '' && $gemmindele1 != null) {
                    $average_divisor++;
                    $average = ($gemmindele1) / 1;
                  }
                  if ($rapnummer == 2) {
                    if ((float)$gemmindele2 >= 0 && $gemmindele2 != '' && $gemmindele2 != null) {
                      $average_divisor++;
                      $average = ($gemmindele1 + $gemmindele2) / 2;
                    }
                  }
                  if ($rapnummer == 3) {
                    if ((float)$gemmindele3 >= 0 && $gemmindele3 != '' && $gemmindele3 != null) {
                      $average_divisor++;
                      $average = ($gemmindele1 + $gemmindele2 + $gemmindele3) / 3;
                    }
                  }
                  $ckv .= "<td " . ((float)$gemmindele1 >= 1 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" ? $gemmindele1 : "") . "</td>";

                  if ($rapnummer == 2) {
                    $ckv .= "<td " . ((float)$gemmindele2 >= 1 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" ? $gemmindele2 : "") . "</td>";
                  }
                  if ($rapnummer == 3) {
                    $ckv .= "<td " . ((float)$gemmindele2 >= 1 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" ? $gemmindele2 : "") . "</td>";
                    $ckv .= "<td " . ((float)$gemmindele3 >= 1 && (float)$gemmindele3 <= 4.4  && (float)$gemmindele3 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele3 > 0.0 && $gemmindele3 != null  && $gemmindele3 != "" ? $gemmindele3 : "") . "</td>";
                  }

                  $ckv .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? round($average) : "") . "</td>";
                }
              } else {
                $ckv = "";
              }
            } else {
              $result = 0;
            }
          } else {
            $result = 0;
          }
        }
      } catch (Exception $e) {
        $result = -2;
        $result = $e->getMessage();
      }
      return $ckv;
    }
  }

  function get_vakken_CKV($studentid, $schooljaar)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    $vakarray = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select distinct le_cijfers.vak from le_cijfers INNER JOIN le_vakken ON le_vakken.ID = le_cijfers.vak
    where le_cijfers.studentid = $studentid and le_cijfers.schooljaar = '$schooljaar' and le_vakken.volgorde <> 99 and le_vakken.complete_name like '%CKV%'
    order by le_cijfers.vak asc";

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->execute()) {

          $result = 1;
          $select->bind_result($vakid);
          $select->store_result();

          if ($select->num_rows > 0) {
            while ($select->fetch()) {
              $returnarr["vakid"] = $vakid;


              array_push($vakarray, $returnarr);
            }
          } else {

            $returnarr["results"] = 0;
          }
        } else {
          /* error executing query */

          $result = 0;
        }
      } else {
        /* error preparing query */

        $result = 0;
      }
      $returnvalue = $vakarray;
      return $returnvalue;
    } catch (Exception $e) {

      $result = 0;
    }
    return $returnvalue;
  }

  function getgemiddelde($schoolid, $studentid_in, $rap_in, $vak_in, $schooljaar)
  {
    $returnvalue = '';
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "select c.gemiddelde from le_cijfers c where c.studentid = $studentid_in and c.rapnummer = $rap_in and c.vak = $vak_in and c.schooljaar =  '$schooljaar'";
    // print($sql_query);
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare($sql_query)) {
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

          $result = '';
        }
      } else {
        /* error preparing query */

        $result = '';
      }
    } catch (Exception $e) {

      $result = '';
    }
    return $returnvalue;
  }

  function get_gemiddelde_ckv($schoolid, $schooljaar, $studentid, $rap)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare("CALL sp_get_gemiddelde_ckv_ (?,?,?,?)")) {
        if ($select->bind_param("isii", $schoolid, $schooljaar, $studentid, $rap)) {
          if ($select->execute()) {
            $result = 1;

            $select->bind_result($gemiddelde);

            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                // print('<br> Gemmindele: '. $gemiddelde);
                $htmlcontrol = $gemiddelde;
              }
            } else {
              $htmlcontrol = "";
            }
          } else {
            $result = 0;
            $result = $mysqli->error;
          }
        } else {
          /* error executing query */
          $result = 0;
        }
      } else {
        /* error preparing query */

        $result = 0;

        print "error del sql: ";
      }
      // Cierre del prepare
      $returnvalue = $htmlcontrol;
    } catch (Exception $e) {
      $result = 0;
    }
    return $returnvalue;
  }

  function get_gemiddelde_ckv_2A($schoolid, $schooljaar, $studentid, $klas, $rap)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;

    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $query = "SELECT gemiddelde,vak FROM le_cijfers WHERE schooljaar = '$schooljaar' AND rapnummer = $rap AND (vak = 6383 OR vak = 6384) AND studentid = $studentid AND klas = '$klas'";
      $resultado = mysqli_query($mysqli, $query);
      if ($resultado->num_rows > 0) {
        $mu = 0;
        $bv = 0;
        while ($row = mysqli_fetch_assoc($resultado)) {
          if ($row["vak"] == 6383) {
            $mu = $row["gemiddelde"];
          } else if ($row["vak"] == 6384) {
            $bv = $row["gemiddelde"];
          }
        }
        $gemiddelde = ($mu + (2 * $bv)) / 3;
        $htmlcontrol = $gemiddelde;
      } else {
        $htmlcontrol = "";
      }
      $returnvalue = $htmlcontrol;
    } catch (Exception $e) {
      $result = 0;
    }
    return $returnvalue;
  }

  function objectToArray($d)
  {
    if (is_object($d)) {
      // Gets the properties of the given object
      // with get_object_vars function
      $d = get_object_vars($d);
    }

    if (is_array($d)) {
      /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
      return array_map(__FUNCTION__, $d);
    } else {
      // Return array
      return $d;
    }
  }

  function _writerapportdata_houding($klas_in, $houding_in, $studentid_out, $rap_in, $schooljaar)
  {

    $returnvalue = 0;
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $activesheet_index = 0;
    $returnarr = array();

    //$_default_activesheet_index = 0;
    mysqli_report(MYSQLI_REPORT_STRICT);

    $sql_query = "";
    $sum_average = 0;

    try {
      // print('sql_query de Houding: '.$sql_query);
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($_SESSION["SchoolType"] == 1) {
        $sql_query = "SELECT avg($houding_in) FROM le_houding where studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in LIMIT 1";
      } else if ($rap_in == 4) {
        $sql_query = "SELECT avg(coalesce($houding_in,4))  FROM le_houding_hs where klas = '$klas_in' and studentid = $studentid_out and schooljaar = '$schooljaar'";
      } else {
        $sql_query = "SELECT avg(coalesce($houding_in,4))  FROM le_houding_hs where klas = '$klas_in' and studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in";
      }
      // print('Este es el query de houding: '.$sql_query);

      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($avg);
            while ($select->fetch()) {
              $result = 0;
              if ($_SESSION["SchoolType"] == 1) {
                // else if ($avg == null && $_SESSION["SchoolID"] == 18) {
                //   $result = 'A';
                // }
                // if ($avg == null) {
                //   $result = 'A';
                // } else {
                if ($avg == 4) {
                  $result = "D";
                } else if ($avg == 3) {
                  $result = "C";
                } else if ($avg == 2) {
                  $result = "B";
                } else if ($avg == 5) {
                  $result = "E";
                } else if ($avg == 6) {
                  $result = "F";
                } else if ($avg == 7) {
                  $result = "G";
                } else if ($avg == 8) {
                  $result = "H";
                } else {
                  $result = "A";
                }
                // }
              } else {
                if ($avg != null) {
                  $result = round($avg, 0, PHP_ROUND_HALF_UP);
                } else {
                  $result = 4;
                }
              }
            }
          } else {
            if ($_SESSION['SchoolType'] == 1) {
              $result = "A";
            } else {
              $result = 4;
            }
          }
        } else {
          /* error executing query */

          $result = 0;
        }
      } else {
        /* error preparing query */

        $result = 0;
      }
      // print('| Este es Result: '.$result);
      return $result;
    } catch (PHPExcel_Exception $excel) {
      return -2;
    }
  }

  function _writerapportdata_cijfers($vak, $studentid_out, $schooljaar, $schoolid, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = null;
    try {
      // print('sql_query de Houding: '.$sql_query);
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $schooljaar_array = explode("-", $schooljaar);
      $schooljaar_pasado = $schooljaar_array[0];
      if ($schooljaar_pasado <= "2021") {
        switch ($vak) {
          case 1:
            $vak = 'Tekennen';
            break;
          case 2:
            $vak = 'Technisch Lezen';
            break;
          case 3:
            $vak = 'Begrijpend lezen';
            break;
            // case 4:
            //   $vak = 'Spelling';
            //   break;
          case 5:
            $vak = 'Schrijven';
            break;
          case 6:
            $vak = 'Taaloefeningen';
            break;
            // case 7:
            //   $vak = 'Spelling';
            //   break;
            // case 8:
            //   $vak = 'Rekenen';
            //   break;
          case 9:
            $vak = 'Lichamelijke Opvoeding';
            break;
          case 10:
            $vak = 'Engels';
            break;
          case 11:
            $vak = 'Spaans';
            break;
          case 12:
            $vak = 'Verkeer';
            break;
          case 27:
            $vak = 'Maatschappijleer';
            break;
          case 28:
            $vak = 'Spreekbeurten/presentatie';
            break;
        }
        $sql_query = "SELECT c.gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON c.vak = v.ID where c.studentid = $studentid_out and c.schooljaar = '$schooljaar' and c.rapnummer = $rap_in and v.volledigenaamvak = '$vak' and c.gemiddelde > 0.0 LIMIT 1";
      } else {
        $sql_query = "SELECT gemiddelde FROM le_cijfers_ps where studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in and school_id = $schoolid and vak = $vak and gemiddelde > 0.0";
      }
      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($avg);
            while ($select->fetch()) {
              $result = $avg;
            }
          }
        }
      }
      return $result;
    } catch (PHPExcel_Exception $excel) {
      return null;
    }
  }

  function _getstudent_cijfers_18($vaks, $studentid_out, $schooljaar, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $list = "";
    $result = null;
    $length = count($vaks);
    $i = 1;
    foreach ($vaks as $vak) {
      $list = $list . $vak;
      if ($i < $length) {
        $list = $list . ",";
      }
      $i++;
    }
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $sql_query = "SELECT v.volledigenaamvak,v.volgorde,c.rapnummer,c.gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON c.vak = v.ID WHERE c.studentid = $studentid_out AND c.rapnummer <= $rap_in AND c.gemiddelde > 0 AND c.schooljaar = '$schooljaar' AND v.volgorde IN ($list) ORDER BY c.rapnummer,v.volgorde";
    if ($select = $mysqli->prepare($sql_query)) {
      if ($select->execute()) {
        $select->store_result();
        if ($select->num_rows > 0) {
          $select->bind_result($vak, $vol, $rap, $avg);
          while ($select->fetch()) {
            if ($vol == 2) {
              $vak == "lesa comprension" ? $vol = 50 : $vol = 2;
            }
            $result[$vol][$rap] = $avg;
          }
        }
      } else {
        return null;
      }
    }
    return $result;
  }

  function _getstudent_houding_18($vaks, $studentid_out, $schooljaar, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $list = "";
    $result = null;
    $length = count($vaks);
    $i = 1;
    foreach ($vaks as $vak) {
      if ($vak != "") {
        $list = $list . "h" . $vak;
        if ($i < $length) {
          $list = $list . ",";
        }
      }
      $i++;
    }
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $sql_query = "SELECT rapnummer,$list FROM le_houding WHERE studentid = $studentid_out AND schooljaar = '$schooljaar' AND rapnummer <= $rap_in";
    $result = $mysqli->query($sql_query);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $houding = array();
        foreach ($vaks as $vak) {
          $avg = $row["h" . $vak];
          if ($avg != null && $avg != "") {
            switch ($avg) {
              case 2:
                $avg = "B";
                break;
              case 3:
                $avg = "C";
                break;
              case 4:
                $avg = "D";
                break;
              case 5:
                $avg = "E";
                break;
              case 6:
                $avg = "F";
                break;
              case 7:
                $avg = "G";
                break;
              case 8:
                $avg = "H";
                break;
              default:
                $avg = "A";
                break;
            }
          } else {
            $avg = "A";
          }
          if ($vak != "") {
            $houding["h" . $vak] = $avg;
          }
        }
        $return[$row["rapnummer"]] = $houding;
      }
      return $return;
    } else {
      return null;
    }
  }

  function _writerapportdata_cijfers_18($klas, $vak, $studentid_out, $schooljaar, $schoolid, $rap_in, $cijfers)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = null;

    if (!empty($cijfers[$vak][$rap_in])) {
      $avg = $cijfers[$vak][$rap_in];
      if ($avg > 0 && $avg < 4.5) {
        $result = 4;
      } else if ($avg >= 4.5 && $avg < 5.5) {
        $result = 5;
      } else if ($avg >= 5.5 && $avg < 6.5) {
        $result = 6;
      } else if ($avg >= 6.5 && $avg < 7.5) {
        $result = 7;
      } else if ($avg >= 7.5 && $avg < 8.5) {
        $result = 8;
      } else if ($avg >= 8.5) {
        $result = 9;
      }
    }

    return $result;
  }

  function _writerapportdata_houding_18($vak, $rap_in, $houdings)
  {
    $avg = $houdings[$rap_in][$vak];
    return $avg;
  }

  function _writerapportdata_verzuim_18($vaks, $rap_in, $verzuim)
  {
    $page = "";
    foreach ($vaks as $vak) {
      $page .= "<tr>";
      $page .= "<td width='65%'>" . $vak . "</td>";
      $page .= "<td></td>";
      $page .= "<td>" . $verzuim[1][$vak] . " </td>";
      $page .= "<td>" . ($rap_in >= 2 ? $verzuim[2][$vak] : "") . " </td>";
      $page .= "<td>" . ($rap_in >= 3 ? $verzuim[3][$vak] : "") . " </td>";
      $page .= "</tr>";
    }
    return $page;
  }

  function _writerapportdata_cijfers_8($klas, $vak, $studentid_out, $schooljaar, $schoolid, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = null;
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sql_query = "SELECT gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak where c.studentid = $studentid_out and c.schooljaar = '$schooljaar' and c.rapnummer = $rap_in and v.Klas = '$klas' and v.SchoolID = $schoolid and v.volledigenaamvak = '$vak' AND c.gemiddelde > 0.0 AND c.gemiddelde IS NOT NULL LIMIT 1";
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($avg);
            while ($select->fetch()) {
              $result = $avg;
            }
          }
        }
      }
      return $result;
    } catch (PHPExcel_Exception $excel) {
      return null;
    }
  }

  function _getstudent_cijfers_8($vaks, $studentid_out, $schooljaar, $rap_in, $level_klas)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $list = "";
    $result = null;
    $hul = array();
    $length = count($vaks);
    $i = 1;
    $hul_avg_1 = "";
    $hul_avg_2 = "";
    $hul_avg_3 = "";
    foreach ($vaks as $name => $vak) {
      if ($name == "HUL Scucha y Papia / Luisteren en Spreken") {
        foreach ($vak as $key => $value) {
          $list = $list . "'" . $value . "',";
        }
      } else {
        $list = $list . "'" . $vak . "'";
        if ($i < $length) {
          $list = $list . ",";
        }
      }
      $i++;
    }
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $sql_query = "SELECT v.volledigenaamvak,c.rapnummer,c.gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON c.vak = v.ID WHERE c.studentid = $studentid_out AND c.rapnummer <= $rap_in AND c.gemiddelde > 0 AND c.schooljaar = '$schooljaar' AND v.volledigenaamvak IN ($list) ORDER BY c.rapnummer";
    if ($select = $mysqli->prepare($sql_query)) {
      if ($select->execute()) {
        $select->store_result();
        if ($select->num_rows > 0) {
          $select->bind_result($vak, $rap, $avg);
          while ($select->fetch()) {
            if ($level_klas != 6) {
              switch ($vak) {
                case "Stellen":
                case "Woordenschat":
                case "Luistervaardigheid":
                case "Leesbegrip":
                case "Dictee":
                case "Taalbeschouwing":
                  $hul[$rap][$vak] = $avg;
                  break;
                default:
                  $result[$vak][$rap] = $avg;
                  break;
              }
            } else {
              $result[$vak][$rap] = $avg;
            }
          }
          if ($level_klas != 6) {
            for ($i = 1; $i <= $rap_in; $i++) {
              $hul_avg = "";
              if (!empty($hul[$i]) && is_array($hul[$i])) {
                $filtered = array_filter($hul[$i], function ($value) {
                  return ($value !== "" && $value !== null && $value !== 0);
                });
                $hul_avg = round(array_sum($filtered) / count($filtered), 1);
              }
              $result["HUL Scucha y Papia / Luisteren en Spreken"][$i] = $hul_avg;
            }
          }
        }
      } else {
        return null;
      }
    }
    return $result;
  }

  function _getstudent_houding_8($vaks, $studentid_out, $schooljaar, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $list = "";
    $result = null;
    $length = count($vaks);
    $i = 1;
    foreach ($vaks as $name => $vak) {
      if ($vak != "") {
        $list = $list . $vak;
        if ($i < $length) {
          $list = $list . ",";
        }
      }
      $i++;
    }
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $sql_query = "SELECT rapnummer,$list FROM le_houding WHERE studentid = $studentid_out AND schooljaar = '$schooljaar' AND rapnummer <= $rap_in";
    $result = $mysqli->query($sql_query);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $houding = array();
        foreach ($vaks as $name => $vak) {
          $avg = $row[$vak];
          if ($avg != null && $avg != "") {
            switch ($avg) {
              case 10:
                $avg = "B";
                break;
              case 11:
                $avg = "S";
                break;
              case 12:
                $avg = "I";
                break;
            }
          } else {
            $avg = "B";
          }
          if ($vak != "") {
            $houding[$vak] = $avg;
          }
        }
        $return[$row["rapnummer"]] = $houding;
      }
      return $return;
    } else {
      return null;
    }
  }

  // function _writerapportdata_houding_8($klas, $vak, $studentid_out, $schooljaar, $schoolid, $rap_in)
  // {
  //   mysqli_report(MYSQLI_REPORT_STRICT);
  //   $sql_query = "";
  //   $result = null;
  //   try {
  //     require_once("DBCreds.php");
  //     $DBCreds = new DBCreds();
  //     $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
  //     $sql_query = "SELECT $vak FROM le_houding WHERE studentid = $studentid_out AND schooljaar = '$schooljaar' AND rapnummer = $rap_in and klas = '$klas'";
  //     if ($select = $mysqli->prepare($sql_query)) {
  //       if ($select->execute()) {
  //         $select->store_result();
  //         if ($select->num_rows > 0) {
  //           $select->bind_result($avg);
  //           while ($select->fetch()) {
  //             if ($avg != null && $avg != "") {
  //               switch ($avg) {
  //                 case 10:
  //                   $result = "B";
  //                   break;
  //                 case 11:
  //                   $result = "S";
  //                   break;
  //                 case 12:
  //                   $result = "I";
  //                   break;
  //               }
  //             } else {
  //               $result = "B";
  //             }
  //           }
  //         }
  //       }
  //     }
  //     return $result;
  //   } catch (PHPExcel_Exception $excel) {
  //     return null;
  //   }
  // }

  function _writerapportdata_verzuim_8($klas, $vak, $studentid_out, $schooljaar, $schoolid, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = 0;
    try {
      $u = new spn_utils();
      $s = new spn_setting();
      $s->getsetting_info($schoolid, false);
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sql_query = "SELECT v.$vak,v.created,v.datum FROM students s 
    LEFT JOIN le_verzuim v ON s.id = v.studentid AND v.schooljaar = '$schooljaar' AND v.studentid = s.id
    LEFT JOIN opmerking o ON o.studentid = s.id AND o.schooljaar = '$schooljaar' AND o.rapport = $rap_in
    WHERE s.class = '$klas' AND s.schoolid = $schoolid AND s.id = $studentid_out ORDER BY v.created";
      if ($select = $mysqli->prepare($sql_query)) {
        $begin = "_setting_begin_rap_" . $rap_in;
        $end = "_setting_end_rap_" . $rap_in;
        $fecha2 = $s->$end;
        $fecha1 = $s->$begin;
        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($verzuim, $created, $datum);
            while ($select->fetch()) {
              $datum = $u->convertfrommysqldate_new($datum);
              if ($datum >= $fecha1 && $datum <= $fecha2) {
                if ($verzuim > 0) {
                  $result++;
                }
              }
            }
          }
        }
      }
      return $result;
    } catch (PHPExcel_Exception $excel) {
      return null;
    }
  }

  function _getstudent_verzuim_8($vak, $studentid_out, $schooljaar, $rap_in, $schoolid)
  {
    $u = new spn_utils();
    $s = new spn_setting();
    $s->getsetting_info($schoolid, false);
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = 0;
    $verzuim = array();

    for ($i = 1; $i <= $rap_in; $i++) {
      $begin = "_setting_begin_rap_" . $i;
      $end = "_setting_end_rap_" . $i;
      $fecha1 = "fecha1_" . $i;
      $fecha2 = "fecha2_" . $i;
      $$fecha2 = $s->$end;
      $$fecha1 = $s->$begin;
      !isset($verzuim[$i]) ? $verzuim[$i] = array('absentie' => 0, 'telaat' => 0, 'huiswerk' => 0) : null;
    }

    try {
      $sql_query = "SELECT v.absentie,v.telaat,v.huiswerk,v.datum FROM students s 
    LEFT JOIN le_verzuim v ON s.id = v.studentid AND v.schooljaar = '$schooljaar' AND v.studentid = s.id
    WHERE  s.id = $studentid_out AND (v.absentie > 0 OR v.telaat > 0 OR v.huiswerk > 0) ORDER BY v.created";
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($absentie, $telaat, $huiswerk, $datum);
            while ($select->fetch()) {
              $datum = $u->convertfrommysqldate_new($datum);
              if ($datum >= $fecha1_1 && $datum <= $fecha2_1) {
                $absentie > 0 ? $verzuim[1]['absentie']++ : null;
                $telaat > 0 ? $verzuim[1]['telaat']++ : null;
                $huiswerk > 0 ? $verzuim[1]['huiswerk']++ : null;
              } else if ($datum >= $fecha1_2 && $datum <= $fecha2_2) {
                $absentie > 0 ? $verzuim[2]['absentie']++ : null;
                $telaat > 0 ? $verzuim[2]['telaat']++ : null;
                $huiswerk > 0 ? $verzuim[2]['huiswerk']++ : null;
              } else if ($datum >= $fecha1_3 && $datum <= $fecha2_3) {
                $absentie > 0 ? $verzuim[3]['absentie']++ : null;
                $telaat > 0 ? $verzuim[3]['telaat']++ : null;
                $huiswerk > 0 ? $verzuim[3]['huiswerk']++ : null;
              }
            }
          }
        }
      }
      return $verzuim;
    } catch (PHPExcel_Exception $excel) {
      return null;
    }
  }
  function _calculate_gemiddelde($array)
  {
    $sum = 0;
    $count = 0;
    foreach ($array as $value) {
      if ($value != null && $value != "" && $value > 0) {
        $sum += $value;
        $count++;
      }
    }
    if ($count > 0) {
      return round($sum / $count, 1);
    } else {
      return null;
    }
  }

  function _print_vaks_table_8($type, $table, $vaks, $rap, $avg, $head, $cijfers)
  {
    if ($avg) {
      $gem_r1 = array();
      $gem_r2 = array();
      $gem_r3 = array();
    }

    $page_html = "<style>.table-sm td, .table th{padding: 0.1rem !important; }</style>";
    $page_html .= "<table align='center'  cellpadding='1' cellspacing='1' class='table table-sm'>";
    $page_html .= "<thead>";
    if ($table != "" && $head) {
      if ($type == 1) {
        $page_html .= "<th>" . $table . "</th>";
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th style='text-align: center;'>1</th>";
        $page_html .= "<th style='text-align: center;'>2</th>";
        $page_html .= "<th style='text-align: center;'>3</th>";
        $page_html .= "<th style='text-align: center;'>4</th>";
      } else {
        $page_html .= "<th>" . $table . "</th>";
        $page_html .= "<th style='width: 75px;'>Rapport</th>";
        $page_html .= "<th style='text-align: center;'>1</th>";
        $page_html .= "<th style='text-align: center;'>2</th>";
        $page_html .= "<th style='text-align: center;'>3</th>";
        $page_html .= "<th style='text-align: center;'>4</th>";
      }
    } else if ($table != "" && !$head) {
      $page_html .= "<th>" . $table . "</th>";
      $page_html .= "<th style='width:14%;'></th>";
      $page_html .= "<th></th>";
      $page_html .= "<th></th>";
      $page_html .= "<th></th>";
      $page_html .= "<th></th>";
    } else {
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;'></th>";
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;width:14%;'></th>";
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;'></th>";
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;'></th>";
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;'></th>";
      $page_html .= "<th style='border-bottom: none; padding: 0 !important;'></th>";
    }
    $page_html .= "</thead>";
    $page_html .= "<tbody>";

    foreach ($vaks as $key => $value) {
      $page_html .= "<tr>";
      $page_html .= "<td width='65%'>" . $key . "</td>";
      $page_html .= "<td></td>";

      switch ($type) {
        case 1:
          // $_h1_1 =  $this->_writerapportdata_cijfers_8($klas, $value, $student, $schooljaar, $schoolId, 1);
          // $_h1_2 =  $rap >= 2 ? ($this->_writerapportdata_cijfers_8($klas, $value, $student, $schooljaar, $schoolId, 2)) : "";
          // $_h1_3 =  $rap >= 3 ? ($this->_writerapportdata_cijfers_8($klas, $value, $student, $schooljaar, $schoolId, 3)) : "";
          if ($key == "HUL Scucha y Papia / Luisteren en Spreken") {
            $_h1_1 =  $cijfers[$key][1];
            $_h1_2 =  $rap >= 2 ? ($cijfers[$key][2]) : "";
            $_h1_3 =  $rap >= 3 ? ($cijfers[$key][3]) : "";
          } else {
            $_h1_1 =  $cijfers[$value][1];
            $_h1_2 =  $rap >= 2 ? ($cijfers[$value][2]) : "";
            $_h1_3 =  $rap >= 3 ? ($cijfers[$value][3]) : "";
          }
          break;
        case 2:
          // $_h1_1 =  $this->_writerapportdata_houding_8($klas, $value, $student, $schooljaar, $schoolId, 1);
          // $_h1_2 =  $rap >= 2 ? ($this->_writerapportdata_houding_8($klas, $value, $student, $schooljaar, $schoolId, 2)) : "";
          // $_h1_3 =  $rap >= 3 ? ($this->_writerapportdata_houding_8($klas, $value, $student, $schooljaar, $schoolId, 3)) : "";
          $_h1_1 =  $cijfers[1][$value];
          $_h1_2 =  $rap >= 2 ? ($cijfers[2][$value]) : "";
          $_h1_3 =  $rap >= 3 ? ($cijfers[3][$value]) : "";
          break;
        case 3:
          // $_h1_1 =  $this->_writerapportdata_verzuim_8($klas, $value, $student, $schooljaar, $schoolId, 1);
          // $_h1_2 =  $rap >= 2 ? ($this->_writerapportdata_verzuim_8($klas, $value, $student, $schooljaar, $schoolId, 2)) : "";
          // $_h1_3 =  $rap >= 3 ? ($this->_writerapportdata_verzuim_8($klas, $value, $student, $schooljaar, $schoolId, 3)) : "";
          $_h1_1 =  $cijfers[1][$value];
          $_h1_2 =  $rap >= 2 ? ($cijfers[2][$value]) : "";
          $_h1_3 =  $rap >= 3 ? ($cijfers[3][$value]) : "";
          break;
      }

      if ($avg && $type == 1) {
        array_push($gem_r1, $_h1_1);
        array_push($gem_r2, $_h1_2);
        array_push($gem_r3, $_h1_3);
      }

      $page_html .= "<td style='text-align: center;' " . ((float)$_h1_1 <= 5.4 && $_h1_1 && $type == 1 ? " class=\"bg-danger\"" : "") . ">" . $_h1_1 . " </td>";
      $page_html .= "<td style='text-align: center;' " . (((float)$_h1_2 <= 5.4 && $_h1_2 && $rap >= 2 && $type == 1) ? " class=\"bg-danger\"" : "") . ">" . ($rap >= 2 ? $_h1_2 : "") . " </td>";
      $page_html .= "<td style='text-align: center;' " . (((float)$_h1_3 <= 5.4 && $_h1_3 && $rap >= 3 && $type == 1) ? " class=\"bg-danger\"" : "") . ">" . ($rap >= 3 ? $_h1_3 : "") . " </td>";
      if ($type == 3) {
        $avg_r4 = ((float)$_h1_1 + (float)$_h1_2 + (float)$_h1_3);
        $page_html .= "<td style='text-align: center;' ><b>" . ($avg_r4) . " </b></td>";
      } else {
        $page_html .= "<td></td>";
      }

      $page_html .= "</tr>";
    }

    if ($avg && $type == 1) {
      $page_html .= "<tr>";
      $page_html .= "<td width='65%'>Averahe / Gemiddelde</td>";
      $page_html .= "<td></td>";

      $avg_h1 = $this->_calculate_gemiddelde($gem_r1);
      $avg_h2 = $this->_calculate_gemiddelde($gem_r2);
      $avg_h3 = $this->_calculate_gemiddelde($gem_r3);

      $page_html .= "<td style='text-align: center;' " . ((float)$avg_h1 <= 5.4 && $avg_h1 ? " class=\"bg-danger\"" : "") . "><b>" . $avg_h1 . " </b></td>";
      $page_html .= "<td style='text-align: center;' " . (((float)$avg_h2 <= 5.4 && $avg_h2 && $rap >= 2) ? " class=\"bg-danger\"" : "") . "><b>" . ($rap >= 2 ? $avg_h2 : "") . " </b></td>";
      $page_html .= "<td style='text-align: center;' " . (((float)$avg_h3 <= 5.4 && $avg_h3 && $rap >= 3) ? " class=\"bg-danger\"" : "") . "><b>" . ($rap >= 3 ? $avg_h3 : "") . " </b></td>";
      if ($type == 1) {
        $num_r4 = ((float)$avg_h1 + (float)$avg_h2 + (float)$avg_h3);
        $den_r4 = (($avg_h1 > 0 ? 1 : 0) + ($avg_h2 > 0 ? 1 : 0) + ($avg_h3 > 0 ? 1 : 0));
        $avg_r4 = $den_r4 >= 3 ? round((float)$num_r4 / (float)$den_r4, 1) : "";
        $page_html .= "<td style='text-align: center;' " . ((float)$avg_r4 <= 5.4 && $avg_r4 ? " class=\"bg-danger\"" : "") . "><b>" . ($avg_r4) . " </b></td>";
      } else {
        $page_html .= "<td></td>";
      }
      $page_html .= "</tr>";
    }

    $page_html .= "</tbody>";
    $page_html .= "</table>";
    return $page_html;
  }
}
