<?php
include '../document_start.php';
include '../classes/DBCreds.php';
require_once("../classes/spn_montessori.php"); //CODE CaribeDevelopers
$montessori_print = new spn_montessori();

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$get_students = "SELECT id FROM students WHERE class = '" . $_GET["klas"] . "' AND schoolid = 11 ORDER BY lastname ASC, firstname ASC";
$result = mysqli_query($mysqli, $get_students);
$students = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($students, $row["id"]);
    }
}
?>

<div id="container_print">
    <div class="container">
        <main id="main" role="main">
            <?php foreach ($students as $row) {
                $print_table = $montessori_print->print_montessori($row, $_GET['klas'], $_GET['rapport']);
            ?>
                <section style="margin-bottom: 700px;">
                    <div class="row">
                        <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                            <div class="row">
                                <div class="col-md-12">
                                    <table border="0">
                                        <tr>
                                            <td width="310px">
                                                <img src="<?php print appconfig::GetBaseURL(); ?>/assets/img/angela.png" width="100px" height="100px" class="block-center img-responsive" alt="Scol pa Nos">
                                                <h5 class="col-md-12 secondary-color"><?php echo $_SESSION["schoolname"] ?></h5>
                                                <?php $get_student = "SELECT firstname, lastname FROM students WHERE id = " . $row;
                                                $result = mysqli_query($mysqli, $get_student);
                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $student_name = $row["lastname"] . " " . $row["firstname"];
                                                    }
                                                } ?>
                                                <h6>Schooljaar: <?php echo $_SESSION["SchoolJaar"] ?></h6>
                                                <h6>Klas: <?php echo $_GET["klas"] ?></h6>
                                                <h6>Rapport: <?php echo $_GET["rapport"] ?></h6>
                                                <h6>Leerling: <?php echo $student_name ?></h6>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div id="div_data_table" class="col-md-12">
                            <table><?php echo $print_table ?></table>
                        </div>
                        <div id="div_data_grafiek" class="col-md-10">

                        </div>
                        <?php
                        $klas = $_GET["klas"];
                        $schoolJaar = $_SESSION["SchoolJaar"];
                        $query = "SELECT FirstName, LastName FROM app_useraccounts WHERE Class = '$klas' AND SchoolID = 11 AND UserRights = 'DOCENT' LIMIT 1";
                        $resultado = mysqli_query($mysqli, $query);
                        while ($row = mysqli_fetch_assoc($resultado)) {
                            $teacher = $row["FirstName"] . " " . $row["LastName"];
                        }

                        $query1 = "SELECT concat(a.FirstName, ' ', a.LastName) as name,a.signature FROM setting s INNER JOIN app_useraccounts a ON s.director = a.UserGUID WHERE s.schoolid = 11 AND s.year_period = '$schoolJaar'";
                        $resultado1 = mysqli_query($mysqli, $query1);
                        if (mysqli_num_rows($resultado1) > 0) {
                            while ($row1 = mysqli_fetch_assoc($resultado1)) {
                                $cabesante = $row1["name"];
                                $signature_dir = $row1["signature"];
                            }
                        }
                        ?>
                        <div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%; align-items: end; min-height: 100px;'>
                            <h5 style='margin-bottom: .5rem !important;display: inline;'>Naam schoolboofd: <?php echo $cabesante; ?></h5>
                            <?php if ($signature_dir != "" && $signature_dir != NULL) { ?>
                                <img src='../signatures/<?php echo $signature_dir; ?> ' width=' 230' height='80'>
                            <?php } else { ?>
                                <p style="margin-bottom: 0;">.................................................................</p>
                            <?php } ?>
                        </div>
                        <div style='display:flex; flex-direction: row; justify-content: space-between; width: 100%; align-items:end; min-height: 100px;'>
                            <h5 style='margin-bottom: .5rem !important;display: inline;'>Naam leerkracht: <?php echo $teacher; ?></h5>
                            <p style="margin-bottom: 0;">.................................................................</p>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </main>
    </div>
</div>
<?php include 'document_end.php'; ?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            window.print();
        }, 1000);
    });
</script>