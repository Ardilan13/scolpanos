<?php require_once "config/app.config.php";
require_once "classes/DBCreds.php";
ob_start();
ob_flush();
session_start();
if (!isset($_SESSION['already_refreshed'])) {
    $ActualizarDespues = 5;
    header('Refresh:' . $ActualizarDespues);
    $_SESSION['already_refreshed'] = true;
} ?>
<style type="text/css" media="screen">
    h2 {
        color: red;
        border-bottom: 2px solid red;
        margin: 0 25%;
        margin-bottom: 3%;
    }

    label {
        display: inline-block;
        margin: 1.2% .5% 1.2% 2%;
    }

    button {
        margin: 4%;
        background-color: yellow;
        font-size: large;
        cursor: pointer;
        padding: 1% 6%;
        border-radius: 7px;
        border: 2px solid black;
    }

    button:hover {
        background-color: orange;
        transition: 100ms;
    }

    body {
        background-color: #1d407a;
    }

    label {
        font-size: 1rem;
    }

    .container {
        text-align: center;
    }

    .form-bg {
        background-color: white;
        margin: 1%;
        padding: 1%;
        text-align: center;
        border-radius: 5px;
        box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
    }

    table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
        border: 2px solid #1d407a;
    }

    thead {
        background-color: lightgray;
        font-size: 20px;
        padding: 10px;
    }

    thead th {
        padding: 10px 0;
    }

    th {
        border: 2px solid #1d407a;
    }

    tbody th {
        font-size: 15px;
        width: 100%;
        padding: 10px 0;
    }

    .status {
        width: 5%;
    }

    .sex {
        width: 3%;
    }

    .id {
        width: 2%;
    }

    .datum {
        width: 8%;
    }

    .button {
        background-color: yellow;
        font-size: large;
        cursor: pointer;
        padding: 1% 6%;
        border-radius: 3px;
        border: 1px solid black;
    }

    .delete {
        background-color: red;
    }

    .button:hover {
        background-color: orange !important;
        transition: 100ms !important;
    }

    .qwihi {
        background-color: #262532;
        padding-top: 1%;
        margin: -2% -0.45%;
        align-items: center;
        text-align: center;
        bottom: 0;

    }

    #qwihi {
        color: white;
        font-size: larger;
    }

    #logo {
        border-right: 1px solid white;
        margin-right: 3%;
        padding-right: 5%;
    }
</style>

<head>
    <title>Aplicacion Form Admin</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <link rel="icon" href="img/scholenlogo.png">
</head>
<main>
    <div class="container">
        <div class="form-bg"'>
            <div class="spn-logo">
				<h1 class="brand">
					<img class="img-responsive" src="img/scholen.png" alt="Scol Pa Nos" width="22%">
				</h1>
			</div>
            <h1>Admin</h1>
            <h2>Aplicacion Scol 2023-2024:</h2>
            <?php $DBCreds = new DBCreds();
            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
            $mysqli->set_charset('utf8');
            $query = "SELECT ID,status,fam,nomber,sexo,f_nacemento,districto,ruman,collega,number_mayor1,number_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE (prome_preferencia>=9 OR segundo_preferencia>=9) ORDER BY ID ASC";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) { ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="id">ID</th>
                        <th class="sex">Status</th>
                        <th class="datum">Fam</th>
                        <th class="datum">Nomber</th>
                        <th class="sex">Sexo</th>
                        <th class="datum">Fecha di nacemento</th>
                        <th class="status">Districto</th>   
                        <th class="status">Ruman</th>                                       
                        <th class="status">Collega</th>                                       
                        <th class="datum">Cellular 1</th>
                        <th class="datum">Cellular 2</th>
                        <th class="datum">Preferencia 1</th>
                        <th class="datum">Preferencia 2</th>
                        <th class="id">Inf</th>
                        <th class="id">-</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <th class="sex"><?php echo $row["ID"] ?></th>
                        <th class="sex"><?php if ($row["status"] == '1') { ?>
                            <img src="https://cdn.pixabay.com/photo/2017/03/28/01/46/check-mark-2180770_960_720.png" alt="correcto" width="25px" height="25px">
                        <?php } else if ($row["status"] == '2') { ?>
                            <img src="https://cdn-icons-png.flaticon.com/512/148/148766.png" alt="error" width="25px" height="25px">
                        <?php  } else { ?>
                            <img src="http://fotoigual.com/wp-content/uploads/2015/03/se--al-icono-amarillo-peligro.png" alt="pendiente" width="25px" height="25px">
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
                            } else if ($row["prome_preferencia"] == 9) {
                                echo 'Scol Primario Kudawecha';
                            } else if ($row["prome_preferencia"] == 10) {
                                echo 'Scol Preparatorio Kudawecha';
                            } else if ($row["prome_preferencia"] == 11) {
                                echo 'Colegio Conrado Coronel';
                            } else if ($row["prome_preferencia"] == 12) {
                                echo 'Colegio Conrado Coronel Kleuter';
                            } else if ($row["prome_preferencia"] == 13) {
                                echo 'Prinses Amalia Basisschool';
                            } else if ($row["prome_preferencia"] == 14) {
                                echo 'Prinses Amalia Kleuterschool';
                            } else if ($row["prome_preferencia"] == 15) {
                                echo 'Scol Basico Washington';
                            } else if ($row["prome_preferencia"] == 16) {
                                echo 'Scol Preparatorio Washington';
                            } else if ($row["prome_preferencia"] == 17) {
                                echo 'Arco Iris Kleuterschool';
                            } else if ($row["prome_preferencia"] == 18) {
                                echo 'Fontein Kleuterschool';
                            } else if ($row["prome_preferencia"] == 19) {
                                echo 'Scol Basico Dornasol';
                            } else if ($row["prome_preferencia"] == 23) {
                                echo 'Reina Beatrix School';
                            } else if ($row["prome_preferencia"] == 24) {
                                echo 'Scol Basico Xander Bogaerts';
                            } else if ($row["prome_preferencia"] == 25) {
                                echo 'Scol Preparatorio Xander Bogaerts';
                            } else if ($row["prome_preferencia"] == 26) {
                                echo 'Hilario Angela';
                            } else {
                                echo 'null';
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
                            } else if ($row["segundo_preferencia"] == 9) {
                                echo 'Scol Primario Kudawecha';
                            } else if ($row["segundo_preferencia"] == 10) {
                                echo 'Scol Preparatorio Kudawecha';
                            } else if ($row["segundo_preferencia"] == 11) {
                                echo 'Colegio Conrado Coronel';
                            } else if ($row["segundo_preferencia"] == 12) {
                                echo 'Colegio Conrado Coronel Kleuter';
                            } else if ($row["segundo_preferencia"] == 13) {
                                echo 'Prinses Amalia Basisschool';
                            } else if ($row["segundo_preferencia"] == 14) {
                                echo 'Prinses Amalia Kleuterschool';
                            } else if ($row["segundo_preferencia"] == 15) {
                                echo 'Scol Basico Washington';
                            } else if ($row["segundo_preferencia"] == 16) {
                                echo 'Scol Preparatorio Washington';
                            } else if ($row["segundo_preferencia"] == 17) {
                                echo 'Arco Iris Kleuterschool';
                            } else if ($row["segundo_preferencia"] == 18) {
                                echo 'Fontein Kleuterschool';
                            } else if ($row["segundo_preferencia"] == 19) {
                                echo 'Scol Basico Dornasol';
                            } else if ($row["segundo_preferencia"] == 23) {
                                echo 'Reina Beatrix School';
                            } else if ($row["segundo_preferencia"] == 24) {
                                echo 'Scol Basico Xander Bogaerts';
                            } else if ($row["segundo_preferencia"] == 25) {
                                echo 'Scol Preparatorio Xander Bogaerts';
                            } else if ($row["segundo_preferencia"] == 26) {
                                echo 'Hilario Angela';
                            } else {
                                echo 'null';
                            } ?></th>
                            <th class="sex">
                                <form action="aplicacion_form-update.php" method="POST">
                                    <input id="form_id" name="id" hidden type="text" value="<?php echo $row["ID"]; ?>">
                                    <input class="button" type="submit" value="+">
                                </form>
                            </th>
                        <th class="sex"><input class="button delete" type="submit" valor="<?php echo $row["ID"]; ?>" value="-"></th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <form action="ajax/get_aplicacionform_excel.php" method="POST">
                <input name="scol" type="text" hidden>
                <button style="margin-bottom: 0;" type="submit" id="excelButton">Export</button>
            </form>
            <?php } ?>
        </div>
    </div>
</main>
<div class="qwihi">
		<a href="https://qwihi.com"><img id="logo" src="img/QwihiLogo.png" alt="qwihi" width="150px"></a>
		<label id="qwihi">Your Imagination is our Creation.</label>
</div>
<script>
    $(document).ready(function () {
        var table=$("table").DataTable({
            responsive: true,
        }); 
    });
$(".delete").on(' click', function(e) { e.preventDefault(); id=$(this).attr('valor'); if(confirm("Are you sure you want to delete the form "+ id +" ?")){ $.ajax({ url:"ajax/aplicacion_form-delete.php", data: "id=" +id, type : 'POST' , success: function(data){ if(data==1){ alert('FORM DELETED'); location.reload(); } else { alert('ERROR, TRY AGAIN'); console.log(data) } } }); } }); </script>