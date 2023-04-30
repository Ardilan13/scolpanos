<?php
require_once "classes/DBCreds.php";
require_once "config/app.config.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$id = $_POST["id"];
$scol = $_POST["scol"];

$query = "SELECT nomber,fam,sexo,nacionalidad,f_nacemento,l_nacemento,azv,priva,censo,caya,cas,districto,idioma,guia,problema,zildelijk,zildelijk1,ultimo_scol,nomber_fam,scol,klas,prome_preferencia,segundo_preferencia,ruman,collega,publico,estado,relacion,voogd,mesun,nomber_mayor1,fam_mayor1,number_mayor1,email_mayor1,traha_mayor1,ocupacion_mayor1,telefon_mayor1,caya_mayor1,cas_mayor1,relacion2,voogd2,mesun2,nomber_mayor2,fam_mayor2,number_mayor2,email_mayor2,traha_mayor2,ocupacion_mayor2,telefon_mayor2,caya_mayor2,cas_mayor2,motivacion,status,coment FROM aplicacion_forms WHERE ID = $id";
$resultado = mysqli_query($mysqli, $query);
if (mysqli_num_rows($resultado) != 0) {
	$row = mysqli_fetch_assoc($resultado); ?>
	<style type="text/css" media="screen">
		h2 {
			color: red;
			border-bottom: 2px solid red;
			margin: 0 25%;
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
			margin: 3% 15%;
			padding: 0 5%;
			text-align: center;
			border-radius: 5px;
			box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
		}

		.info {
			margin: 4% 0;
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 2em;
		}

		.form_group {
			position: relative;
			color: #4f4f4f;
		}

		.input-text {
			background: none;
			font-size: 1rem;
			padding: 1.3em 0.4em;
			width: 250px;
			height: 30px;
			border: 1px solid #1d407a;
			border-radius: 5px;
			width: 100%;
			outline: none;
		}

		select {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			outline: none;
			background: none;
			box-sizing: content-box;
			padding: 0.32em 0.4em;
			height: 30px;
			font-size: 1rem;
			width: 250px;
			border: 1px solid #1d407a;
			border-radius: 5px;
			width: 97%;
			cursor: pointer;
		}

		.input-text:focus+.form_label,
		.input-text:not(:placeholder-shown)+.form_label {
			transform: translateY(-17px) scale(1.1);
			transform-origin: left top;
			color: #1d407a;
		}

		.form_label {
			color: #4f4f4f;
			font-size: 1rem;
			background-color: white;
			position: absolute;
			top: 0;
			left: 10px;
			transform: translateY(7px);
			transition: transform .5s, color .3s;
		}

		.form-select {
			transform: translateY(-17px) scale(1.1);
			transform-origin: left top;
			color: #1d407a;
		}

		#bishita {
			grid-column: 1/3;
		}

		#input_file {
			padding-left: 20px;
		}

		.qwihi {
			background-color: #262532;
			padding-top: 1%;
			margin: -2% -0.45%;
			align-items: center;
			text-align: center;

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

		@media (max-width: 1000px) {

			.info,
			select {
				display: grid;
				grid-template-columns: repeat(1, 1fr);
			}

			.form-bg {
				margin: 3%;
				padding: 3%;
			}

			button {
				padding: 5% 35%;
				font-size: large;
				text-align: center;
				margin: auto;
			}

			.input-text {
				height: 100px;
				border-width: 2px;
			}

			select {
				width: 97%;
				font-size: 1.8rem;
				height: 78px;
				border-width: 2px;
			}

			.form_label {
				transform: translateY(22px);
			}

			.form-select {
				transform: translateY(-30px) scale(1.1);
			}

			.input-text:focus+.form_label,
			.input-text:not(:placeholder-shown)+.form_label {
				transform: translateY(-28px) scale(1.1);
			}

			label,
			.form_label,
			.input-text {
				font-size: 1.8rem;
			}

			#bishita {
				font-size: 1.5rem;
			}

			input[type=radio],
			input[type=checkbox] {
				border: 0;
				width: 2rem;
				height: 2rem;
			}

			span {
				font-size: 1.5rem;
				margin-left: .5rem;
			}

			#bishita {
				grid-column: 1/2;
			}

			#input_file {
				font-size: 1.8rem;
			}

			.img-responsive {
				width: 60%;
			}

			button {
				margin-top: 5%;
				font-size: 2.5rem;
				font-weight: bold;
			}
		}
	</style>

	<head>
		<title>Aplicacion Form Update</title>
		<link rel="icon" href="img/scholenlogo.png">
	</head>

	<body>
		<div class="container">
			<div class="form-bg">
				<div class="spn-logo">
					<h1 class="brand">
						<img class="img-responsive" src="img/scholen.png" alt="Scol Pa Nos" width="25%">
					</h1>
				</div>
				<form action="ajax/aplicacion_update.php" method="POST" enctype="multipart/form-data" id="formulario">
					<h1>Aplicacion Scol Preparatorio 2022-2023</h1>
					<h2>Informacion di e mucha</h2>
					<div class="info">
						<div class="form_group">
							<input name="nomber" type="text" class="input-text" placeholder=" " required value="<?php echo $row["nomber"]; ?>"><label class="form_label">Nomber:</label>
						</div>
						<div class="form_group">
							<input name="fam" type="text" class="input-text" placeholder=" " required value="<?php echo $row["fam"]; ?>"><label class="form_label">Fam:</label>
						</div>
						<div class="form_group">
							<label>Sexo:</label>
							<?php if ($row["sexo"] == 1) { ?>
								<input name="sexo" type="radio" value="1" required checked><span>M</span>
								<input name="sexo" type="radio" value="2" required><span>F</span>
							<?php } else if ($row["sexo"] == 2) { ?>
								<input name="sexo" type="radio" value="1" required><span>M</span>
								<input name="sexo" type="radio" value="2" required checked><span>F</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="nacionalidad" type="text" class="input-text" placeholder=" " required value="<?php echo $row["nacionalidad"]; ?>"><label class="form_label">Nacionalidad:</label>
						</div>
						<div class="form_group">
							<input name="f_nacemento" type="date" class="input-text" placeholder=" " required value="<?php echo $row["f_nacemento"]; ?>"><label class="form_label" value="<?php echo $row["f_nacemento"]; ?>">Fecha di nacemento:</label>
						</div>
						<div class="form_group">
							<input name="l_nacemento" type="text" class="input-text" placeholder=" " required value="<?php echo $row["l_nacemento"]; ?>"><label class="form_label" value="<?php echo $row["l_nacemento"]; ?>">Luga di nacemento:</label>
						</div>
						<div class="form_group">
							<label>Mucha tin AZV?:</label>
							<?php if ($row["azv"] == 2) { ?>
								<input name="azv" type="radio" value="1" required><span>Si</span>
								<input name="azv" type="radio" value="2" required checked><span>No</span>
							<?php } else { ?>
								<input name="azv" type="radio" value="1" required checked><span>Si</span>
								<input name="azv" type="radio" value="2" required><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<?php if ($row["azv"] == '1' || $row["azv"] == '2') { ?>
								<input name="azv_number" type="number" placeholder=" " class="input-text "><label class="form_label">Number di AZV:</label>
							<?php } else { ?>
								<input name="azv_number" type="number" placeholder=" " class="input-text " value="<?php echo $row["azv"]; ?>"><label class="form_label">Number di AZV:</label>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>Mucha tin seguro priva?:</label>
							<?php if ($row["priva"] == 1) { ?>
								<input name="priva" type="radio" value="1" required checked><span>Si</span>
								<input name="priva" type="radio" value="2" required><span>No</span>
							<?php } else if ($row["priva"] == 2) { ?>
								<input name="priva" type="radio" value="1" required><span>Si</span>
								<input name="priva" type="radio" value="2" required checked><span>No</span>
							<?php } ?>

						</div>
						<div class="form_group">
							<label>Censo: </label><a href="ajax/<?php echo $row["censo"]; ?>" download="censo_<?php echo $row["fam"]; ?>_<?php echo $row["nomber"]; ?>_<?php echo $id; ?>">Download</a>
						</div>
					</div>

					<h2>Adres:</h2>
					<div class="info">
						<div class="form_group">
							<input name="caya" type="text" class="input-text" placeholder=" " required value="<?php echo $row["caya"]; ?>"><label class="form_label">Nomber di caya:</label>
						</div>
						<div class="form_group">
							<input name="cas" type="text" class="input-text" placeholder=" " required value="<?php echo $row["cas"]; ?>"><label class="form_label">Number di cas:</label>
						</div>
						<div class="form_group">
							<label class="form_label form-select">Idioma na cas:</label>
							<select name="idioma" class="" required>
								<?php if ($row["idioma"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">Papiamento</option>
									<option value="2">Nederlands</option>
									<option value="3">Español</option>
									<option value="4">English</option>
									<option value="5">Otro</option>
								<?php } else if ($row["idioma"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>Papiamento</option>
									<option value="2">Nederlands</option>
									<option value="3">Español</option>
									<option value="4">English</option>
									<option value="5">Otro</option>
								<?php } else if ($row["idioma"] == '2') { ?>
									<option value="0"></option>
									<option value="1">Papiamento</option>
									<option value="2" selected>Nederlands</option>
									<option value="3">Español</option>
									<option value="4">English</option>
									<option value="5">Otro</option>
								<?php } else if ($row["idioma"] == '3') { ?>
									<option value="0"></option>
									<option value="1">Papiamento</option>
									<option value="2">Nederlands</option>
									<option value="3" selected>Español</option>
									<option value="4">English</option>
									<option value="5">Otro</option>
								<?php } else if ($row["idioma"] == '4') { ?>
									<option value="0"></option>
									<option value="1">Papiamento</option>
									<option value="2">Nederlands</option>
									<option value="3">Español</option>
									<option value="4" selected>English</option>
									<option value="5">Otro</option>
								<?php } else { ?>
									<option value="0"></option>
									<option value="1">Papiamento</option>
									<option value="2">Nederlands</option>
									<option value="3">Español</option>
									<option value="4">English</option>
									<option value="5" selected>Otro</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<?php if ($row["idioma"] == '0' || $row["idioma"] == '1' || $row["idioma"] == '2' || $row["idioma"] == '3' || $row["idioma"] == '4') { ?>
								<input name="idioma_otro" type="text" placeholder=" " class="input-text"><label class="form_label">Otro idioma?:</label>
							<?php } else { ?>
								<input name="idioma_otro" type="text" placeholder=" " class="input-text" value="<?php echo $row["idioma"]; ?>"><label class="form_label">Otro idioma?:</label>
							<?php } ?>
						</div>
						<div class="form_group">
							<label class="form_label form-select">E mucha ta bou di guia di:</label>
							<select name="guia" class="" required>
								<?php if ($row["guia"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '2') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2" selected>Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '3') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3" selected>MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '4') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4" selected>Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '5') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5" selected>Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '6') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6" selected>Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '7') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7" selected>FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '8') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8" selected>FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else if ($row["guia"] == '9') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9" selected>Fundacion guiami</option>
									<option value="10">Otro</option>
								<?php } else { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Trahado social di scol</option>
									<option value="3">MDC</option>
									<option value="4">Directie sociale zaken</option>
									<option value="5">Logopedista</option>
									<option value="6">Respaldo</option>
									<option value="7">FAVI</option>
									<option value="8">FEPO</option>
									<option value="9">Fundacion guiami</option>
									<option value="10" selected>Otro</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<?php if ($row["guia"] == '0' || $row["guia"] == '1' || $row["guia"] == '2' || $row["guia"] == '3' || $row["guia"] == '4' || $row["guia"] == '5' || $row["guia"] == '6' || $row["guia"] == '7' || $row["guia"] == '8' || $row["guia"] == '9') { ?>
								<input name="guia_otro" type="text" placeholder=" " class="input-text"><label class="form_label">Otro guia?:</label>
							<?php } else { ?>
								<input name="guia_otro" type="text" placeholder=" " class="input-text" value="<?php echo $row["guia"] ?>"><label class="form_label">Otro guia?:</label>
							<?php } ?>
						</div>
						<div class="form_group">
							<label class="form_label form-select">E mucha tin problema cu:</label>
							<select name="problema" class="" required>
								<?php if ($row["problema"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '2') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2" selected>Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '3') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3" selected>Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '4') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4" selected>Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '5') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5" selected>Motorico</option>
									<option value="6">Alergia</option>
									<option value="7">Otro</option>
								<?php } else if ($row["problema"] == '6') { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6" selected>Alergia</option>
									<option value="7">Otro</option>
								<?php } else { ?>
									<option value="0"></option>
									<option value="1">No ta aplicabel</option>
									<option value="2">Bista</option>
									<option value="3">Oido</option>
									<option value="4">Habla</option>
									<option value="5">Motorico</option>
									<option value="6">Alergia</option>
									<option value="7" selected>Otro</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<?php if ($row["problema"] == '0' || $row["problema"] == '1' || $row["problema"] == '2' || $row["problema"] == '3' || $row["problema"] == '4' || $row["problema"] == '5' || $row["problema"] == '6') { ?>
								<input name="problema_otro" type="text" placeholder=" " class="input-text"><label class="form_label">Otro problema cu?:</label>
							<?php } else { ?>
								<input name="problema_otro" type="text" placeholder=" " class="input-text" value="<?php echo $row["problema"]; ?>"><label class="form_label">Otro problema cu?:</label>
							<?php } ?>
						</div>
						<div class="form_group">
							<label class="form_label form-select">Districto:</label>
							<select name="districto" class="" required>
								<?php if ($row["districto"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '2') { ?>
									<option value="0"></option>
									<option value="1">Noord</option>
									<option value="2" selected>Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '3') { ?>
									<option value="0"></option>
									<option value="1">Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3" selected>Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '4') { ?>
									<option value="0"></option>
									<option value="1">Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4" selected>San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '5') { ?>
									<option value="0"></option>
									<option value="1">Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5" selected>Santa Cruz</option>
									<option value="6">Savaneta</option>
								<?php } else if ($row["districto"] == '6') { ?>
									<option value="0"></option>
									<option value="1">Noord</option>
									<option value="2">Oranjestad</option>
									<option value="3">Paradera</option>
									<option value="4">San Nicolaas</option>
									<option value="5">Santa Cruz</option>
									<option value="6" selected>Savaneta</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<label>E mucha ta zindelijk? (Por haci uzo di baño independiente y haci su mes limpi)</label>
							<?php if ($row["zildelijk"] == 1) { ?>
								<input name="zildelijk" type="radio" value="1" required checked><span>Si</span>
								<input name="zildelijk" type="radio" value="2" required><span>No</span>
							<?php } else if ($row["zildelijk"] == 2) { ?>
								<input name="zildelijk" type="radio" value="1" required><span>Si</span>
								<input name="zildelijk" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>E mucha tin ruman ta bayendo scol/creche:</label>
							<?php if ($row["zildelijk1"] == 1) { ?>
								<input name="zildelijk1" type="radio" value="1" required checked><span>Si</span>
								<input name="zildelijk1" type="radio" value="2" required><span>No</span>
							<?php } else if ($row["zildelijk1"] == 2) { ?>
								<input name="zildelijk1" type="radio" value="1" required><span>Si</span>
								<input name="zildelijk1" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group" id="bishita">
							<input name="ultimo_scol" type="text" placeholder=" " class="input-text" value="<?php echo $row["ultimo_scol"]; ?>"><label class="form_label">Nomber di ultimo scol/creche cu a mucha e bishita:</label>
						</div>
					</div>
					<h2>Scol Di Ruman (nan)</h2>
					<div class="info">
						<div class="form_group">
							<input name="nomber_fam" type="text" placeholder=" " class="input-text" value="<?php echo $row["nomber_fam"]; ?>"><label class="form_label">Nomber y Fam:</label>
						</div>
						<div class="form_group">
							<input name="scol" type="text" placeholder=" " class="input-text" value="<?php echo $row["scol"]; ?>"><label class="form_label">Scol:</label>
						</div>
						<div class="form_group">
							<input name="klas" type="text" placeholder=" " class="input-text " value="<?php echo $row["klas"]; ?>"><label class="form_label">Klas:</label>
						</div>
						<div class="form_group">
							<label>Pa un cupo na un scol, ta tene cuenta cu adres di cas.</label>
						</div>
					</div>
					<h2>Preferencia Di Scol</h2>
					<div class="info">
						<div class="form_group">
							<label class="form_label form-select">Prome preferencia:</label>
							<select name="prome_preferencia" class="" required>
								<?php if ($row["prome_preferencia"] == '0') { ?>
									<!-- <option value="0" selected></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option> -->
									<option value="0" selected></option>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '2') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2" selected>Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '3') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3" selected>Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '4') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4" selected>Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '5') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5" selected>Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '6') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6" selected>Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '7') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7" selected>Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '8') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8" selected>Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["prome_preferencia"] == '9') { ?>
									<option value="9" selected>Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '10') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10" selected>Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '11') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11" selected>Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '12') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12" selected>Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '13') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13" selected>Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '14') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14" selected>Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '15') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15" selected>Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '16') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16" selected>Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '17') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17" selected>Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '18') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18" selected>Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '19') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19" selected>Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '23') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23" selected>Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '24') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24" selected>Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '25') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25" selected>Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["prome_preferencia"] == '26') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26" selected>Hilario Angela</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<label class="form_label form-select">Segundo preferencia:</label>
							<select name="segundo_preferencia" class="" required>
								<?php if ($row["segundo_preferencia"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '2') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2" selected>Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '3') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3" selected>Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '4') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4" selected>Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '5') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5" selected>Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '6') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6" selected>Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '7') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7" selected>Scol Primario Kudawecha</option>
									<option value="8">Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '8') { ?>
									<option value="0"></option>
									<option value="1">Colegio Conrado Coronel</option>
									<option value="2">Fontein Kleuterschool</option>
									<option value="3">Prinses Amalia School</option>
									<option value="4">Reina Beatrix School</option>
									<option value="5">Scol Preparatorio Washington</option>
									<option value="6">Scol Primario Arco Iris</option>
									<option value="7">Scol Primario Kudawecha</option>
									<option value="8" selected>Scol Basico Xander Bogaerts</option>
								<?php } else if ($row["segundo_preferencia"] == '9') { ?>
									<option value="9" selected>Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '10') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10" selected>Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '11') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11" selected>Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '12') { ?>
									<option value="9">Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12" selected>Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '13') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13" selected>Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '14') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14" selected>Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '15') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15" selected>Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '16') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16" selected>Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '17') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17" selected>Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '18') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18" selected>Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '19') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19" selected>Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '23') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23" selected>Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '24') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24" selected>Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '25') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="13">Prinses Amalia Basisschool</option>
									<option value="14">Prinses Amalia Kleuterschool</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25" selected>Scol Preparatorio Xander Bogaerts</option>
									<option value="26">Hilario Angela</option>
								<?php } else if ($row["segundo_preferencia"] == '26') { ?>
									<option value="9" >Scol Primario Kudawecha</option>
									<option value="10">Scol Preparatorio Kudawecha</option>
									<option value="11">Colegio Conrado Coronel</option>
									<option value="12">Colegio Conrado Coronel Kleuter</option>
									<option value="15">Scol Basico Washington</option>
									<option value="16">Scol Preparatorio Washington</option>
									<option value="17">Arco Iris Kleuterschool</option>
									<option value="18">Fontein Kleuterschool</option>
									<option value="19">Scol Basico Dornasol</option>
									<option value="23">Reina Beatrix School</option>
									<option value="24">Scol Basico Xander Bogaerts</option>
									<option value="25">Scol Preparatorio Xander Bogaerts</option>
									<option value="26" selected>Hilario Angela</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group">
							<label>E mucha tin ruman na e mesun scol of na un scol basico publico:</label>
							<?php if ($row["ruman"] == 1) { ?>
								<input name="ruman" type="radio" value="1" required checked><span>Si</span>
								<input name="ruman" type="radio" value="2" required><span>No</span>
							<?php } else { ?>
								<input name="ruman" type="radio" value="1" required><span>Si</span>
								<input name="ruman" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>E mucha ta yiu di un collega:</label>
							<?php if ($row["collega"] == 1) { ?>
								<input name="collega" type="radio" value="1" required checked><span>Si</span>
								<input name="collega" type="radio" value="2" required><span>No</span>
							<?php } else { ?>
								<input name="collega" type="radio" value="1" required><span>Si</span>
								<input name="collega" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>E mucha tin un ruman na un otro scol publico:</label>
							<?php if ($row["publico"] == 1) { ?>
								<input name="publico" type="radio" value="1" required checked><span>Si</span>
								<input name="publico" type="radio" value="2" required><span>No</span>
							<?php } else { ?>
								<input name="publico" type="radio" value="1" required><span>Si</span>
								<input name="publico" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="motivacion" type="text" placeholder=" " class="input-text" value="<?php echo $row["motivacion"]; ?>"><label class="form_label">Motivacion:</label>
						</div>
					</div>
					<h2>Informacion di mayor (nan)</h2>
					<div class="info">
						<div class="form_group">
							<label class="form_label form-select">Estado civil di mayor (nan):</label>
							<select name="estado" class="" id="select_estado" required>
								<?php if ($row["estado"] == '0') { ?>
									<option value="0" selected></option>
									<option value="1">Casa</option>
									<option value="2">Biba hunto</option>
									<option value="3">Divorcia / separa</option>
									<option value="4">Viudo</option>
									<option value="5">Otro</option>
								<?php } else if ($row["estado"] == '1') { ?>
									<option value="0"></option>
									<option value="1" selected>Casa</option>
									<option value="2">Biba hunto</option>
									<option value="3">Divorcia / separa</option>
									<option value="4">Viudo</option>
									<option value="5">Otro</option>
								<?php } else if ($row["estado"] == '2') { ?>
									<option value="0"></option>
									<option value="1">Casa</option>
									<option value="2" selected>Biba hunto</option>
									<option value="3">Divorcia / separa</option>
									<option value="4">Viudo</option>
									<option value="5">Otro</option>
								<?php } else if ($row["estado"] == '3') { ?>
									<option value="0"></option>
									<option value="1">Casa</option>
									<option value="2">Biba hunto</option>
									<option value="3" selected>Divorcia / separa</option>
									<option value="4">Viudo</option>
									<option value="5">Otro</option>
								<?php } else if ($row["estado"] == '4') { ?>
									<option value="0"></option>
									<option value="1">Casa</option>
									<option value="2">Biba hunto</option>
									<option value="3">Divorcia / separa</option>
									<option value="4" selected>Viudo</option>
									<option value="5">Otro</option>
								<?php } else { ?>
									<option value="0"></option>
									<option value="1">Casa</option>
									<option value="2">Biba hunto</option>
									<option value="3">Divorcia / separa</option>
									<option value="4">Viudo</option>
									<option value="5" selected>Otro</option>
								<?php } ?>
							</select>
						</div>
						<div class="form_group" id="estado_otro1">
							<?php if ($row["estado"] == '0' || $row["estado"] == '1' || $row["estado"] == '2' || $row["estado"] == '3' || $row["estado"] == '4') { ?>
								<input name="estado_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Otro estado civil?:</label>
							<?php } else { ?>
								<input name="estado_otro" type="text" placeholder=" " class="input-text " value="<?php echo $row["estado"]; ?>"><label class="form_label">Otro estado civil?:</label>
							<?php } ?>
						</div>
					</div>
					<h2>Mayor 1:</h2>
					<div class="info">
						<div class="form_group">
							<label>Relacion:</label>
							<?php if ($row["relacion"] == 1) { ?>
								<input name="relacion" type="radio" value="1" required checked><span>Mama</span>
								<input name="relacion" type="radio" value="2" required><span>Tata</span>
								<input name="relacion" type="radio" value="3" required><span>Otro</span>
							<?php } else if ($row["relacion"] == 2) { ?>
								<input name="relacion" type="radio" value="1" required><span>Mama</span>
								<input name="relacion" type="radio" value="2" required checked><span>Tata</span>
								<input name="relacion" type="radio" value="3" required><span>Otro</span>
							<?php } else if ($row["relacion"] == 3) { ?>
								<input name="relacion" type="radio" value="1" required><span>Mama</span>
								<input name="relacion" type="radio" value="2" required><span>Tata</span>
								<input name="relacion" type="radio" value="3" required checked><span>Otro</span>
							<?php } else { ?>
								<input name="relacion" type="radio" value="1" required><span>Mama</span>
								<input name="relacion" type="radio" value="2" required><span>Tata</span>
								<input name="relacion" type="radio" value="3" required><span>Otro</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="relacion_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Otro?:</label>
						</div>
						<div class="form_group">
							<label>Ta voogd di e yiu:</label>
							<?php if ($row["voogd"] == 1) { ?>
								<input name="voogd" type="radio" value="1" required checked><span>Si</span>
								<input name="voogd" type="radio" value="2" required><span>No</span>
							<?php } else if ($row["voogd"] == 2) { ?>
								<input name="voogd" type="radio" value="1" required><span>Si</span>
								<input name="voogd" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>Tin mesun adres cu e mucha?</label>
							<?php if ($row["mesun"] == 1) { ?>
								<input name="mesun" type="radio" value="1" required checked><span>Si</span>
								<input name="mesun" type="radio" value="2" required><span>No</span>
							<?php } else if ($row["mesun"] == 2) { ?>
								<input name="mesun" type="radio" value="1" required><span>Si</span>
								<input name="mesun" type="radio" value="2" required checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="nomber_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["nomber_mayor1"]; ?>"><label class="form_label">Nomber:</label>
						</div>
						<div class="form_group">
							<input name="fam_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["fam_mayor1"]; ?>"><label class="form_label">Fam:</label>
						</div>
						<div class="form_group">
							<input name="number_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["number_mayor1"]; ?>"><label class="form_label">Number di telefon:</label>
						</div>
						<div class="form_group">
							<input name="email_mayor1" type="email" placeholder=" " class="input-text" required value="<?php echo $row["email_mayor1"]; ?>"><label class="form_label">Email adres:</label>
						</div>
						<div class="form_group">
							<input name="traha_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["traha_mayor1"]; ?>"><label class="form_label">Ta traha na:</label>
						</div>
						<div class="form_group">
							<input name="ocupacion_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["ocupacion_mayor1"]; ?>"><label class="form_label">Ocupacion:</label>
						</div>
						<div class="form_group">
							<input name="telefon_mayor1" type="tel" placeholder=" " class="input-text " required value="<?php echo $row["telefon_mayor1"]; ?>"><label class="form_label">Telefon na trabou:</label>
						</div>
						<div class="form_group">
							<input name="caya_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["caya_mayor1"]; ?>"><label class="form_label">Adres: Nomber di caya:</label>
						</div>
						<div class="form_group">
							<input name="cas_mayor1" type="text" placeholder=" " class="input-text" required value="<?php echo $row["cas_mayor1"]; ?>"><label class="form_label">Adres: Number di cas:</label>
						</div>
					</div>
					<h2>Mayor 2:</h2>
					<div class="info">
						<div class="form_group">
							<label>Relacion:</label>
							<?php if ($row["relacion2"] == 1) { ?>
								<input name="relacion2" type="radio" value="1" checked><span>Mama</span>
								<input name="relacion2" type="radio" value="2"><span>Tata</span>
								<input name="relacion2" type="radio" value="3"><span>Otro</span>
							<?php } else if ($row["relacion2"] == 2) { ?>
								<input name="relacion2" type="radio" value="1"><span>Mama</span>
								<input name="relacion2" type="radio" value="2" checked><span>Tata</span>
								<input name="relacion2" type="radio" value="3"><span>Otro</span>
							<?php } else if ($row["relacion2"] == 3) { ?>
								<input name="relacion2" type="radio" value="1"><span>Mama</span>
								<input name="relacion2" type="radio" value="2"><span>Tata</span>
								<input name="relacion2" type="radio" value="3" checked><span>Otro</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="relacion_otro2" type="text" placeholder=" " class="input-text "><label class="form_label">Otro?:</label>
						</div>
						<div class="form_group">
							<label>Ta voogd di e yiu:</label>
							<?php if ($row["voogd2"] == 1) { ?>
								<input name="voogd2" type="radio" value="1" checked><span>Si</span>
								<input name="voogd2" type="radio" value="2"><span>No</span>
							<?php } else if ($row["voogd2"] == 2) { ?>
								<input name="voogd2" type="radio" value="1"><span>Si</span>
								<input name="voogd2" type="radio" value="2" checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<label>Tin mesun adres cu e mucha?</label>
							<?php if ($row["mesun2"] == 1) { ?>
								<input name="mesun2" type="radio" value="1" checked><span>Si</span>
								<input name="mesun2" type="radio" value="2"><span>No</span>
							<?php } else if ($row["mesun2"] == 2) { ?>
								<input name="mesun2" type="radio" value="1"><span>Si</span>
								<input name="mesun2" type="radio" value="2" checked><span>No</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="nomber_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["nomber_mayor2"]; ?>"><label class="form_label">Nomber:</label>
						</div>
						<div class="form_group">
							<input name="fam_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["fam_mayor2"]; ?>"><label class="form_label">Fam:</label>
						</div>
						<div class="form_group">
							<input name="number_mayor2" type="text" placeholder=" " class="input-text " value="<?php echo $row["number_mayor2"]; ?>"><label class="form_label">Number di telefon:</label>
						</div>
						<div class="form_group">
							<input name="email_mayor2" type="email" placeholder=" " class="input-text" value="<?php echo $row["email_mayor2"]; ?>"><label class="form_label">Email adres:</label>
						</div>
						<div class="form_group">
							<input name="traha_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["traha_mayor2"]; ?>"><label class="form_label">Ta traha na:</label>
						</div>
						<div class="form_group">
							<input name="ocupacion_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["ocupacion_mayor2"]; ?>"><label class="form_label">Ocupacion:</label>
						</div>
						<div class="form_group">
							<input name="telefon_mayor2" type="tel" placeholder=" " class="input-text" value="<?php echo $row["telefon_mayor2"]; ?>"><label class="form_label">Telefon na trabou:</label>
						</div>
						<div class="form_group">
							<input name="caya_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["caya_mayor2"]; ?>"><label class="form_label">Adres: Nomber di caya:</label>
						</div>
						<div class="form_group">
							<input name="cas_mayor2" type="text" placeholder=" " class="input-text" value="<?php echo $row["cas_mayor2"]; ?>"><label class="form_label">Adres: Number di cas:</label>
						</div>
					</div>
					<h2>Info:</h2>
					<div class="info">
						<div class="form_group">
							<label>Status:</label>
							<?php if ($row["status"] == null || $row["status"] == 3) { ?>
								<input name="status" type="radio" value="1"><span>Accepted</span>
								<input name="status" type="radio" value="2"><span>Rejected</span>
								<input name="status" type="radio" value="3" checked><span>Pending</span>
							<?php } else if ($row["status"] == '1') { ?>
								<input name="status" type="radio" value="1" checked><span>Accepted</span>
								<input name="status" type="radio" value="2"><span>Rejected</span>
								<input name="status" type="radio" value="3"><span>Pending</span>
							<?php } else if ($row["status"] == '2') { ?>
								<input name="status" type="radio" value="1"><span>Accepted</span>
								<input name="status" type="radio" value="2" checked><span>Rejected</span>
								<input name="status" type="radio" value="3"><span>Pending</span>
							<?php } ?>
						</div>
						<div class="form_group">
							<input name="coment" type="text" placeholder=" " class="input-text" value="<?php echo $row["coment"]; ?>"><label class="form_label">Coment:</label>
							<input name="scool" type="text" hidden value="<?php echo $scol; ?>">
						</div>
					</div>
					<input type="number" name="id" hidden value="<?php echo $id; ?>">
					<div><button type="submit">UPDATE</button></div>
				</form>
			</div>
		</div>
	</body>
	<div class="qwihi">
		<a href="https://qwihi.com"><img id="logo" src="img/QwihiLogo.png" alt="qwihi" width="150px"></a>
		<label id="qwihi">Your Imagination is our Creation.</label>
	</div>
<?php } else {
	echo 'ERROR.';
} ?>