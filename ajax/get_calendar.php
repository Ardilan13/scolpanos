<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
require_once "../classes/spn_utils.php";
$utils = new spn_utils();

$start = $_POST["start_date"];
$end = $_POST["end_date"];
$start = $utils->convertfrommysqldate_new($start);
$end = $utils->convertfrommysqldate_new($end);
$schoolid = $_SESSION["SchoolID"];
$i = 1;
?>
<style>
    .opmerking {
        width: 50%;
    }

    .opmerking_input {
        width: 100%;
    }

    .definitiet {
        width: 3% !important;
    }

    .definitiet_input {
        width: 5rem;
    }
</style>
<table class="table table-bordered table-colored table-houding">
    <thead>
        <tr>
            <th>Datum</th>
            <th class="definiet">Docent</th>
            <th>Class</th>
            <th>Subject</th>
            <th class="opmerking">Observations</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $get_students = "SELECT (SELECT CONCAT(firstname , ' ', lastname) as name FROM app_useraccounts WHERE UserGUID = c.docent) as docent, c.class, c.date, c.subject, c.observations FROM calendar c WHERE c.date >= '$start' AND c.date <= '$end' AND c.SchoolID = $schoolid;";
        $result = mysqli_query($mysqli, $get_students);
        if (mysqli_num_rows($result) == 0) {
            echo "<tr><td colspan='5'>No data found</td></tr>";
        } else {
            while ($row1 = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row1["date"]; ?></td>
                    <td><?php echo $row1["docent"]; ?></td>
                    <td><?php echo $row1["class"]; ?></td>
                    <td>
                        <?php switch ($row1["subject"]) {
                            case "Homework":
                                echo "Huiswerk";
                                break;
                            case "Test":
                                echo "Overhoring";
                                break;
                            case "Exam":
                                echo "Toets Proefwerk";
                                break;
                            case "Other":
                                echo "Anders";
                                break;
                            default:
                                echo $row1["subject"];
                                break;
                        } ?>
                    </td>
                    <td><?php echo $row1["observations"]; ?></td>
                </tr>
        <?php $i++;
            }
        } ?>
    </tbody>
</table>