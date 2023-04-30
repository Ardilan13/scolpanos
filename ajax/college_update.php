<?php
require_once "../classes/DBCreds.php";
require_once "../classes/spn_utils.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$id = $_POST["id"];
$datum = date('d-m-Y');
$naam = $_POST["naam"];
$meld = isset($_POST["meld"]) ? $_POST["meld"] : 'null';
$posicion = strpos($naam, "'");
if ($posicion != false) {
    $a = explode("'", $naam);
    $naam = $a[0] . "\'" . $a[1];
}
$voornamen = $_POST["voornamen"];
$posicion = strpos($voornamen, "'");
if ($posicion != false) {
    $a = explode("'", $voornamen);
    $voornamen = $a[0] . "\'" . $a[1];
}
$roepnaam = $_POST["roepnaam"];
$posicion = strpos($roepnaam, "'");
if ($posicion != false) {
    $a = explode("'", $roepnaam);
    $roepnaam = $a[0] . "\'" . $a[1];
}
$sexo = $_POST["sexo"];

$geboorteland = $_POST["geboorteland"];
$posicion = strpos($geboorteland, "'");
if ($posicion != false) {
    $a = explode("'", $geboorteland);
    $geboorteland = $a[0] . "\'" . $a[1];
}
$nacionalidad = $_POST["nacionalidad"];
$posicion = strpos($nacionalidad, "'");
if ($posicion != false) {
    $a = explode("'", $nacionalidad);
    $nacionalidad = $a[0] . "\'" . $a[1];
}

$f_nacemento = $_POST["f_nacemento"];
$id_nr = $_POST["id_nr"];

$wonachtig = $_POST["wonachtig"];
$posicion = strpos($wonachtig, "'");
if ($posicion != false) {
    $a = explode("'", $wonachtig);
    $wonachtig = $a[0] . "\'" . $a[1];
}
$adres = $_POST["adres"];
$posicion = strpos($adres, "'");
if ($posicion != false) {
    $a = explode("'", $adres);
    $adres = $a[0] . "\'" . $a[1];
}


$districto = $_POST["districto"];
$telefoon_a = $_POST["telefoon_a"];
$telefoon_c = $_POST["telefoon_c"];
$email = $_POST["email"];
$religion = $_POST["religion"];
$batisa = $_POST["batisa"];
$number_azv = $_POST["number_azv"];
$cas = $_POST["cas"];
$medicina = $_POST["medicina"];
$alergia = $_POST["alergia"];
$psycholoog = $_POST["psycholoog"];
$berkingen = $_POST["berkingen"];
$leerstoorniseen = $_POST["leerstoorniseen"];
$idioma = $_POST["idioma"];
$idioma_otro = $_POST["idioma_otro"];
$posicion = strpos($idioma_otro, "'");
if ($posicion != false) {
    $a = explode("'", $idioma_otro);
    $idioma_otro = $a[0] . "\'" . $a[1];
}
switch ($idioma) {
    case 0:
        $idioma = $idioma_otro;
        break;
    case 5:
        $idioma = $idioma_otro;
        break;
}


$naam_p = $_POST["naam_p"];
$posicion = strpos($naam_p, "'");
if ($posicion != false) {
    $a = explode("'", $naam_p);
    $naam_p = $a[0] . "\'" . $a[1];
}
$voornamen_p = $_POST["voornamen_p"];
$posicion = strpos($voornamen_p, "'");
if ($posicion != false) {
    $a = explode("'", $voornamen_p);
    $voornamen_p = $a[0] . "\'" . $a[1];
}
$adres_p = $_POST["adres_p"];
$celular_p = $_POST["celular_p"];
$tel_p = $_POST["tel_p"];
$geboorteland_p = $_POST["geboorteland_p"];
$nacionalidad_p = $_POST["nacionalidad_p"];
$bibando_p = $_POST["bibando_p"];
$districto_p = $_POST["districto_p"];
$email_p = $_POST["email_p"];
$religion_p = $_POST["religion_p"];
$ocupacion_p = $_POST["ocupacion_p"];
$werkzaam_p = $_POST["werkzaam_p"];
$teltra_p = $_POST["teltra_p"];


$naam_m = $_POST["naam_m"];
$posicion = strpos($naam_m, "'");
if ($posicion != false) {
    $a = explode("'", $naam_m);
    $naam_m = $a[0] . "\'" . $a[1];
}
$voornamen_m = $_POST["voornamen_m"];
$posicion = strpos($voornamen_m, "'");
if ($posicion != false) {
    $a = explode("'", $voornamen_m);
    $voornamen_m = $a[0] . "\'" . $a[1];
}
$adres_m = $_POST["adres_m"];
$celular_m = $_POST["celular_m"];
$tel_m = $_POST["tel_m"];
$geboorteland_m = $_POST["geboorteland_m"];
$nacionalidad_m = $_POST["nacionalidad_m"];
$bibando_m = $_POST["bibando_m"];
$districto_m = $_POST["districto_m"];
$email_m = $_POST["email_m"];
$religion_m = $_POST["religion_m"];
$ocupacion_m = $_POST["ocupacion_m"];
$werkzaam_m = $_POST["werkzaam_m"];
$teltra_m = $_POST["teltra_m"];


$naam_v = $_POST["naam_v"];
$posicion = strpos($naam_v, "'");
if ($posicion != false) {
    $a = explode("'", $naam_v);
    $naam_v = $a[0] . "\'" . $a[1];
}
$voornamen_v = $_POST["voornamen_v"];
$posicion = strpos($voornamen_v, "'");
if ($posicion != false) {
    $a = explode("'", $voornamen_v);
    $voornamen_v = $a[0] . "\'" . $a[1];
}
$relatie = $_POST["relatie"];
$adres_v = $_POST["adres_v"];
$celular_v = $_POST["celular_v"];
$tel_v = $_POST["tel_v"];
$geboorteland_v = $_POST["geboorteland_v"];
$nacionalidad_v = $_POST["nacionalidad_v"];
$bibando_v = $_POST["bibando_v"];
$districto_v = $_POST["districto_v"];
$email_v = $_POST["email_v"];
$religion_v = $_POST["religion_v"];
$ocupacion_v = $_POST["ocupacion_v"];
$werkzaam_v = $_POST["werkzaam_v"];
$teltra_v = $_POST["teltra_v"];


$naam_g1 = $_POST["naam_g1"];
$voornamen_g1 = $_POST["voornamen_g1"];
$broer_g1 = $_POST["broer_g1"];
$geboortedatum_g1 = $_POST["geboortedatum_g1"];
$naam_g2 = $_POST["naam_g2"];
$voornamen_g2 = $_POST["voornamen_g2"];
$broer_g2 = $_POST["broer_g2"];
$geboortedatum_g2 = $_POST["geboortedatum_g2"];
$naam_g3 = $_POST["naam_g3"];
$voornamen_g3 = $_POST["voornamen_g3"];
$broer_g3 = $_POST["broer_g3"];
$geboortedatum_g3 = $_POST["geboortedatum_g3"];
$naam_g4 = $_POST["naam_g4"];
$voornamen_g4 = $_POST["voornamen_g4"];
$broer_g4 = $_POST["broer_g4"];
$geboortedatum_g4 = $_POST["geboortedatum_g4"];
/* $naam_g5 = $_POST["naam_g5"];
$voornamen_g5 = $_POST["voornamen_g5"];
$broer_g5 = $_POST["broer_g5"];
$geboortedatum_g5 = $_POST["geboortedatum_g5"];
$naam_g6 = $_POST["naam_g6"];
$voornamen_g6 = $_POST["voornamen_g6"];
$broer_g6 = $_POST["broer_g6"];
$geboortedatum_g6 = $_POST["geboortedatum_g6"]; */
$gesproken = $_POST["gesproken"];
$informatie = $_POST["informatie"];

$naam_b1 = $_POST["naam_b1"];
$basisschool_b1 = $_POST["basisschool_b1"];
$school_b1 = $_POST["school_b1"];
$klas_b1 = $_POST["klas_b1"];
$naam_b2 = $_POST["naam_b2"];
$basisschool_b2 = $_POST["basisschool_b2"];
$school_b2 = $_POST["school_b2"];
$klas_b2 = $_POST["klas_b2"];
$naam_b3 = $_POST["naam_b3"];
$basisschool_b3 = $_POST["basisschool_b3"];
$school_b3 = $_POST["school_b3"];
$klas_b3 = $_POST["klas_b3"];
$naam_b4 = $_POST["naam_b4"];
$basisschool_b4 = $_POST["basisschool_b4"];
$school_b4 = $_POST["school_b4"];
$klas_b4 = $_POST["klas_b4"];
/* $naam_b5 = $_POST["naam_b5"];
$basisschool_b5 = $_POST["basisschool_b5"];
$school_b5 = $_POST["school_b5"];
$klas_b5 = $_POST["klas_b5"];
$naam_b6 = $_POST["naam_b6"];
$basisschool_b6 = $_POST["basisschool_b6"];
$school_b6 = $_POST["school_b6"];
$klas_b6 = $_POST["klas_b6"]; */
$relevant = $_POST["relevant"];


/* $azv = $_POST["azv"];
$huisarts = $_POST["huisarts"];
$telefoon1 = $_POST["telefoon1"];
$tandarts = $_POST["tandarts"];
$telefoon2 = $_POST["telefoon2"];
$medicijngebruik = $_POST["medicijngebruik"];
$allergieen = $_POST["allergieen"];
$ruimte = $_POST["ruimte"];
$logopedist = $_POST["logopedist"];
$fysiotherapeut = $_POST["fysiotherapeut"];
$behandeling = $_POST["behandeling"];
$voor = $_POST["voor"];
$dyslexie_f = $_POST["dyslexie_f"];
$dyslexie = $_POST["dyslexie"];
$dyscalculie = $_POST["dyscalculie"]; */

$naam_o1 = $_POST["naam_o1"];
$plaats_o1 = $_POST["plaats_o1"];
$datum_o1 = $_POST["datum_o1"];
$naam_o2 = $_POST["naam_o2"];
$plaats_o2 = $_POST["plaats_o2"];
$datum_o2 = $_POST["datum_o2"];
$afleverende = $_POST["afleverende"];


$status = $_POST["status"];

$insertar = "UPDATE aplicacion_college set meld = '$meld',naam = '$naam',voornamen = '$voornamen',roepnaam = '$roepnaam',sexo = '$sexo',geboorteland= '$geboorteland',nacionalidad= '$nacionalidad',f_nacemento= '$f_nacemento',id_nr= '$id_nr',wonachtig= '$wonachtig',adres= '$adres',districto= '$districto',telefoon_a= '$telefoon_a',telefoon_c= '$telefoon_c',email= '$email',religion= '$religion',batisa= '$batisa',number_azv= '$number_azv',cas= '$cas',medicina= '$medicina',alergia= '$alergia',psycholoog= '$psycholoog',berkingen= '$berkingen',leerstoorniseen='$leerstoorniseen',idioma='$idioma',naam_p= '$naam_p',voornamen_p= '$voornamen_p',adres_p= '$adres_p',celular_p= '$celular_p',tel_p= '$tel_p',geboorteland_p= '$geboorteland_p',nacionalidad_p= '$nacionalidad_p',bibando_p= '$bibando_p',districto_p= '$districto_p',email_p= '$email_p',religion_p= '$religion_p',ocupacion_p= '$ocupacion_p',werkzaam_p= '$werkzaam_p',teltra_p= '$teltra_p',naam_m= '$naam_m',voornamen_m= '$voornamen_m',adres_m= '$adres_m',celular_m= '$celular_m',tel_m= '$tel_m',geboorteland_m= '$geboorteland_m',nacionalidad_m= '$nacionalidad_m',bibando_m = '$bibando_m',districto_m = '$districto_m',email_m = '$email_m',religion_m = '$religion_m',ocupacion_m = '$ocupacion_m',werkzaam_m = '$werkzaam_m',teltra_m = '$teltra_m',naam_v= '$naam_v',voornamen_v= '$voornamen_v',relatie= '$relatie',adres_v= '$adres_v',celular_v= '$celular_v',tel_v= '$tel_v',geboorteland_v= '$geboorteland_v',nacionalidad_v= '$nacionalidad_v',bibando_v = '$bibando_v',districto_v = '$districto_v',email_v = '$email_v',religion_v = '$religion_v',ocupacion_v = '$ocupacion_v',werkzaam_v = '$werkzaam_v',naam_g1 = '$naam_g1',voornamen_g1 = '$voornamen_g1',broer_g1 = '$broer_g1',geboortedatum_g1 = '$geboortedatum_g1',naam_g2 = '$naam_g2',voornamen_g2 = '$voornamen_g2',broer_g2 = '$broer_g2',geboortedatum_g2 = '$geboortedatum_g2',naam_g3 = '$naam_g3',voornamen_g3 = '$voornamen_g3',broer_g3 = '$broer_g3',geboortedatum_g3 = '$geboortedatum_g3',naam_g4 = '$naam_g4',voornamen_g4 = '$voornamen_g4',broer_g4 = '$broer_g4',geboortedatum_g4 = '$geboortedatum_g4',gesproken = '$gesproken',informatie = '$informatie',naam_b1 = '$naam_b1',basisschool_b1 = '$basisschool_b1',school_b1 = '$school_b1',klas_b1 = '$klas_b1',naam_b2 = '$naam_b2',basisschool_b2 = '$basisschool_b2',school_b2 = '$school_b2',klas_b2 = '$klas_b2',naam_b3 = '$naam_b3',basisschool_b3 = '$basisschool_b3',school_b3 = '$school_b3',klas_b3 = '$klas_b3',naam_b4 = '$naam_b4',basisschool_b4 = '$basisschool_b4',school_b4 = '$school_b4',klas_b4 = '$klas_b4',relevant='$relevant',naam_o1='$naam_o1',plaats_o1='$plaats_o1',datum_o1='$datum_o1',naam_o2='$naam_o2',plaats_o2='$plaats_o2',datum_o2='$datum_o2',afleverende='$afleverende',status = '$status' WHERE ID = '$id'";
$resultado = mysqli_query($mysqli, $insertar);
if ($resultado) { ?>

    <body style="background-color: green;">
        <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:11%">
            <h1>Updated successfully.</h1>
            <img src="https://cdn.pixabay.com/photo/2017/03/28/01/46/check-mark-2180770_960_720.png" alt="correcto" width="100px" height="100px">
            <h3>the acceptance data was updated correctly.</h3>
            <a style="font-size: large; display: block; margin-top: 20px;" href="javascript:history.back();history.back()">Go back.</a>
        </div>
    </body>
<?php
} else {
    /* printf("Error message: %s\n", mysqli_error($mysqli)); */
?>

    <body style="background-color: red;">
        <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
            <h1>The form was not sent, please try again.</h1>
            <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
            <a style="font-size: large; display: block; margin-top: 20px;" href="javascript:history.back();history.back()">Go back.</a>
        </div>
    </body>

<?php
} ?>