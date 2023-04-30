<?php
require_once "../classes/DBCreds.php";
require_once "../classes/3rdparty/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$scol = $_POST["scol"];
$tipo = $_POST["tipo"];
if ($scol == 11) {
    $query = "SELECT ID,type,status,naam,voornamen,roepnaam,sexo,geboorteland,f_nacemento,adres,districto,identiteitsbewijs,verklaring,klas5,klas6,profielh3,profielh4,mavo4,profielm4 FROM aplicacion_college WHERE type = '$tipo' ORDER BY ID ASC";
} else if ($scol == 10) {
    $query = "SELECT ID,status,fam,nomber,sexo,f_nacemento,districto,ruman,collega,number_mayor1,number_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms ORDER BY ID ASC";
} else if ($scol == 3) {
    $query = "SELECT status,fam,nomber,sexo,f_nacemento,number_mayor1,number_mayor2,email_mayor1,email_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE (prome_preferencia = '$scol' OR segundo_preferencia = '$scol') ORDER BY status ASC, fam ASC, nomber ASC";
}

if ($scol != null) {
    $resultado = mysqli_query($mysqli, $query);
    header("Content-Type: application/xls");
    if ($scol != 11) {
        header("Content-Disposition: attachment; filename=aplicacion_form.xls");
    } else {
        if ($tipo == 0) {
            header("Content-Disposition: attachment; filename=aplicacion_college.xls");
        } else {
            header("Content-Disposition: attachment; filename=aplicacion_college-HAVO.xls");
        }
    }
    header("Pragma: no-cache");
    header("Expires: 0");
    if (mysqli_num_rows($resultado) != 0) { ?>
        <table class="table table-striped table-bordered" border="1">
            <thead>
                <caption>
                    <h1><?php
                        if ($scol == 1) {
                            echo 'Colegio Conrado Coronel';
                        } else if ($scol == 2) {
                            echo 'Fontein Kleuterschool';
                        } else if ($scol == 3) {
                            echo 'Prinses Amalia School';
                        } else if ($scol == 4) {
                            echo 'Reina Beatrix School';
                        } else if ($scol == 5) {
                            echo 'Scol Preparatorio Washington';
                        } else if ($scol == 6) {
                            echo 'Scol Primario Arco Iris';
                        } else if ($scol == 7) {
                            echo 'Scol Primario Kudawecha';
                        } else if ($scol == 8) {
                            echo 'Scol Basico Xander Bogaerts';
                        } else if ($scol == 10) {
                            echo 'Admin';
                        } else if ($scol == 11) {
                            if ($tipo == 0) {
                                echo 'Mon Plaisir';
                            } else {
                                echo 'Mon Plaisir HAVO';
                            }
                        }

                        ?></h1>
                </caption>
                <?php if ($scol == 11) { ?>
                    <tr>
                        <th class="sex">ID</th>
                        <th class="status">Status</th>
                        <th class="datum">Naam</th>
                        <th class="datum">Voornamen</th>
                        <th class="datum">Roepnaam</th>
                        <th class="sex">Sexo</th>
                        <th>Geboorteland</th>
                        <th class="datum">Fecha di nacemento</th>
                        <th class="datum">Adres</th>
                        <th class="datum">Districto</th>
                        <th>Identiteitsbewijs</th>
                        <th>Verklaring</th>
                        <?php if ($tipo == 1) { ?>
                            <th>HAVO 3</th>
                            <th>Profiel H3</th>
                            <th>HAVO 4</th>
                            <th>Profiel H4</th>
                            <th>MAVO 4</th>
                            <th>Profiel M4</th>
                        <?php } else {  ?>
                            <th>Klas5</th>
                            <th>Klas6</th>
                        <?php } ?>
                        <th class="sex">Info</th>
                    </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <th class="sex"><?php echo $row["ID"] ?></th>
                        <th class="sex"><?php if ($row["status"] == '1') { ?>
                                accepted
                            <?php } else if ($row["status"] == '2') { ?>
                                rejected
                            <?php  } else { ?>
                                waiting
                            <?php } ?></th>
                        <th><?php echo $row["naam"] ?></th>
                        <th><?php echo $row["voornamen"] ?></th>
                        <th><?php echo $row["roepnaam"] ?></th>
                        <th class="sex"><?php if ($row["sexo"] == 1) {
                                            echo 'M';
                                        } else {
                                            echo 'F';
                                        } ?></th>
                        <th><?php echo $row["geboorteland"] ?></th>
                        <th class="datum"><?php echo $row["f_nacemento"] ?></th>
                        <th><?php echo $row["adres"] ?></th>
                        <th class="datum"><?php if ($row["districto"] == 1) {
                                                echo 'Noord';
                                            } else if ($row["districto"] == 2) {
                                                echo 'Oranjestad';
                                            } else if ($row["districto"] == 3) {
                                                echo 'Paradera';
                                            } else if ($row["districto"] == 4) {
                                                echo 'San Nicolaas';
                                            } else if ($row["districto"] == 5) {
                                                echo 'Santa Cruz';
                                            } else {
                                                echo 'Savaneta';
                                            } ?></th>
                        <th><?php if ($row["identiteitsbewijs"] != null && $row["identiteitsbewijs"] != "") { ?>
                                <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["identiteitsbewijs"]; ?>">Download.</a>
                            <?php } else {
                                echo "Empty.";
                            } ?>
                        </th>
                        <th><?php if ($row["verklaring"] != null && $row["verklaring"] != "") { ?>
                                <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["verklaring"]; ?>">Download.</a>
                            <?php } else {
                                echo "Empty.";
                            } ?>
                        </th>
                        <?php if ($tipo == 1) { ?>
                            <th><?php if ($row["klas5"] != null && $row["identiteitsbewijs"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["klas5"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["profielh3"] != null && $row["profielh3"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["profielh3"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["klas6"] != null && $row["klas6"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["klas6"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["profielh4"] != null && $row["profielh4"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["profielh4"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["mavo4"] != null && $row["mavo4"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["mavo4"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["profielm4"] != null && $row["profielm4"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["profielm4"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                        <?php } else {  ?>
                            <th><?php if ($row["klas5"] != null && $row["klas5"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["klas5"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                            <th><?php if ($row["klas6"] != null && $row["klas6"] != "") { ?>
                                    <a href="https://scolpanos.qwihi.com/ajax/<?php echo $row["klas6"]; ?>">Download.</a>
                                <?php } else {
                                    echo "Empty.";
                                } ?>
                            </th>
                        <?php } ?>
                        <th class="sex"><a href="https://scolpanos.qwihi.com/aplicacion_college-update.php?ID=<?php echo $row["ID"] ?>">+</a></th>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } else if ($scol == 3) { ?>
            <tr>
                <th class="status">Status</th>
                <th class="name">Fam</th>
                <th class="name">Nomber</th>
                <th class="sex">Sexo</th>
                <th class="datum">Fecha di nacemento</th>
                <th class="datum">Cellular 1</th>
                <th class="datum">Cellular 2</th>
                <th>Email 1</th>
                <th>Email 2</th>
                <th class="datum">Preferencia 1</th>
                <th class="datum">Preferencia 2</th>
            </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <th class="sex"><?php if ($row["status"] == '1') { ?>
                                accepted
                            <?php } else if ($row["status"] == '2') { ?>
                                rejected
                            <?php  } else { ?>
                                waiting
                            <?php } ?></th>
                        <th><?php echo $row["fam"] ?></th>
                        <th><?php echo $row["nomber"] ?></th>
                        <th class="sex"><?php if ($row["sexo"] == 1) {
                                            echo 'M';
                                        } else {
                                            echo 'F';
                                        } ?></th>
                        <th class="datum"><?php echo $row["f_nacemento"] ?></th>
                        <th class="datum"><?php echo $row["number_mayor1"] ?></th>
                        <th class="datum"><?php echo $row["number_mayor2"] ?></th>
                        <th class="datum"><?php echo $row["email_mayor1"] ?></th>
                        <th class="datum"><?php echo $row["email_mayor2"] ?></th>
                        <th><?php if ($row["prome_preferencia"] == 1) {
                                echo 'conrado';
                            } else if ($row["prome_preferencia"] == 2) {
                                echo 'kleuterschool';
                            } else if ($row["prome_preferencia"] == 3) {
                                echo 'amalia';
                            } else if ($row["prome_preferencia"] == 4) {
                                echo 'beatrix';
                            } else if ($row["prome_preferencia"] == 5) {
                                echo 'washington';
                            } else if ($row["prome_preferencia"] == 6) {
                                echo 'arco iris';
                            } else if ($row["prome_preferencia"] == 7) {
                                echo 'kudawecha';
                            } else if ($row["prome_preferencia"] == 8) {
                                echo 'xander';
                            } ?></th>
                        <th><?php if ($row["segundo_preferencia"] == 1) {
                                echo 'conrado';
                            } else if ($row["segundo_preferencia"] == 2) {
                                echo 'kleuterschool';
                            } else if ($row["segundo_preferencia"] == 3) {
                                echo 'amalia';
                            } else if ($row["segundo_preferencia"] == 4) {
                                echo 'beatrix';
                            } else if ($row["segundo_preferencia"] == 5) {
                                echo 'washington';
                            } else if ($row["segundo_preferencia"] == 6) {
                                echo 'arco iris';
                            } else if ($row["segundo_preferencia"] == 7) {
                                echo 'kudawecha';
                            } else if ($row["segundo_preferencia"] == 8) {
                                echo 'xander';
                            } ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } else if ($scol != 10) { ?>
            <tr>
                <th class="sex">
                    <h2>ID</h2>
                </th>
                <th class="status">
                    <h2>Status</h2>
                </th>
                <th>
                    <h2>Fam</h2>
                </th>
                <th>
                    <h2>Nomber</h2>
                </th>
                <th class="sex">
                    <h2>Sexo</h2>
                </th>
                <th class="datum">
                    <h2>Fecha di nacemento</h2>
                </th>
                <th class="datum">
                    <h2>Districto</h2>
                </th>
                <th class="status">
                    <h2>Ruman</h2>
                </th>
                <th class="status">
                    <h2>Collega</h2>
                </th>
                <th class="datum">
                    <h2>Cellular 1</h2>
                </th>
                <th class="datum">
                    <h2>Cellular 2</h2>
                </th>
                <th class="datum">
                    <h2>Preferencia</h2>
                </th>
            </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <th class="sex"><?php echo $row["ID"] ?></th>
                        <th class="sex"><?php if ($row["status"] == '1') { ?>
                                accepted
                            <?php } else if ($row["status"] == '2') { ?>
                                rejected
                            <?php  } else { ?>
                                waiting
                            <?php } ?></th>
                        <th><?php echo $row["fam"] ?></th>
                        <th><?php echo $row["nomber"] ?></th>
                        <th class="sex"><?php if ($row["sexo"] == 1) {
                                            echo 'M';
                                        } else {
                                            echo 'F';
                                        } ?></th>
                        <th class="datum"><?php echo $row["f_nacemento"] ?></th>
                        <th class="datum"><?php if ($row["districto"] == 1) {
                                                echo 'Noord';
                                            } else if ($row["districto"] == 2) {
                                                echo 'Oranjestad';
                                            } else if ($row["districto"] == 3) {
                                                echo 'Paradera';
                                            } else if ($row["districto"] == 4) {
                                                echo 'San Nicolaas';
                                            } else if ($row["districto"] == 5) {
                                                echo 'Santa Cruz';
                                            } else {
                                                echo 'Savaneta';
                                            } ?></th>
                        <th class="sex"><?php if ($row["ruman"] == 1) {
                                            echo 'Si';
                                        } else {
                                            echo 'No';
                                        } ?></th>
                        <th class="sex"><?php if ($row["collega"] == 1) {
                                            echo 'Si';
                                        } else {
                                            echo 'No';
                                        } ?></th>
                        <th class="datum"><?php echo $row["number_mayor1"] ?></th>
                        <th class="datum"><?php echo $row["number_mayor2"] ?></th>
                        <th><?php if ($row["prome_preferencia"] == 1) {
                                echo '1';
                            } else {
                                echo '2';
                            } ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } else { ?>
            <tr>
                <th class="sex">ID</th>
                <th class="status">Status</th>
                <th>Fam</th>
                <th>Nomber</th>
                <th class="sex">Sexo</th>
                <th class="datum">Fecha di nacemento</th>
                <th class="datum">Districto</th>
                <th class="status">Ruman</th>
                <th class="status">Collega</th>
                <th class="datum">Cellular 1</th>
                <th class="datum">Cellular 2</th>
                <th class="datum">Preferencia 1</th>
                <th class="datum">Preferencia 2</th>
            </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <th class="sex"><?php echo $row["ID"] ?></th>
                        <th class="sex"><?php if ($row["status"] == '1') { ?>
                                accepted
                            <?php } else if ($row["status"] == '2') { ?>
                                rejected
                            <?php  } else { ?>
                                waiting
                            <?php } ?></th>
                        <th><?php echo $row["fam"] ?></th>
                        <th><?php echo $row["nomber"] ?></th>
                        <th class="sex"><?php if ($row["sexo"] == 1) {
                                            echo 'M';
                                        } else {
                                            echo 'F';
                                        } ?></th>
                        <th class="datum"><?php echo $row["f_nacemento"] ?></th>
                        <th class="datum"><?php if ($row["districto"] == 1) {
                                                echo 'Noord';
                                            } else if ($row["districto"] == 2) {
                                                echo 'Oranjestad';
                                            } else if ($row["districto"] == 3) {
                                                echo 'Paradera';
                                            } else if ($row["districto"] == 4) {
                                                echo 'San Nicolaas';
                                            } else if ($row["districto"] == 5) {
                                                echo 'Santa Cruz';
                                            } else {
                                                echo 'Savaneta';
                                            } ?></th>
                        <th class="sex"><?php if ($row["ruman"] == 1) {
                                            echo 'Si';
                                        } else {
                                            echo 'No';
                                        } ?></th>
                        <th class="sex"><?php if ($row["collega"] == 1) {
                                            echo 'Si';
                                        } else {
                                            echo 'No';
                                        } ?></th>
                        <th class="datum"><?php echo $row["number_mayor1"] ?></th>
                        <th class="datum"><?php echo $row["number_mayor2"] ?></th>
                        <th><?php if ($row["prome_preferencia"] == 1) {
                                echo 'conrado';
                            } else if ($row["prome_preferencia"] == 2) {
                                echo 'kleuterschool';
                            } else if ($row["prome_preferencia"] == 3) {
                                echo 'amalia';
                            } else if ($row["prome_preferencia"] == 4) {
                                echo 'beatrix';
                            } else if ($row["prome_preferencia"] == 5) {
                                echo 'washington';
                            } else if ($row["prome_preferencia"] == 6) {
                                echo 'arco iris';
                            } else if ($row["prome_preferencia"] == 7) {
                                echo 'kudawecha';
                            } else if ($row["prome_preferencia"] == 8) {
                                echo 'xander';
                            } ?></th>
                        <th><?php if ($row["segundo_preferencia"] == 1) {
                                echo 'conrado';
                            } else if ($row["segundo_preferencia"] == 2) {
                                echo 'kleuterschool';
                            } else if ($row["segundo_preferencia"] == 3) {
                                echo 'amalia';
                            } else if ($row["segundo_preferencia"] == 4) {
                                echo 'beatrix';
                            } else if ($row["segundo_preferencia"] == 5) {
                                echo 'washington';
                            } else if ($row["segundo_preferencia"] == 6) {
                                echo 'arco iris';
                            } else if ($row["segundo_preferencia"] == 7) {
                                echo 'kudawecha';
                            } else if ($row["segundo_preferencia"] == 8) {
                                echo 'xander';
                            } ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
        </table>
<?php }
} else {
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load("../templates/applicationform.xlsx");
    $spreadsheet->setActiveSheetIndex(0);
    $hojaActiva = $spreadsheet->getActiveSheet();
    $admin = $_POST["admin"];
    if($admin != null){
        $query = "SELECT ID,status,fam,nomber,sexo,f_nacemento,districto,ruman,collega,number_mayor1,number_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE (prome_preferencia >= 9 OR segundo_preferencia >= 9) ORDER BY status ASC, fam ASC, nomber ASC";
    } else {
        $query = "SELECT ID,status,fam,nomber,sexo,f_nacemento,districto,ruman,collega,number_mayor1,number_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE (prome_preferencia = $admin OR segundo_preferencia = $admin) ORDER BY status ASC, fam ASC, nomber ASC";
    }
    $resultado = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($resultado) != 0) {
        $fila = 4;
        while ($row = mysqli_fetch_assoc($resultado)) {
            $hojaActiva->setCellValue('A' . $fila, $row["ID"]);
            if ($row["status"] == '1') {
                $status = 'accepted';
            } else if ($row["status"] == '2') {
                $status = 'rejected';
            } else {
                $status = 'waiting';
            }
            $hojaActiva->setCellValue('B' . $fila, $status);
            $hojaActiva->setCellValue('C' . $fila, $row["fam"]);
            $hojaActiva->setCellValue('D' . $fila, $row["nomber"]);
            if ($row["sexo"] == 1) {
                $sexo = 'M';
            } else {
                $sexo = 'F';
            }
            $hojaActiva->setCellValue('E' . $fila, $sexo);
            $hojaActiva->setCellValue('F' . $fila, $row["f_nacemento"]);
            if ($row["districto"] == 1) {
                $districto = 'Noord';
            } else if ($row["districto"] == 2) {
                $districto = 'Oranjestad';
            } else if ($row["districto"] == 3) {
                $districto = 'Paradera';
            } else if ($row["districto"] == 4) {
                $districto = 'San Nicolaas';
            } else if ($row["districto"] == 5) {
                $districto = 'Santa Cruz';
            } else {
                $districto = 'Savaneta';
            }
            $hojaActiva->setCellValue('G' . $fila, $districto);
            if ($row["ruman"] == 1) {
                $ruman = 'Si';
            } else {
                $ruman = 'No';
            }
            $hojaActiva->setCellValue('H' . $fila, $ruman);
            if ($row["collega"] == 1) {
                $collega = 'Si';
            } else {
                $collega = 'No';
            }
            $hojaActiva->setCellValue('I' . $fila, $collega);
            $hojaActiva->setCellValue('J' . $fila, $row["number_mayor1"]);
            $hojaActiva->setCellValue('K' . $fila, $row["number_mayor2"]);
            if ($row["prome_preferencia"] == 9) {
                $prome = 'Scol Primario Kudawecha';
            } else if ($row["prome_preferencia"] == 10) {
                $prome = 'Scol Preparatorio Kudawecha';
            } else if ($row["prome_preferencia"] == 11) {
                $prome = 'Colegio Conrado Coronel';
            } else if ($row["prome_preferencia"] == 12) {
                $prome = 'Colegio Conrado Coronel Kleuter';
            } else if ($row["prome_preferencia"] == 13) {
                $prome = 'Prinses Amalia Basisschool';
            } else if ($row["prome_preferencia"] == 14) {
                $prome = 'Prinses Amalia Kleuterschool';
            } else if ($row["prome_preferencia"] == 15) {
                $prome = 'Scol Basico Washington';
            } else if ($row["prome_preferencia"] == 16) {
                $prome = 'Scol Preparatorio Washington';
            } else if ($row["prome_preferencia"] == 17) {
                $prome = 'Arco Iris Kleuterschool';
            } else if ($row["prome_preferencia"] == 18) {
                $prome = 'Fontein Kleuterschool';
            } else if ($row["prome_preferencia"] == 19) {
                $prome = 'Scol Basico Dornasol';
            } else if ($row["prome_preferencia"] == 23) {
                $prome = 'Reina Beatrix School';
            } else if ($row["prome_preferencia"] == 24) {
                $prome = 'Scol Basico Xander Bogaerts';
            } else if ($row["prome_preferencia"] == 25) {
                $prome = 'Scol Preparatorio Xander Bogaerts';
            } else if ($row["prome_preferencia"] == 26) {
                $prome = 'Hilario Angela';
            } else {
                $prome = 'null';
            }
            $hojaActiva->setCellValue('L' . $fila, $prome);
            if ($row["segundo_preferencia"] == 9) {
                $segundo = 'Scol Primario Kudawecha';
            } else if ($row["segundo_preferencia"] == 10) {
                $segundo = 'Scol Preparatorio Kudawecha';
            } else if ($row["segundo_preferencia"] == 11) {
                $segundo = 'Colegio Conrado Coronel';
            } else if ($row["segundo_preferencia"] == 12) {
                $segundo = 'Colegio Conrado Coronel Kleuter';
            } else if ($row["segundo_preferencia"] == 13) {
                $segundo = 'Prinses Amalia Basisschool';
            } else if ($row["segundo_preferencia"] == 14) {
                $segundo = 'Prinses Amalia Kleuterschool';
            } else if ($row["segundo_preferencia"] == 15) {
                $segundo = 'Scol Basico Washington';
            } else if ($row["segundo_preferencia"] == 16) {
                $segundo = 'Scol Preparatorio Washington';
            } else if ($row["segundo_preferencia"] == 17) {
                $segundo = 'Arco Iris Kleuterschool';
            } else if ($row["segundo_preferencia"] == 18) {
                $segundo = 'Fontein Kleuterschool';
            } else if ($row["segundo_preferencia"] == 19) {
                $segundo = 'Scol Basico Dornasol';
            } else if ($row["segundo_preferencia"] == 23) {
                $segundo = 'Reina Beatrix School';
            } else if ($row["segundo_preferencia"] == 24) {
                $segundo = 'Scol Basico Xander Bogaerts';
            } else if ($row["segundo_preferencia"] == 25) {
                $segundo = 'Scol Preparatorio Xander Bogaerts';
            } else if ($row["segundo_preferencia"] == 26) {
                $segundo = 'Hilario Angela';
            } else {
                $segundo = 'null';
            }
            $hojaActiva->setCellValue('M' . $fila, $segundo);
            $fila++;
        }
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="aplicacion_form.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
}
