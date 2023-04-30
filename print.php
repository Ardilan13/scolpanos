<?php
include("config/app.config.php");
include 'document_start.php';

$leerling_html = "";
switch ($_GET['name']) {
    case "montessori":
        require_once("classes/spn_montessori.php"); //CODE CaribeDevelopers
        $montessori_print = new spn_montessori();
        $print_table = $montessori_print->print_montessori($_GET['student'], $_GET['klas'], $_GET['rapport']);
        break;
    case "houding":
        require_once("classes/spn_houding.php"); //CODE CaribeDevelopers
        $houding_print = new spn_houding(); //CODE CaribeDevelopers
        if ($_SESSION["SchoolID"] == 8) {
            $print_table = $houding_print->listhouding_school_8_klas_5($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
        } else if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 18) {
            $print_table = $houding_print->listhouding_skoa($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], 1);
        } else {
            $print_table = $houding_print->listhouding($_SESSION["SchoolJaar"], $_SESSION["SchoolID"], $_GET["klas"], $_GET["rapport"], "");
        }
        break;
    case "verzuim":
        require_once("classes/spn_verzuim.php"); //CODE CaribeDevelopers
        $s = new spn_verzuim();
        if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
            if (isset($_SESSION["Class"])) {
                $print_table = $s->listverzuimprint($_SESSION["SchoolID"], $_GET["klas"], $_GET["datum"], "");
            }
        } else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
            $print_table = $s->listverzuimprint($_SESSION["SchoolID"], $_GET["klas"], $_GET["datum"], "");
        }
        break;
    case "leerling":
        require_once("classes/spn_leerling.php");
        $leerling_print = new spn_leerling();
        if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
            if (isset($_SESSION["Class"])) {
                $print_table = $leerling_print->liststudents($_SESSION["SchoolID"], $_SESSION["Class"], $baseurl, $detailpage);
            }
        } else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
            $print_table = $leerling_print->liststudents($_SESSION["SchoolID"], "ALL", $baseurl, $detailpage);
        }
        break;
    case "cijfers":
        require_once("classes/spn_cijfers.php"); //CODE CaribeDevelopers
        $s = new spn_cijfers();
        if ($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT") {
            if (isset($_SESSION["Class"])) {
                $print_table =  $s->listcijfers($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["cijfers_vakken_lijst"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
            }
        } else if ($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING") {
            $print_table = $s->listcijfers($_SESSION["SchoolID"], $_GET["cijfers_klassen_lijst"], $_GET["cijfers_vakken_lijst"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);
        }
        break;
    case "cijfers_teacher":
        require_once("classes/spn_cijfers.php"); //CODE CaribeDevelopers
        $s = new spn_cijfers();

        $print_table =  $s->listcijfers($_SESSION["SchoolID"], $_GET["list_class_teacher"], $_GET["cijfers_vakken_list_teacher"], $_GET["cijfers_rapporten_lijst"], "", $_SESSION["SchoolJaar"]);

        break;
    case "facturen":
        require_once("classes/spn_paymentinvoice.php"); //CODE CaribeDevelopers
        $s = new spn_paymentinvoice();
        $print_table = $s->listpaymentinvoice($baseurl, $detailpage, null);
        break;
    case "avi":
        require_once("classes/spn_avi.php"); //CODE CaribeDevelopers
        $a = new spn_avi();
        $userGUID = $_SESSION["UserGUID"];
        $UserRights = $_SESSION["UserRights"];
        if ($UserRights == "BEHEER" || $UserRights == "ADMIN")
            $class = (isset($_POST['class']) ? ($_POST['class'] == "All" ? "" : $_POST['class']) : "");
        else
            $class = $_SESSION["Class"];

        $print_table = $a->get_avi($_SESSION["SchoolID"], $class, $_SESSION["SchoolJaar"], appconfig::GetDummy());
        break;
    case "kalendar":
        require_once("classes/spn_calendar.php"); //CODE CaribeDevelopers
        $c = new spn_calendar();
        $docent = $_SESSION["UserGUID"];
        $print_table = $c->read_calendar_json($docent, appconfig::GetDummy());
        break;
    case "leerling_detail_contact":
        require_once("classes/spn_contact.php");
        $c = new spn_contact();
        $print_table = $c->list_contacts($_GET["id_family"], null);

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";
        break;
    case "leerling_detail_event":
        require_once("classes/spn_event.php");
        $c = new spn_event();
        $print_table = $c->list_event($_SESSION["SchoolJaar"], null, $_GET["id"], null);

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";
        break;
    case "leerling_detail_swv":
        require_once("classes/spn_social_work.php");
        $c = new spn_social_work();
        $print_table = $c->get_social_work($_SESSION["SchoolJaar"], 0, $_GET["id"], appconfig::GetDummy());

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";
        break;
    case "leerling_detail_mdc":
        require_once("classes/spn_mdc.php");
        $c = new spn_mdc();
        $print_table = $c->get_mdc($_SESSION["SchoolJaar"], 0, $_GET["id"], appconfig::GetDummy());

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;

    case "leerling_detail_remedial":
        require_once("classes/spn_remedial.php");
        $c = new spn_remedial();
        $print_table =  $c->get_remedial($_GET["schoolJaar"], null, $_GET["id"], appconfig::GetDummy());

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;


    case "leerling_inner_detail_remedial":


        require_once("classes/spn_remedial.php");
        $c = new spn_remedial();

        $print_table =  $c->get_remedial_detail_print(0, $_GET["id_remedial"], appconfig::GetDummy());
        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }
        $leerling_html = "<table class=\"table table-bordered table-colored\">
                  <tbody>
                      <tr>
                          <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                          <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                          <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";
        break;



    case "leerling_detail_cijfer":
        require_once("classes/spn_cijfers.php");
        $c = new spn_cijfers();
        require_once("classes/spn_cijfers_mobile.php");
        $cm = new spn_cijfers_mobile();
        if ($_GET["schoolJaar"] == "2022-2023" && $_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 11) {
            $print_table = $c->list_cijfers_by_student_ps($_GET["schoolJaar"], $_GET["id"], appconfig::GetDummy());
        } else {
            $print_table = $c->list_cijfers_by_student($_GET["schoolJaar"], $_GET["id"], appconfig::GetDummy());
        }

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;
    case "leerling_detail_houding":
        require_once("classes/spn_houding.php");
        $c = new spn_houding();
        $print_table = $c->listhoudingbystudent($_GET["schoolJaar"], $_GET["id"]); //CODE CaribeDevelopers

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;
    case "leerling_detail_verzuim":
        if ($_SESSION["SchoolType"] == 1 || $_SESSION["SchoolID"] == 16) {
            require_once("classes/spn_verzuim.php");
            $c = new spn_verzuim();
            $print_table = $c->list_verzuim_by_student($_GET["schoolJaar"], $_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers

            require_once("classes/spn_leerling.php");
            $l = new spn_leerling();
            $data_leerling = $l->liststudentdetail($_GET["id"]);
            if ($data_leerling["results"] == 1) {
                $studentid = $data_leerling["studentid"];
                $studentnumber = $data_leerling["studentnumber"];
                $voornamen = $data_leerling["voornamen"];
                $achternaam = $data_leerling["achternaam"];
                $geslacht = $data_leerling["geslacht"];
                $geboortedatum = $data_leerling["geboortedatum"];
                $klas = $data_leerling["klas"];
            }

            $leerling_html = "<table class=\"table table-bordered table-colored\">
                        <tbody>
                            <tr>
                                <td><strong>Student nr.</strong></td>
                                <td>" . $studentnumber . "</td>
                                <td><strong>Klas</strong></td>
                                <td id=\"klas\" name=\"klas\">.
                                " . $klas . "</td>
                            </tr>
                            <tr>
                                <td><strong>Achternaam</strong></td>
                                <td> " . $achternaam . " </td>
                                <td><strong>Voornamen</strong></td>
                                <td> " . $voornamen . "</td>
                            </tr>
                            <tr>
                                <td><strong>Geslacht</strong></td>
                                <td>" . $geslacht . "</td>
                               <td><strong>Geboortedatum</strong></td>
                                    <td>" . $geboortedatum . "</td>
                            </tr>
                        </tbody>
                    </table>";
        } else {
            $studentid = $_GET['id'];
            require_once("classes/spn_leerling.php");
            $l = new spn_leerling();
            $data_leerling = $l->liststudentdetail($_GET["id"]);
            if ($data_leerling["results"] == 1) {
                $studentid = $data_leerling["studentid"];
                $studentnumber = $data_leerling["studentnumber"];
                $voornamen = $data_leerling["voornamen"];
                $achternaam = $data_leerling["achternaam"];
                $geslacht = $data_leerling["geslacht"];
                $geboortedatum = $data_leerling["geboortedatum"];
                $klas = $data_leerling["klas"];
            }

            $leerling_html = "<table class=\"table table-bordered table-colored\">
                        <tbody>
                            <tr>
                                <td><strong>Student nr.</strong></td>
                                <td>" . $studentnumber . "</td>
                                <td><strong>Klas</strong></td>
                                <td id=\"klas\" name=\"klas\">.
                                " . $klas . "</td>
                            </tr>
                            <tr>
                                <td><strong>Achternaam</strong></td>
                                <td> " . $achternaam . " </td>
                                <td><strong>Voornamen</strong></td>
                                <td> " . $voornamen . "</td>
                            </tr>
                            <tr>
                                <td><strong>Geslacht</strong></td>
                                <td>" . $geslacht . "</td>
                               <td><strong>Geboortedatum</strong></td>
                                    <td>" . $geboortedatum . "</td>
                            </tr>
                        </tbody>
                    </table>";
            $DBCreds = new DBCreds();
            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
            $mysqli->set_charset('utf8');
            $query = "SELECT datum,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10 FROM le_verzuim_hs WHERE studentid = $studentid";

            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) {
                $print_table = '<table id="tbl_rappor_verzuim_table1" class="table table-striped table-bordered">
            <thead>

            <tr>

            <th>Date</th>

            <th>p1</th>

            <th>p2</th>
            <th>p3</th>
            <th>p4</th>
            <th>p5</th>
            <th>p6</th>
            <th>p7</th>
            <th>p8</th>
            <th>p9</th>
            <th>dag</th>

            </tr>

            </thead>

            <tbody>';
                while ($row = mysqli_fetch_assoc($resultado)) {
                    if ($row["p1"] != '0' || $row["p2"] != '0' || $row["p3"] != '0' || $row["p4"] != '0' || $row["p5"] != '0' || $row["p6"] != '0' || $row["p7"] != '0' || $row["p8"] != '0' || $row["p9"] != '0' || $row["p10"] != '0') {
                        $xd = 1;
                        $print_table .= '<tr>
                    
                    <td>' . $row["datum"] . '</td>


                    <td>' . $row["p1"] . '</td>
                    <td>' . $row["p2"] . '</td>
                    <td>' . $row["p3"] . '</td>
                    <td>' . $row["p4"] . '</td>
                    <td>' . $row["p5"] . '</td>
                    <td>' . $row["p6"] . '</td>
                    <td>' . $row["p7"] . '</td>
                    <td>' . $row["p8"] . '</td>
                    <td>' . $row["p9"] . '</td>
                    <td> ' . $row["p10"] . '</td>

                    </tr>';
                    }
                }

                $print_table .= "</tbody>

            </table>";

                if ($xd == 0) {
                    echo "No results to show";
                }
            } else {
                echo "No results to show";
            }
        }

        break;
    case "leerling_detail_avi":
        require_once("classes/spn_avi.php");
        $c = new spn_avi();
        $print_table = $c->list_avi_by_student($_GET["id"], $_GET["schoolJaar"], false); //CODE CaribeDevelopers

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;
    case "leerling_detail_financial":
        require_once("classes/spn_paymentinvoice.php");
        $c = new spn_paymentinvoice();
        $print_table = $c->list_invoice_by_student($_GET["id"], appconfig::GetDummy()); //CODE CaribeDevelopers

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["id"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Student nr.</strong></td>
                            <td>" . $studentnumber . "</td>
                            <td><strong>Klas</strong></td>
                            <td id=\"klas\" name=\"klas\">.
                            " . $klas . "</td>
                        </tr>
                        <tr>
                            <td><strong>Achternaam</strong></td>
                            <td> " . $achternaam . " </td>
                            <td><strong>Voornamen</strong></td>
                            <td> " . $voornamen . "</td>
                        </tr>
                        <tr>
                            <td><strong>Geslacht</strong></td>
                            <td>" . $geslacht . "</td>
                           <td><strong>Geboortedatum</strong></td>
                                <td>" . $geboortedatum . "</td>
                        </tr>
                    </tbody>
                </table>";

        break;
    case "leerling_personalia":
        require_once("classes/spn_leerling.php");
        $c = new spn_leerling();
        $print_table = $c->read_leerling_detail_info_merge($_GET["id"], appconfig::GetDummy(), $_GET["idfamily"]);



        break;
    case "rapport_verzuim1":

        $verzuim = "";
        if ($_GET["te_laat"] == 1) {
            $verzuim = "Te Laat";
        }
        if ($_GET["absent"] == 1) {
            $verzuim = "Absent";
        }
        if ($_GET["inhalen"] == 1) {
            $verzuim = "Toets inhalen";
        }
        if ($_GET["uitsturen"] == 1) {
            $verzuim = $_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen';
        }
        if ($_GET["huiswerk"] == 1) {
            $verzuim = "Geen huiswerk";
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                    <tbody>
                        <tr>
                            <td><strong>Start Date</strong></td>
                            <td>" . $_GET["start_date"] . "</td>
                            <td><strong>End Date</strong></td>
                            <td>" . $_GET["end_date"] . "</td>
                            <td><strong>Klas</strong></td>
                            <td >" . $_GET["klas"] . "</td>
                            <td><strong>Verzuim</strong></td>
                            <td >" . $verzuim . "</td>
                        </tr>

                    </tbody>
                </table>";
        require_once("classes/spn_utils.php");
        require_once("classes/spn_rapport_verzuim.php");
        $c = new spn_rapport_verzuim();
        $u = new spn_utils();
        $print_table = $c->list_rapport_verzuim_table1($u->converttomysqldatetime($_GET["start_date"]), $u->converttomysqldatetime($_GET["end_date"]), $_GET["klas"], $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["te_laat"], $_GET["absent"], $_GET["inhalen"], $_GET["uitsturen"], $_GET["huiswerk"], false);

        break;
    case "rapport_verzuim2":

        $verzuim = "";
        if ($_GET["te_laat"] == 1) {
            $verzuim = "Te Laat";
        }
        if ($_GET["absent"] == 1) {
            $verzuim = "Absent";
        }
        if ($_GET["inhalen"] == 1) {
            $verzuim = "Toets inhalen";
        }
        if ($_GET["uitsturen"] == 1) {
            $verzuim = $_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen';
        }
        if ($_GET["huiswerk"] == 1) {
            $verzuim = "Geen huiswerk";
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                        <tbody>
                            <tr>
                                <td><strong>Start Date</strong></td>
                                <td>" . $_GET["start_date"] . "</td>
                                <td><strong>End Date</strong></td>
                                <td>" . $_GET["end_date"] . "</td>
                                <td><strong>Klas</strong></td>
                                <td >" . $_GET["klas"] . "</td>
                                <td><strong>Verzuim</strong></td>
                                <td >" . $verzuim . "</td>
                            </tr>

                        </tbody>
                    </table>";
        require_once("classes/spn_utils.php");
        require_once("classes/spn_rapport_verzuim.php");
        $c = new spn_rapport_verzuim();
        $u = new spn_utils();
        $print_table = $c->list_rapport_verzuim_table2($u->converttomysqldatetime($_GET["start_date"]), $u->converttomysqldatetime($_GET["end_date"]), $_GET["klas"], $_SESSION["SchoolID"], $_SESSION["SchoolJaar"], $_GET["te_laat"], $_GET["absent"], $_GET["inhalen"], $_GET["uitsturen"], $_GET["huiswerk"], false);

        break;
    case "rapport_verzuim_grafiek_leerling":

        $verzuim = "";
        if ($_GET["te_laat"] == 1) {
            $verzuim = "Te Laat";
        }
        if ($_GET["absent"] == 1) {
            $verzuim = "Absent";
        }
        if ($_GET["inhalen"] == 1) {
            $verzuim = "Toets inhalen";
        }
        if ($_GET["uitsturen"] == 1) {
            $verzuim = $_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen';
        }
        if ($_GET["huiswerk"] == 1) {
            $verzuim = "Geen huiswerk";
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
  <tbody>
  <tr>
  <td><strong>Start Date</strong></td>
  <td>" . $_GET["start_date"] . "</td>
  <td><strong>End Date</strong></td>
  <td>" . $_GET["end_date"] . "</td>
  <td><strong>Klas</strong></td>
  <td >" . $_GET["klas"] . "</td>
  <td><strong>Verzuim</strong></td>
  <td >" . $verzuim . "</td>
  </tr>

  </tbody>
  </table>";
        // require_once("classes/spn_utils.php");
        // require_once("classes/spn_rapport_verzuim.php");
        // $c = new spn_rapport_verzuim();
        // $u = new spn_utils();
        // $print_table = $c->list_rapport_verzuim_table2($u->converttomysqldatetime($_GET["start_date"]), $u->converttomysqldatetime($_GET["end_date"]),$_GET["klas"],$_SESSION["SchoolID"],$_SESSION["SchoolJaar"],$_GET["te_laat"],$_GET["absent"], $_GET["inhalen"],$_GET["uitsturen"],$_GET["huiswerk"],false);

        break;
    case "rapport_verzuim_grafiek_klas":

        $verzuim = "";
        if ($_GET["te_laat"] == 1) {
            $verzuim = "Te Laat";
        }
        if ($_GET["absent"] == 1) {
            $verzuim = "Absent";
        }
        if ($_GET["inhalen"] == 1) {
            $verzuim = "Toets inhalen";
        }
        if ($_GET["uitsturen"] == 1) {
            $verzuim = $_SESSION['SchoolType'] == 1 ? 'Naar huis' : 'Spijbelen';
        }
        if ($_GET["huiswerk"] == 1) {
            $verzuim = "Geen huiswerk";
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
  <tbody>
  <tr>
  <td><strong>Start Date</strong></td>
  <td>" . $_GET["start_date"] . "</td>
  <td><strong>End Date</strong></td>
  <td>" . $_GET["end_date"] . "</td>
  <td><strong>Klas</strong></td>
  <td >" . $_GET["klas"] . "</td>
  <td><strong>Verzuim</strong></td>
  <td >" . $verzuim . "</td>
  </tr>

  </tbody>
  </table>";
        // require_once("classes/spn_utils.php");
        // require_once("classes/spn_rapport_verzuim.php");
        // $c = new spn_rapport_verzuim();
        // $u = new spn_utils();
        // $print_table = $c->list_rapport_verzuim_table2($u->converttomysqldatetime($_GET["start_date"]), $u->converttomysqldatetime($_GET["end_date"]),$_GET["klas"],$_SESSION["SchoolID"],$_SESSION["SchoolJaar"],$_GET["te_laat"],$_GET["absent"], $_GET["inhalen"],$_GET["uitsturen"],$_GET["huiswerk"],false);

        break;
    case "opmerking":
        require_once("classes/spn_opmerking.php");
        $c = new spn_opmerking();
        $print_table = $c->print_opmerking($_SESSION["SchoolJaar"], $_GET["student"], $_SESSION["SchoolID"], $_GET["klas"]); //CODE CaribeDevelopers

        require_once("classes/spn_leerling.php");
        $l = new spn_leerling();
        $data_leerling = $l->liststudentdetail($_GET["student"]);
        if ($data_leerling["results"] == 1) {
            $studentid = $data_leerling["studentid"];
            $studentnumber = $data_leerling["studentnumber"];
            $voornamen = $data_leerling["voornamen"];
            $achternaam = $data_leerling["achternaam"];
            $geslacht = $data_leerling["geslacht"];
            $geboortedatum = $data_leerling["geboortedatum"];
            $klas = $data_leerling["klas"];
        }

        $leerling_html = "<table class=\"table table-bordered table-colored\">
                  <tbody>
                      <tr>
                          <td><strong>Student nr.</strong></td>
                          <td>" . $studentnumber . "</td>
                          <td><strong>Klas</strong></td>
                          <td id=\"klas\" name=\"klas\">.
                          " . $klas . "</td>
                      </tr>
                      <tr>
                          <td><strong>Achternaam</strong></td>
                          <td> " . $achternaam . " </td>
                          <td><strong>Voornamen</strong></td>
                          <td> " . $voornamen . "</td>
                      </tr>
                      <tr>
                          <td><strong>Geslacht</strong></td>
                          <td>" . $geslacht . "</td>
                         <td><strong>Geboortedatum</strong></td>
                              <td>" . $geboortedatum . "</td>
                      </tr>
                  </tbody>
              </table>";

        break;



    default:
        echo "Your favorite color is neither red, blue, nor green!";
}
?>
<div id="container_print">
    <div class="container">
        <main id="main" role="main">

            <section>
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                        <div class="row">
                            <div class="col-md-12">
                                <table border="0">
                                    <tr>
                                        <td width="310px">
                                            <?php
                                            if ($_SESSION["SchoolID"] == 11 && $_GET['name'] == 'montessori') { ?>
                                                <img src="<?php print appconfig::GetBaseURL(); ?>/assets/img/angela.png" width="100px" height="100px" class="block-center img-responsive" alt="Scol pa Nos">
                                            <?php } else { ?>
                                                <img src="<?php print appconfig::GetBaseURL(); ?>/assets/img/logo_spn_small.png" width="100px" height="100px" class="block-center img-responsive" alt="Scol pa Nos">
                                            <?php }
                                            ?>
                                            <h5 class="col-md-12 secondary-color"><?php echo $_SESSION["schoolname"] ?></h5>
                                        </td>
                                        <td align="CENTER">
                                            <h1 class="col-md-12 primary-color"><?php echo $_GET['title']; ?></h1>
                                        </td>
                                        <td align="CENTER">
                                            <div class='square' style="float: right; border-width: 1px; height: 100px; width: 100px; border-color: #000000f5; background-color: #fff0; border-style: solid;"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div id="div_content_leerling">
                        <?php echo $leerling_html; ?>

                    </div>
                    <div id="div_data_table" class="col-md-12">
                        <table><?php echo $print_table ?></table>
                    </div>
                    <div id="div_data_grafiek" class="col-md-10">

                    </div>
                    <?php
                    if ($_SESSION["SchoolID"] == 11 && $_GET['name'] == 'montessori') {
                        $klas = $_GET["klas"];
                        require_once "classes/DBCreds.php";
                        $DBCreds = new DBCreds();
                        date_default_timezone_set("America/Aruba");
                        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
                        $query = "SELECT FirstName, LastName FROM app_useraccounts WHERE Class = '$klas' AND SchoolID = 11 AND UserRights = 'DOCENT' LIMIT 1";
                        $resultado = mysqli_query($mysqli, $query);
                        while ($row = mysqli_fetch_assoc($resultado)) {
                            $teacher = $row["FirstName"] . " " . $row["LastName"];
                        }
                    ?>
                        <div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%;'>
                            <h5 style='margin-bottom: .5rem !important;display: inline;'>Naam schoolboofd: Cornelia Aventurin Connor</h5>
                            <p>.................................................................</p>
                        </div>
                        <div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%;'>
                            <h5 style='margin-bottom: .5rem !important;display: inline;'>Naam leerkracht: <?php echo $teacher; ?></h5>
                            <p>.................................................................</p>
                        </div>
                    <?php } else { ?>
                        <h5 class="text-left">User print by:<?php echo $_SESSION['User']; ?></h5>
                        <h5 class="text-left"><?php echo "User Name: " . $_SESSION["FirstName"] . " " . $_SESSION["LastName"] ?></h5>
                    <?php } ?>


                </div>
            </section>
        </main>
    </div>
</div>
<?php include 'document_end.php'; ?>
<script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/print.js"></script>
<script type="text/javascript">
    var type_grafiek = '<?php echo $_GET['name']; ?>';

    var rapport_klassen_lijst = '<?php echo $_GET['klas']; ?>';
    start_date = '<?php echo $_GET['start_date']; ?>',
        end_date = '<?php echo $_GET['end_date']; ?>',
        _rappor_te_laat = '<?php echo $_GET['te_laat']; ?>',
        _rappor_absent = '<?php echo $_GET['absent']; ?>',
        _rappor_inhalen = '<?php echo $_GET['inhalen']; ?>',
        _rappor_uitsturen = '<?php echo $_GET['uitsturen']; ?>',
        _rappor_huiswerk = '<?php echo $_GET['huiswerk']; ?>';

    if (type_grafiek == "rapport_verzuim_grafiek_leerling") {

        Modernizr.load({
            test: $.kendoChart,
            nope: [WebApi.Config.baseUri + 'assets/telerik/styles/kendo.common.min.css', WebApi.Config.baseUri + 'assets/telerik/styles/kendo.default.min.css', WebApi.Config.baseUri + 'assets/telerik/js/kendo.all.min.js'],
            complete: function executeTelerik() {

                $("#div_data_grafiek").kendoChart({
                    dataSource: {
                        transport: {
                            read: {
                                url: "ajax/get_rapport_verzuim_grafiek.php?rapport_klassen_lijst=" +
                                    rapport_klassen_lijst +
                                    "&start_date=" + start_date +
                                    "&end_date=" + end_date +
                                    "&rappor_te_laat=" + _rappor_te_laat +
                                    "&rappor_absent=" + _rappor_absent +
                                    "&rappor_inhalen=" + _rappor_inhalen +
                                    "&rappor_uitsturen=" + _rappor_uitsturen +
                                    "&rappor_huiswerk=" + _rappor_huiswerk,
                                dataType: "json"
                            }
                        },
                        sort: {
                            field: "leerling",
                            dir: "asc"
                        }
                    },
                    title: {
                        text: "Verzuim Resume by Leerling"
                    },
                    legend: {
                        position: "top"
                    },
                    seriesDefaults: {
                        type: "column"
                    },
                    series: [{
                        labels: {
                            visible: true
                        },
                        field: "aantal",
                        name: "Aantal",
                        color: "#8AD6E2"
                    }],
                    categoryAxis: {
                        field: "leerling",
                        labels: {
                            rotation: -90
                        },
                        majorGridLines: {
                            visible: true
                        }
                    },
                    valueAxis: {
                        labels: {
                            format: "N0"
                        },
                        majorUnit: 10,
                        line: {
                            visible: true
                        }
                    },
                    tooltip: {
                        visible: true,
                        format: "N0"
                    }
                });
            }
        });
    }
    if (type_grafiek == "rapport_verzuim_grafiek_klas") {
        Modernizr.load({
            test: $.kendoChart,
            nope: [WebApi.Config.baseUri + 'assets/telerik/styles/kendo.common.min.css', WebApi.Config.baseUri + 'assets/telerik/styles/kendo.default.min.css', WebApi.Config.baseUri + 'assets/telerik/js/kendo.all.min.js'],
            complete: function executeTelerik() {

                $("#div_data_grafiek").kendoChart({

                    dataSource: {
                        transport: {
                            read: {
                                //url: "ajax/cijfers-graph.php",
                                url: "ajax/get_rapport_verzuim_grafiek_resume.php?rapport_klassen_lijst=" +
                                    rapport_klassen_lijst +
                                    "&start_date=" + start_date +
                                    "&end_date=" + end_date +
                                    "&rappor_te_laat=" + _rappor_te_laat +
                                    "&rappor_absent=" + _rappor_absent +
                                    "&rappor_inhalen=" + _rappor_inhalen +
                                    "&rappor_uitsturen=" + _rappor_uitsturen +
                                    "&rappor_huiswerk=" + _rappor_huiswerk,
                                dataType: "json"
                            }
                        },
                        sort: {
                            field: "klas",
                            dir: "asc"
                        }
                    },
                    title: {
                        text: "Verzuim Resume by Klas"
                    },
                    legend: {
                        position: "top"
                    },
                    seriesDefaults: {
                        type: "column"
                    },
                    series: [{
                        labels: {
                            visible: true
                        },
                        field: "aantal",
                        name: "Aantal",
                        color: "#8AD6E2"
                    }],
                    categoryAxis: {
                        field: "klas",
                        labels: {
                            rotation: -90
                        },
                        majorGridLines: {
                            visible: true
                        }
                    },
                    valueAxis: {
                        labels: {
                            format: "N0"
                        },
                        majorUnit: 10,
                        line: {
                            visible: true
                        }
                    },
                    tooltip: {
                        visible: true,
                        format: "N0"
                    }
                });
            }
        });
    }

    $(function() {

        $("#dataRequest-student tr").each(function(index, element) {
            $(element).find("td").eq(6).remove();
            $(element).find("th").eq(6).remove();
        });


        // $("#container_print").printThis();

    })

    $(window).load(function() {

        setTimeout(function() {
            window.print();
        }, 2000);
        $('select').prop('disabled', true);
        // $("#container_print").printThis();

    });
</script>