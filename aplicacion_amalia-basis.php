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
        margin: 3%;
        padding: 3%;
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
        width: 4%;
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
    <title>Prinses Amalia Basisschool</title>
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
            <h1>Prinses Amalia Basisschool</h1>
            <h2>Aplicacion Scol 2023-2024:</h2>
            <?php $DBCreds = new DBCreds();
            $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
            $mysqli->set_charset('utf8');
            $query = "SELECT ID,status,fam,nomber,sexo,f_nacemento,districto,ruman,collega,number_mayor1,number_mayor2,prome_preferencia,segundo_preferencia FROM aplicacion_forms WHERE (prome_preferencia=13 OR segundo_preferencia=13) ORDER BY ID ASC";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) != 0) { ?>
            <table class="table table-striped table-bordered">
                <thead>
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
                        <th class="datum">Preferencia</th>
                        <th class="sex">Info</th>
                        <th class="sex">-</th>
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
                        <th><?php if($row["prome_preferencia"]==13){ echo '1';} else{ echo '2';} ?></th>
                        <form action="aplicacion_form-update.php" method="POST">
                        <input id="form_id" name="id" hidden type="text" value="<?php echo $row["ID"]; ?>">
                        <input name="scol" type="text" hidden value="13">
                        <th class="sex"><input class="button" type="submit" value="+"></th>
                        </form>
                        <th class="sex"><input class="button delete" type="submit" valor="<?php echo $row["ID"]; ?>" value="-"></th>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <form action="ajax/get_aplicacionform_excel.php" method="POST">
                <input name="scol" type="text" hidden>
                <input name="admin" type="text" value="13" hidden>
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
<script src="components/jquery/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(".delete").on(' click', function(e) {
    e.preventDefault()
    id = $(this).attr('valor')
    if(confirm("Are you sure you want to delete the form "+ id +"?")){
        $.ajax({
			url:"ajax/aplicacion_form-delete.php",
			data: "id="+id,
			type  : 'POST',
			success: function(data){
				if(data==1){
                    alert('FORM DELETED')
                    location.reload();
                } else {
                    alert('ERROR, TRY AGAIN')
                }
			}
		});
    }    
}); 
</script>