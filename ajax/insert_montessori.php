<?php
session_start();
require_once "../classes/DBCreds.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$id = $_POST["id"];
$klas = $_POST["klas"];
$exist = $_POST["exist"];
$periode = $_POST["periode"];
$schooljaar = $_SESSION["SchoolJaar"];
$finish = $_POST["finish"];
if ($finish == 1) {
    $query = "SELECT * FROM montessori WHERE student_id = $id AND klas = '$klas' AND period = $periode AND schooljaar = '$schooljaar'";
    $resultado = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($resultado) != 0) {
        $row = mysqli_fetch_assoc($resultado);
        if ($row["zelf1"] > 0 && $row["zelf2"] > 0 && $row["zelf3"] > 0 && $row["zelf4"] > 0 && $row["zelf5"] > 0 && $row["werk1"] > 0 && $row["werk2"] > 0 && $row["werk3"] > 0 && $row["werk4"] > 0 && $row["werk5"] > 0 && $row["werk6"] > 0 && $row["werk7"] > 0 && $row["werk8"] > 0 && $row["social1"] > 0 && $row["social2"] > 0 && $row["social3"] > 0 && $row["social4"] > 0 && $row["social5"] > 0 && $row["social6"] > 0 && $row["social7"] > 0 && $row["social8"] > 0 && $row["social9"] > 0 && $row["social10"] > 0 && $row["gedrag1"] > 0 && $row["gedrag2"] > 0 && $row["gedrag3"] > 0 && $row["gedrag4"] > 0 && $row["gedrag5"] > 0 && $row["gedrag6"] > 0 && $row["buit1"] > 0 && $row["buit2"] > 0 && $row["buit3"] > 0 &&  $row["motor1"] > 0 &&  $row["motor2"] > 0 &&  $row["motor3"] > 0) {
            if ($row["mondelinge"] != " " &&  $row["taalgebruik"] != " " &&  $row["lezen"] != " " &&  $row["schrijven"] != " " &&  $row["rekenen"] != " " &&  $row["wereldorientatie"] != " " &&  $row["verkeer"] != " " &&  $row["muziek"] != " " &&  $row["art"] != " " &&  $row["gymnastiek"] != " " &&  $row["zwemmen"] != " " &&  $row["laat"] != " " &&  $row["verzuim"] != " " &&  $row["sociale"] != " " &&  $row["conclusie"] != " ") {
                $query = "UPDATE montessori SET status = 1 WHERE student_id = $id AND klas = '$klas' AND period = $periode AND schooljaar = '$schooljaar'";
                $resultado = mysqli_query($mysqli, $query);
                if ($resultado) {
                    print 1;
                } else {
                    print 0;
                }
            } else {
                print 2;
            }
        } else {
            print 2;
        }
    } else {
        print 0;
    }

    exit;
}

if ($_POST["zelf1"] == null) {
    $zelf1 = 0;
} else {
    $zelf1 = $_POST["zelf1"];
}

if ($_POST["zelf2"] == null) {
    $zelf2 = 0;
} else {
    $zelf2 = $_POST["zelf2"];
}

if ($_POST["zelf3"] == null) {
    $zelf3 = 0;
} else {
    $zelf3 = $_POST["zelf3"];
}

if ($_POST["zelf4"] == null) {
    $zelf4 = 0;
} else {
    $zelf4 = $_POST["zelf4"];
}

if ($_POST["zelf5"] == null) {
    $zelf5 = 0;
} else {
    $zelf5 = $_POST["zelf5"];
}

if ($_POST["werk1"] == null) {
    $werk1 = 0;
} else {
    $werk1 = $_POST["werk1"];
}

if ($_POST["werk2"] == null) {
    $werk2 = 0;
} else {
    $werk2 = $_POST["werk2"];
}

if ($_POST["werk3"] == null) {
    $werk3 = 0;
} else {
    $werk3 = $_POST["werk3"];
}

if ($_POST["werk4"] == null) {
    $werk4 = 0;
} else {
    $werk4 = $_POST["werk4"];
}

if ($_POST["werk5"] == null) {
    $werk5 = 0;
} else {
    $werk5 = $_POST["werk5"];
}

if ($_POST["werk6"] == null) {
    $werk6 = 0;
} else {
    $werk6 = $_POST["werk6"];
}

if ($_POST["werk7"] == null) {
    $werk7 = 0;
} else {
    $werk7 = $_POST["werk7"];
}

if ($_POST["werk8"] == null) {
    $werk8 = 0;
} else {
    $werk8 = $_POST["werk8"];
}

if ($_POST["social1"] == null) {
    $social1 = 0;
} else {
    $social1 = $_POST["social1"];
}

if ($_POST["social2"] == null) {
    $social2 = 0;
} else {
    $social2 = $_POST["social2"];
}

if ($_POST["social3"] == null) {
    $social3 = 0;
} else {
    $social3 = $_POST["social3"];
}

if ($_POST["social4"] == null) {
    $social4 = 0;
} else {
    $social4 = $_POST["social4"];
}

if ($_POST["social5"] == null) {
    $social5 = 0;
} else {
    $social5 = $_POST["social5"];
}

if ($_POST["social6"] == null) {
    $social6 = 0;
} else {
    $social6 = $_POST["social6"];
}

if ($_POST["social7"] == null) {
    $social7 = 0;
} else {
    $social7 = $_POST["social7"];
}

if ($_POST["social8"] == null) {
    $social8 = 0;
} else {
    $social8 = $_POST["social8"];
}

if ($_POST["social9"] == null) {
    $social9 = 0;
} else {
    $social9 = $_POST["social9"];
}

if ($_POST["social10"] == null) {
    $social10 = 0;
} else {
    $social10 = $_POST["social10"];
}

if ($_POST["gedrag1"] == null) {
    $gedrag1 = 0;
} else {
    $gedrag1 = $_POST["gedrag1"];
}

if ($_POST["gedrag2"] == null) {
    $gedrag2 = 0;
} else {
    $gedrag2 = $_POST["gedrag2"];
}

if ($_POST["gedrag3"] == null) {
    $gedrag3 = 0;
} else {
    $gedrag3 = $_POST["gedrag3"];
}

if ($_POST["gedrag4"] == null) {
    $gedrag4 = 0;
} else {
    $gedrag4 = $_POST["gedrag4"];
}

if ($_POST["gedrag5"] == null) {
    $gedrag5 = 0;
} else {
    $gedrag5 = $_POST["gedrag5"];
}

if ($_POST["gedrag6"] == null) {
    $gedrag6 = 0;
} else {
    $gedrag6 = $_POST["gedrag6"];
}

if ($_POST["buit1"] == null) {
    $buit1 = 0;
} else {
    $buit1 = $_POST["buit1"];
}

if ($_POST["buit2"] == null) {
    $buit2 = 0;
} else {
    $buit2 = $_POST["buit2"];
}

if ($_POST["buit3"] == null) {
    $buit3 = 0;
} else {
    $buit3 = $_POST["buit3"];
}

if ($_POST["motor1"] == null) {
    $motor1 = 0;
} else {
    $motor1 = $_POST["motor1"];
}

if ($_POST["motor2"] == null) {
    $motor2 = 0;
} else {
    $motor2 = $_POST["motor2"];
}

if ($_POST["motor3"] == null) {
    $motor3 = 0;
} else {
    $motor3 = $_POST["motor3"];
}

if ($_POST["mondelinge"] == null) {
    $mondelinge = ' ';
} else {
    $mondelinge = $_POST["mondelinge"];
}

if ($_POST["taalgebruik"] == null) {
    $taalgebruik = ' ';
} else {
    $taalgebruik = $_POST["taalgebruik"];
}

if ($_POST["lezen"] == null) {
    $lezen = ' ';
} else {
    $lezen = $_POST["lezen"];
}

if ($_POST["schrijven"] == null) {
    $schrijven = ' ';
} else {
    $schrijven = $_POST["schrijven"];
}

if ($_POST["rekenen"] == null) {
    $rekenen = ' ';
} else {
    $rekenen = $_POST["rekenen"];
}

if ($_POST["wereldorientatie"] == null) {
    $wereldorientatie = ' ';
} else {
    $wereldorientatie = $_POST["wereldorientatie"];
}

if ($_POST["verkeer"] == null) {
    $verkeer = ' ';
} else {
    $verkeer = $_POST["verkeer"];
}

if ($_POST["muziek"] == null) {
    $muziek = ' ';
} else {
    $muziek = $_POST["muziek"];
}

if ($_POST["art"] == null) {
    $art = ' ';
} else {
    $art = $_POST["art"];
}

if ($_POST["gymnastiek"] == null) {
    $gymnastiek = ' ';
} else {
    $gymnastiek = $_POST["gymnastiek"];
}

if ($_POST["zwemmen"] == null) {
    $zwemmen = ' ';
} else {
    $zwemmen = $_POST["zwemmen"];
}

if ($_POST["laat"] == null) {
    $laat = ' ';
} else {
    $laat = $_POST["laat"];
}

if ($_POST["verzuim"] == null) {
    $verzuim = ' ';
} else {
    $verzuim = $_POST["verzuim"];
}

if ($_POST["sociale"] == null) {
    $sociale = ' ';
} else {
    $sociale = $_POST["sociale"];
}

if ($_POST["conclusie"] == null) {
    $conclusie = ' ';
} else {
    $conclusie = $_POST["conclusie"];
}

$created = date('d-m-Y H:i:s');
$query_test = "SELECT id FROM montessori WHERE student_id = $id AND klas = '$klas' AND period = $periode AND schooljaar = '$schooljaar'";
$resultado = mysqli_query($mysqli, $query_test);
if (mysqli_num_rows($resultado) != 0 || $exist == '1') {
    $query = "UPDATE montessori SET klas='$klas',zelf1=$zelf1,zelf2=$zelf2,zelf3=$zelf3,zelf4=$zelf4,zelf5=$zelf5,werk1=$werk1,werk2=$werk2,werk3=$werk3,werk4=$werk4,werk5=$werk5,werk6=$werk6,werk7=$werk7,werk8=$werk8,social1=$social1,social2=$social2,social3=$social3,social4=$social4,social5=$social5,social6=$social6,social7=$social7,social8=$social8,social9=$social9,social10=$social10,gedrag1=$gedrag1,gedrag2=$gedrag2,gedrag3=$gedrag3,gedrag4=$gedrag4,gedrag5=$gedrag5,gedrag6=$gedrag6,buit1=$buit1,buit2=$buit2,buit3=$buit3,motor1=$motor1,motor2=$motor2,motor3=$motor3,mondelinge='$mondelinge',taalgebruik='$taalgebruik',lezen='$lezen',schrijven='$schrijven',rekenen='$rekenen',wereldorientatie='$wereldorientatie',verkeer='$verkeer',muziek='$muziek',art='$art',gymnastiek='$gymnastiek',zwemmen='$zwemmen',laat='$laat',verzuim='$verzuim',sociale='$sociale',conclusie='$conclusie' WHERE student_id = $id AND klas = '$klas' AND period = $periode AND schooljaar = '$schooljaar'";
} else {
    $query = "INSERT INTO montessori (student_id,created,schooljaar, klas, period, zelf1, zelf2, zelf3, zelf4, zelf5, werk1, werk2, werk3, werk4, werk5, werk6, werk7, werk8, social1, social2, social3, social4, social5, social6, social7, social8, social9, social10, gedrag1, gedrag2, gedrag3, gedrag4, gedrag5, gedrag6, buit1, buit2, buit3, motor1, motor2, motor3, mondelinge, taalgebruik, lezen, schrijven, rekenen, wereldorientatie, verkeer, muziek, art, gymnastiek, zwemmen, laat, verzuim, sociale, conclusie) VALUES ($id,'$created','$schooljaar','$klas',$periode,$zelf1,$zelf2,$zelf3,$zelf4,$zelf5,$werk1,$werk2,$werk3,$werk4,$werk5,$werk6,$werk7,$werk8,$social1,$social2,$social3,$social4,$social5,$social6,$social7,$social8,$social9,$social10,$gedrag1,$gedrag2,$gedrag3,$gedrag4,$gedrag5,$gedrag6,$buit1,$buit2,$buit3,$motor1,$motor2,$motor3,'$mondelinge','$taalgebruik','$lezen','$schrijven','$rekenen','$wereldorientatie','$verkeer','$muziek','$art','$gymnastiek','$zwemmen','$laat','$verzuim','$sociale','$conclusie')";
}
$resultado = mysqli_query($mysqli, $query);
if ($resultado) {
    if ($exist == 1) {
        print 2;
    } else {
        print 1;
    }
} else {
    print 0;
}
