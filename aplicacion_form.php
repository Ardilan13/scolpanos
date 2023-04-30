<!-- Ardilan: formulario cerrado -->

<head>
	<title>Aplicacion Form</title>
</head>

<body style="background-color: #1d407a;">
	<!-- <div  style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:11%">
        <h1>Aplicacion Scol Preparatorio 2022-2023</h1>
        <img class="img-responsive" src="img/scholen.png" alt="Scol Pa Nos" width="25%"><br>
        <h2 style="color: red;">Aplicacion pa Scol Preparatorio (kleuter) pa aña escolar 2022-2023 a sera !</h2>
    </div> -->
</body>

<!-- Ardilan: formulario abierto -->
<?php require_once "config/app.config.php";
ob_start();
ob_flush() ?>

<head>
	<title>Aplicacion Form</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="img/scholenlogo.png">
</head>
<script src="components/jquery/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).on('change', 'input[type="file"]', function() {
		// this.files[0].size recupera el tamaño del archivo
		// alert(this.files[0].size);

		var fileName = this.files[0].name;
		var fileSize = this.files[0].size;

		if (fileSize > 2097152) {
			alert('The file cant exceed 2MB');
			this.value = '';
			this.files[0].name = '';
		} else {
			// recuperamos la extensión del archivo
			var ext = fileName.split('.').pop();

			// Convertimos en minúscula porque 
			// la extensión del archivo puede estar en mayúscula
			ext = ext.toLowerCase();

			// console.log(ext);
			switch (ext) {
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'pdf':
					break;
				default:
					alert('The file can be only an image or an pdf.');
					this.value = ''; // reset del valor
					this.files[0].name = '';
			}
		}
	});
</script>
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
			transform: translateY(-30px) scale(1.1);
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
<main id="main" role="main" class="login">
	<div class="container">
		<div class="form-bg"'>
			<div class="spn-logo">
				<h1 class="brand">
				<img class="img-responsive" src="img/scholen.png" alt="Scol Pa Nos" width="25%">
				</h1>
			</div>
		<form  method="POST" action="ajax/aplicacion_mail.php" enctype="multipart/form-data" id="formulario">
				<h1>Aplicacion Scol Preparatorio 2023-2024</h1>
				<h2>Informacion di e mucha</h2>
			<div class="info">
				<div class="form_group">
					<input name="nomber" type="text" class="input-text" placeholder=" " required><label class="form_label">Nomber:</label>
				</div>
				<div class="form_group">
					<input name="fam" type="text" class="input-text" placeholder=" " required><label class="form_label">Fam:</label>
				</div>
				<div class="form_group">
					<label>Sexo:</label>
						<input name="sexo" type="radio" value="1" required><span>M</span>
						<input name="sexo" type="radio" value="2" required><span>F</span>
				</div>
				<div class="form_group">
					<input name="nacionalidad" type="text" class="input-text" placeholder=" " required><label class="form_label">Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="f_nacemento" type="date" class="input-text" placeholder=" " required><label class="form_label">Fecha di nacemento:</label>
				</div>
				<div class="form_group">
					<input name="l_nacemento" type="text" class="input-text" placeholder=" " required><label class="form_label">Luga di nacemento:</label>
				</div>
				<div class="form_group">
					<label>Mucha tin AZV?:</label>
						<input name="azv" type="radio" value="1" required><span>Si</span>
						<input name="azv" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<input name="azv_number" type="number" placeholder=" " class="input-text "><label class="form_label">Number di AZV:</label>
				</div>
				<div class="form_group">
					<label>Mucha tin seguro priva?:</label>
						<input name="priva" type="radio" value="1" required><span>Si</span>
						<input name="priva" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">			
					<label>Censo: (jpg, jpeg, png of pdf, max 2mb)</label><input name="censo" accept="image/jpeg,image/jpg,image/png,.pdf" placeholder=" " type="file" id="input_file" required>
				</div>
			</div>

				<h2>Adres:</h2>
			<div class="info">
				<div class="form_group">
					<input name="caya" type="text" class="input-text" placeholder=" " required><label class="form_label">Nomber di caya:</label>
				</div>
				<div class="form_group">
					<input name="cas" type="text" class="input-text" placeholder=" " required><label class="form_label">Number di cas:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Idioma na cas:</label>
					<select name="idioma" class="" required>
						<option value="0"></option>
						<option value="1">Papiamento</option>
						<option value="2">Nederlands</option>
						<option value="3">Español</option>
						<option value="4">English</option>
						<option value="5">Otro</option>
					</select>
				</div>
				<div class="form_group">
					<input name="idioma_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Otro idioma?:</label>
				</div>
				<div class="form_group">
				<label class="form_label form-select">E mucha ta bou di guia di:</label>
					<select name="guia" class="" required>
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
						<option value="10">Otro</option>
					</select>
				</div>
				<div class="form_group">
					<input name="guia_otro" type="text" placeholder=" " class="input-text"><label class="form_label">Otro guia?:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">E mucha tin problema cu:</label>
					<select name="problema" class="" required>
						<option value="0"></option>
						<option value="1">No ta aplicabel</option>
						<option value="2">Bista</option>
						<option value="3">Oido</option>
						<option value="4">Habla</option>
						<option value="5">Motorico</option>
						<option value="6">Alergia</option>
						<option value="7">Otro</option>
					</select>
				</div>
				<div class="form_group">
					<input name="problema_otro" type="text" placeholder=" " class="input-text"><label class="form_label">Otro problema cu?:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Districto:</label>
					<select name="districto" class="" required> 
						<option value="0"></option>
						<option value="1">Noord</option>
						<option value="2">Oranjestad</option>
						<option value="3">Paradera</option>
						<option value="4">San Nicolaas</option>
						<option value="5">Santa Cruz</option>
						<option value="6">Savaneta</option>
					</select>
				</div>
				<div class="form_group">
					<label>E mucha ta zindelijk? (Por haci uzo di baño independiente y haci su mes limpi)</label>
						<input name="zindelijk" type="radio" value="1" required><span>Si</span>
						<input name="zindelijk" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<label>E mucha tin ruman ta bayendo scol/creche:</label>
						<input name="zildelijk1" type="radio" value="1" required><span>Si</span>
						<input name="zildelijk1" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group" id="bishita">
					<input name="ultimo_scol" type="text" placeholder=" " class="input-text"><label class="form_label">Nomber di ultimo scol/creche cu a mucha e bishita:</label>
				</div>
			</div>
				<h2>Scol Di Ruman (nan)</h2>
			<div class="info">
				<div class="form_group">
					<input name="nomber_fam" type="text" placeholder=" " class="input-text" ><label class="form_label">Nomber y Fam:</label>
				</div>
				<div class="form_group">
					<input name="scol" type="text" placeholder=" " class="input-text" ><label class="form_label">Scol:</label>
				</div>
				<div class="form_group">
					<input name="klas" type="text" placeholder=" " class="input-text " ><label class="form_label">Klas:</label>
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
						<!-- <option value="0"></option>
						 <option value="1">Colegio Conrado Coronel</option>
						<option value="2">Fontein Kleuterschool</option>
						<option value="3">Prinses Amalia School</option>
						<option value="4">Reina Beatrix School</option>
						<option value="5">Scol Preparatorio Washington</option>
						<option value="6">Scol Primario Arco Iris</option>
						<option value="7">Scol Primario Kudawecha</option>
						<option value="8">Scol Basico Xander Bogaerts</option> -->
						<option value="0"></option>
						<option value="9">Scol Primario Kudawecha</option>
<!-- 						<option value="10">Scol Preparatorio Kudawecha</option>-->
						<option value="11">Colegio Conrado Coronel</option>
<!-- 						<option value="12">Colegio Conrado Coronel Kleuter</option>-->
						<!-- <option value="13">Prinses Amalia Basisschool</option>
						<option value="14">Prinses Amalia Kleuterschool</option> -->
<!-- 						<option value="15">Scol Basico Washington</option>-->
<!-- 						<option value="16">Scol Preparatorio Washington</option>-->
						<option value="17">Arco Iris Kleuterschool</option>
						<option value="18">Fontein Kleuterschool</option>
<!-- 						<option value="19">Scol Basico Dornasol</option>-->
						<!-- <option value="20">Scol Preparatorio Dornasol</option>
						<option value="21">Scol Seour Juliette</option>
						<option value="22">Scol Paso pa Futuro</option> -->
<!-- 						<option value="23">Reina Beatrix School</option>-->
						<option value="24">Scol Basico Xander Bogaerts</option>
<!-- 						<option value="25">Scol Preparatorio Xander Bogaerts</option>-->
						<option value="26">Hilario Angela</option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Segundo preferencia:</label>
					<select name="segundo_preferencia" class="" required>
					<!-- <option value="0"></option>
						 <option value="1">Colegio Conrado Coronel</option>
						<option value="2">Fontein Kleuterschool</option>
						<option value="3">Prinses Amalia School</option>
						<option value="4">Reina Beatrix School</option>
						<option value="5">Scol Preparatorio Washington</option>
						<option value="6">Scol Primario Arco Iris</option>
						<option value="7">Scol Primario Kudawecha</option>
						<option value="8">Scol Basico Xander Bogaerts</option> -->
						<option value="0"></option>
						<option value="9">Scol Primario Kudawecha</option>
<!-- 						<option value="10">Scol Preparatorio Kudawecha</option>-->
						<option value="11">Colegio Conrado Coronel</option>
<!-- 						<option value="12">Colegio Conrado Coronel Kleuter</option>-->
						<!-- <option value="13">Prinses Amalia Basisschool</option>
						<option value="14">Prinses Amalia Kleuterschool</option> -->
<!-- 						<option value="15">Scol Basico Washington</option>-->
<!-- 						<option value="16">Scol Preparatorio Washington</option>-->
						<option value="17">Arco Iris Kleuterschool</option>
						<option value="18">Fontein Kleuterschool</option>
<!-- 						<option value="19">Scol Basico Dornasol</option>-->
						<!-- <option value="20">Scol Preparatorio Dornasol</option>
						<option value="21">Scol Seour Juliette</option>
						<option value="22">Scol Paso pa Futuro</option> -->
<!-- 						<option value="23">Reina Beatrix School</option>-->
						<option value="24">Scol Basico Xander Bogaerts</option>
<!-- 						<option value="25">Scol Preparatorio Xander Bogaerts</option>-->
						<option value="26">Hilario Angela</option>
					</select>
				</div>
				<div class="form_group">
					<label>E mucha tin ruman na e mesun scol of na un scol basico publico:</label>
						<input name="ruman" type="radio" value="1" required><span>Si</span>
						<input name="ruman" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<label>E mucha ta yiu di un collega:</label>
						<input name="collega" type="radio" value="1" required><span>Si</span>
						<input name="collega" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<label>E mucha tin un ruman na un otro scol publico:</label>
						<input name="publico" type="radio" value="1" required><span>Si</span>
						<input name="publico" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<input name="motivacion" type="text" placeholder=" " class="input-text"><label class="form_label">Motivacion:</label>
				</div>
			</div>
				<h2>Informacion di mayor (nan)</h2>
			<div class="info">
				<div class="form_group">
					<label class="form_label form-select">Estado civil di mayor (nan):</label>
					<select name="estado" class="" id="select_estado" required>
						<option value="0"></option>
						<option value="1">Casa</option>
						<option value="2">Biba hunto</option>
						<option value="3">Divorcia / separa</option>
						<option value="4">Viudo</option>
						<option value="5">Otro</option>
					</select>
				</div>
				<div class="form_group" id="estado_otro1">
					<input name="estado_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Otro estado civil?:</label>
				</div>
			</div>
					<h2>Mayor 1:</h2>
			<div class="info">
				<div class="form_group">
					<label>Relacion:</label>
						<input name="relacion" type="radio" value="1" required><span>Mama</span>
						<input name="relacion" type="radio" value="2" required><span>Tata</span>
						<input name="relacion" type="radio" value="3" required><span>Otro</span>
				</div>
				<div class="form_group">
					<input name="relacion_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Otro?:</label>
				</div>
				<div class="form_group">
					<label>Ta voogd di e yiu:</label>
						<input name="voogd" type="radio" value="1" required><span>Si</span>
						<input name="voogd" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<label>Tin mesun adres cu e mucha?</label>
						<input name="mesun" type="radio" value="1" required><span>Si</span>
						<input name="mesun" type="radio" value="2" required><span>No</span>
				</div>
				<div class="form_group">
					<input name="nomber_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Nomber:</label>
				</div>
				<div class="form_group">
					<input name="fam_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Fam:</label>
				</div>
				<div class="form_group">
					<input name="number_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Number di telefon:</label>
				</div>
				<div class="form_group">
					<input name="email_mayor1" type="email" placeholder=" " class="input-text" required><label class="form_label">Email adres:</label>
				</div>
				<div class="form_group">
					<input name="traha_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="ocupacion_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="telefon_mayor1" type="tel" placeholder=" " class="input-text " required><label class="form_label">Telefon na trabou:</label>
				</div>
				<div class="form_group">
					<input name="caya_mayor1" type="text" placeholder=" " class="input-text" required><label class="form_label">Adres: Nomber di caya:</label>
				</div>
				<div class="form_group">
					<input name="cas_mayor1" type="text" placeholder=" " class="input-text"  required><label class="form_label">Adres: Number di cas:</label>
				</div>
			</div>
					<h2>Mayor 2:</h2>
			<div class="info">
				<div class="form_group">
					<label>Relacion:</label>
						<input name="relacion2" type="radio" value="1" ><span>Mama</span>
						<input name="relacion2" type="radio" value="2" ><span>Tata</span>
						<input name="relacion2" type="radio" value="3" ><span>Otro</span>
				</div>
				<div class="form_group">
					<input name="relacion_otro2" type="text" placeholder=" " class="input-text "><label class="form_label">Otro?:</label>
				</div>
				<div class="form_group">
					<label>Ta voogd di e yiu:</label>
						<input name="voogd2" type="radio" value="1" ><span>Si</span>
						<input name="voogd2" type="radio" value="2" ><span>No</span>
				</div>
				<div class="form_group">
					<label>Tin mesun adres cu e mucha?</label>
						<input name="mesun2" type="radio" value="1"  ><span>Si</span>
						<input name="mesun2" type="radio" value="2" ><span>No</span>
				</div>
				<div class="form_group">
					<input name="nomber_mayor2" type="text" placeholder=" " class="input-text" ><label class="form_label">Nomber:</label>
				</div>
				<div class="form_group">
					<input name="fam_mayor2" type="text" placeholder=" " class="input-text" ><label class="form_label">Fam:</label>
				</div>
				<div class="form_group">
					<input name="number_mayor2" type="text" placeholder=" " class="input-text " ><label class="form_label">Number di telefon:</label>
				</div>
				<div class="form_group">
					<input name="email_mayor2" type="email" placeholder=" " class="input-text" ><label class="form_label">Email adres:</label>
				</div>
				<div class="form_group">
					<input name="traha_mayor2" type="text" placeholder=" " class="input-text" ><label class="form_label">Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="ocupacion_mayor2" type="text" placeholder=" " class="input-text"  ><label class="form_label">Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="telefon_mayor2" type="tel" placeholder=" " class="input-text" ><label class="form_label">Telefon na trabou:</label>
				</div>
				<div class="form_group">
					<input name="caya_mayor2" type="text" placeholder=" " class="input-text" ><label class="form_label">Adres: Nomber di caya:</label>
				</div>
				<div class="form_group">
					<input name="cas_mayor2" type="text" placeholder=" " class="input-text" ><label class="form_label">Adres: Number di cas:</label>
				</div>
			</div>
			<div>
				<div class="form_group">
					<h3>Declaracion</h3>
					<label>Mi ta declara cu e informacion cu mi ta duna den e aplicacion aki ta basa riba berdad y sin tene informacion atras. Mi ta consciente cu si informacion yena ta falso, e aplicacion aki ta invalido.</label>
					<input type="checkbox" name="declaracion1" required style="margin-bottom: 2%;"><span>Ok</span>
				</div>
			</div>
				<div><button type="submit">SEND</button></div>
			</form>
		</div>
</div>
	
</main>
<div class="qwihi">
		<a href="https://qwihi.com"><img id="logo" src="img/QwihiLogo.png" alt="qwihi" width="150px"></a>
		<label id="qwihi">Your Imagination is our Creation.</label>
</div> -->