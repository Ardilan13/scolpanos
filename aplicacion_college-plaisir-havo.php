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

    thead {
        cursor: pointer;
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
        margin: 3% 5%;
        padding: 3%;
        text-align: center;
        border-radius: 5px;
        box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
    }

    form {
        margin: 0;
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
        width: 4%;
    }

    .sex {
        width: 3%;
    }

    .datum {
        width: 11%;
    }

    .button {
        background-color: yellow;
        font-size: large;
        cursor: pointer;
        padding: 1% 6%;
        border-radius: 3px;
        border: 1px solid black;
    }

    .button:hover {
        background-color: orange;
        transition: 100ms;
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

    .img-responsive {
        width: 8%;
    }
</style>

<head>
    <title>Aplicacion Mon Plaisir</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
</head>
<main>
    <div class="container">
        <div class="form-bg"'>
            <div class="spn-logo">
				<h1 class="brand">
					<img class="img-responsive" src="img/monplaisir123.png" alt="Scol Pa Nos" width="22%">
				</h1>
			</div>
            <h1>Mon Plaisir</h1>
            <h2>Aplicacion College 2024-2025:</h2>
            <?php $DBCreds = new DBCreds();
            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
            $mysqli->set_charset('utf8');
            $query = "SELECT ID,status,naam,voornamen,roepnaam,sexo,geboorteland,f_nacemento,klas5,klas6,profielh3,profielh4,profielm4,mavo4,districto,meld,afleverende FROM aplicacion_college WHERE type = 1 ORDER BY ID ASC";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) { ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="sex">ID</th>
                        <th class="status">Status</th>
                        <th class="datum">Naam</th>
                        <th class="datum">Voornamen</th>
                        <th class="datum">Roepnaam</th>
                        <th class="sex">Sexo</th>
                        <th class="datum">Geboort</th>
                        <th class="datum">Fecha di nacemento</th>
                        <th class="datum">Afleverende</th>                                         
                        <th class="datum">Meld</th>                                         
                        <th class="datum">Districto</th>
                        <th class="sex">Info</th>
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
                        <th><?php echo $row["afleverende"] ?></th> 
                        <!-- <th><?php if (($row["mavo4"] != null && $row["mavo4"] != '') || ($row["profielm4"] != null && $row["profielm4"] != '')) {
                                        echo "MAVO 4";
                                    } else if (($row["klas6"] != null && $row["klas6"] != '') || ($row["profielh4"] != null && $row["profielh4"] != '')) {
                                        echo "HAVO 4";
                                    } else if (($row["klas5"] != null && $row["klas5"] != '') || ($row["profielh3"] != null && $row["profielh3"] != '')) {
                                        echo "HAVO 3";
                                    } /* else{
                            echo "None";
                        } */ ?></th>  -->
                        <th><?php if ($row["meld"] == 1) {
                                echo 'CB2';
                            } else if ($row["meld"] == 2) {
                                echo 'HAVO 3';
                            } else if ($row["meld"] == 3) {
                                echo 'VWO 3';
                            } else if ($row["meld"] == 4) {
                                echo 'Havo 4';
                            } ?></th>
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
                        
                            <th class="sex">
                                <form action="aplicacion_college-update-havo.php" method="POST">
                                    <input name="id" hidden type="text" value="<?php echo $row["ID"]; ?>">
                                    <input class="button" type="submit" value="+">
                                </form>
                            </th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <form action="ajax/get_aplicacionform_excel.php" method="POST">
                <input name="scol" type="text" hidden value="11">
                <input name="tipo" type="text" hidden value="1">
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
</script>