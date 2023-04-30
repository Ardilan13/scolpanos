<?php
require_once "classes/DBCreds.php";
require_once "classes/spn_utils.php";
require_once "document_start.php";
require_once "classes/spn_leerling.php";
require_once "classes/spn_contact.php";
require_once "classes/spn_social_work.php"; //CODE CaribeDevelopers
require_once "classes/spn_mdc.php"; //CODE CaribeDevelopers
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
require_once("../classes/DBCreds.php");
$DBCreds = new DBCreds();
date_default_timezone_set("America/Aruba");

$user_hs_email = "dilan@gmail.com";

$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
// print('diferente');
$delete = "DELETE FROM app_useraccounts WHERE Email = '$user_hs_email';";
$resultado123 = mysqli_query($mysqli, $delete);
if ($resultado123) {
	echo "hola";
} else {
	echo "adios";
}

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
