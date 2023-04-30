<?php
require_once "../classes/DBCreds.php";
require_once "../classes/spn_utils.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$id = $_POST["id"];
$pp = $_POST["pp"];
$id = $_POST["id"];
$nomber = $_POST["nomber"];
$posicion = strpos($nomber, "'");
if ($posicion != false) {
    $a = explode("'", $nomber);
    $nomber = $a[0] . "\'" . $a[1];
}
$fam = $_POST["fam"];
$posicion = strpos($fam, "'");
if ($posicion != false) {
    $a = explode("'", $fam);
    $fam = $a[0] . "\'" . $a[1];
}
$sexo = $_POST["sexo"];
$nacionalidad = $_POST["nacionalidad"];
$posicion = strpos($nacionalidad, "'");
if ($posicion != false) {
    $a = explode("'", $nacionalidad);
    $nacionalidad = $a[0] . "\'" . $a[1];
}
$f_nacemento = $_POST["f_nacemento"];
$l_nacemento = $_POST["l_nacemento"];
$posicion = strpos($l_nacemento, "'");
if ($posicion != false) {
    $a = explode("'", $l_nacemento);
    $l_nacemento = $a[0] . "\'" . $a[1];
}
$azv = $_POST["azv"];
if ($azv == 1) {
    $azv_number = $_POST["azv_number"];
} else {
    $azv_number = 2;
}
$priva = $_POST["priva"];
$caya = $_POST["caya"];
$posicion = strpos($caya, "'");
if ($posicion != false) {
    $a = explode("'", $caya);
    $caya = $a[0] . "\'" . $a[1];
}
$cas = $_POST["cas"];
$posicion = strpos($cas, "'");
if ($posicion != false) {
    $a = explode("'", $cas);
    $cas = $a[0] . "\'" . $a[1];
}
$districto = $_POST["districto"];
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
$guia = $_POST["guia"];
$guia_otro = $_POST["guia_otro"];
$posicion = strpos($guia_otro, "'");
if ($posicion != false) {
    $a = explode("'", $guia_otro);
    $guia_otro = $a[0] . "\'" . $a[1];
}
switch ($guia) {
    case 0:
        $guia = $guia_otro;
        break;
    case 10:
        $guia = $guia_otro;
        break;
}
$problema = $_POST["problema"];
$problema_otro = $_POST["problema_otro"];
$posicion = strpos($problema_otro, "'");
if ($posicion != false) {
    $a = explode("'", $problema_otro);
    $problema_otro = $a[0] . "\'" . $a[1];
}
switch ($problema) {
    case 0:
        $problema = $problema_otro;
        break;
    case 7:
        $problema = $problema_otro;
        break;
}
$zildelijk = $_POST["zildelijk"];
$zildelijk1 = $_POST["zildelijk1"];
$ultimo_scol = $_POST["ultimo_scol"];
$posicion = strpos($ultimo_scol, "'");
if ($posicion != false) {
    $a = explode("'", $ultimo_scol);
    $ultimo_scol = $a[0] . "\'" . $a[1];
}
$nomber_fam = $_POST["nomber_fam"];
$posicion = strpos($nomber_fam, "'");
if ($posicion != false) {
    $a = explode("'", $nomber_fam);
    $nomber_fam = $a[0] . "\'" . $a[1];
}
$scol = $_POST["scol"];
$posicion = strpos($scol, "'");
if ($posicion != false) {
    $a = explode("'", $scol);
    $scol = $a[0] . "\'" . $a[1];
}
$klas = $_POST["klas"];
$posicion = strpos($klas, "'");
if ($posicion != false) {
    $a = explode("'", $klas);
    $klas = $a[0] . "\'" . $a[1];
}
$prome_preferencia = $_POST["prome_preferencia"];
$segundo_preferencia = $_POST["segundo_preferencia"];
$ruman = $_POST["ruman"];
$collega = $_POST["collega"];
$publico = $_POST["publico"];
$estado = $_POST["estado"];
$estado_otro = $_POST["estado_otro"];
$posicion = strpos($estado_otro, "'");
if ($posicion != false) {
    $a = explode("'", $estado_otro);
    $estado_otro = $a[0] . "\'" . $a[1];
}
switch ($estado) {
    case 0:
        $estado = $estado_otro;
        break;
    case 5:
        $estado = $estado_otro;
        break;
}
$relacion = $_POST["relacion"];
$relacion_otro = $_POST["relacion_otro"];
$posicion = strpos($relacion_otro, "'");
if ($posicion != false) {
    $a = explode("'", $relacion_otro);
    $relacion_otro = $a[0] . "\'" . $a[1];
}
if ($relacion == 3) {
    $relacion = $relacion_otro;
}
$voogd = $_POST["voogd"];
$mesun = $_POST["mesun"];
$nomber_mayor1 = $_POST["nomber_mayor1"];
$posicion = strpos($nomber_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $nomber_mayor1);
    $nomber_mayor1 = $a[0] . "\'" . $a[1];
}
$fam_mayor1 = $_POST["fam_mayor1"];
$posicion = strpos($fam_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $fam_mayor1);
    $fam_mayor1 = $a[0] . "\'" . $a[1];
}
$number_mayor1 = $_POST["number_mayor1"];
$posicion = strpos($number_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $number_mayor1);
    $number_mayor1 = $a[0] . "\'" . $a[1];
}
$email_mayor1 = $_POST["email_mayor1"];
$posicion = strpos($email_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $email_mayor1);
    $email_mayor1 = $a[0] . "\'" . $a[1];
}
$traha_mayor1 = $_POST["traha_mayor1"];
$posicion = strpos($traha_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $traha_mayor1);
    $traha_mayor1 = $a[0] . "\'" . $a[1];
}
$ocupacion_mayor1 = $_POST["ocupacion_mayor1"];
$posicion = strpos($ocupacion_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $ocupacion_mayor1);
    $ocupacion_mayor1 = $a[0] . "\'" . $a[1];
}
$telefon_mayor1 = $_POST["telefon_mayor1"];
$posicion = strpos($telefon_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $telefon_mayor1);
    $telefon_mayor1 = $a[0] . "\'" . $a[1];
}
$caya_mayor1 = $_POST["caya_mayor1"];
$posicion = strpos($caya_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $caya_mayor1);
    $caya_mayor1 = $a[0] . "\'" . $a[1];
}
$cas_mayor1 = $_POST["cas_mayor1"];
$posicion = strpos($cas_mayor1, "'");
if ($posicion != false) {
    $a = explode("'", $cas_mayor1);
    $cas_mayor1 = $a[0] . "\'" . $a[1];
}
$relacion2 = $_POST["relacion2"];
$posicion = strpos($relacion_otro2, "'");
if ($posicion != false) {
    $a = explode("'", $relacion_otro2);
    $relacion_otro2 = $a[0] . "\'" . $a[1];
}
$relacion_otro2 = $_POST["relacion_otro2"];
if ($relacion2 == 3) {
    $relacion2 = $relacion_otro2;
}
$voogd2 = $_POST["voogd2"];
$mesun2 = $_POST["mesun2"];
$nomber_mayor2 = $_POST["nomber_mayor2"];
$posicion = strpos($nomber_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $nomber_mayor2);
    $nomber_mayor2 = $a[0] . "\'" . $a[1];
}
$fam_mayor2 = $_POST["fam_mayor2"];
$posicion = strpos($fam_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $fam_mayor2);
    $fam_mayor2 = $a[0] . "\'" . $a[1];
}
$number_mayor2 = $_POST["number_mayor2"];
$posicion = strpos($number_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $number_mayor2);
    $number_mayor2 = $a[0] . "\'" . $a[1];
}
$email_mayor2 = $_POST["email_mayor2"];
$posicion = strpos($email_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $email_mayor2);
    $email_mayor2 = $a[0] . "\'" . $a[1];
}
$traha_mayor2 = $_POST["traha_mayor2"];
$posicion = strpos($traha_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $traha_mayor2);
    $traha_mayor2 = $a[0] . "\'" . $a[1];
}
$ocupacion_mayor2 = $_POST["ocupacion_mayor2"];
$posicion = strpos($ocupacion_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $ocupacion_mayor2);
    $ocupacion_mayor2 = $a[0] . "\'" . $a[1];
}
$telefon_mayor2 = $_POST["telefon_mayor2"];
$posicion = strpos($telefon_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $telefon_mayor2);
    $telefon_mayor2 = $a[0] . "\'" . $a[1];
}
$caya_mayor2 = $_POST["caya_mayor2"];
$posicion = strpos($caya_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $caya_mayor2);
    $caya_mayor2 = $a[0] . "\'" . $a[1];
}
$cas_mayor2 = $_POST["cas_mayor2"];
$posicion = strpos($cas_mayor2, "'");
if ($posicion != false) {
    $a = explode("'", $cas_mayor2);
    $cas_mayor2 = $a[0] . "\'" . $a[1];
}
$motivacion = $_POST["motivacion"];
if ($posicion != false) {
    $a = explode("'", $motivacion);
    $motivacion = $a[0] . "\'" . $a[1];
}
$status = $_POST["status"];
if ($status == 1) {
    $scool = $_POST["scool"];
    if ($scool != 0 && $scool != null) {
        $prome_preferencia = $scool;
        $segundo_preferencia = $scool;
    }
}
if ($status == 2) {
    $scool = $_POST["scool"];
    if ($scool != 0 && $scool != null) {
        $select = "SELECT prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE ID = '$id'";
        $resultado1 = mysqli_query($mysqli, $select);
        if ($resultado1) {
            $row = mysqli_fetch_array($resultado1);
            $primer = $row["prome_preferencia"];
            $segunda = $row["segundo_preferencia"];
            if ($segunda == $scool && $primer == $scool) {
                $prome_preferencia = $scool;
                $segundo_preferencia = $scool;
            } else if ($primer == $scool) {
                $prome_preferencia = $segunda;
                $segundo_preferencia = $segunda;
                $status = 3;
            } else if ($segunda == $scool) {
                $prome_preferencia = $primer;
                $segundo_preferencia = $primer;
                $status = 3;
            }
        }
    }
}
$coment = $_POST["coment"];
/* if ($_FILES["censo"]) {
    $censo_base = basename($_FILES["censo"]["name"]);
    $censo_final = date("m-d-y") . "-" . date("h-i-s") . "-" . $censo_base;
    $subir = move_uploaded_file($_FILES["censo"]["tmp_name"], $censo_final);
    if ($subir) { */
$insertar = "UPDATE aplicacion_forms set nomber = '$nomber',fam = '$fam',sexo = '$sexo',nacionalidad = '$nacionalidad',f_nacemento= '$f_nacemento',l_nacemento= '$l_nacemento',azv= '$azv_number',priva= '$priva',caya= '$caya',cas= '$cas',districto= '$districto',idioma= '$idioma',guia= '$guia',problema= '$problema',zildelijk= '$zildelijk',zildelijk1= '$zildelijk1',ultimo_scol= '$ultimo_scol',nomber_fam= '$nomber_fam',scol= '$scol',klas= '$klas',prome_preferencia= '$prome_preferencia',segundo_preferencia= '$segundo_preferencia',ruman= '$ruman',collega='$collega',publico='$publico',estado= '$estado',relacion= '$relacion',voogd= '$voogd',mesun= '$mesun',nomber_mayor1= '$nomber_mayor1',fam_mayor1= '$fam_mayor1',number_mayor1= '$number_mayor1',email_mayor1= '$email_mayor1',traha_mayor1= '$traha_mayor1',ocupacion_mayor1= '$ocupacion_mayor1',telefon_mayor1= '$telefon_mayor1',caya_mayor1= '$caya_mayor1',cas_mayor1= '$cas_mayor1',relacion2= '$relacion2',voogd2= '$voogd2',mesun2= '$mesun2',nomber_mayor2= '$nomber_mayor2',fam_mayor2= '$fam_mayor2',number_mayor2= '$number_mayor2',email_mayor2= '$email_mayor2',traha_mayor2= '$traha_mayor2',ocupacion_mayor2= '$ocupacion_mayor2',telefon_mayor2= '$telefon_mayor2',caya_mayor2= '$caya_mayor2',cas_mayor2= '$cas_mayor2',motivacion = '$motivacion',status = '$status' ,coment = '$coment' WHERE ID = '$id'";
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
}
/* }  else {
        ?>
            <body style="background-color: red;">
            <div  style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:17%">
                <h1>The form was not sent, please try again.</h1>
                <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="100px" height="100px">
                <a style="font-size: large; display: block; margin-top: 20px;" href="../aplicacion_form.php">Go back.</a>
            </div>
            </body>

            <?php
}
} */

?>