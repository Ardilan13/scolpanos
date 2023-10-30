<?php
require_once("spn_audit.php");
require_once("spn_setting.php");
class spn_see_kaart
{
  function list_cijfers_by_student_se_kaart_rapport($schooljaar, $studentid, $rapnummer, $klas, $profiel, $color, $dummy)
  {

    $returnvalue = "";

    $sql_query = "";

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
                  $stmt->bind_result($vak, $volledigenaamvak, $complete_name,  $gemmindele1, $gemmindele2, $gemmindele3, $po);
                } else {
                  $stmt->bind_result($vak, $volledigenaamvak, $complete_name,  $gemmindele1, $gemmindele2, $gemmindele3);
                }
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                  $table .= "<table align='center' cellpadding='1' cellspacing='1' class='table table-sm'>";
                  if ($rapnummer == 1) {

                    $table .= "<thead>";
                    $table .= "<th>Vakken</th>";
                    $table .= "<th>1</th>";
                    $table .= "<th>2</th>";
                    $table .= "<th>3</th>";
                    $table .= $level_klas == 4 ? "<th>GSE</th>" : "<th>Eind</th>";
                    $table .= "</thead>";
                  }
                  if ($rapnummer == 2) {

                    $table .= "<thead>";
                    $table .= "<th>Vakken</th>";
                    $table .= "<th>1</th>";
                    $table .= "<th>2</th>";
                    $table .= "<th>3</th>";
                    $table .= $level_klas == 4 ? "<th>GSE</th>" : "<th>Eind</th>";
                    $table .= "</thead>";
                  }
                  if ($rapnummer == 3) {

                    $table .= "<thead>";
                    $table .= "<th>Vakken</th>";
                    $table .= "<th>1</th>";
                    $table .= "<th>2</th>";
                    $table .= "<th>3</th>";
                    $table .= $level_klas == 4 ? "<th>GSE</th>" : "<th>Eind</th>";
                    $table .= "</thead>";
                  }

                  $table .= "<tbody>";
                  $i_array = 0;
                  while ($stmt->fetch()) {



                    $vak_code = strtoupper($volledigenaamvak);
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
                    } else if ($complete_name != null && $complete_name != '') {
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
                      if ($rapnummer == 1) {
                        $table .= "<td " . ((float)$gemmindele1 >= 1 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td></td>";
                        $table .= "<td></td>";
                        if ($level_klas == 4) {
                          $average = 0.0;
                          $color = "";
                        }
                      }

                      if ($rapnummer == 2) {
                        $table .= "<td " . ((float)$gemmindele1 >= 1 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td " . ((float)$gemmindele2 >= 1 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" ? $gemmindele2 : "") . "</td>";
                        $table .= "<td></td>";
                        if ($level_klas == 4) {
                          $average = 0.0;
                          $color = "";
                        }
                      }
                      if ($rapnummer == 3) {
                        $table .= "<td " . ((float)$gemmindele1 >= 1 && (float)$gemmindele1 <= 4.4  && (float)$gemmindele1 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele1 > 0.0 && $gemmindele1 != null  && $gemmindele1 != "" ? $gemmindele1 : "") . "</td>";
                        $table .= "<td " . ((float)$gemmindele2 >= 1 && (float)$gemmindele2 <= 4.4  && (float)$gemmindele2 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele2 > 0.0 && $gemmindele2 != null  && $gemmindele2 != "" ? $gemmindele2 : "") . "</td>";
                        $table .= "<td " . ((float)$gemmindele3 >= 1 && (float)$gemmindele3 <= 4.4  && (float)$gemmindele3 ? "class=\"bg-danger\"" : "") . ">" . ((float)$gemmindele3 > 0.0 && $gemmindele3 != null  && $gemmindele3 != "" ? $gemmindele3 : "") . "</td>";
                      }

                      if ($vak_code == "NE" || $vak_code == "EN" || $vak_code == "LO" || ($vak_code == "WI" && $profiel != "HU08")) {
                        $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                      } else {
                        $paket = substr($profiel, 0, 2);

                        switch ($vak_code) {
                          case 'EC':
                            if ($profiel != "MM10" && $profiel != "MM11" && $profiel != "MM12" && $profiel != "NW01" && $profiel != "NW02" && $profiel != "NW04" && $profiel != "NW07" && $profiel != "HU07") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'GS':
                            if ($profiel != "MM04" && $profiel != "MM05" && $profiel != "MM07" && $paket != "NW") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'SP':
                            if ($profiel != "MM03" && $profiel != "MM07" && $profiel != "MM08" && $profiel != "MM09" && $profiel != "MM12" && $profiel != "NW04" && $profiel != "NW05" && $profiel != "NW06" && $profiel != "NW09") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'PA':
                            if ($profiel != "MM02" && $profiel != "MM05" && $profiel != "MM06" && $profiel != "MM09" && $profiel != "MM11" && $profiel != "NW02" && $profiel != "NW03" && $profiel != "NW06" && $profiel != "NW08" && $profiel != "HU10") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'BI':
                            if ($profiel != "MM01" && $profiel != "MM04" && $profiel != "MM06" && $profiel != "MM08" && $profiel != "MM10" && $profiel != "NW01" && $profiel != "NW03" && $profiel != "NW05" && $paket != "HU") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'AK':
                            if ($profiel != "MM01" && $profiel != "MM02" && $profiel != "MM03" && $paket != "NW") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'NA':
                            if ($paket != "MM" && $profiel != "NW07" && $profiel != "NW08" && $profiel != "NW09" && $paket != "HU") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          case 'SK':
                            if ($paket != "MM" && $paket != "HU") {
                              $table .= "<td class='" . $color . "' " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            } else {
                              $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
                            }
                            break;
                          default:
                            $table .= "<td " . ((float)$average >= 1 && (float)$average <= 4.4  && (float)$average ? "class=\"bg-danger\"" : "") . ">" . ((float)$average > 0.0 && $average != null  && $average != "" ? ($level_klas != 4 ? round($average) : round($average, 1)) : "") . "</td>";
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
        $sql_query = "SELECT avg($houding_in) FROM le_houding where klas = '$klas_in' and studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in";
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
                if ($avg == null && $_SESSION["SchoolID"] != 18) {
                  $result = 'A';
                } else if ($avg == null && $_SESSION["SchoolID"] == 18) {
                  $result = '';
                } else {
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
                }
              } else {
                if ($avg != null) {
                  $result = round($avg, 0, PHP_ROUND_HALF_UP);
                } else {
                  $result = 4;
                }
              }
            }
          } else {
            if ($_SESSION['SchoolType'] == 1 && $_SESSION['SchoolID'] != 18) {
              $result = "A";
            } else if ($_SESSION['SchoolID'] == 18) {
              $result = '';
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
      if ($_SESSION['SchoolID'] != 18) {
        $sql_query = "SELECT gemiddelde FROM le_cijfers_ps where studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in and school_id = $schoolid and vak = $vak and gemiddelde > 0.0";
      } else {
        $sql_query = "SELECT gemiddelde FROM le_cijfers where studentid = $studentid_out and schooljaar = '$schooljaar' and rapnummer = $rap_in and vak = $vak and gemiddelde > 0.0";
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

  function _writerapportdata_cijfers_18($klas, $vak, $studentid_out, $schooljaar, $schoolid, $rap_in)
  {
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "";
    $result = null;
    try {
      // print('sql_query de Houding: '.$sql_query);
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($vak == 50) {
        $sql_query = "SELECT gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak where c.studentid = $studentid_out and c.schooljaar = '$schooljaar' and c.rapnummer = $rap_in and v.Klas = '$klas' and v.SchoolID = $schoolid and v.volledigenaamvak = 'lesa comprension'";
      } else if ($vak == 2) {
        $sql_query = "SELECT gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak where c.studentid = $studentid_out and c.schooljaar = '$schooljaar' and c.rapnummer = $rap_in and v.Klas = '$klas' and v.SchoolID = $schoolid and v.volledigenaamvak = 'idioma comprension'";
      } else {
        $sql_query = "SELECT gemiddelde FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak where c.studentid = $studentid_out and c.schooljaar = '$schooljaar' and c.rapnummer = $rap_in and v.Klas = '$klas' and v.SchoolID = $schoolid and v.volgorde = $vak";
      }
      if ($select = $mysqli->prepare($sql_query)) {

        if ($select->execute()) {
          $select->store_result();
          if ($select->num_rows > 0) {
            $select->bind_result($avg);
            while ($select->fetch()) {
              if ($avg != null) {
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
                } else {
                  $result = null;
                }
              } else {
                $result = null;
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
}
