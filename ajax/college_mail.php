<?php
require_once "../classes/DBCreds.php";
require_once "../classes/spn_utils.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$datum = date('d-m-Y');
$meld = isset($_POST["meld"]) ? $_POST["meld"] : 'null';
$naam = $_POST["naam"];
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


if ($_FILES["identiteitsbewijs"]["size"] != 0) {
    $identiteitsbewijs_base = basename($_FILES["identiteitsbewijs"]["name"]);
    $identiteitsbewijs_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $identiteitsbewijs_base;
    $subir = move_uploaded_file($_FILES["identiteitsbewijs"]["tmp_name"], $identiteitsbewijs_final);
}
if ($_FILES["verklaring"]["size"] != 0) {
    $verklaring_base = basename($_FILES["verklaring"]["name"]);
    $verklaring_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $verklaring_base;
    $subir1 = move_uploaded_file($_FILES["verklaring"]["tmp_name"], $verklaring_final);
}
if ($_FILES["schooladvies"]["size"] != 0) {
    $schooladvies_base = basename($_FILES["schooladvies"]["name"]);
    $schooladvies_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $schooladvies_base;
    $subir1 = move_uploaded_file($_FILES["schooladvies"]["tmp_name"], $schooladvies_final);
}
if ($_FILES["klas5"]["size"] != 0) {
    $klas5_base = basename($_FILES["klas5"]["name"]);
    $klas5_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $klas5_base;
    $subir2 = move_uploaded_file($_FILES["klas5"]["tmp_name"], $klas5_final);
}
if ($_FILES["klas6"]["size"] != 0) {
    $klas6_base = basename($_FILES["klas6"]["name"]);
    $klas6_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $klas6_base;
    $subir3 = move_uploaded_file($_FILES["klas6"]["tmp_name"], $klas6_final);
}
if ($_FILES["profielh3"]["size"] != 0) {
    $profielh3_base = basename($_FILES["profielh3"]["name"]);
    $profielh3_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $profielh3_base;
    $subir4 = move_uploaded_file($_FILES["profielh3"]["tmp_name"], $profielh3_final);
}
if ($_FILES["profielh4"]["size"] != 0) {
    $profielh4_base = basename($_FILES["profielh4"]["name"]);
    $profielh4_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $profielh4_base;
    $subir5 = move_uploaded_file($_FILES["profielh4"]["tmp_name"], $profielh4_final);
}
if ($_FILES["profielm4"]["size"] != 0) {
    $profielm4_base = basename($_FILES["profielm4"]["name"]);
    $profielm4_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $profielm4_base;
    $subir6 = move_uploaded_file($_FILES["profielm4"]["tmp_name"], $profielm4_final);
}
if ($_FILES["mavo4"]["size"] != 0) {
    $mavo4_base = basename($_FILES["mavo4"]["name"]);
    $mavo4_final = "college_forms/" . date("m-d-y") . "-" . date("h-i-s") . "-" . $mavo4_base;
    $subir7 = move_uploaded_file($_FILES["mavo4"]["tmp_name"], $mavo4_final);
}



$naam_o1 = $_POST["naam_o1"];
$plaats_o1 = $_POST["plaats_o1"];
$datum_o1 = $_POST["datum_o1"];
$naam_o2 = $_POST["naam_o2"];
$plaats_o2 = $_POST["plaats_o2"];
$datum_o2 = $_POST["datum_o2"];
$afleverende = $_POST["afleverende"];
$type = $_POST["type"];

/* $consulta = "SELECT naam,voornamen,f_nacemento FROM aplicacion_college WHERE naam = '$naam' AND voornamen = '$voornamen' AND f_nacemento = '$f_nacemento'";
$resultado = mysqli_query($mysqli, $consulta);
if (mysqli_num_rows($resultado) == 0) { */
if ($type == 2) {
    $profiel = $_POST["profiel"];
    $profiel1 = $_POST["profiel1"];
    $profiel2 = $_POST["profiel2"];
    $profiel3 = $_POST["profiel3"];
    $profiel4 = $_POST["profiel4"];
    $profiel5 = $_POST["profiel5"];

    $insertar = "INSERT INTO aplicacion_college(type,meld,datum,naam,voornamen,roepnaam,sexo,geboorteland,nacionalidad,f_nacemento,id_nr,wonachtig,adres,districto,telefoon_a,telefoon_c,email,religion,batisa,number_azv,cas,medicina,alergia,psycholoog,berkingen,leerstoorniseen,idioma,naam_p,voornamen_p,adres_p,celular_p,tel_p,geboorteland_p,nacionalidad_p,bibando_p,districto_p,email_p,religion_p,ocupacion_p,werkzaam_p,teltra_p,naam_m,voornamen_m,adres_m,celular_m,tel_m,geboorteland_m,nacionalidad_m,bibando_m,districto_m,email_m,religion_m,ocupacion_m,werkzaam_m,teltra_m,naam_v,voornamen_v,relatie,adres_v,celular_v,tel_v,geboorteland_v,nacionalidad_v,bibando_v,districto_v,email_v,religion_v,ocupacion_v,werkzaam_v,teltra_v,naam_g1,voornamen_g1,broer_g1,geboortedatum_g1,naam_g2,voornamen_g2,broer_g2,geboortedatum_g2,naam_g3,voornamen_g3,broer_g3,geboortedatum_g3,naam_g4,voornamen_g4,broer_g4,geboortedatum_g4,gesproken,informatie,naam_b1,basisschool_b1,school_b1,klas_b1,naam_b2,basisschool_b2,school_b2,klas_b2,naam_b3,basisschool_b3,school_b3,klas_b3,naam_b4,basisschool_b4,school_b4,klas_b4,relevant,identiteitsbewijs,verklaring,schooladvies,klas5,klas6,profielh3,profielh4,mavo4,profielm4,profiel,profiel1,profiel2,profiel3,profiel4,profiel5,naam_o1,plaats_o1,datum_o1,naam_o2,plaats_o2,datum_o2,afleverende) VALUES ('$type','$meld','$datum','$naam', '$voornamen','$roepnaam', '$sexo', '$geboorteland','$nacionalidad' , '$f_nacemento', '$id_nr','$wonachtig','$adres','$districto','$telefoon_a','$telefoon_c','$email','$religion','$batisa','$number_azv','$cas','$medicina','$alergia','$psycholoog','$berkingen','$leerstoorniseen','$idioma','$naam_p','$voornamen_p','$adres_p','$celular_p','$tel_p','$geboorteland_p','$nacionalidad_p','$bibando_p','$districto_p','$email_p','$religion_p','$ocupacion_p','$werkzaam_p','$teltra_p','$naam_m','$voornamen_m','$adres_m','$celular_m','$tel_m','$geboorteland_m','$nacionalidad_m','$bibando_m','$districto_m','$email_m','$religion_m','$ocupacion_m','$werkzaam_m','$teltra_m','$naam_v','$voornamen_v','$relatie','$adres_v','$celular_v','$tel_v','$geboorteland_v','$nacionalidad_v','$bibando_v','$districto_v','$email_v','$religion_v','$ocupacion_v','$werkzaam_v','$teltra_v','$naam_g1','$voornamen_g1','$broer_g1','$geboortedatum_g1','$naam_g2','$voornamen_g2','$broer_g2','$geboortedatum_g2','$naam_g3','$voornamen_g3','$broer_g3','$geboortedatum_g3','$naam_g4','$voornamen_g4','$broer_g4','$geboortedatum_g4','$gesproken','$informatie','$naam_b1','$basisschool_b1','$school_b1','$klas_b1','$naam_b2','$basisschool_b2','$school_b2','$klas_b2','$naam_b3','$basisschool_b3','$school_b3','$klas_b3','$naam_b4','$basisschool_b4','$school_b4','$klas_b4','$relevant','$identiteitsbewijs_final','$verklaring_final','$schooladvies_final','$klas5_final','$klas6_final','$profielh3_final','$profielh4_final','$mavo4_final','$profielm4_final','$profiel','$profiel1','$profiel2','$profiel3','$profiel4','$profiel5','$naam_o1','$plaats_o1','$datum_o1','$naam_o2','$plaats_o2','$datum_o2','$afleverende')";
    $resultado = mysqli_query($mysqli, $insertar);
    if ($resultado) {
        $consulta = "SELECT ID FROM aplicacion_college WHERE naam = '$naam' AND f_nacemento = '$f_nacemento'";
        $resultado = mysqli_query($mysqli, $consulta);
        $row = mysqli_fetch_assoc($resultado);
        $id = $row["ID"];
        $headers = "From: no-reply@qwihi.com";
        $subject = "Registratie nummer :" . $row["ID"];
        if ($meld != null && $meld != 0 && $meld != '') {
            $correo1 = "Bedankt dat u gekozen heeft voor de Mon Plaisir College Havo Vwo. Uw aanmelding wordt verwerkt, binnen 4 weken indien u alle nodige gegevens heeft opgestuurd.

            Indien er tijdens het uploaden van de nodige documenten problemen ontstaan. Kunt u de nodige gegevens mailen naar havompc.aanmelding@gmail.com. Dit valt onder optie 2.
            Maak alleen gebruik van optie 2 indien optie 1 niet lukt.";
        } else {
            $correo1 = "Bedankt dat u gekozen heeft voor de Mon Plaisir College Havo Vwo. Uw aanmelding wordt verwerkt, na het ontvangen van de uitslag van doorstroomtoets. Hooguit 2 juni moet u de uitslag ontvangen van de basisschool van uw kind.
            Mail de uitslag naar havompc.aanmelding@gmail.com zodat uw aanmelding verwerkt kan worden.";
        }
        mail($email_p, $subject, $correo1, $headers);
        mail($email_m, $subject, $correo1, $headers);
?>

        <body style="background-color: green;">
            <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:11%">
                <h1>Uploaded successfully.</h1>
                <img src="https://cdn.pixabay.com/photo/2017/03/28/01/46/check-mark-2180770_960_720.png" alt="correcto" width="100px" height="100px">
                <h3>We send you a confirmation to the parent's email.</h3>
                <?php echo $mavo4_final;
                echo $_FILES["profielm4_final"]["name"];
                echo $_FILES["klas6_final"]["name"]; ?>
                <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
            </div>
        </body>
    <?php } else { ?>

        <body style="background-color: red;">
            <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
                <h1>The form was not sent, please try again.</h1>
                <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
                <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
            </div>
        </body>
    <?php }
    /* } else {
    ?>

    <body style="background-color: red;">
        <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
            <h1>The form was not sent, the student has already been registered.</h1>
            <?php echo $mavo4_final;
            echo $_FILES["profielm4_final"]["name"];
            echo basename($_FILES["profielm4_final"]["name"]); ?>
            <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
            <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
        </div>
    </body>

<?php
}   ?> */
} else {
    $insertar = "INSERT INTO aplicacion_college(type,meld,datum,naam,voornamen,roepnaam,sexo,geboorteland,nacionalidad,f_nacemento,id_nr,wonachtig,adres,districto,telefoon_a,telefoon_c,email,religion,batisa,number_azv,cas,medicina,alergia,psycholoog,berkingen,leerstoorniseen,idioma,naam_p,voornamen_p,adres_p,celular_p,tel_p,geboorteland_p,nacionalidad_p,bibando_p,districto_p,email_p,religion_p,ocupacion_p,werkzaam_p,teltra_p,naam_m,voornamen_m,adres_m,celular_m,tel_m,geboorteland_m,nacionalidad_m,bibando_m,districto_m,email_m,religion_m,ocupacion_m,werkzaam_m,teltra_m,naam_v,voornamen_v,relatie,adres_v,celular_v,tel_v,geboorteland_v,nacionalidad_v,bibando_v,districto_v,email_v,religion_v,ocupacion_v,werkzaam_v,teltra_v,naam_g1,voornamen_g1,broer_g1,geboortedatum_g1,naam_g2,voornamen_g2,broer_g2,geboortedatum_g2,naam_g3,voornamen_g3,broer_g3,geboortedatum_g3,naam_g4,voornamen_g4,broer_g4,geboortedatum_g4,gesproken,informatie,naam_b1,basisschool_b1,school_b1,klas_b1,naam_b2,basisschool_b2,school_b2,klas_b2,naam_b3,basisschool_b3,school_b3,klas_b3,naam_b4,basisschool_b4,school_b4,klas_b4,relevant,identiteitsbewijs,verklaring,schooladvies,klas5,klas6,profielh3,profielh4,mavo4,profielm4,naam_o1,plaats_o1,datum_o1,naam_o2,plaats_o2,datum_o2,afleverende) VALUES ('$type','$meld','$datum','$naam', '$voornamen','$roepnaam', '$sexo', '$geboorteland','$nacionalidad' , '$f_nacemento', '$id_nr','$wonachtig','$adres','$districto','$telefoon_a','$telefoon_c','$email','$religion','$batisa','$number_azv','$cas','$medicina','$alergia','$psycholoog','$berkingen','$leerstoorniseen','$idioma','$naam_p','$voornamen_p','$adres_p','$celular_p','$tel_p','$geboorteland_p','$nacionalidad_p','$bibando_p','$districto_p','$email_p','$religion_p','$ocupacion_p','$werkzaam_p','$teltra_p','$naam_m','$voornamen_m','$adres_m','$celular_m','$tel_m','$geboorteland_m','$nacionalidad_m','$bibando_m','$districto_m','$email_m','$religion_m','$ocupacion_m','$werkzaam_m','$teltra_m','$naam_v','$voornamen_v','$relatie','$adres_v','$celular_v','$tel_v','$geboorteland_v','$nacionalidad_v','$bibando_v','$districto_v','$email_v','$religion_v','$ocupacion_v','$werkzaam_v','$teltra_v','$naam_g1','$voornamen_g1','$broer_g1','$geboortedatum_g1','$naam_g2','$voornamen_g2','$broer_g2','$geboortedatum_g2','$naam_g3','$voornamen_g3','$broer_g3','$geboortedatum_g3','$naam_g4','$voornamen_g4','$broer_g4','$geboortedatum_g4','$gesproken','$informatie','$naam_b1','$basisschool_b1','$school_b1','$klas_b1','$naam_b2','$basisschool_b2','$school_b2','$klas_b2','$naam_b3','$basisschool_b3','$school_b3','$klas_b3','$naam_b4','$basisschool_b4','$school_b4','$klas_b4','$relevant','$identiteitsbewijs_final','$verklaring_final','$schooladvies_final','$klas5_final','$klas6_final','$profielh3_final','$profielh4_final','$mavo4_final','$profielm4_final','$naam_o1','$plaats_o1','$datum_o1','$naam_o2','$plaats_o2','$datum_o2','$afleverende')";
    $resultado = mysqli_query($mysqli, $insertar);
    if ($resultado) {
        $consulta = "SELECT ID FROM aplicacion_college WHERE naam = '$naam' AND f_nacemento = '$f_nacemento'";
        $resultado = mysqli_query($mysqli, $consulta);
        $row = mysqli_fetch_assoc($resultado);
        $id = $row["ID"];
        $headers = "From: no-reply@qwihi.com";
        $subject = "Registratie nummer :" . $row["ID"];
        if ($meld != null && $meld != 0 && $meld != '') {
            $correo1 = "Bedankt dat u gekozen heeft voor de Mon Plaisir College Havo Vwo. Uw aanmelding wordt verwerkt, binnen 4 weken indien u alle nodige gegevens heeft opgestuurd.

            Indien er tijdens het uploaden van de nodige documenten problemen ontstaan. Kunt u de nodige gegevens mailen naar havompc.aanmelding@gmail.com. Dit valt onder optie 2.
            Maak alleen gebruik van optie 2 indien optie 1 niet lukt.";
        } else {
            $correo1 = "Bedankt dat u gekozen heeft voor de Mon Plaisir College Havo Vwo. Uw aanmelding wordt verwerkt, na het ontvangen van de uitslag van doorstroomtoets. Hooguit 2 juni moet u de uitslag ontvangen van de basisschool van uw kind.
            Mail de uitslag naar havompc.aanmelding@gmail.com zodat uw aanmelding verwerkt kan worden.";
        }
        mail($email_p, $subject, $correo1, $headers);
        mail($email_m, $subject, $correo1, $headers);
    ?>

        <body style="background-color: green;">
            <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:11%">
                <h1>Uploaded successfully.</h1>
                <img src="https://cdn.pixabay.com/photo/2017/03/28/01/46/check-mark-2180770_960_720.png" alt="correcto" width="100px" height="100px">
                <h3>We send you a confirmation to the parent's email.</h3>
                <?php echo $mavo4_final;
                echo $_FILES["profielm4_final"]["name"];
                echo $_FILES["klas6_final"]["name"]; ?>
                <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
            </div>
        </body>
    <?php } else { ?>

        <body style="background-color: red;">
            <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
                <h1>The form was not sent, please try again.</h1>
                <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
                <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
            </div>
        </body>
<?php }
    /* } else {
    ?>

    <body style="background-color: red;">
        <div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
            <h1>The form was not sent, the student has already been registered.</h1>
            <?php echo $mavo4_final;
            echo $_FILES["profielm4_final"]["name"];
            echo basename($_FILES["profielm4_final"]["name"]); ?>
            <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
            <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_college.php">Go back.</a>
        </div>
    </body>

<?php
}   ?> */
}
