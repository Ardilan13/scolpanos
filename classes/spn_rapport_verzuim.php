<?php

class spn_rapport_verzuim
{

    public $exceptionvalue = "";

    public $mysqlierror = "";

    public $mysqlierrornumber = "";

    public $sp_get_verzuim_rapport_table1 = "sp_get_verzuim_rapport_table1";

    public $sp_get_verzuim_rapport_table2 = "sp_get_verzuim_rapport_table2";

    public $sp_get_verzuim_rapport_grafiek2 = "sp_get_verzuim_rapport_grafiek2";

    public $debug = true;

    public $error = "";

    public $errormessage = "";

    public function get_verzuim_resume($_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk, $dummy)
    {

        $returnvalue = "";

        $sql_query = "";

        $json_result = "";

        $inloop = false;

        $indexvaluetoshow = 3;

        $result = 0;

        if ($_rappor_te_laat == 1) {$verzuim_name = "Te Laat";}

        if ($_rappor_absent == 1) {$verzuim_name = "Absent";}

        if ($_rappor_inhalen == 1) {$verzuim_name = "Toets inhalen";}

        if ($_rappor_uitsturen == 1) {$verzuim_name = ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen');}

        if ($_rappor_huiswerk == 1) {$verzuim_name = "Geen huiswerk";}

        if ($dummy) {

            $result = 1;

        } else {

            mysqli_report(MYSQLI_REPORT_STRICT);

            try

            {

                require_once "DBCreds.php";

                $DBCreds = new DBCreds();

                $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

                $mysqli->set_charset('utf8');

                if ($select = $mysqli->prepare("CALL " . $this->sp_get_verzuim_rapport_grafiek2 . " (?,?,?,?,?,?,?,?,?,?)")) {

                    if ($select->bind_param("sssisiiiii", $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk)) {

                        if ($select->execute()) {

                            $this->error = false;

                            $result = 1;

                            $select->bind_result($klas, $aantal_verzuim, $aantal_minutes);

                            $select->store_result();

                            if ($select->num_rows > 0) {

                                $json_result = array();

                                while ($select->fetch()) {

                                    $json_result[] = array("klas" => $klas, "aantal" => $aantal_verzuim, "minutes" => $aantal_minutes);

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

    public function list_rapport_verzuim_table1($_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk, $dummy)
    {

        require_once "spn_utils.php";

        $sql_query = "";

        $htmlcontrol = "";

        $result = 0;

        $dummy = 0;

        require_once "DBCreds.php";

        $DBCreds = new DBCreds();

        date_default_timezone_set("America/Aruba");

        $_DateTime = date("Y-m-d H:i:s");

        $status = 1;

        $_lp = "";

        mysqli_report(MYSQLI_REPORT_STRICT);

        try

        {

            require_once "DBCreds.php";

            $DBCreds = new DBCreds();

            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

            $mysqli->set_charset('utf8');

            if ($_schoolid < 12 || $_schoolid == 16 || $_schoolid == 18) {

                if ($select = $mysqli->prepare("CALL " . $this->sp_get_verzuim_rapport_table1 . " (?,?,?,?,?,?,?,?,?,?)")) {

                    if ($select->bind_param("sssisiiiii", $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk)) {

                        if ($select->execute()) {

                            // Audit by Caribe Developers

                            // require_once ("spn_audit.php");

                            // $spn_audit = new spn_audit();

                            // $UserGUID = $_SESSION['UserGUID'];

                            // $spn_audit->create_audit($UserGUID, 'app_useraccount','List Users Accounts',appconfig::GetDummy());

                            $this->error = false;

                            $result = 1;

                            $select->bind_result($id_verzuim, $studentid, $studentnr, $studentname, $klas, $datum_verzuim, $lp, $minutes);

                            $select->store_result();

                            if ($select->num_rows > 0) { /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

                                $htmlcontrol .= "<table id=\"tbl_rappor_verzuim_table1\"class=\"table table-striped table-bordered\">";

                                $htmlcontrol .= "<thead>";

                                $htmlcontrol .= "<tr>";

                                $htmlcontrol .= "<th>Date</th>";

                                $htmlcontrol .= "<th>Student Nr.</th>";

                                $htmlcontrol .= "<th>Name</th>";

                                $htmlcontrol .= "<th>Klas</th>";

                                $htmlcontrol .= "<th>LP</th>";

                                if ($_rappor_te_laat == 1) {

                                    $htmlcontrol .= "<th>Minutes</th>";

                                }

                                $htmlcontrol .= "</tr>";

                                $htmlcontrol .= "</thead>";

                                $htmlcontrol .= "<tbody>";

                                while ($select->fetch()) {

                                    if ($lp == 1) {

                                        $_lp = "Z1";

                                    };

                                    if ($lp == 2) {

                                        $_lp = "Z2";

                                    };

                                    if ($lp == 3) {

                                        $_lp = "V1";

                                    };

                                    if ($lp == 4) {

                                        $_lp = "V2";

                                    };

                                    if ($lp == 5) {

                                        $_lp = "V3";

                                    };

                                    if ($lp == 6) {

                                        $_lp = "V4";

                                    };

                                    if ($lp == 7) {

                                        $_lp = "D";

                                    };

                                    if ($lp == 8) {

                                        $_lp = "P1";

                                    };

                                    if ($lp == 9) {

                                        $_lp = "P2";

                                    };

                                    $htmlcontrol .= "<tr>";

                                    // $htmlcontrol .= "<td></td>";

                                    $htmlcontrol .= "<td>";

                                    $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $studentid . "'>";

                                    $htmlcontrol .= htmlentities($datum_verzuim) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($studentnr) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($studentname) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($_lp) . "</td>";

                                    if ($_rappor_te_laat == 1) {

                                        $htmlcontrol .= "<td>" . htmlentities($minutes) . "</td>";

                                    }

                                    $htmlcontrol .= "</tr>";

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

                        $result = 0;

                        $this->mysqlierror = $mysqli->error;

                        $this->mysqlierrornumber = $mysqli->errno;

                    }

                } else {

                    /* error preparing query */

                    $this->error = true;

                    $this->errormessage = $mysqli->error;

                    $result = 0;

                    print "error del sql: " . $this->errormessage;

                    if ($this->debug) {

                        print "error preparing query";

                    }

                }

                // Cierre del prepare
            } else {
                /* $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk */
                if ($_klas == 'ALL') {
                    $verzuim_hs = "SELECT v.klas,v.datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,s.firstname,s.lastname,s.id,s.schoolid FROM le_verzuim_hs as v INNER JOIN students as s WHERE s.schoolid = '$_schoolid' AND v.studentid = s.id AND v.datum >= '$_start_date' AND v.datum <= '$_end_date' AND (p1 != '0' OR p2 != '0' OR p3 != '0' OR p4 != '0' OR p5 != '0' OR p6 != '0' OR p7 != '0' OR p8 != '0' OR p9 != '0' OR p10 != '0') ORDER BY v.klas ASC";
                } else {
                    $verzuim_hs = "SELECT v.klas,v.datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,s.firstname,s.lastname,s.id FROM le_verzuim_hs as v INNER JOIN students as s WHERE s.schoolid = '$_schoolid' AND v.studentid = s.id AND v.klas = '$_klas' AND v.datum >= '$_start_date' AND v.datum <= '$_end_date' AND (p1 != '0' OR p2 != '0' OR p3 != '0' OR p4 != '0' OR p5 != '0' OR p6 != '0' OR p7 != '0' OR p8 != '0' OR p9 != '0' OR p10 != '0') ORDER BY v.datum ASC";
                }
                $select = mysqli_query($mysqli, $verzuim_hs);

                if (mysqli_num_rows($select) != 0) { /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

                    $htmlcontrol .= "<table id=\"tbl_rappor_verzuim_table1\"class=\"table table-striped table-bordered\">";

                    $htmlcontrol .= "<thead>";

                    $htmlcontrol .= "<tr>";

                    $htmlcontrol .= "<th>Date</th>";

                    $htmlcontrol .= "<th>Student Nr.</th>";

                    $htmlcontrol .= "<th>Name</th>";

                    $htmlcontrol .= "<th>Klas</th>";

                    $htmlcontrol .= "</tr>";

                    $htmlcontrol .= "</thead>";

                    $htmlcontrol .= "<tbody>";

                    while ($row = mysqli_fetch_assoc($select)) {

                        $complete_name = $row["firstname"]." ".$row["lastname"];

                        if ($_rappor_te_laat == 1 && ($row["p1"] == "L" || $row["p2"] == "L" || $row["p3"] == "L" || $row["p4"] == "L" || $row["p5"] == "L" || $row["p6"] == "L" || $row["p7"] == "L" || $row["p8"] == "L" || $row["p9"] == "L")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($complete_name) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($row["p10"] == "A" && $_rappor_absent == 1) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($complete_name) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";

                        }

                        if ($_rappor_inhalen == 1 && ($row["p1"] == "S" || $row["p2"] == "S" || $row["p3"] == "S" || $row["p4"] == "S" || $row["p5"] == "S" || $row["p6"] == "S" || $row["p7"] == "S" || $row["p8"] == "S" || $row["p9"] == "S")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($complete_name) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($_rappor_uitsturen == 1 && ($row["p1"] == "M" || $row["p2"] == "M" || $row["p3"] == "M" || $row["p4"] == "M" || $row["p5"] == "M" || $row["p6"] == "M" || $row["p7"] == "M" || $row["p8"] == "M" || $row["p9"] == "M")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($complete_name) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($_rappor_huiswerk == 1 && ($row["p1"] == "A" || $row["p2"] == "A" || $row["p3"] == "A" || $row["p4"] == "A" || $row["p5"] == "A" || $row["p6"] == "A" || $row["p7"] == "A" || $row["p8"] == "A" || $row["p9"] == "A")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" .htmlentities($complete_name) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                    }

                    $htmlcontrol .= "</tbody>";

                    $htmlcontrol .= "</table>";
                } else {

                    $htmlcontrol .= "No results to show";

                }

            }

            $returnvalue = $htmlcontrol;

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

    public function list_rapport_verzuim_table2($_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk, $dummy)
    {

        require_once "spn_utils.php";

        $sql_query = "";

        $htmlcontrol = "";

        $result = 0;

        $dummy = 0;

        require_once "DBCreds.php";

        $DBCreds = new DBCreds();

        date_default_timezone_set("America/Aruba");

        $_DateTime = date("Y-m-d H:i:s");

        $status = 1;

        mysqli_report(MYSQLI_REPORT_STRICT);

        try

        {

            require_once "DBCreds.php";

            $DBCreds = new DBCreds();

            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

            $mysqli->set_charset('utf8');

            if ($_schoolid < 12 || $_schoolid == 16 || $_schoolid == 18) {

                if ($select = $mysqli->prepare("CALL " . $this->sp_get_verzuim_rapport_table1 . " (?,?,?,?,?,?,?,?,?,?)")) {

                    if ($select->bind_param("sssisiiiii", $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk)) {

                        if ($select->execute()) {

                            // Audit by Caribe Developers

                            // require_once ("spn_audit.php");

                            // $spn_audit = new spn_audit();

                            // $UserGUID = $_SESSION['UserGUID'];

                            // $spn_audit->create_audit($UserGUID, 'app_useraccount','List Users Accounts',appconfig::GetDummy());

                            $this->error = false;

                            $result = 1;

                            $select->bind_result($id_verzuim, $studentid, $studentnr, $studentname, $klas, $datum_verzuim, $lp, $minutes);

                            $select->store_result();

                            if ($select->num_rows > 0) { /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

                                $htmlcontrol .= "<table id=\"tbl_rappor_verzuim_table1\"class=\"table table-striped table-bordered\">";

                                $htmlcontrol .= "<thead>";

                                $htmlcontrol .= "<tr>";

                                $htmlcontrol .= "<th>Date</th>";

                                $htmlcontrol .= "<th>Student Nr.</th>";

                                $htmlcontrol .= "<th>Name</th>";

                                $htmlcontrol .= "<th>Klas</th>";

                                $htmlcontrol .= "<th>LP</th>";

                                if ($_rappor_te_laat == 1) {

                                    $htmlcontrol .= "<th>Minutes</th>";

                                }

                                $htmlcontrol .= "</tr>";

                                $htmlcontrol .= "</thead>";

                                $htmlcontrol .= "<tbody>";

                                while ($select->fetch()) {

                                    if ($lp == 1) {

                                        $_lp = "Z1";

                                    };

                                    if ($lp == 2) {

                                        $_lp = "Z2";

                                    };

                                    if ($lp == 3) {

                                        $_lp = "V1";

                                    };

                                    if ($lp == 4) {

                                        $_lp = "V2";

                                    };

                                    if ($lp == 5) {

                                        $_lp = "V3";

                                    };

                                    if ($lp == 6) {

                                        $_lp = "V4";

                                    };

                                    if ($lp == 7) {

                                        $_lp = "D";

                                    };

                                    if ($lp == 8) {

                                        $_lp = "P1";

                                    };

                                    if ($lp == 9) {

                                        $_lp = "P2";

                                    };

                                    $htmlcontrol .= "<tr>";

                                    // $htmlcontrol .= "<td></td>";

                                    $htmlcontrol .= "<td>";

                                    $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $studentid . "'>";

                                    $htmlcontrol .= htmlentities($datum_verzuim) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($studentnr) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($studentname) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";

                                    $htmlcontrol .= "<td>" . htmlentities($_lp) . "</td>";

                                    if ($_rappor_te_laat == 1) {

                                        $htmlcontrol .= "<td>" . htmlentities($minutes) . "</td>";

                                    }

                                    $htmlcontrol .= "</tr>";

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

                        $result = 0;

                        $this->mysqlierror = $mysqli->error;

                        $this->mysqlierrornumber = $mysqli->errno;

                    }

                } else {

                    /* error preparing query */

                    $this->error = true;

                    $this->errormessage = $mysqli->error;

                    $result = 0;

                    print "error del sql: " . $this->errormessage;

                    if ($this->debug) {

                        print "error preparing query";

                    }

                }

                // Cierre del prepare
            } else {
                /* $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk */
                if ($_klas == 'ALL') {
                    $verzuim_hs = "SELECT v.klas,v.datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,s.firstname,s.lastname,s.id,s.schoolid FROM le_verzuim_hs as v INNER JOIN students as s WHERE s.schoolid = '$_schoolid' AND v.studentid = s.id AND v.datum >= '$_start_date' AND v.datum <= '$_end_date' AND (p1 != '0' OR p2 != '0' OR p3 != '0' OR p4 != '0' OR p5 != '0' OR p6 != '0' OR p7 != '0' OR p8 != '0' OR p9 != '0' OR p10 != '0') ORDER BY v.klas ASC";
                } else {
                    $verzuim_hs = "SELECT v.klas,v.datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,s.firstname,s.lastname,s.id FROM le_verzuim_hs as v INNER JOIN students as s WHERE s.schoolid = '$_schoolid' AND v.studentid = s.id AND v.klas = '$_klas' AND v.datum >= '$_start_date' AND v.datum <= '$_end_date' AND (p1 != '0' OR p2 != '0' OR p3 != '0' OR p4 != '0' OR p5 != '0' OR p6 != '0' OR p7 != '0' OR p8 != '0' OR p9 != '0' OR p10 != '0') ORDER BY v.datum ASC";
                }
                
                $select = mysqli_query($mysqli, $verzuim_hs);

                if (mysqli_num_rows($select) != 0) { /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

                    $htmlcontrol .= "<table id=\"tbl_rappor_verzuim_table1\"class=\"table table-striped table-bordered\">";

                    $htmlcontrol .= "<thead>";

                    $htmlcontrol .= "<tr>";

                    $htmlcontrol .= "<th>Date</th>";

                    $htmlcontrol .= "<th>Student Nr.</th>";

                    $htmlcontrol .= "<th>Name</th>";

                    $htmlcontrol .= "<th>Klas</th>";

                    $htmlcontrol .= "</tr>";

                    $htmlcontrol .= "</thead>";

                    $htmlcontrol .= "<tbody>";

                    while ($row = mysqli_fetch_assoc($select)) {

                        if ($_rappor_te_laat == 1 && ($row["p1"] == "L" || $row["p2"] == "L" || $row["p3"] == "L" || $row["p4"] == "L" || $row["p5"] == "L" || $row["p6"] == "L" || $row["p7"] == "L" || $row["p8"] == "L" || $row["p9"] == "L")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["firstname"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($row["p10"] == "A" && $_rappor_absent == 1) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["firstname"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";

                        }

                        if ($_rappor_inhalen == 1 && ($row["p1"] == "S" || $row["p2"] == "S" || $row["p3"] == "S" || $row["p4"] == "S" || $row["p5"] == "S" || $row["p6"] == "S" || $row["p7"] == "S" || $row["p8"] == "S" || $row["p9"] == "S")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["firstname"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($_rappor_uitsturen == 1 && ($row["p1"] == "M" || $row["p2"] == "M" || $row["p3"] == "M" || $row["p4"] == "M" || $row["p5"] == "M" || $row["p6"] == "M" || $row["p7"] == "M" || $row["p8"] == "M" || $row["p9"] == "M")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["firstname"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                        if ($_rappor_huiswerk == 1 && ($row["p1"] == "A" || $row["p2"] == "A" || $row["p3"] == "A" || $row["p4"] == "A" || $row["p5"] == "A" || $row["p6"] == "A" || $row["p7"] == "A" || $row["p8"] == "A" || $row["p9"] == "A")) {
                            $htmlcontrol .= "<tr>";
                            $htmlcontrol .= "<td>";

                            $htmlcontrol .= "<input type='hidden' name='student_id' value='" . $row["s.id"] . "'>";

                            $htmlcontrol .= htmlentities($row["datum"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["id"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["firstname"]) . "</td>";

                            $htmlcontrol .= "<td>" . htmlentities($row["klas"]) . "</td>";

                            $htmlcontrol .= "</tr>";
                        }

                    }

                    $htmlcontrol .= "</tbody>";

                    $htmlcontrol .= "</table>";
                } else {

                    $htmlcontrol .= "No results to show";

                }

            }

            // Cierre del prepare

            $returnvalue = $htmlcontrol;

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

    public function get_verzuim_graph_by_class_and_date($_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk, $dummy)
    {

        $returnvalue = "";

        $sql_query = "";

        $json_result = "";

        $inloop = false;

        $indexvaluetoshow = 3;

        $result = 0;

        if ($_rappor_te_laat == 1) {$verzuim_name = "Te Laat";}

        if ($_rappor_absent == 1) {$verzuim_name = "Absent";}

        if ($_rappor_inhalen == 1) {$verzuim_name = "Toets inhalen";}

        if ($_rappor_uitsturen == 1) {$verzuim_name = ($_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen');}

        if ($_rappor_huiswerk == 1) {$verzuim_name = "Geen huiswerk";}

        if ($dummy) {

            $result = 1;

        } else {

            mysqli_report(MYSQLI_REPORT_STRICT);

            try

            {

                require_once "DBCreds.php";

                $DBCreds = new DBCreds();

                $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

                $mysqli->set_charset('utf8');

                if ($select = $mysqli->prepare("CALL " . $this->sp_get_verzuim_rapport_table2 . " (?,?,?,?,?,?,?,?,?,?)")) {

                    if ($select->bind_param("sssisiiiii", $_start_date, $_end_date, $_klas, $_schoolid, $_schooljaar, $_rappor_te_laat, $_rappor_absent, $_rappor_inhalen, $_rappor_uitsturen, $_rappor_huiswerk)) {

                        if ($select->execute()) {

                            $this->error = false;

                            $result = 1;

                            $select->bind_result($id_verzuim, $studentid, $studentnr, $studentname, $klas, $aantal_verzuim, $minutes);

                            $select->store_result();

                            if ($select->num_rows > 0) {

                                $json_result = array();

                                while ($select->fetch()) {

                                    // $json_result .= "{";

                                    $json_result[] = array("leerling" => $studentname, "aantal" => $aantal_verzuim, "minutes" => $minutes);

                                    // $json_result .= "\"leerling\": \"". $studentname ."\",";

                                    // //$json_result .= "\"aantal\":\"$aantal_verzuim\" ";

                                    // $json_result .= "\"aantal\": $aantal_verzuim,";

                                    // // $json_result .= "},";

                                }

                                // $json_result .= "]";

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
