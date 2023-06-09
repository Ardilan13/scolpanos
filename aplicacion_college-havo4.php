<!-- Ardilan: formulario cerrado -->

<!-- <head>
	<title>AANMELDINGSFORMULIER</title>
</head>

<body style="background-color: #1d407a;">
	<div style="background-color: white; text-align: center; border-radius: 10px; padding:5% 0; margin:11%">
		<h1>Aanmeldingsformulier Schooljaar 2022 – 2023</h1>
		<img class="img-responsive" src="img/monplaisir123.png" alt="Scol Pa Nos" width="15%"><br>
		<h2 style="color: red;">Aplicacion pa aña escolar 2022-2023 a sera !</h2>
	</div>
</body> -->


<!-- Ardilan: formulario abierto -->
<?php require_once "config/app.config.php";
ob_start();
ob_flush() ?>

<head>
	<title>AANMELDINGSFORMULIER</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
				case 'docx':
				case 'xls':
				case 'xlsx':
				case 'xlsb':
					break;
				default:
					alert('The file can be only an image, pdf, docx or excel.');
					this.value = ''; // reset del valor
					this.files[0].name = '';
			}
		}
	});

	$("#select_profiel").change(function() {
		/* var selected = $(this).children("option:selected").val();
		if (selected == "1") {
			$("#select_profiel").css("border", "1px solid red");
			$("#select_profiel").css("color", "red");
		} else {
			$("#select_profiel").css("border", "1px solid #1d407a");
			$("#select_profiel").css("color", "#1d407a");
		} */
		alert("hola");
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
		margin: 3% 7%;
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

	.info-top {
		border-top: 1px solid #1d407a;
		padding-top: 3%;
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
			width: 40%;
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
				<img class="img-responsive" src="img/monplaisir123.png" alt="Scol Pa Nos" width="12%">
				</h1>
			</div>
			<form action="ajax/college_mail.php" method="POST" id="formulario" enctype="multipart/form-data">
				<h1>Aanmeldingsformulier Schooljaar 2023-2024</h1>
                <h3>Mon Plaisir College HAVO</h3>
				<h4>Havo 4</h4>

				<div class="info">
					<div class="form_group">
					<label class="form_label form-select">Ik meld mijn kind aan voor:</label>
						<select name="meld" class="" required>
							<option value="0"></option>
							<option value="1">CB2</option>
							<option value="2">HAVO 3</option>
							<option value="3">VWO 3</option>
							<option value="4">Havo 4 (afkomstig van havo 3)</option>
						</select>
					</div>
					<div>
						<div class="form_group">
							<p>	
							Extra informatie: Aanmelding voor havo 4 leerlingen afkomstig van mavo 4 zal na de CE openen.
							</p>
						</div>
					</div>
				</div>
				<h2>Personalia:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam" type="text" class="input-text" placeholder=" " required><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen" type="text" class="input-text" placeholder=" " required><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
                <div class="form_group">
					<input name="roepnaam" type="text" class="input-text" placeholder=" " required><label class="form_label">Roepnaam: / Nomber comun:</label>
				</div>
				<div class="form_group">
					<label>Geslacht: / Sexo:</label>
						<input name="sexo" type="radio" value="1" required><span>M</span>
						<input name="sexo" type="radio" value="2" required><span>F</span>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Geboorteland: / Pais di Nacemento:</label>
					<select name="geboorteland"> 
						<option value=""></option>
   						<option value="Afganistan">Afghanistan</option>
   						<option value="Albania">Albania</option>
   						<option value="Algeria">Algeria</option>
   						<option value="American Samoa">American Samoa</option>
   						<option value="Andorra">Andorra</option>
   						<option value="Angola">Angola</option>
   						<option value="Anguilla">Anguilla</option>
   						<option value="Antigua & Barbuda">Antigua & Barbuda</option>
   						<option value="Argentina">Argentina</option>
   						<option value="Armenia">Armenia</option>
   						<option value="Aruba">Aruba</option>
   						<option value="Australia">Australia</option>
   						<option value="Austria">Austria</option>
   						<option value="Azerbaijan">Azerbaijan</option>
   						<option value="Bahamas">Bahamas</option>
   						<option value="Bahrain">Bahrain</option>
   						<option value="Bangladesh">Bangladesh</option>
   						<option value="Barbados">Barbados</option>
   						<option value="Belarus">Belarus</option>
   						<option value="Belgium">Belgium</option>
   						<option value="Belize">Belize</option>
   						<option value="Benin">Benin</option>
   						<option value="Bermuda">Bermuda</option>
   						<option value="Bhutan">Bhutan</option>
   						<option value="Bolivia">Bolivia</option>
   						<option value="Bonaire">Bonaire</option>
   						<option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
   						<option value="Botswana">Botswana</option>
   						<option value="Brazil">Brazil</option>
   						<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
   						<option value="Brunei">Brunei</option>
   						<option value="Bulgaria">Bulgaria</option>
   						<option value="Burkina Faso">Burkina Faso</option>
   						<option value="Burundi">Burundi</option>
   						<option value="Cambodia">Cambodia</option>
   						<option value="Cameroon">Cameroon</option>
   						<option value="Canada">Canada</option>
   						<option value="Canary Islands">Canary Islands</option>
   						<option value="Cape Verde">Cape Verde</option>
   						<option value="Cayman Islands">Cayman Islands</option>
   						<option value="Central African Republic">Central African Republic</option>
   						<option value="Chad">Chad</option>
   						<option value="Channel Islands">Channel Islands</option>
   						<option value="Chile">Chile</option>
   						<option value="China">China</option>
   						<option value="Christmas Island">Christmas Island</option>
   						<option value="Cocos Island">Cocos Island</option>
   						<option value="Colombia">Colombia</option>
   						<option value="Comoros">Comoros</option>
   						<option value="Congo">Congo</option>
   						<option value="Cook Islands">Cook Islands</option>
   						<option value="Costa Rica">Costa Rica</option>
   						<option value="Cote DIvoire">Cote DIvoire</option>
   						<option value="Croatia">Croatia</option>
   						<option value="Cuba">Cuba</option>
   						<option value="Curaco">Curacao</option>
   						<option value="Cyprus">Cyprus</option>
   						<option value="Czech Republic">Czech Republic</option>
   						<option value="Denmark">Denmark</option>
   						<option value="Djibouti">Djibouti</option>
   						<option value="Dominica">Dominica</option>
   						<option value="Dominican Republic">Dominican Republic</option>
   						<option value="East Timor">East Timor</option>
   						<option value="Ecuador">Ecuador</option>
   						<option value="Egypt">Egypt</option>
   						<option value="El Salvador">El Salvador</option>
   						<option value="Equatorial Guinea">Equatorial Guinea</option>
   						<option value="Eritrea">Eritrea</option>
   						<option value="Estonia">Estonia</option>
   						<option value="Ethiopia">Ethiopia</option>
   						<option value="Falkland Islands">Falkland Islands</option>
   						<option value="Faroe Islands">Faroe Islands</option>
   						<option value="Fiji">Fiji</option>
   						<option value="Finland">Finland</option>
   						<option value="France">France</option>
   						<option value="French Guiana">French Guiana</option>
   						<option value="French Polynesia">French Polynesia</option>
   						<option value="French Southern Ter">French Southern Ter</option>
   						<option value="Gabon">Gabon</option>
   						<option value="Gambia">Gambia</option>
   						<option value="Georgia">Georgia</option>
   						<option value="Germany">Germany</option>
   						<option value="Ghana">Ghana</option>
   						<option value="Gibraltar">Gibraltar</option>
   						<option value="Great Britain">Great Britain</option>
   						<option value="Greece">Greece</option>
   						<option value="Greenland">Greenland</option>
   						<option value="Grenada">Grenada</option>
   						<option value="Guadeloupe">Guadeloupe</option>
   						<option value="Guam">Guam</option>
   						<option value="Guatemala">Guatemala</option>
   						<option value="Guinea">Guinea</option>
   						<option value="Guyana">Guyana</option>
   						<option value="Haiti">Haiti</option>
   						<option value="Hawaii">Hawaii</option>
   						<option value="Honduras">Honduras</option>
   						<option value="Hong Kong">Hong Kong</option>
   						<option value="Hungary">Hungary</option>
   						<option value="Iceland">Iceland</option>
   						<option value="Indonesia">Indonesia</option>
   						<option value="India">India</option>
   						<option value="Iran">Iran</option>
   						<option value="Iraq">Iraq</option>
   						<option value="Ireland">Ireland</option>
   						<option value="Isle of Man">Isle of Man</option>
   						<option value="Israel">Israel</option>
   						<option value="Italy">Italy</option>
   						<option value="Jamaica">Jamaica</option>
   						<option value="Japan">Japan</option>
   						<option value="Jordan">Jordan</option>
   						<option value="Kazakhstan">Kazakhstan</option>
   						<option value="Kenya">Kenya</option>
   						<option value="Kiribati">Kiribati</option>
   						<option value="Korea North">Korea North</option>
   						<option value="Korea Sout">Korea South</option>
   						<option value="Kuwait">Kuwait</option>
   						<option value="Kyrgyzstan">Kyrgyzstan</option>
   						<option value="Laos">Laos</option>
   						<option value="Latvia">Latvia</option>
   						<option value="Lebanon">Lebanon</option>
   						<option value="Lesotho">Lesotho</option>
   						<option value="Liberia">Liberia</option>
   						<option value="Libya">Libya</option>
   						<option value="Liechtenstein">Liechtenstein</option>
   						<option value="Lithuania">Lithuania</option>
   						<option value="Luxembourg">Luxembourg</option>
   						<option value="Macau">Macau</option>
   						<option value="Macedonia">Macedonia</option>
   						<option value="Madagascar">Madagascar</option>
   						<option value="Malaysia">Malaysia</option>
   						<option value="Malawi">Malawi</option>
   						<option value="Maldives">Maldives</option>
   						<option value="Mali">Mali</option>
   						<option value="Malta">Malta</option>
   						<option value="Marshall Islands">Marshall Islands</option>
   						<option value="Martinique">Martinique</option>
   						<option value="Mauritania">Mauritania</option>
   						<option value="Mauritius">Mauritius</option>
   						<option value="Mayotte">Mayotte</option>
   						<option value="Mexico">Mexico</option>
   						<option value="Midway Islands">Midway Islands</option>
   						<option value="Moldova">Moldova</option>
   						<option value="Monaco">Monaco</option>
   						<option value="Mongolia">Mongolia</option>
   						<option value="Montserrat">Montserrat</option>
   						<option value="Morocco">Morocco</option>
   						<option value="Mozambique">Mozambique</option>
   						<option value="Myanmar">Myanmar</option>
   						<option value="Nambia">Nambia</option>
   						<option value="Nauru">Nauru</option>
   						<option value="Nepal">Nepal</option>
   						<option value="Netherland Antilles">Netherland Antilles</option>
   						<option value="Netherlands">Netherlands (Holland, Europe)</option>
   						<option value="Nevis">Nevis</option>
   						<option value="New Caledonia">New Caledonia</option>
   						<option value="New Zealand">New Zealand</option>
   						<option value="Nicaragua">Nicaragua</option>
   						<option value="Niger">Niger</option>
   						<option value="Nigeria">Nigeria</option>
   						<option value="Niue">Niue</option>
   						<option value="Norfolk Island">Norfolk Island</option>
   						<option value="Norway">Norway</option>
   						<option value="Oman">Oman</option>
   						<option value="Pakistan">Pakistan</option>
   						<option value="Palau Island">Palau Island</option>
   						<option value="Palestine">Palestine</option>
   						<option value="Panama">Panama</option>
   						<option value="Papua New Guinea">Papua New Guinea</option>
   						<option value="Paraguay">Paraguay</option>
   						<option value="Peru">Peru</option>
   						<option value="Phillipines">Philippines</option>
   						<option value="Pitcairn Island">Pitcairn Island</option>
   						<option value="Poland">Poland</option>
   						<option value="Portugal">Portugal</option>
   						<option value="Puerto Rico">Puerto Rico</option>
   						<option value="Qatar">Qatar</option>
   						<option value="Republic of Montenegro">Republic of Montenegro</option>
   						<option value="Republic of Serbia">Republic of Serbia</option>
   						<option value="Reunion">Reunion</option>
   						<option value="Romania">Romania</option>
   						<option value="Russia">Russia</option>
   						<option value="Rwanda">Rwanda</option>
   						<option value="St Barthelemy">St Barthelemy</option>
   						<option value="St Eustatius">St Eustatius</option>
   						<option value="St Helena">St Helena</option>
   						<option value="St Kitts-Nevis">St Kitts-Nevis</option>
   						<option value="St Lucia">St Lucia</option>
   						<option value="St Maarten">St Maarten</option>
   						<option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
   						<option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
   						<option value="Saipan">Saipan</option>
   						<option value="Samoa">Samoa</option>
   						<option value="Samoa American">Samoa American</option>
   						<option value="San Marino">San Marino</option>
   						<option value="Sao Tome & Principe">Sao Tome & Principe</option>
   						<option value="Saudi Arabia">Saudi Arabia</option>
   						<option value="Senegal">Senegal</option>
   						<option value="Seychelles">Seychelles</option>
   						<option value="Sierra Leone">Sierra Leone</option>
   						<option value="Singapore">Singapore</option>
   						<option value="Slovakia">Slovakia</option>
   						<option value="Slovenia">Slovenia</option>
   						<option value="Solomon Islands">Solomon Islands</option>
   						<option value="Somalia">Somalia</option>
   						<option value="South Africa">South Africa</option>
   						<option value="Spain">Spain</option>
   						<option value="Sri Lanka">Sri Lanka</option>
   						<option value="Sudan">Sudan</option>
   						<option value="Suriname">Suriname</option>
   						<option value="Swaziland">Swaziland</option>
   						<option value="Sweden">Sweden</option>
   						<option value="Switzerland">Switzerland</option>
   						<option value="Syria">Syria</option>
   						<option value="Tahiti">Tahiti</option>
   						<option value="Taiwan">Taiwan</option>
   						<option value="Tajikistan">Tajikistan</option>
   						<option value="Tanzania">Tanzania</option>
   						<option value="Thailand">Thailand</option>
   						<option value="Togo">Togo</option>
   						<option value="Tokelau">Tokelau</option>
   						<option value="Tonga">Tonga</option>
   						<option value="Trinidad & Tobago">Trinidad & Tobago</option>
   						<option value="Tunisia">Tunisia</option>
   						<option value="Turkey">Turkey</option>
   						<option value="Turkmenistan">Turkmenistan</option>
   						<option value="Turks & Caicos Is">Turks & Caicos Is</option>
   						<option value="Tuvalu">Tuvalu</option>
   						<option value="Uganda">Uganda</option>
   						<option value="United Kingdom">United Kingdom</option>
   						<option value="Ukraine">Ukraine</option>
   						<option value="United Arab Erimates">United Arab Emirates</option>
   						<option value="United States of America">United States of America</option>
   						<option value="Uraguay">Uruguay</option>
   						<option value="Uzbekistan">Uzbekistan</option>
   						<option value="Vanuatu">Vanuatu</option>
   						<option value="Vatican City State">Vatican City State</option>
   						<option value="Venezuela">Venezuela</option>
   						<option value="Vietnam">Vietnam</option>
   						<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
   						<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
   						<option value="Wake Island">Wake Island</option>
   						<option value="Wallis & Futana Is">Wallis & Futana Is</option>
   						<option value="Yemen">Yemen</option>
   						<option value="Zaire">Zaire</option>
   						<option value="Zambia">Zambia</option>
   						<option value="Zimbabwe">Zimbabwe</option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Nationaliteit / Nacionalidad:</label>
					<select name="nacionalidad">
						<option value=""></option>
   						<option value="Afganistan">Afghanistan</option>
   						<option value="Albania">Albania</option>
   						<option value="Algeria">Algeria</option>
   						<option value="American Samoa">American Samoa</option>
   						<option value="Andorra">Andorra</option>
   						<option value="Angola">Angola</option>
   						<option value="Anguilla">Anguilla</option>
   						<option value="Antigua & Barbuda">Antigua & Barbuda</option>
   						<option value="Argentina">Argentina</option>
   						<option value="Armenia">Armenia</option>
   						<option value="Aruba">Aruba</option>
   						<option value="Australia">Australia</option>
   						<option value="Austria">Austria</option>
   						<option value="Azerbaijan">Azerbaijan</option>
   						<option value="Bahamas">Bahamas</option>
   						<option value="Bahrain">Bahrain</option>
   						<option value="Bangladesh">Bangladesh</option>
   						<option value="Barbados">Barbados</option>
   						<option value="Belarus">Belarus</option>
   						<option value="Belgium">Belgium</option>
   						<option value="Belize">Belize</option>
   						<option value="Benin">Benin</option>
   						<option value="Bermuda">Bermuda</option>
   						<option value="Bhutan">Bhutan</option>
   						<option value="Bolivia">Bolivia</option>
   						<option value="Bonaire">Bonaire</option>
   						<option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
   						<option value="Botswana">Botswana</option>
   						<option value="Brazil">Brazil</option>
   						<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
   						<option value="Brunei">Brunei</option>
   						<option value="Bulgaria">Bulgaria</option>
   						<option value="Burkina Faso">Burkina Faso</option>
   						<option value="Burundi">Burundi</option>
   						<option value="Cambodia">Cambodia</option>
   						<option value="Cameroon">Cameroon</option>
   						<option value="Canada">Canada</option>
   						<option value="Canary Islands">Canary Islands</option>
   						<option value="Cape Verde">Cape Verde</option>
   						<option value="Cayman Islands">Cayman Islands</option>
   						<option value="Central African Republic">Central African Republic</option>
   						<option value="Chad">Chad</option>
   						<option value="Channel Islands">Channel Islands</option>
   						<option value="Chile">Chile</option>
   						<option value="China">China</option>
   						<option value="Christmas Island">Christmas Island</option>
   						<option value="Cocos Island">Cocos Island</option>
   						<option value="Colombia">Colombia</option>
   						<option value="Comoros">Comoros</option>
   						<option value="Congo">Congo</option>
   						<option value="Cook Islands">Cook Islands</option>
   						<option value="Costa Rica">Costa Rica</option>
   						<option value="Cote DIvoire">Cote DIvoire</option>
   						<option value="Croatia">Croatia</option>
   						<option value="Cuba">Cuba</option>
   						<option value="Curaco">Curacao</option>
   						<option value="Cyprus">Cyprus</option>
   						<option value="Czech Republic">Czech Republic</option>
   						<option value="Denmark">Denmark</option>
   						<option value="Djibouti">Djibouti</option>
   						<option value="Dominica">Dominica</option>
   						<option value="Dominican Republic">Dominican Republic</option>
   						<option value="East Timor">East Timor</option>
   						<option value="Ecuador">Ecuador</option>
   						<option value="Egypt">Egypt</option>
   						<option value="El Salvador">El Salvador</option>
   						<option value="Equatorial Guinea">Equatorial Guinea</option>
   						<option value="Eritrea">Eritrea</option>
   						<option value="Estonia">Estonia</option>
   						<option value="Ethiopia">Ethiopia</option>
   						<option value="Falkland Islands">Falkland Islands</option>
   						<option value="Faroe Islands">Faroe Islands</option>
   						<option value="Fiji">Fiji</option>
   						<option value="Finland">Finland</option>
   						<option value="France">France</option>
   						<option value="French Guiana">French Guiana</option>
   						<option value="French Polynesia">French Polynesia</option>
   						<option value="French Southern Ter">French Southern Ter</option>
   						<option value="Gabon">Gabon</option>
   						<option value="Gambia">Gambia</option>
   						<option value="Georgia">Georgia</option>
   						<option value="Germany">Germany</option>
   						<option value="Ghana">Ghana</option>
   						<option value="Gibraltar">Gibraltar</option>
   						<option value="Great Britain">Great Britain</option>
   						<option value="Greece">Greece</option>
   						<option value="Greenland">Greenland</option>
   						<option value="Grenada">Grenada</option>
   						<option value="Guadeloupe">Guadeloupe</option>
   						<option value="Guam">Guam</option>
   						<option value="Guatemala">Guatemala</option>
   						<option value="Guinea">Guinea</option>
   						<option value="Guyana">Guyana</option>
   						<option value="Haiti">Haiti</option>
   						<option value="Hawaii">Hawaii</option>
   						<option value="Honduras">Honduras</option>
   						<option value="Hong Kong">Hong Kong</option>
   						<option value="Hungary">Hungary</option>
   						<option value="Iceland">Iceland</option>
   						<option value="Indonesia">Indonesia</option>
   						<option value="India">India</option>
   						<option value="Iran">Iran</option>
   						<option value="Iraq">Iraq</option>
   						<option value="Ireland">Ireland</option>
   						<option value="Isle of Man">Isle of Man</option>
   						<option value="Israel">Israel</option>
   						<option value="Italy">Italy</option>
   						<option value="Jamaica">Jamaica</option>
   						<option value="Japan">Japan</option>
   						<option value="Jordan">Jordan</option>
   						<option value="Kazakhstan">Kazakhstan</option>
   						<option value="Kenya">Kenya</option>
   						<option value="Kiribati">Kiribati</option>
   						<option value="Korea North">Korea North</option>
   						<option value="Korea Sout">Korea South</option>
   						<option value="Kuwait">Kuwait</option>
   						<option value="Kyrgyzstan">Kyrgyzstan</option>
   						<option value="Laos">Laos</option>
   						<option value="Latvia">Latvia</option>
   						<option value="Lebanon">Lebanon</option>
   						<option value="Lesotho">Lesotho</option>
   						<option value="Liberia">Liberia</option>
   						<option value="Libya">Libya</option>
   						<option value="Liechtenstein">Liechtenstein</option>
   						<option value="Lithuania">Lithuania</option>
   						<option value="Luxembourg">Luxembourg</option>
   						<option value="Macau">Macau</option>
   						<option value="Macedonia">Macedonia</option>
   						<option value="Madagascar">Madagascar</option>
   						<option value="Malaysia">Malaysia</option>
   						<option value="Malawi">Malawi</option>
   						<option value="Maldives">Maldives</option>
   						<option value="Mali">Mali</option>
   						<option value="Malta">Malta</option>
   						<option value="Marshall Islands">Marshall Islands</option>
   						<option value="Martinique">Martinique</option>
   						<option value="Mauritania">Mauritania</option>
   						<option value="Mauritius">Mauritius</option>
   						<option value="Mayotte">Mayotte</option>
   						<option value="Mexico">Mexico</option>
   						<option value="Midway Islands">Midway Islands</option>
   						<option value="Moldova">Moldova</option>
   						<option value="Monaco">Monaco</option>
   						<option value="Mongolia">Mongolia</option>
   						<option value="Montserrat">Montserrat</option>
   						<option value="Morocco">Morocco</option>
   						<option value="Mozambique">Mozambique</option>
   						<option value="Myanmar">Myanmar</option>
   						<option value="Nambia">Nambia</option>
   						<option value="Nauru">Nauru</option>
   						<option value="Nepal">Nepal</option>
   						<option value="Netherland Antilles">Netherland Antilles</option>
   						<option value="Netherlands">Netherlands (Holland, Europe)</option>
   						<option value="Nevis">Nevis</option>
   						<option value="New Caledonia">New Caledonia</option>
   						<option value="New Zealand">New Zealand</option>
   						<option value="Nicaragua">Nicaragua</option>
   						<option value="Niger">Niger</option>
   						<option value="Nigeria">Nigeria</option>
   						<option value="Niue">Niue</option>
   						<option value="Norfolk Island">Norfolk Island</option>
   						<option value="Norway">Norway</option>
   						<option value="Oman">Oman</option>
   						<option value="Pakistan">Pakistan</option>
   						<option value="Palau Island">Palau Island</option>
   						<option value="Palestine">Palestine</option>
   						<option value="Panama">Panama</option>
   						<option value="Papua New Guinea">Papua New Guinea</option>
   						<option value="Paraguay">Paraguay</option>
   						<option value="Peru">Peru</option>
   						<option value="Phillipines">Philippines</option>
   						<option value="Pitcairn Island">Pitcairn Island</option>
   						<option value="Poland">Poland</option>
   						<option value="Portugal">Portugal</option>
   						<option value="Puerto Rico">Puerto Rico</option>
   						<option value="Qatar">Qatar</option>
   						<option value="Republic of Montenegro">Republic of Montenegro</option>
   						<option value="Republic of Serbia">Republic of Serbia</option>
   						<option value="Reunion">Reunion</option>
   						<option value="Romania">Romania</option>
   						<option value="Russia">Russia</option>
   						<option value="Rwanda">Rwanda</option>
   						<option value="St Barthelemy">St Barthelemy</option>
   						<option value="St Eustatius">St Eustatius</option>
   						<option value="St Helena">St Helena</option>
   						<option value="St Kitts-Nevis">St Kitts-Nevis</option>
   						<option value="St Lucia">St Lucia</option>
   						<option value="St Maarten">St Maarten</option>
   						<option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
   						<option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
   						<option value="Saipan">Saipan</option>
   						<option value="Samoa">Samoa</option>
   						<option value="Samoa American">Samoa American</option>
   						<option value="San Marino">San Marino</option>
   						<option value="Sao Tome & Principe">Sao Tome & Principe</option>
   						<option value="Saudi Arabia">Saudi Arabia</option>
   						<option value="Senegal">Senegal</option>
   						<option value="Seychelles">Seychelles</option>
   						<option value="Sierra Leone">Sierra Leone</option>
   						<option value="Singapore">Singapore</option>
   						<option value="Slovakia">Slovakia</option>
   						<option value="Slovenia">Slovenia</option>
   						<option value="Solomon Islands">Solomon Islands</option>
   						<option value="Somalia">Somalia</option>
   						<option value="South Africa">South Africa</option>
   						<option value="Spain">Spain</option>
   						<option value="Sri Lanka">Sri Lanka</option>
   						<option value="Sudan">Sudan</option>
   						<option value="Suriname">Suriname</option>
   						<option value="Swaziland">Swaziland</option>
   						<option value="Sweden">Sweden</option>
   						<option value="Switzerland">Switzerland</option>
   						<option value="Syria">Syria</option>
   						<option value="Tahiti">Tahiti</option>
   						<option value="Taiwan">Taiwan</option>
   						<option value="Tajikistan">Tajikistan</option>
   						<option value="Tanzania">Tanzania</option>
   						<option value="Thailand">Thailand</option>
   						<option value="Togo">Togo</option>
   						<option value="Tokelau">Tokelau</option>
   						<option value="Tonga">Tonga</option>
   						<option value="Trinidad & Tobago">Trinidad & Tobago</option>
   						<option value="Tunisia">Tunisia</option>
   						<option value="Turkey">Turkey</option>
   						<option value="Turkmenistan">Turkmenistan</option>
   						<option value="Turks & Caicos Is">Turks & Caicos Is</option>
   						<option value="Tuvalu">Tuvalu</option>
   						<option value="Uganda">Uganda</option>
   						<option value="United Kingdom">United Kingdom</option>
   						<option value="Ukraine">Ukraine</option>
   						<option value="United Arab Erimates">United Arab Emirates</option>
   						<option value="United States of America">United States of America</option>
   						<option value="Uraguay">Uruguay</option>
   						<option value="Uzbekistan">Uzbekistan</option>
   						<option value="Vanuatu">Vanuatu</option>
   						<option value="Vatican City State">Vatican City State</option>
   						<option value="Venezuela">Venezuela</option>
   						<option value="Vietnam">Vietnam</option>
   						<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
   						<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
   						<option value="Wake Island">Wake Island</option>
   						<option value="Wallis & Futana Is">Wallis & Futana Is</option>
   						<option value="Yemen">Yemen</option>
   						<option value="Zaire">Zaire</option>
   						<option value="Zambia">Zambia</option>
   						<option value="Zimbabwe">Zimbabwe</option>
					</select>
				</div>
				<div class="form_group">
					<input name="f_nacemento" type="date" class="input-text" placeholder=" " required><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="id_nr" type="number" placeholder=" " class="input-text "><label class="form_label">ID nr:</label>
				</div>
                <div class="form_group">
					<input name="wonachtig" type="text" class="input-text" placeholder=" " required><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
                <div class="form_group">
					<input name="adres" type="text" class="input-text" placeholder=" " required><label class="form_label">Adres:</label>
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
					<input name="telefoon_a" type="text" class="input-text" placeholder=" " required><label class="form_label">Telefoon leerling: / Telefoon alumno:</label>
				</div>
                <div class="form_group">
					<input name="telefoon_c" type="text" class="input-text" placeholder=" " required><label class="form_label">Telefoon thuis: / Telefoon di cas:</label>
				</div>
                <div class="form_group">
					<input name="email" type="email" class="input-text" placeholder=" " required><label style="margin-left: 0;margin-right: 0; font-size: .7rem;" class="form_label">Email leerling: / Email di alumno: Geen schoolmail maar persoonlijke mail</label>
				</div>
                <div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<select name="religion">
						<option></option>
						<option value="1">Adventist</option>
						<option value="2">Anglican</option>
						<option value="3">Boedisme</option>
						<option value="4">Christelijk</option>
						<option value="5">Diciple di Jesus</option>
						<option value="6">Evangelist</option>
						<option value="7">Geen</option>
						<option value="8">Gereformeerd</option>
						<option value="9">Hindoeisme</option>
						<option value="10">Islam</option>
						<option value="11">Jehova getuige</option>
						<option value="12">Jodendom</option>
						<option value="13">Mormonen</option>
						<option value="14">Niet van toepassing</option>
						<option value="15">Pentecostal</option>
						<option value="16">Pinkstergemeente</option>
						<option value="17">Protestant</option>
						<option value="18">Rooms Katholiek</option>
					</select>
				</div>
                <div class="form_group">
					<label>Gedoopt: / Batisa:</label>
						<input name="batisa" type="radio" value="1" required><span>ja/si</span>
						<input name="batisa" type="radio" value="2" required><span>nee/no</span>
				</div>
                <div class="form_group">
					<input name="number_azv" type="number" class="input-text" placeholder=" " required><label class="form_label">AZV relatienummer: / Number di AZV:</label>
				</div>
                <div class="form_group">
					<input name="cas" type="text" class="input-text" placeholder=" " required><label class="form_label">Huisarts: / Dokter di cas:</label>
				</div>
                <div class="form_group">
					<input name="medicina" type="text" class="input-text" placeholder=" " required><label class="form_label">Medicijngebruik: / Uzo di medicina:</label>
				</div>
                <div class="form_group">
					<input name="alergia" type="text" class="input-text" placeholder=" " required><label class="form_label">Allergieёn: / Alergia:</label>
				</div>
                <div class="form_group" id="bishita">
					<input name="psycholoog" type="text" class="input-text" placeholder=" " required><label class="form_label">Is/was de leerling onder behandeling van een psycholoog:</label>
				</div>
                <div class="form_group">
					<input name="berkingen" type="text" class="input-text" placeholder=" " required><label class="form_label">Beperkingen: / Limitacionan:</label>
				</div>
                <div class="form_group">
					<input name="leerstoorniseen" type="text" class="input-text" placeholder=" " required><label class="form_label">Leerstoornissen: / Problema di siña:</label>
				</div>
                <div class="form_group">
					<label class="form_label form-select">Thuistaal: / Idioma Materno:</label>
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
					<input name="idioma_otro" type="text" placeholder=" " class="input-text "><label class="form_label">Andere talen: / Otro idioma:</label>
				</div>
			</div>

				<h1>Gegevens ouders / verzorger (voogd)</h1>
				<h2>Vader:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="adres_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Tel thuis: / Tel di cas:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<select name="districto_p" class="" >
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
					<input name="email_p" type="email" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<select name="religion_p">
						<option></option>
						<option value="1">Adventist</option>
						<option value="2">Anglican</option>
						<option value="3">Boedisme</option>
						<option value="4">Christelijk</option>
						<option value="5">Diciple di Jesus</option>
						<option value="6">Evangelist</option>
						<option value="7">Geen</option>
						<option value="8">Gereformeerd</option>
						<option value="9">Hindoeisme</option>
						<option value="10">Islam</option>
						<option value="11">Jehova getuige</option>
						<option value="12">Jodendom</option>
						<option value="13">Mormonen</option>
						<option value="14">Niet van toepassing</option>
						<option value="15">Pentecostal</option>
						<option value="16">Pinkstergemeente</option>
						<option value="17">Protestant</option>
						<option value="18">Rooms Katholiek</option>
					</select>
				</div>
				<div class="form_group">
					<input name="ocupacion_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_p" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>
			</div>
				<h2>Moeder:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="adres_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Tel thuis: / Tel di cas:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<select name="districto_m" class="" >
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
					<input name="email_m" type="email" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<select name="religion_m">
						<option></option>
						<option value="1">Adventist</option>
						<option value="2">Anglican</option>
						<option value="3">Boedisme</option>
						<option value="4">Christelijk</option>
						<option value="5">Diciple di Jesus</option>
						<option value="6">Evangelist</option>
						<option value="7">Geen</option>
						<option value="8">Gereformeerd</option>
						<option value="9">Hindoeisme</option>
						<option value="10">Islam</option>
						<option value="11">Jehova getuige</option>
						<option value="12">Jodendom</option>
						<option value="13">Mormonen</option>
						<option value="14">Niet van toepassing</option>
						<option value="15">Pentecostal</option>
						<option value="16">Pinkstergemeente</option>
						<option value="17">Protestant</option>
						<option value="18">Rooms Katholiek</option>
					</select>
				</div>
				<div class="form_group">
					<input name="ocupacion_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_m" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>
			</div>
				<h2>Voogd:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="relatie" type="text" class="input-text" placeholder=" " ><label class="form_label">Relatie tot de leerling: / Relacion cu e alumno:</label>
				</div>
				<div class="form_group">
					<input name="adres_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon bij noodgeval: / Telefoon di emergencia:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<select name="districto_v" class="" >
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
					<input name="email_v" type="email" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<select name="religion_v">
						<option></option>
						<option value="1">Adventist</option>
						<option value="2">Anglican</option>
						<option value="3">Boedisme</option>
						<option value="4">Christelijk</option>
						<option value="5">Diciple di Jesus</option>
						<option value="6">Evangelist</option>
						<option value="7">Geen</option>
						<option value="8">Gereformeerd</option>
						<option value="9">Hindoeisme</option>
						<option value="10">Islam</option>
						<option value="11">Jehova getuige</option>
						<option value="12">Jodendom</option>
						<option value="13">Mormonen</option>
						<option value="14">Niet van toepassing</option>
						<option value="15">Pentecostal</option>
						<option value="16">Pinkstergemeente</option>
						<option value="17">Protestant</option>
						<option value="18">Rooms Katholiek</option>
					</select>
				</div>
				<div class="form_group">
					<input name="ocupacion_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_v" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>

			</div>

				 <h2>Medishe Gegevens</h2>
			<div class="info">
				<div class="form_group" id="bishita">
					<input name="azv" type="text" class="input-text" placeholder=" " ><label class="form_label">AZV:</label>
				</div>
				<div class="form_group">
					<input name="huisarts" type="text" class="input-text" placeholder=" " ><label class="form_label">Huisarts:</label>
				</div>
				<div class="form_group">
					<input name="telefoon1" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon:</label>
				</div>
				<div class="form_group">
					<input name="tandarts" type="text" class="input-text" placeholder=" " ><label class="form_label">Tandarts:</label>
				</div>
				<div class="form_group">
					<input name="telefoon2" type="text" class="input-text" placeholder=" " ><label class="form_label">Telefoon:</label>
				</div>
				<div class="form_group">
					<input name="medicijngebruik" type="text" class="input-text" placeholder=" " ><label class="form_label">Medicijngebruik:</label>
				</div>
				<div class="form_group">
					<input name="allergieen" type="text" class="input-text" placeholder=" " ><label class="form_label">Allergieen:</label>
				</div>
				<div class="form_group">
					<input name="ruimte" type="text" class="input-text" placeholder=" " ><label class="form_label">Ruimte voor toelichting:</label>
				</div>
				<div class="form_group">
					<input name="logopedist" type="text" class="input-text" placeholder=" " ><label class="form_label">Logopedist:</label>
				</div>
				<div class="form_group">
					<input name="fysiotherapeut" type="text" class="input-text" placeholder=" " ><label class="form_label">Fysiotherapeut:</label>
				</div>
				<div class="form_group">
					<input name="behandeling" type="text" class="input-text" placeholder=" " ><label class="form_label">Behandeling specialist:</label>
				</div>
				<div class="form_group">
					<input name="voor" type="text" class="input-text" placeholder=" " ><label class="form_label">Ontwikkelingsvoorsprong:</label>
				</div>
				<div class="form_group">
					<input name="dyslexie_f" type="text" class="input-text" placeholder=" " ><label class="form_label">Dyslexie in familie:</label>
				</div>
				<div class="form_group">
					<label>Dyslexie:</label>
					<input name="dyslexie" type="radio" value="1" ><span>Ja</span>
					<input name="dyslexie" type="radio" value="2" ><span>Nee</span>
				</div>
				<div class="form_group">
					<label>Dyscalculie:</label>
					<input name="dyscalculie" type="radio" value="1" ><span>Ja</span>
					<input name="dyscalculie" type="radio" value="2" ><span>Nee</span>
				</div>
			</div>

				<h2>Uploaden</h2>
			<div class="info">
				<div class="form_group">
					<label>ID:</label><input name="identiteitsbewijs" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx">
				</div>
				<div class="form_group">
					<label>Cijferlijst SE1, SE2, SE 3 Mavo 4:</label><input name="verklaring" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div>
				<!-- <div class="form_group">
					<label>Verklaring van censo:</label><input name="verklaring" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx">
				</div> -->

				
				<!-- <div class="form_group">
					<label>Download profielkeuze:</label><a href="ajax/profiel_h.xlsb" download="Profiel_HAVO">Download.</a>
				</div> -->
<!-- 				<div class="form_group">
					<label>Upload profielkeuze:</label><input name="profielh3" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div> -->
				<div class="form_group">
					<label>Cijferlijst CSE TV1:</label><input name="klas5" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div>

				
				<div class="form_group">
					<label>Cijferlijst CSE TV2:</label><input name="schooladvies" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div>

<!-- 				
				<div class="form_group">
					<label>Download profielkeuze:</label><a href="ajax/havo4.docx" download="Mon Plaisir College HAVO - VWO Profiel 2023 - 2024">Download.</a>
				</div> -->
				<div class="form_group">
					<label>Profielkeuze Havo:</label><input name="profielh4" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div>
<!-- 				<div class="form_group">
					<label>Uploaden rapport 1 en 2 van havo 3 of Uploaden rapport 1 en 2 van havo 4:</label><input name="klas6" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div> -->

				<!-- <div id="bishita" class="form_group">
					<h1 style="margin: 0;">MAVO 4</h1>
				</div>
				<div class="form_group">
					<label>Download profielkeuze en cijferlijst:</label><a href="ajax/profiel_m.xlsb" download="Profiel_MAVO">Download.</a>
				</div>
				<div class="form_group">
					<label>Upload profielkeuze en cijferlijst:</label><input name="profielm4" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div>
				<div class="form_group">
					<label>Cijferlijsten SE 1, SE 2 en SE 3:</label><input name="mavo4" type="file" accept="image/jpeg,image/jpg,image/png,.pdf,.docx,.xls,.xlsx, .xlsb">
				</div> -->
			</div>

			<div>
				<div class="form_group">
					<label>voor de vakken INF/FIL/CKV-PR geldt dat deze vakken alleen aangeboden kunnen worden als er minstens 15 leerlingen ervoor hebben gekozen.
					Mavo leerlingen worden met hun huidige profiel examenklas aangenomen.
					</label>
				</div>
			</div>

			<div class="info">
				<div class="form_group">
					<label class="form_label form-select">Profiel:</label>
					<select name="profiel" class="" required id="select_profiel">
						<option value=""></option>
						<option value="1">NW01</option>
						<option value="2">NW02</option>
						<option value="3">NW03</option>
						<option value="4">MM01</option>
						<option value="5">MM02</option>
						<option value="6">HU01</option>
						<option value="7">HU02</option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">3:</label>
					<select name="profiel1" id="profiel1" class="" required>
						<option value=""></option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">4:</label>
					<select name="profiel2" id="profiel2" class="" required>
						<option value=""></option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">5:</label>
					<select name="profiel3" id="profiel3" class="" required>
						<option value=""></option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">6:</label>
					<select name="profiel4" id="profiel4" class="" required>
						<option value=""></option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">7:</label>
					<select name="profiel5" id="profiel5" class="" required>
						<option value=""></option>
					</select>
				</div>
			</div>
			<!-- <div>
				<div class="form_group">
					<p>	
					Je moet het excel bestand nogmaals inleveren op:</br>
						1.	Excel bestand en cijferlijst: 20 juni deadline cijfers CE-tijdvak 1 opsturen naar: havompc.aanmelding@gmail.com </br>
					Indien je een herkansing moet maken:</br>
						2.	Excel bestand en cijferlijst: 3 juli deadline cijfers CE-tijdvak 2 opsturen naar: havompc.aanmelding@gmail.com
					</p>
				</div>
			</div> -->

				<h2>Ouder/Verzorger 1:</h2>
			<div class="info">
				<div class="form_group">					
					<input name="naam_o1" type="text" class="input-text" placeholder=" " required><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">	
					<input name="plaats_o1" type="text" class="input-text" placeholder=" " required><label class="form_label">Plaats:</label>
				</div>
				<div class="form_group">
					<input name="datum_o1" type="date" class="input-text" placeholder=" " required><label class="form_label">Datum:</label>
				</div>
			</div>

				<h2>Ouder/Verzorger 2:</h2>
			<div class="info">
				<div class="form_group">					
					<input name="naam_o2" type="text" class="input-text" placeholder=" " required><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">	
					<input name="plaats_o2" type="text" class="input-text" placeholder=" " required><label class="form_label">Plaats:</label>
				</div>
				<div class="form_group">
					<input name="datum_o2" type="date" class="input-text" placeholder=" " required><label class="form_label">Datum:</label>
				</div>
				<input type="text" name="type" value="2" hidden>
				<div class="form_group" id="bishita">
					<input name="afleverende" type="text" class="input-text" placeholder=" " required><label class="form_label">Afleverende school/van welke school komt de leerling:</label>
				</div>
			</div>

			<div>
				<div class="form_group">
					<h3>Declaracion</h3>
					<label>Elke ouder heeft recht op inzage en correctie van onjuiste gegevens in het deel van de leerlingenadministratie dat op zijn of haar kind betrekking heeft.
							Het is zonder toestemming van ouders niet toegestaan dat de school gegevens uit de administratie ter kennis brengt van anderen dan degenen die wettelijk bevoegd zijn inlichtingen omtrent de school en het onderwijs te ontvangen.

							Met het invullen van dit formulier verklaart u dat de uitgangspunten en waarden (de levensbeschouwelijke identiteit) van deze school respecteert (zoals beschreven in de schoolgids). Ook stemt u in met deelname van het kind aan godsdienstonderwijs in schoolverband.

							Ik verklaar dat de gegevens in dit aanmeldingsformulier naar waarheid zijn ingevuld en dat ook de aangehechte bijlagen de werkelijkheid weergeven.</label>
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
</div>
<script type="text/javascript">
	$("#select_profiel").change(function() {
		var selected = $(this).children("option:selected").val();
		switch (selected) {
			case '1': 
				$("#profiel1").html(' <option value=""></option><option value="1">WB</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">SK</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">NA</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">EC</option><option value="2">BI</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">BE</option><option value="2">EC</option><option value="3">INF**</option>');
			break;

			case '2': 
				$("#profiel1").html(' <option value=""></option><option value="1">WA</option><option value="2">WB</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">SK</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">NA</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">BI</option><option value="2">SP</option><option value="3">PAP</option><option value="4">EC</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">BE</option><option value="2">SP</option><option value="3">PAP</option><option value="4">EC</option><option value="5">AK</option><option value="6">GS</option><option value="7">FIL**</option><option value="8">INF**</option>');
			break;

			case '3': 
				$("#profiel1").html(' <option value=""></option><option value="1">WA</option><option value="2">WB</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">SK</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">BI</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">EC</option><option value="2">SP</option><option value="3">PAP</option><option value="4">EC</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">BE</option><option value="2">SP</option><option value="3">CKV-pr**</option><option value="4">EC</option><option value="5">AK</option><option value="6">GS</option><option value="7">FIL**</option><option value="8">INF**</option>');
			break;

			case '4': 
				$("#profiel1").html(' <option value=""></option><option value="1">WA</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">GS</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">AK</option><option value="2">EC</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">SP</option><option value="2">PAP</option><option value="3">BE</option><option value="4">BI</option><option value="5">AK</option><option value="6">EC</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">BE</option><option value="2">SP</option><option value="3">PAP</option><option value="4">BI</option><option value="5">FIL**</option><option value="6">INF**</option><option value="7">CKV-pr**</option>');
			break;

			case '5': 
				$("#profiel1").html(' <option value=""></option><option value="1">WA</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">AK</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">GS</option><option value="2">EC</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">SP</option><option value="2">PAP</option><option value="3">BE</option><option value="4">BI</option><option value="5">GS</option><option value="6">EC</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">BE</option><option value="2">SP</option><option value="3">PAP</option><option value="4">BI</option><option value="5">FIL**</option><option value="6">INF**</option><option value="7">CKV-pr**</option>');
			break;

			case '6': 
				$("#profiel1").html(' <option value=""></option><option value="1">SP</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">CKV-pr**</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">AK</option><option value="2">GS</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">PAP</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">AK</option><option value="2">GS</option><option value="3">FIL**</option>');
			break;

			case '7': 
				$("#profiel1").html(' <option value=""></option><option value="1">SP</option>');
				$("#profiel2").html(' <option value=""></option><option value="1">CKV-pr**</option>');
				$("#profiel3").html(' <option value=""></option><option value="1">AK</option><option value="2">GS</option>');
				$("#profiel4").html(' <option value=""></option><option value="1">WA</option>');
				$("#profiel5").html(' <option value=""></option><option value="1">PAP</option><option value="2">BE</option><option value="3">EC</option><option value="4">GS</option><option value="5">AK</option><option value="6">FIL**</option><option value="7">INF**</option>');
				break;

			default:
			break;
			}
			});
			</script>