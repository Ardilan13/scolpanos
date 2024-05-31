<?php

use function PHPSTORM_META\type;

session_start();
require_once "classes/DBCreds.php";
require_once("classes/spn_setting.php");
require_once("classes/spn_cijfers.php");
require_once("classes/spn_utils.php");
require_once("classes/spn_controls.php");
require_once("classes/spn_authentication.php");
$auth = new spn_authentication();
$u = new spn_utils();
$s = new spn_setting();
$c = new spn_controls();
$ci = new spn_cijfers();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$schoolid = $_SESSION["SchoolID"];
$schooljaar = $_SESSION["SchoolJaar"];

// $klas = ["1A", "1B", "1C", "2A", "2B", "2C", "3A", "3B", "3C", "4A", "4B", "4C", "5A", "5B", "5C", "6A", "6B", "6C"];

// $sql_query_text = "select distinct v.vak_naam from le_vakken_ps v where v.type = 'c' order by v.id asc;";
// $resultado = mysqli_query($mysqli, $sql_query_text);
// while ($row = mysqli_fetch_assoc($resultado)) {
//     $vak = $row["vak_naam"];
//     $query1 = "INSERT INTO teacher (userGUID, schoolID,subject) VALUES ('58AD1DFC-8EDE-AD16-3394-40E338AA6DD3', $schoolid,'$vak')";
//     // echo $query1 . "<br>";
//     $resultado1 = mysqli_query($mysqli, $query1);
//     // echo $row["id"] . " " . $row["vak_naam"] . " " . $schoolid . "<br>";
// }												


// $get = "SELECT DISTINCT volledigenaamvak FROM `le_vakken` where SchoolID IN (4,5,6,7,9,10,11)  
// ORDER BY `le_vakken`.`volledigenaamvak` ASC";
// $result = mysqli_query($mysqli, $get);
// while ($row = mysqli_fetch_assoc($result)) {
//     $vak = $row["volledigenaamvak"];
//     echo $vak . ", ";
// }

// $get = "SELECT * FROM app_useraccounts WHERE UserRights = 'BEHEER' and Email like 'schoo%' ORDER BY Email";
// $result = mysqli_query($mysqli, $get);
// while ($row = mysqli_fetch_assoc($result)) {
//     $vak = $row["Email"];
//     echo $vak . "<br>";
// }



// $date1 = $s->_setting_begin_rap_1;
// $date2 = $s->_setting_end_rap_1;
// echo $date1 . " - " . $date2;

// $get = "SELECT volledigenaamvak FROM le_vakken WHERE SchoolID = 18 AND Klas = 2";
// $result = mysqli_query($mysqli, $get);
// while ($row = mysqli_fetch_assoc($result)) {
//     $vak = $row["volledigenaamvak"];
//     echo $vak . "<br>";
// }

// $test = $c->getdistinctvakken_json(18, 1);
// echo $test;

//leer csv y usar los datos como un array
// $file = fopen('vak.csv', 'r'); // Abre el archivo en modo lectura
// $i = 552929;
// $k = 0;
// if ($file !== false) {
//     while (($data = fgetcsv($file)) !== false) {
//         foreach ($data as $v) {
//             $k = $k + 1;
//             $i = $i + 1;
//             $insert = "INSERT INTO le_vakken (ID,SchoolID,Klas,volgorde,volledigenaamvak,complete_name) VALUES ($i,18,1,$k,'$v','$v');";
//             $mysqli->query($insert);
//         }
//         echo '<br>';
//     }

//     fclose($file); // Cierra el archivo
// }


// require_once("./classes/spn_cijfers.php");
// $s = new spn_cijfers();
// $get_klas = "SELECT DISTINCT c.studentid, c.klas, GROUP_CONCAT(c.vak SEPARATOR ', ') AS vak FROM `le_cijfers` c INNER JOIN le_vakken v ON c.vak = v.ID where c.schooljaar = '2023-2024' and c.rapnummer = 1 AND v.SchoolID = 8 AND c.studentid =  GROUP BY studentid ORDER BY `c`.`klas` ASC";
// $result = mysqli_query($mysqli, $get_klas);
// $studets = [];
// $cont = 1;
// while ($row = mysqli_fetch_assoc($result)) {
//     $vaks = explode(", ", $row['vak']);

//     $students[] = [
//         "id" => $row["studentid"],
//         "klas" => $row["klas"],
//         "vak" => $vaks
//     ];
//     $cont++;
// }
// echo $cont;
// foreach ($students as $student) {
//     $id = $student["id"];
//     $klas = $student["klas"];
//     $vak = $student["vak"];
//     print_r($vak);
//     echo $id . " " . $klas . "<br>";
//     // $.ajax({
//     //     url: "ajax/getgemiddelde_by_cijferwarde.php",
//     //     data: "klas=" + $klas + "&rapport=" + $rapnummer + "&vak=" + $vak + "&studentid=" + student_id_array[z],
//     //     type: 'POST',
//     //     dataType: "HTML",
//     //     cache: false,
//     //     async: true,
//     //     success: function(data) {
//     //         // console.log("gem:" + data)
//     //     }
//     // });
// }

// $get_klas = "SELECT id FROM students WHERE schoolid = 13 AND class like '4%'";
// $result = mysqli_query($mysqli, $get_klas);
// while ($row1 = mysqli_fetch_assoc($result)) {
//     $id = $row1["id"];
//     $paket = null;
//     $student_group = [];
//     $vaks_paket = [];
//     $get = "SELECT SUBSTRING(gr.name, 1, 2) AS name FROM group_student g INNER JOIN groups gr ON gr.id = g.group_id WHERE g.student_id = " . $row1["id"] . " AND g.schooljaar = '$schooljaar' AND gr.schoolid = 13;";
//     $result1 = mysqli_query($mysqli, $get);
//     while ($row = mysqli_fetch_assoc($result1)) {
//         $student_group[] = strtoupper($row["name"]);
//     }
//     sort($student_group);

//     $get_paket = "SELECT * FROM paket";
//     $result2 = mysqli_query($mysqli, $get_paket);
//     while ($row2 = mysqli_fetch_assoc($result2)) {
//         $vaks_paket = [$row2['g1'], $row2['g2'], $row2['g3'], $row2['g4'], $row2['p1'], $row2['p2'], $row2['p3'], $row2['k1'], $row2['k2'], $row2['k3']];
//         $vaks_paket = array_filter($vaks_paket, function ($elemento) {
//             return strpos($elemento, 'CKV') !== 0 && !empty($elemento);
//         });
//         sort($vaks_paket);
//         $paket = ($vaks_paket == $student_group && $paket == null) ? $row2['paket'] : $paket;
//         if ($paket !== null) {
//             $profiel = "UPDATE students SET profiel = '$paket' WHERE id = " . $row1["id"] . ";";
//             $result3 = mysqli_query($mysqli, $profiel);
//             echo $profiel . "<br>";
//             $paket = null;
//             break;
//         }
//     }
// }


/* echo $returnhash = hash('sha512', '27qSiZxCqJSHSu0JNLX1zf7Ksvw=' . 'dilan');
 */
/* $id = 552917;
$get_klas = "SELECT name_class FROM class WHERE schoolid = $schoolid AND level_class > 0 ORDER BY name_class ASC";
$result = mysqli_query($mysqli, $get_klas);
while ($row = mysqli_fetch_assoc($result)) {
    $klas = $row['name_class'];
    $update = "INSERT INTO le_vakken (ID,SchoolID,Klas,volledigenaamvak,complete_name) VALUES ($id,13,'$klas','msl','msl');";
    echo $update . "<br>";
    $mysqli->query($update);
    $id++;
} */
/* for ($i = 12; $i <= 13; $i++) {
    foreach ($klas as $k) {
        $update = "INSERT INTO le_vakken (ID,SchoolID,Klas,volledigenaamvak,complete_name) VALUES ($id,$i,'$k','msl','msl');";
        echo $update . "<br>";
        $id++;
    }
} */

/* $sql = "SELECT s.id,s.class,gr.vak FROM students s INNER JOIN group_student g ON s.id = g.student_id INNER JOIN groups gr ON g.group_id = gr.id WHERE s.schoolid = '$schoolid' AND g.group_id = $group AND g.schooljaar = '$schooljaar';";
$result = mysqli_query($mysqli, $sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentid = $row['id'];
        $klas = $row['class'];
        $vak = $row['vak'];
        $select = "SELECT COUNT(lc.id) as num FROM le_cijferswaarde lc 
        INNER JOIN le_vakken lv ON lc.vak = lv.id 
        AND lc.schooljaar = '$schooljaar'
        AND lc.rapnummer = $rapnummer
        AND lv.schoolid = $schoolid
        AND lc.klas = '$klas'
        AND lv.id = $vak;";
        $result1 = mysqli_query($mysqli, $select);
        $row1 = mysqli_fetch_assoc($result1)['num'];
        if ($row1 == 0) {
            $insert = "INSERT INTO `le_cijferswaarde`
            (`klas`,`schooljaar`,`rapnummer`,`vak`,
            `c1`,`c2`,`c3`,`c4`,`c5`,`c6`,`c7`,`c8`,`c9`,`c10`,
            `c11`,`c12`,`c13`,`c14`,`c15`,`c16`,`c17`,`c18`,`c19`,`c20`)
            VALUES
                ('$klas','$schooljaar','$rapnummer','$vak',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);;";
            $mysqli->query($insert);
            echo $insert . "<br>";
        } else {
            echo $select . $row1 . "<br>";
        }
    }
} */

/* $get_students = "SELET ID_PK FROM `le_vakken` where SchoolID = 13 and Klas like '4%' and ID = 0
ORDER BY `le_vakken`.`ID`  DESC";
$result = mysqli_query($mysqli, $get_students);
$x = 552843;
while ($row1 = mysqli_fetch_assoc($result)) {
    $id = $row1["ID_PK"];
    $update = "UPDATE le_vakken SET ID = $x WHERE ID_PK = $id";
    $mysqli->query($update);
    echo $update . "<br>";
    $x++;
} */

/* $schoolid = 12;
$klas = "3A";
$schooljaar = "2022-2023";

$get_students = "SELECT id,firstname,lastname FROM students WHERE schoolid = '$schoolid' AND class = '$klas' ORDER BY lastname, firstname;";
$result = mysqli_query($mysqli, $get_students);
while ($row1 = mysqli_fetch_assoc($result)) {
    $id = $row1["id"];
    $get_cijfers = "SELECT v.volledigenaamvak as vak,SUM(c.gemiddelde) as gemiddelde,COUNT(c.rapnummer) as rapnummer FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE v.volgorde > 0 AND c.studentid = '$id' AND c.klas = '$klas' AND c.schooljaar = '$schooljaar' AND c.gemiddelde is not NULL GROUP BY vak ORDER BY c.studentid,vak;";
    $result2 = mysqli_query($mysqli, $get_cijfers);
    if ($result2->num_rows > 0) {
        while ($row3 = mysqli_fetch_assoc($result2)) {
            echo $row3["vak"] . " " . $row3["gemiddelde"] . " " . $row3["rapnummer"] . "<br>";
        }
    }
} */

/* $id = 552839;
$klas = ["3A", "3B", "3C", "3D"];
for ($i = 12; $i <= 13; $i++) {
    foreach ($klas as $k) {
        $update = "INSERT INTO le_vakken (ID,SchoolID,Klas,volledigenaamvak,volgorde,complete_name) VALUES ($id,$i,'$k','CKV',20,'CKV');";
        echo $update . "<br>";
        $mysqli->query($update);
        $id++;
    }
} */


/* $klas = ["1A", "1B", "1C", "1D", "2A", "2B", "2C", "2D"];
$scol = 12;
$volgorde = 2;
foreach ($vaks as $vak) {
    $vak = strtolower($vak);
    foreach ($klas as $klas) {
        $id = mysqli_query($mysqli, "SELECT id FROM le_vakken WHERE volledigenaamvak = '" . $vak . "' AND Klas ='$klas' AND SchoolID = $scol LIMIT 1");
        $id = mysqli_fetch_assoc($id);
        $vak_id[$klas] = $id['id'];
    }
}

foreach ($vak_id as $k => $v) {
    if ($v != 0 && $v != null && $v != "") {
        $update = "UPDATE le_vakken SET volgorde = $volgorde WHERE id = $v AND SchoolID = $scol and Klas = '$k'";
        $mysqli->query($update);
        echo $update . "<br>";
    }
} */

/* $insert = "INSERT INTO
`le_vakken`(
    `ID`,
    `SchoolID`,
    `Klas`,
    `vakid`,
    `volledigenaamvak`,
    `x_index`,
    `complete_name`
)
VALUES ";
$vaks = [
    'Ne1', 'En1',    'Sp1',    'Pa1',    'Wi1',    'Lo1',    'Ak1',    'Gs1',    'Ec1',    'Bi1',    'Na1',    'Sk1',    'RT',
    'Ne2',    'En2',    'Sp2',    'Pa2',    'Wi2',    'Lo2',    'Ak2',    'Gs2',    'Ec2',    'Bi2',    'Na2',    'Sk2',
    'Ne3',    'En3',    'Sp3',    'Pa3',    'Wi3',    'Lo3',    'Ak3',    'Gs3',    'Ec3',    'Bi3',    'Na3',    'Sk3'
];
$i = 552777;
foreach ($vaks as $vak) {
    $i++;
    $vak = strtolower($vak);
    $insert .= "($i, 12, '4B', 0, '$vak', 0, '$vak'),";
}

echo $insert; */

/* $query = "SELECT * FROM excel WHERE klas like '4%'";
$result = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_assoc($result)) {
    switch ($row['dia']) {
        case "ma":
            $dag = "Monday";
            break;
        case "di":
            $dag = "Tuesday";
            break;
        case "wo":
            $dag = "Wednesday";
            break;
        case "do":
            $dag = "Thursday";
            break;
        case "vr":
            $dag = "Friday";
            break;
    }
    $schooljaar = "2022-2023";
    $schoolid = 12;
    $klas = $row['klas'];
    $p = 'p' . $row['p'];
    $id = mysqli_query($mysqli, "SELECT ID FROM le_vakken WHERE volledigenaamvak = '" . $row['vak'] . "' AND Klas ='$klas' AND SchoolID = 12 LIMIT 1");
    $id = mysqli_fetch_assoc($id);
    $vak = $id['ID'];
    $klasenboek = mysqli_query($mysqli, "SELECT id FROM klassenboek_vak WHERE day = '$dag' AND klas ='$klas' AND schoolid = 12");
    $id_klasenboek = mysqli_fetch_assoc($klasenboek);
    if (mysqli_num_rows($klasenboek) == 0 && $vak != "" && $vak != null) {
        $insert = "INSERT INTO `klassenboek_vak`(`day`, `schooljaar`, `schoolid`, `klas` ," . $p . ") VALUES ('$dag','$schooljaar',$schoolid,'$klas','$vak')";
        $mysqli->query($insert);
        echo $insert . "<br>";
    } else if (mysqli_num_rows($klasenboek) > 0) {
        $update = "UPDATE `klassenboek_vak` SET `" . $p . "` = '$vak' WHERE `id` = " . $id_klasenboek['id'];
        $mysqli->query($update);
        echo $update . "<br>";
    }
} */


/* echo date('l', strtotime("2021-05-20"));
$email = "dilancorr@gmail.com";
$headers = 'From: no-reply@qwihi.com' . "\r\n" . 'Reply-To: havompc.aanmelding@gmail.com'  . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-type: multipart/mixed;';
$subject = "Aanmelding MPCH";
$correo1 = "Hierbij ontvangt u een belangrijk bericht van de Mon Plaisir College havo.
Zie brief in de bijlage.";

mail($email, $subject, $correo1, $headers); */

/* $datum = $u->convertfrommysqldate_new("21-05-2023");
$klas = "1A";
$schoolID = 13;

$get_vak = "SELECT distinct v.ID, v.volledigenaamvak from le_vakken v where v.SchoolID = $schoolID and v.Klas = '$klas' and volgorde <> 99 order by v.volledigenaamvak asc;";
$result = mysqli_query($mysqli, $get_vak);
while ($row1 = mysqli_fetch_assoc($result)) {
    $vaks[] = array("id" => $row1['ID'], "vak" => $row1['volledigenaamvak']);
}


$select_klas = "SELECT id,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10 FROM klassenboek_vak where schoolid = $schoolID and klas = '$klas' and datum = '$datum'";
$resultado = mysqli_query($mysqli, $select_klas);
if ($resultado->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $id = $row['id'];
        $p1 = $row['p1'];
        $p2 = $row['p2'];
        $p3 = $row['p3'];
        $p4 = $row['p4'];
        $p5 = $row['p5'];
        $p6 = $row['p6'];
        $p7 = $row['p7'];
        $p8 = $row['p8'];
        $p9 = $row['p9'];
        $p10 = $row['p10'];
    }
} else {
    $insert = "INSERT INTO klassenboek_vak (schoolid,klas,datum) VALUES ($schoolID,'$klas','$datum')";
    $mysqli->query($insert);
}

$final = "";

for ($i = 1; $i <= 10; $i++) {
    $select = "select" . $i;
    $$select .= '<th>';
    $$select .= '<select name="vak_' . $i . '" id="vak_' . $i . '">';
    $$select .= '<option value="0">' . $i . '</option>';
    $x = 'p' . $i;
    foreach ($vaks as $vak) {
        if ($vak["id"] == $$x) {
            $conf = " selected";
        } else {
            $conf = "";
        }
        $$select .= '<option ' . $conf . ' value="' . $vak["id"] . '">' . $vak["vak"] . '</option>';
    }
    $$select .= '</select>';
    $$select .= '</th>';
    $final .= $$select;
}

echo "<script>console.log('" . $final . "');</script>";
echo $final; */


/* $get_vak = "SELECT distinct v.ID, v.volledigenaamvak from le_vakken v where v.SchoolID = 13 and v.Klas = '1A' and volgorde <> 99 order by v.volledigenaamvak asc;";
$result = mysqli_query($mysqli, $get_vak);
while ($row1 = mysqli_fetch_assoc($result)) {
    $vaks[] = array("id" => $row1['ID'], "vak" => $row1['volledigenaamvak']);
}

for ($i = 1; $i <= 10; $i++) {
    $select = "select" . $i;
    $$select = '<select name="vak_' . $i . '" id="vak_' . $i . '">';
    $$select .= '<option value="0">' . $i . '</option>';
    foreach ($vaks as $vak) {
        $$select .= '<option value="' . $vak["id"] . '">' . $vak["vak"] . '</option>';
    }
    $$select .= '</select>';
    echo $$select;
} */
/* $auth->ChangePassword("dilancorr@gmail.com", "prueba123", false); */
/* $bytes = random_bytes(20);
var_dump(bin2hex($bytes));
print($bytes); */


/* $plaintexttoken = "123";
$username = "dilan";
$email = "dilancorr@gmail.com";
$subject = "Password reset for Scol Pa Nos App";
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$cabeceras .= 'From: Qwihi <no-reply@qwihi.com>' . "\r\n";
$mensaje = 'Please click on this <a href=' . "http:/scolpanos.qwihi.com" . '/reset.php?token=' . $plaintexttoken . '&username=' . urlencode($username) . '>link</a> to reset your password <p> The link is only valid for 30 minutes</p> <b>no-reply@qwihi.com ,Scol Pa Nos App | Do Not Reply</b>';
mail($email, $subject, $mensaje, $cabeceras); */
// echo $_SESSION["UserGUID"];
/* $select = "SELECT * FROM excel";
$y = 10949;
$resultado1 = mysqli_query($mysqli, $select);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $rand = mt_rand(1000, 9999);
    $userguid = $u->CreateGUID();
    $date = date("Y-m-d H:i:s");
    $school = 12;
    $status = 1;
    $date1 = $u->converttomysqldate(date("Y-m-d H:i:s"));
    $colorcode = '#CC2AA';

    $class = $row["klas"];
    $fam = $row["name"];
    $nombre = $row["lastname"];
    $sexo = $row["sex"];
    $studentnr = $row["number"];

    $famili = $fam . "(" . $studentnr . ")";

    $insertar = "INSERT INTO family(id_family,family_name,family_date,status) VALUES ('$y','$famili', '$date','1')";
    $resultado123 = mysqli_query($mysqli, $insertar);
    if ($resultado123) {
        $insertarS = "INSERT INTO students(uuid,created,schoolid,studentnumber,class,enrollmentdate,firstname,lastname,sex,colorcode,status,id_family,securepin) VALUES ('$userguid','$date', '$school','$studentnr', '$class', '$date1', '$nombre', '$fam','$sexo','$colorcode','$status','$y','$rand')";
        echo $insertarS . "<br>";
        $resultadoS = mysqli_query($mysqli, $insertarS);
        if ($resultadoS) {
            echo "Student insert";
        } else {
            echo "bad";
        }
    } else {
        echo 'adios';
    }
    $y = $y + 1;
} */
/* echo $u->CreateGUID(); */
/* $select = "SELECT * FROM excel ORDER BY id ASC";
$resultado1 = mysqli_query($mysqli, $select);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $rand = mt_rand(1000, 9999);
    $userguid = $u->CreateGUID();
    $date = date("Y-m-d H:i:s");
    $school = 19;
    $status = 1;
    $date1 = $u->converttomysqldate(date("Y-m-d H:i:s"));
    $colorcode = '#CC2AA';

    $class = $row["klas"];
    $fam = utf8_encode($row["lastname"]);
    $nombre = utf8_encode($row["firstname"]);
    $studentnr = $row["studentnr"];
    /* $insertarS = "INSERT INTO students(uuid,created,schoolid,studentnumber,class,enrollmentdate,firstname,lastname,colorcode,status,securepin) VALUES ('$userguid','$date', '$school','$studentnr', '$class', '$date1', '$nombre', '$fam','$colorcode','$status','$rand')";
    $resultadoS = mysqli_query($mysqli, $insertarS);
    if ($resultadoS) {
        echo "Student insert";
    } else {
        echo "bad";
    }
} */

/* $select = "SELECT s.id,e.birthdate FROM students s INNER JOIN excel e ON s.studentnumber = e.studentnr WHERE s.schoolid = 19  ORDER BY s.id ASC";
$resultado1 = mysqli_query($mysqli, $select);
while ($row = mysqli_fetch_assoc($resultado1)) {
    echo $id = $row['id'];
    echo $birthdate = $row['birthdate'];
    $insertar = "UPDATE students set dob = '$birthdate' where id = $id";
    $result = mysqli_query($mysqli, $insertar);
} */

/* $klas = substr("1B", 0, 1); */

/* $fecha1 = $s->_setting_begin_rap_2;
$fecha1 = $u->convertfrommysqldate_new($fecha1);

$fecha2 = $s->_setting_end_rap_2;
$fecha2 = $u->convertfrommysqldate_new($fecha2);
echo $fecha1 . " - " . $fecha2;

$sql_query_verzuim = "SELECT s.id as studentid,v.telaat,v.absentie,v.huiswerk,s.lastname,s.firstname,o.opmerking1, o.opmerking2, o.opmerking3, v.created, v.datum from students s inner join le_verzuim v inner join opmerking o
where o.studentid = 5971 and o.schooljaar = '2022-2023' and s.class = '4B'  and s.schoolid = 8 and v.schooljaar = '2022-2023' and v.studentid = 5971 and s.id = 5971  and v.huiswerk = 1
ORDER BY `v`.`huiswerk`  DESC";
$resultado = mysqli_query($mysqli, $sql_query_verzuim);
while ($row1 = mysqli_fetch_assoc($resultado)) {
    $datum = $u->convertfrommysqldate_new($row1["datum"]);
    echo "<br>" . $row1["huiswerk"] . $datum;
    if ($datum >= $fecha1 && $datum <= $fecha2) {
        if ($row1['telaat'] > 0) {
            $cont_laat++;
        }
        if ($row1['absentie'] > 0) {
            $cont_verzuim++;
        }
        if ($row1['huiswerk'] > 0) {
            $cont_huis++;
        }
    }
    if ($i == 1) {
        $opmerking = $row1["opmerking1"];
    } else if ($i == 2) {
        $opmerking = $row1["opmerking2"];
    } else if ($i == 3) {
        $opmerking = $row1["opmerking3"];
    }
}
echo $cont_huis; */

/* $query = "SELECT id FROM le_vakken_ps WHERE type ='c'";
$x = 1;

while ($x < 3) {

    $resultado = mysqli_query($mysqli, $query);

    while ($row = mysqli_fetch_assoc($resultado)) {
        $id = $row['id'];
        $query1 = "INSERT INTO le_cijfers_ps (studentid, schooljaar,rapnummer, vak, klas) VALUES (8674, '2022-2023',$x, $id, '3B')";
        $resultado1 = mysqli_query($mysqli, $query1);
    }
    $x++;
} */

/* ini_set('memory_limit', '200M');
require_once "classes/3rdparty/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load("templates/verza_hs.xlsx");

header('Cache-Control: age-max:0');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="verzamelstaten_test.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output'); */

/* $query = "SELECT 	
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
    and c.schooljaar = '2022-2023'
    where l.studentid = (select id from students where uuid = '7FEBC6B1-D82D-6A6E-D67A-46F4589D0939') and c.schoolid = 6
    order by c.dc20 DESC,c.dc19 DESC,c.dc18 DESC,c.dc17 DESC, c.dc16 DESC, c.dc15 DESC, c.dc14 DESC, c.dc13 DESC, c.dc12 DESC, c.dc11 DESC, c.dc10 DESC, c.dc9 DESC, c.dc8 DESC, c.dc7 DESC, c.dc6 DESC, c.dc5 DESC, c.dc4 DESC, c.dc3 DESC, c.dc2 DESC, c.dc1 DESC";
$resultado = mysqli_query($mysqli, $query);
$i = 0;
$list = [];
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
    echo "fecha: " . $lista[$x]['dc'] . "<br>";
    echo "extra: " . $lista[$x]['oc'] . "<br>";
    echo "vak: " . $lista[$x]['vo'] . "<br>";
    echo "rap: " . $lista[$x]['ra'] . "<br>";
    echo "cijfer: " . $lista[$x]['c'] . "<br>";
} */
/* require_once "classes/3rdparty/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$spreadsheet = $reader->load("templates/13_verza_3-4.xlsx");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="verzamelstaten_test.xlsx"');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output'); */

/* $DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
session_start();
echo $_SESSION["UserGUID"]; */
/* $schooljaar = $_SESSION['SchoolJaar'];
$schoolid = $_SESSION['SchoolID'];
$klas_in = "1";
$rap_in = 1;
$user = $_SESSION["UserGUID"];
$sql_query = "SELECT DISTINCT
	s.id as studentid,
	c.id as cijferid,
	st.year_period,
	s.class,
	s.firstname,
	s.lastname,
	s.sex,
	c.gemiddelde,
	v.volgorde,
	v.x_index,
	CONCAT(app.firstname,' ',app.lastname) as docent_name
	FROM students s
	LEFT JOIN le_cijfers c ON s.id = c.studentid
	LEFT JOIN le_vakken v ON c.vak = v.id
	INNER JOIN setting st ON st.schoolid = s.schoolid
	INNER JOIN app_useraccounts app ON st.schoolid = app.SchoolID and app.UserGUID = 'cb6b70e8-b736-46ac-a96e-30bfd3c63227'
	WHERE
	s.schoolid = 18
	AND v.SchoolID = 18
	AND st.year_period = '2022-2023'
	and c.gemiddelde >= 0
	AND c.schooljaar = '2022-2023'
	AND s.class = '1'
	AND c.klas = '1'
	AND v.klas = '1'
	AND c.rapnummer = 1
	AND x_index is not null";
$resultado = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado)) {
    echo $row["lastname"];
} */
/* $sql_query = "select s.id, v.id, s.class, s.firstname, s.lastname, s.sex,v.telaat,v.absentie,v.toetsinhalen, v.uitsturen, v.huiswerk, v.lp, v.opmerking from students s inner join le_verzuim v on s.id = v.studentid where s.class = ?  and s.schoolid = ? and (replace(v.datum,'/','-') = ? OR datum = ?)
union
select s.id, null, s.class, s.firstname, s.lastname, s.sex,0,0,0,0,0,0,''  from students s where s.class = ? and s.schoolid = ? and  s.id not in (select studentid from le_verzuim where (replace(datum,'/','-') = ?) OR datum = ?) order by";
$resultado = mysqli_query($mysqli, $sql_query);
while ($row = mysqli_fetch_assoc($resultado)) {
    echo "hola";
} */
/* $select = "SELECT id,vak_naam from le_vakken_ps WHERE type = 'c'";
$resultado = mysqli_query($mysqli, $select);
while ($row = mysqli_fetch_assoc($resultado)) {
    $vak = $row['id'];
    $insertar = "SELECT MAX(CASE WHEN rapnummer = 1 THEN gemiddelde END) Rap1,
    MAX(CASE WHEN rapnummer = 2 THEN gemiddelde END) Rap2,
    MAX(CASE WHEN rapnummer = 3 THEN gemiddelde END) Rap3 FROM le_cijfers_ps WHERE schooljaar = '2022-2023' and  studentid = 2514 and rapnummer <= 1 and vak = $vak";
    $result = mysqli_query($mysqli, $insertar);
    echo $row["vak_naam"] . '<br>';
    while ($row1 = mysqli_fetch_assoc($result)) {
        echo $row1["Rap1"] . '<br>';
        echo $row1["Rap2"] . '<br>';
        echo $row1["Rap3"] . '<br>';
    }
} */
/*  ASIGNAR ID A LOS VAKS
$select = "SELECT ID_PK from le_vakken WHERE ID = 552754 order by SchoolID;";
$resultado = mysqli_query($mysqli, $select);
$date = 552754;
while ($row = mysqli_fetch_assoc($resultado)) {
    $vak = $row['ID_PK'];
    $insertar = "UPDATE le_vakken set ID = $date where ID = $vak";
    $result = mysqli_query($mysqli, $insertar);
    $date++;
}
echo '</br>'; */

/* require_once "classes/spn_utils.php";
require_once "document_start.php";
require_once "classes/spn_leerling.php";
require_once "classes/spn_contact.php";
require_once "classes/spn_social_work.php"; //CODE CaribeDevelopers
require_once "classes/spn_mdc.php"; //CODE CaribeDevelopers
<?php

ob_start();

require_once "../classes/3rdparty/vendor/autoload.php";
require_once "../classes/DBCreds.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$klas_in = $_GET["ttr_klas_list"];
$schoolid = 6;
$schooljaar = "2022-2023";

$spreadsheet = new SpreadSheet();
$spreadsheet->getProperties()->setCreator("Scolpanos")->setTitle("Scolpanos Excel File");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$sql_query = "SELECT
		s.id, s.id_family ,
		CONCAT(s.firstname,' ',s.lastname) as student_full_name,
		sc.schoolname
		FROM students s
		INNER JOIN schools sc ON
		sc.id = s.SchoolID
		INNER JOIN setting stt ON
		s.SchoolID = stt.schoolid
		WHERE s.class = ? AND s.schoolid = ? AND stt.year_period = ?";
if ($select = $mysqli->prepare($sql_query)) {
    if ($select->bind_param("sis", $klas_in, $schoolid, $schooljaar)) {
        if ($select->execute()) {
            $select->store_result();
            if ($select->num_rows > 0) {
                $result = 1;
                $select->bind_result($studentid_out, $id_family, $student_full_name, $schoolname);
                while ($select->fetch()) {

                    $spreadsheet->setActiveSheetIndex(0);
                    $hojaActiva = $spreadsheet->getActiveSheet();

                    $hojaActiva->getColumnDimension('A')->setWidth(100);
                    $hojaActiva->setCellValue('A1', $studentid_out);
                    $hojaActiva->setCellValue('B2', $id_family);

                    $hojaActiva->setCellValue('C1', "20.243")->setCellValue('D1', $student_full_name);
                }
            }
        }
    }
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="prueba.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');


ob_flush();

require_once "classes/spn_test.php"; //CODE CaribeDevelopers
require_once "classes/spn_event.php"; //CODE CaribeDevelopers
require_once "classes/spn_remedial.php"; //CODE CaribeDevelopers
require_once "classes/spn_documents.php"; //CODE CaribeDevelopers
require_once "classes/spn_houding.php"; //CODE CaribeDevelopers
require_once "classes/spn_verzuim.php"; //CODE CaribeDevelopers
require_once "classes/spn_cijfers.php"; //CODE CaribeDevelopers
require_once "config/app.config.php";
require_once "classes/spn_avi.php"; //CODE CaribeDevelopers
require_once "classes/spn_paymentinvoice.php"; //CODE CaribeDevelopers
require_once "classes/spn_user_hs_account.php";
require_once "classes/3rdparty/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include 'document_end.php';

$spreadsheet = new SpreadSheet();
$spreadsheet->getProperties()->setCreator("Scolpanos")->setTitle("Scolpanos Excel File");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$sql_query = "SELECT
		s.id, s.id_family ,
		CONCAT(s.firstname,' ',s.lastname) as student_full_name,
		sc.schoolname
		FROM students s
		INNER JOIN schools sc ON
		sc.id = s.SchoolID
		INNER JOIN setting stt ON
		s.SchoolID = stt.schoolid
		WHERE s.class = ? AND s.schoolid = ? AND stt.year_period = ?";
$klas_in = "1A";
$schoolid = 6;
$schooljaar = "2022-2023";
if ($select = $mysqli->prepare($sql_query)) {
    if ($select->bind_param("sis", $klas_in, $schoolid, $schooljaar)) {
        if ($select->execute()) {
            $select->store_result();
            if ($select->num_rows > 0) {
                $result = 1;
                $select->bind_result($studentid_out, $id_family, $student_full_name, $schoolname);
                while ($select->fetch()) {

                    $spreadsheet->setActiveSheetIndex(0);
                    $hojaActiva = $spreadsheet->getActiveSheet();

                    $hojaActiva->getColumnDimension('A')->setWidth(100);
                    $hojaActiva->setCellValue('A1', $studentid_out);
                    $hojaActiva->setCellValue('B2', $id_family);

                    $hojaActiva->setCellValue('C1', "20.243")->setCellValue('D1', $student_full_name);
                }
            }
        }
    }
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="prueba.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output'); */

/* $writer = new Xlsx($spreadsheet);
$writer->save('Mi excel.xlsx'); */


//CONSULTAR PRIMER CIJFER CREADO DE UN SCOL MEDIANTE SUS VAKS

/* $select = "SELECT ID from le_vakken WHERE SchoolID = 13 and ID != 0 order by ID;";
$resultado = mysqli_query($mysqli, $select);
$date = 2023;
while ($row = mysqli_fetch_assoc($resultado)) {
    $vak = $row['ID'];
    $select1 = "SELECT created FROM le_cijfers WHERE vak = '$vak' ORDER BY created LIMIT 1";
    $resultado1 = mysqli_query($mysqli, $select1);
    while ($row1 = mysqli_fetch_assoc($resultado1)) {
        if($row1["created"] <= $date){
            echo $row1["created"];
            $date = $row1["created"];
        }
    }
    echo '</br>';
} */


/* $user_hs_1 = $_POST["user_hs_firts_name"];
$user_hs_2 = $_POST["user_hs_last_name"];

$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
// print('diferente');
$delete = "DELETE FROM app_useraccounts WHERE FirstName = '$user_hs_1' and LastName = '$user_hs_2';";
$resultado123 = mysqli_query($mysqli, $delete);
if ($resultado123) {
	print 1;
} else {
	echo "adios";
} */

//INSERT DE FAMILY Y STUDENTS DESDE UN TABLE CREADO EN EXCEL
/* $select = "SELECT * FROM `TABLE 57` ORDER BY `TABLE 57`.`COL 5` ASC";
$y = 10231;
$resultado1 = mysqli_query($mysqli, $select);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $rand = mt_rand(1000, 9999);
    $userguid = $u->CreateGUID();
    $date = date("Y-m-d H:i:s");
    $school = 12;
    $status = 1;
    $date1 = $u->converttomysqldate(date("Y-m-d H:i:s"));
    $colorcode = '#CC2AA';

    $class = $row["COL 1"];
    $fam = $row["COL 2"];
    $nombre = $row["COL 3"];
    $sexo = $row["COL 4"];
    $m = $row["COL 5"];

    $famili = $fam . "(" . $m . ")";

    $insertar = "INSERT INTO family(id_family,family_name,family_date,status) VALUES ('$y','$famili', '$date','1')";
    $resultado123 = mysqli_query($mysqli, $insertar);
    if ($resultado123) {
        echo 'hola';
        $insertarS = "INSERT INTO students(uuid,created,schoolid,studentnumber,class,enrollmentdate,firstname,lastname,sex,colorcode,status,id_family,securepin) VALUES ('$userguid','$date', '$school','$m', '$class', '$date1', '$nombre', '$fam','$sexo','$colorcode','$status','$y','$rand')";
        $resultadoS = mysqli_query($mysqli, $insertarS);
        if ($resultadoS) {
            echo "Student insert";
        } else {
            echo "bad";
        }
    } else {
        echo 'adios';
    }
    $y = $y+1;
} */       

/* $sql1 = "SELECT @cijfers_count := COUNT(lc.studentid) 
FROM 
  le_cijfers_ps lc 
INNER JOIN 
  students s on lc.studentid = s.id 
WHERE s.schoolid = 18
AND lc.schooljaar = '2021-2022'
AND lc.rapnummer = 2
AND lc.klas = '4A' AND lc.vak = 1;";
if ($resultado = $mysqli->query($sql1)) {
    $fila = $resultado->fetch_row();
    $student_count = $fila[0];
    printf("La Consulta devolvió %d\n", $fila[0]);

    if ($fila[0] == 0 || $fila[0] == '' || $fila[0] == null) {
        $sqlI = "INSERT INTO
        le_cijfers_ps
        (`studentid`,`lastchanged`,`created`,`schooljaar`,`school_id`,`rapnummer`,`vak`,`klas`)
      SELECT DISTINCT
        st.id,null,now(),'2021-2022',18, 2,lv.id,'4A'
      FROM 
        students st
      INNER JOIN
        le_vakken_ps lv
      WHERE
        lv.id = 1 AND st.status = 1 AND st.class = '4A' AND st.schoolid = 18;";
        $resultado123 = mysqli_query($mysqli, $sqlI);
        if ($resultado123) {
            echo 'hola';
        } else {
            echo 'adios';
        }
    } /* else {
        $sqlI = "SELECT @student_count := (COUNT(id)) FROM students where schoolid = 18 and class = '4A';";
        $resultadoCij = $mysqli->query($sqlI);
        $filaCij = $resultadoCij->fetch_row();
        echo $filaCij[0];

        if ($filaCij[0] != $student_count) {
            echo "sikiti";
            $sqlCij = "CREATE TEMPORARY TABLE IF NOT EXISTS 
            student_not_in_cijfers_table 
          AS (SELECT id FROM students
            WHERE id NOT IN
                (SELECT studentid as id 
                FROM le_cijfers_ps  
                WHERE schooljaar = '2021-2022' and vak = 1 and klas = '4A' and rapnummer = 2) 
            AND schoolid = 18 and class = '4A' and status = 1);
              
            INSERT INTO  le_cijfers_ps
              (studentid,lastchanged,created,schooljaar,rapnummer,vak,klas)
            select
              id,null,now(),'2021-2022',2,1,'4A'
            FROM
              student_not_in_cijfers_table;";
            $resultado123 = mysqli_query($mysqli, $sqlCij);
            if ($resultado123) {
                echo 'done';
            } else {
                echo 'no';
            }
        } else {
            echo 'nooo';
        }
    } 

    $resultado->close();
} */

/* $sql_query_text = "select distinct v.id, v.vak_naam from le_vakken_ps v where v.type = 'c' order by v.id asc;";
$resultado1 = mysqli_query($mysqli, $sql_query_text);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $json[] = array("id" => $row["id"], "vak" => $row["vak_naam"]);
    echo json_encode($json);
}

?>
<select class="form-control" name="cijfers_vakken_lijst" id="cijfers_vakken_lijst_ps">
    <?php
    $sql_query_text = "select distinct v.id, v.vak_naam from le_vakken_ps v where v.type = 'c' order by v.id asc;";
    $resultado1 = mysqli_query($mysqli, $sql_query_text);
    while ($row = mysqli_fetch_assoc($resultado1)) {
    ?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['vak_naam']; ?></option>
    <?php
    } ?>
</select>
<?php */


/* //EDITAR STATUS DE LOS APLICACION FORMS
$datum = "SELECT * FROM `aplicacion_forms` where status IS NULL";
$resultado1 = mysqli_query($mysqli, $datum);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $id = $row["ID"];
    $insertar = "UPDATE aplicacion_forms set status = '3' where id = '$id'";
    $result = mysqli_query($mysqli, $insertar);
} */

/* //EDITAR VERZUIM DATES AL CREATED
$y = 20221123;
$datum = "SELECT id,created,datum FROM le_verzuim WHERE datum = '2021-11-18' ORDER BY `le_verzuim`.`datum` DESC";
$resultado1 = mysqli_query($mysqli, $datum);
while ($row = mysqli_fetch_assoc($resultado1)) {
$id = $row["id"];
$created = $row["created"];
$dateNew = date("Y-m-d", strtotime($created));
echo $dateNew;
echo $id;
$insertar = "UPDATE le_verzuim set datum = '$dateNew' where created = '$created' and id = '$id'";
$result = mysqli_query($mysqli, $insertar);
}

//INSERTAR STUDENTS DEL APLICACION FORM
require_once "../classes/spn_leerling.php";
require_once "../classes/spn_utils.php";
$l = new spn_leerling();

$datum = "SELECT * FROM aplicacion_forms WHERE prome_preferencia = 7 AND status = 1 ORDER BY ID desc";
$resultado1 = mysqli_query($mysqli, $datum);
$m = 1535;
$y = 7763;
while ($row = mysqli_fetch_assoc($resultado1)) {
$m = $m + 1;
$y = $y + 1;
$rand = mt_rand(1000,9999);
$userguid = $u->CreateGUID();
echo $rand;
echo $row["nomber"];
echo $row["fam"];
if ($row["sexo"] == 1) {
$sexo = "M";
} else if ($row["sexo"] == 2) {
$sexo = "V";
}
echo $sexo;
echo $userguid;
echo $row["l_nacemento"];
$adres = $row["caya"] . " " . $row["cas"];
echo $adres;
echo $row["azv"];
echo $row["nacionalidad"];

if ($row["idioma"] == 1) {
$idioma = "Papiamento";
} else if ($row["idioma"] == 2) {
$idioma = "Nederlands";
} else if ($row["idioma"] == 3) {
$idioma = "Español";
} else if ($row["idioma"] == 4) {
$idioma = "English";
} else {
$idioma = $row["idioma"];
}
echo $idioma;

if ($row["problema"] == 1 || $row["problema"] == 0 || $row["problema"] == '' || $row["problema"] == null) {
$problema = "";
} else if ($row["problema"] == 2) {
$problema = "Bista";
} else if ($row["problema"] == 3) {
$problema = "Oido";
} else if ($row["problema"] == 4) {
$problema = "Habla";
} else if ($row["problema"] == 5) {
$problema = "Motorico";
} else if ($row["problema"] == 6) {
$problema = "Alergia";
} else {
$problema = $row["problema"];
}
echo $problema;
$date = date("Y-m-d H:i:s");
$school = 4;
$class = "Aanmelding";
$status = 1;
$date1 = $u->converttomysqldate(date("Y-m-d H:i:s"));
$colorcode = '#CC2AA';
$nombre = $row["nomber"];
$fam = $row["fam"];
$f_nacemento = $u->converttomysqldate($row["f_nacemento"]);
$l_nacemento = $row["l_nacemento"];
$azv = $row["azv"];
$nacionalidad = $row["nacionalidad"];
$ultimo_scol = $row["ultimo_scol"];
$insertar = "INSERT INTO students(uuid,created,schoolid,studentnumber,class,enrollmentdate,firstname,lastname,sex,dob,birthplace,address,azvnumber,colorcode,status,nationaliteit,voertaalanders,anders,securepin,notas) VALUES ('$userguid','$date', '$school','$m', '$class', '$date1', '$nombre', '$fam','$sexo','$f_nacemento','$l_nacemento','$adres','$azv','$colorcode','$status','$nacionalidad','$idioma','$problema','$rand','$ultimo_scol')";
$resultado123 = mysqli_query($mysqli, $insertar);
if ($resultado123){
echo 'hola';
}
else{
echo 'adios';
}
}
echo $m;
echo $y . "<br>";

//CREAR DATOS DE FAMILY PARA LOS NEW STUDENTS
$datu = "SELECT * FROM students WHERE schoolid = 4 AND class = 'Aanmelding' ORDER BY id desc";
$resultado = mysqli_query($mysqli, $datu);
$m = 1057;
$y = 7722;
while ($row = mysqli_fetch_assoc($resultado)) {
$y = $y + 1;
$date = date("Y-m-d H:i:s");
$lastname = $row['lastname'].' ('. $row['studentnumber'] .')';
$ID = $row['id'];
echo $lastname;
$insertar = "INSERT INTO family(id_family,family_name,family_date,status) VALUES ('$y','$lastname', '$date','1')";
$resultado123 = mysqli_query($mysqli, $insertar);
if ($resultado123) {
echo 'hola';
$update = "UPDATE students set id_family = '$y' WHERE id = '$ID'";
$resultado = mysqli_query($mysqli, $update);
} else {
echo 'adios';
}
} */

//CREAR LOS PADRES DE LOS STUDENTS DEL APLICCACION FORM
/* $datum = "SELECT * FROM aplicacion_forms WHERE prome_preferencia = 7 AND status = 1 ORDER BY ID asc";
$resultado1 = mysqli_query($mysqli, $datum);
$y = 7722;
while ($row = mysqli_fetch_assoc($resultado1)) {
    $tutor = $row['voogd2'];
    if ($tutor == 2){
        $tutor = 0;
    }
    $relacion = $row['relacion2'];
    if ($relacion == 1){
        $relacion = "Mother";
    } else if ($relacion == 2){
        $relacion = "Father";
    }
    $full_name = $row["nomber_mayor2"] . " " . $row["fam_mayor2"];
    $adres = $row["caya_mayor2"] . " " . $row["cas_mayor2"];
    $email = $row["email_mayor2"];
    $mobil = $row["number_mayor2"];
    $telefon = $row["telefon_mayor2"];
    $traha = $row["traha_mayor2"];
    $position = $row["ocupacion_mayor2"];
    $y = $y + 1;
        //UPDATE DE DATOS EN CONTACT
        // $update = "UPDATE contact set company = '$traha', position_company = '$position' WHERE id_family = '$y'";
        // $resultado3 = mysqli_query($mysqli, $update);
    $dato = "SELECT * FROM students WHERE schoolid = 4 and class = 'Aanmelding' and id_family = '$y'";
    $resultado2 = mysqli_query($mysqli, $dato);
    if ($resultado2) {
        echo 'hola';
        $insertar = "INSERT INTO contact(tutor,type,full_name,address,email,mobile_phone,work_phone,company,position_company,id_family) VALUES ('$tutor','$relacion','$full_name','$adres','$email','$mobil','$telefon','$traha','$position','$y');";
        $resultado123 = mysqli_query($mysqli, $insertar);
        if ($resultado123) {
            echo 'si';
        }
    } else {
        echo 'adios';
    }
} 

//ACTUALIZAR DATOS DEL UUID Y SECUREPIN EN STUDENTS
$datum = "SELECT * FROM students WHERE schoolid = 18 ORDER BY ID desc";
$resultado1 = mysqli_query($mysqli, $datum);
while ($row = mysqli_fetch_assoc($resultado1)) {
    $userguid = $u->CreateGUID();
    echo $userguid;
    $rand = mt_rand(1000,9999);
    echo $rand;
    $id = $row["id"];
    $insertar = "UPDATE students set uuid = '$userguid',securepin = '$rand' where schoolid = 18 and id = '$id'";
    $result = mysqli_query($mysqli, $insertar);
}*/