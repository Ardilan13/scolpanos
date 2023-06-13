<!-- Ardilan: formulario abierto -->
<?php require_once "config/app.config.php";
ob_start();
ob_flush();
require_once "classes/DBCreds.php";
require_once "config/app.config.php";

$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$id = $_POST["id"];
$ID = $_GET["ID"];
$scol = $_POST["scol"];

if ($id != null && $id != "") {
	$query = "SELECT * FROM aplicacion_college WHERE ID = '$id'";
} else {
	$query = "SELECT * FROM aplicacion_college WHERE ID = '$ID'";
}
$resultado = mysqli_query($mysqli, $query);
if (mysqli_num_rows($resultado) != 0) {
	$row = mysqli_fetch_assoc($resultado); ?>

	<head>
		<title>AANMELDINGSFORMULIER</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<script src="components/jquery/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">

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
			<form action="ajax/college_update.php" method="POST" id="formulario">
				<h1>Aanmeldingsformulier Schooljaar 2023-2024</h1>
                <h3>Mon Plaisir College Havo</h3>
				<h4>Havo 4</h4>

					<div class="form_group">
					<label class="form_label form-select">Ik meld mijn kind aan voor:</label>
						<select name="meld" class="" required>
							<option value="1" <?php $row['meld'] == 1 ? ' selected' : '' ?>>CB2</option>
							<option value="2" <?php $row['meld'] == 2 ? ' selected' : '' ?>>HAVO 3</option>
							<option value="3" <?php $row['meld'] == 3 ? ' selected' : '' ?>>VWO 3</option>
							<option value="4" <?php $row['meld'] == 4 ? ' selected' : '' ?>>Havo 4</option>
						</select>
					</div>
				<h2>Personalia:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam" type="text" value="<?php echo $row["naam"]; ?>" class="input-text" placeholder=" "  required><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen" type="text" value="<?php echo $row["voornamen"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
                <div class="form_group">
					<input name="roepnaam" type="text" value="<?php echo $row["roepnaam"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Roepnaam: / Nomber comun:</label>
				</div>
				<div class="form_group">
					<label>Geslacht: / Sexo:</label>
					<?php if ($row["sexo"] == 1) { ?>
                        <input name="sexo" type="radio" value="1" required checked><span>M</span>
                        <input name="sexo" type="radio" value="2" required><span>F</span>
                    <?php } else if ($row["sexo"] == 2) { ?>
						<input name="sexo" type="radio" value="1" required><span>M</span>
						<input name="sexo" type="radio" value="2" required checked><span>F</span>
                    <?php } ?>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Geboorteland: / Pais di Nacemento:</label>
                    <select name="geboorteland">
						<option value="<?php echo $row["geboorteland"]; ?>"><?php echo $row["geboorteland"]; ?></option>
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
						<option value="<?php echo $row["nacionalidad"]; ?>"><?php echo $row["nacionalidad"]; ?></option>
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
					<input name="f_nacemento" type="date" value="<?php echo $row["f_nacemento"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="id_nr" type="number" value="<?php echo $row["id_nr"]; ?>" placeholder=" " class="input-text "><label class="form_label">ID nr:</label>
				</div>
                <div class="form_group">
					<input name="wonachtig" type="text" value="<?php echo $row["wonachtig"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
                <div class="form_group">
					<input name="adres" type="text" value="<?php echo $row["adres"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Adres:</label>
				</div>
                <div class="form_group">
					<label class="form_label form-select">Districto:</label>
					<?php if ($row["districto"] == 1) {
						$dis = "Noord";
					} else if ($row["districto"] == 2) {
						$dis = "Oranjestad";
					} else if ($row["districto"] == 3) {
						$dis = "Paradera";
					} else if ($row["districto"] == 4) {
						$dis = "San Nicolaas";
					} else if ($row["districto"] == 5) {
						$dis = "Santa Cruz";
					} else if ($row["districto"] == 6) {
						$dis = "Savaneta";
					} ?>
					<select name="districto" class="" required>
						<option value="<?php echo $row["districto"] ?>"> <?php echo $dis; ?> </option>
						<option value="1">Noord</option>
						<option value="2">Oranjestad</option>
						<option value="3">Paradera</option>
						<option value="4">San Nicolaas</option>
						<option value="5">Santa Cruz</option>
						<option value="6">Savaneta</option>
					</select>
				</div>
                <div class="form_group">
					<input name="telefoon_a" type="text" value="<?php echo $row["telefoon_a"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Telefoon leerling: / Telefoon alumno:</label>
				</div>
                <div class="form_group">
					<input name="telefoon_c" type="text" value="<?php echo $row["telefoon_c"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Telefoon thuis: / Telefoon di cas:</label>
				</div>
                <div class="form_group">
					<input name="email" type="email" value="<?php echo $row["email"]; ?>" class="input-text" placeholder=" " required><label style="margin-left: 0;margin-right: 0; font-size: .7rem;" class="form_label">Email leerling: / Email di alumno: Geen schoolmail maar persoonlijke mail</label>
				</div>
                <div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<?php if ($row["religion"] == 1) {
						$rel = "Adventist";
					} else if ($row["religion"] == 2) {
						$rel = "Anglican";
					} else if ($row["religion"] == 3) {
						$rel = "Boedisme";
					} else if ($row["religion"] == 4) {
						$rel = "Christelijk";
					} else if ($row["religion"] == 5) {
						$rel = "Diciple di Jesus";
					} else if ($row["religion"] == 6) {
						$rel = "Evangelist";
					} else if ($row["religion"] == 7) {
						$rel = "Geen";
					} else if ($row["religion"] == 8) {
						$rel = "Gereformeerd";
					} else if ($row["religion"] == 9) {
						$rel = "Hindoeisme";
					} else if ($row["religion"] == 10) {
						$rel = "Islam";
					} else if ($row["religion"] == 11) {
						$rel = "Jehova getuige";
					} else if ($row["religion"] == 12) {
						$rel = "Jodendom";
					} else if ($row["religion"] == 13) {
						$rel = "Mormonen";
					} else if ($row["religion"] == 14) {
						$rel = "Niet van toepassing";
					} else if ($row["religion"] == 15) {
						$rel = "Pentecostal";
					} else if ($row["religion"] == 16) {
						$rel = "Pinkstergemeente";
					} else if ($row["religion"] == 17) {
						$rel = "Protestant";
					} else if ($row["religion"] == 18) {
						$rel = "Rooms Katholiek";
					} ?>
					<select name="religion">
						<option value="<?php echo $row["religion"] ?>"> <?php echo $rel; ?> </option>
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
						<option value="18">Geen</option>
					</select>
				</div>
                <div class="form_group">
					<label>Gedoopt: / Batisa:</label>
						<?php if ($row["batisa"] == 1) { ?>
							<input name="batisa" type="radio" value="1" required checked><span>ja/si</span>
							<input name="batisa" type="radio" value="2" required><span>nee/no</span>
                    	<?php } else if ($row["batisa"] == 2) { ?>
							<input name="batisa" type="radio" value="1" required><span>ja/si</span>
							<input name="batisa" type="radio" value="2" required checked><span>nee/no</span>
                    	<?php } ?>
				</div>
                <div class="form_group">
					<input name="number_azv" type="number" value="<?php echo $row["number_azv"]; ?>" class="input-text" placeholder=" " required><label class="form_label">AZV relatienummer: / Number di AZV:</label>
				</div>
                <div class="form_group">
					<input name="cas" type="text" value="<?php echo $row["cas"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Huisarts: / Dokter di cas:</label>
				</div>
                <div class="form_group">
					<input name="medicina" type="text" value="<?php echo $row["medicina"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Medicijngebruik: / Uzo di medicina:</label>
				</div>
                <div class="form_group">
					<input name="alergia" type="text" value="<?php echo $row["alergia"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Allergieёn: / Alergia:</label>
				</div>
                <div class="form_group" id="bishita">
					<input name="psycholoog" type="text" value="<?php echo $row["psycholoog"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Is/was de leerling onder behandeling van een psycholoog:</label>
				</div>
                <div class="form_group">
					<input name="berkingen" type="text" value="<?php echo $row["berkingen"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Beperkingen: / Limitacionan:</label>
				</div>
                <div class="form_group">
					<input name="leerstoorniseen" type="text" value="<?php echo $row["leerstoorniseen"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Leerstoornissen: / Problema di siña:</label>
				</div>
                <div class="form_group">
					<label class="form_label form-select">Thuistaal: / Idioma Materno:</label>
					<?php if ($row["idioma"] == 1) {
						$idi = "Papiamento";
					} else if ($row["idioma"] == 2) {
						$idi = "Nederlands";
					} else if ($row["idioma"] == 3) {
						$idi = "Español";
					} else if ($row["idioma"] == 4) {
						$idi = "English";
					} else {
						$idi = "Otro";
					} ?>
					<select name="idioma" class="" required>
						<option value="<?php echo $row["idioma"]; ?>"> <?php echo $idi; ?> </option>
						<option value="1">Papiamento</option>
						<option value="2">Nederlands</option>
						<option value="3">Español</option>
						<option value="4">English</option>
						<option value="5">Otro</option>
					</select>
				</div>
				<div class="form_group">
					<?php if ($row["idioma"] == '0' || $row["idioma"] == '1' || $row["idioma"] == '2' || $row["idioma"] == '3' || $row["idioma"] == '4') { ?>
						<input name="idioma_otro" type="text"  placeholder=" " class="input-text "><label class="form_label">Andere talen: / Otro idioma:</label>
                	<?php } else { ?>
                    	<input name="idioma_otro" type="text"  placeholder=" " class="input-text " value="<?php echo $row["idioma"]; ?>"><label class="form_label">Andere talen: / Otro idioma:</label>
                	<?php } ?>
				</div>
			</div>

				<h1>Gegevens ouders / verzorger (voogd)</h1>
				<h2>Vader:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_p" type="text" value="<?php echo $row["naam_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_p" type="text" value="<?php echo $row["voornamen_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="adres_p" type="text" value="<?php echo $row["adres_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_p" type="text" value="<?php echo $row["celular_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_p" type="text" value="<?php echo $row["tel_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Tel thuis: / Tel di cas:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_p" type="text" value="<?php echo $row["geboorteland_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_p" type="text" value="<?php echo $row["nacionalidad_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_p" type="text" value="<?php echo $row["bibando_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<?php if ($row["districto_p"] == 1) {
						$dis_p = "Noord";
					} else if ($row["districto_p"] == 2) {
						$dis_p = "Oranjestad";
					} else if ($row["districto_p"] == 3) {
						$dis_p = "Paradera";
					} else if ($row["districto_p"] == 4) {
						$dis_p = "San Nicolaas";
					} else if ($row["districto_p"] == 5) {
						$dis_p = "Santa Cruz";
					} else if ($row["districto_p"] == 6) {
						$dis_p = "Savaneta";
					} ?>
					<select name="districto_p" class="" >
						<option value="<?php echo $row["districto_p"]; ?>"> <?php echo $dis_p; ?></option>
						<option value="1">Noord</option>
						<option value="2">Oranjestad</option>
						<option value="3">Paradera</option>
						<option value="4">San Nicolaas</option>
						<option value="5">Santa Cruz</option>
						<option value="6">Savaneta</option>
					</select>
				</div>
				<div class="form_group">
					<input name="email_p" type="email" value="<?php echo $row["email_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<?php if ($row["religion_p"] == 1) {
						$rel_p = "Adventist";
					} else if ($row["religion_p"] == 2) {
						$rel_p = "Anglican";
					} else if ($row["religion_p"] == 3) {
						$rel_p = "Boedisme";
					} else if ($row["religion_p"] == 4) {
						$rel_p = "Christelijk";
					} else if ($row["religion_p"] == 5) {
						$rel_p = "Diciple di Jesus";
					} else if ($row["religion_p"] == 6) {
						$rel_p = "Evangelist";
					} else if ($row["religion_p"] == 7) {
						$rel_p = "Geen";
					} else if ($row["religion_p"] == 8) {
						$rel_p = "Gereformeerd";
					} else if ($row["religion_p"] == 9) {
						$rel_p = "Hindoeisme";
					} else if ($row["religion_p"] == 10) {
						$rel_p = "Islam";
					} else if ($row["religion_p"] == 11) {
						$rel_p = "Jehova getuige";
					} else if ($row["religion_p"] == 12) {
						$rel_p = "Jodendom";
					} else if ($row["religion_p"] == 13) {
						$rel_p = "Mormonen";
					} else if ($row["religion_p"] == 14) {
						$rel_p = "Niet van toepassing";
					} else if ($row["religion_p"] == 15) {
						$rel_p = "Pentecostal";
					} else if ($row["religion_p"] == 16) {
						$rel_p = "Pinkstergemeente";
					} else if ($row["religion_p"] == 17) {
						$rel_p = "Protestant";
					} else if ($row["religion_p"] == 18) {
						$rel_p = "Rooms Katholiek";
					} ?>
					<select name="religion_p">
					<option value="<?php echo $row["religion_p"] ?>"> <?php echo $rel_p; ?> </option>
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
					<input name="ocupacion_p" type="text" value="<?php echo $row["ocupacion_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_p" type="text" value="<?php echo $row["werkzaam_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_p" type="text" value="<?php echo $row["teltra_p"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>
			</div>
				<h2>Moeder:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_m" type="text" value="<?php echo $row["naam_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_m" type="text" value="<?php echo $row["voornamen_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="adres_m" type="text" value="<?php echo $row["adres_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_m" type="text" value="<?php echo $row["celular_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_m" type="text" value="<?php echo $row["tel_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Tel thuis: / Tel di cas:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_m" type="text" value="<?php echo $row["geboorteland_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_m" type="text" value="<?php echo $row["nacionalidad_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_m" type="text" value="<?php echo $row["bibando_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<?php if ($row["districto_m"] == 1) {
						$dis_m = "Noord";
					} else if ($row["districto_m"] == 2) {
						$dis_m = "Oranjestad";
					} else if ($row["districto_m"] == 3) {
						$dis_m = "Paradera";
					} else if ($row["districto_m"] == 4) {
						$dis_m = "San Nicolaas";
					} else if ($row["districto_m"] == 5) {
						$dis_m = "Santa Cruz";
					} else if ($row["districto_m"] == 6) {
						$dis_m = "Savaneta";
					} ?>
					<select name="districto_m" class="" >
						<option value="<?php echo $row["districto_m"]; ?>"> <?php echo $dis_m; ?></option>
						<option value="1">Noord</option>
						<option value="2">Oranjestad</option>
						<option value="3">Paradera</option>
						<option value="4">San Nicolaas</option>
						<option value="5">Santa Cruz</option>
						<option value="6">Savaneta</option>
					</select>
				</div>
				<div class="form_group">
					<input name="email_m" type="email" value="<?php echo $row["email_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<?php if ($row["religion_m"] == 1) {
						$rel_m = "Adventist";
					} else if ($row["religion_m"] == 2) {
						$rel_m = "Anglican";
					} else if ($row["religion_m"] == 3) {
						$rel_m = "Boedisme";
					} else if ($row["religion_m"] == 4) {
						$rel_m = "Christelijk";
					} else if ($row["religion_m"] == 5) {
						$rel_m = "Diciple di Jesus";
					} else if ($row["religion_m"] == 6) {
						$rel_m = "Evangelist";
					} else if ($row["religion_m"] == 7) {
						$rel_m = "Geen";
					} else if ($row["religion_m"] == 8) {
						$rel_m = "Gereformeerd";
					} else if ($row["religion_m"] == 9) {
						$rel_m = "Hindoeisme";
					} else if ($row["religion_m"] == 10) {
						$rel_m = "Islam";
					} else if ($row["religion_m"] == 11) {
						$rel_m = "Jehova getuige";
					} else if ($row["religion_m"] == 12) {
						$rel_m = "Jodendom";
					} else if ($row["religion_m"] == 13) {
						$rel_m = "Mormonen";
					} else if ($row["religion_m"] == 14) {
						$rel_m = "Niet van toepassing";
					} else if ($row["religion_m"] == 15) {
						$rel_m = "Pentecostal";
					} else if ($row["religion_m"] == 16) {
						$rel_m = "Pinkstergemeente";
					} else if ($row["religion_m"] == 17) {
						$rel_m = "Protestant";
					} else if ($row["religion_m"] == 18) {
						$rel_m = "Rooms Katholiek";
					} ?>
					<select name="religion_m">
						<option value="<?php echo $row["religion_m"] ?>"> <?php echo $rel_m; ?> </option>
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
					<input name="ocupacion_m" type="text" value="<?php echo $row["ocupacion_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_m" type="text" value="<?php echo $row["werkzaam_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_m" type="text" value="<?php echo $row["teltra_m"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>
			</div>

			<h2>Voogd:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_v" type="text" value="<?php echo $row["naam_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_v" type="text" value="<?php echo $row["voornamen_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
					<input name="relatie" type="text" value="<?php echo $row["relatie"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Relatie tot de leerling: / Relacion cu e alumno:</label>
				</div>
				<div class="form_group">
					<input name="adres_v" type="text" class="input-text" value="<?php echo $row["adres_v"]; ?>" placeholder=" " ><label class="form_label">Adres: / Adres:</label>
				</div>
				<div class="form_group">
					<input name="celular_v" type="text" value="<?php echo $row["celular_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Mobiel: / Cellular:</label>
				</div>
				<div class="form_group">
					<input name="tel_v" type="text" value="<?php echo $row["tel_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon bij noodgeval: / Telefoon di emergencia:</label>
				</div>
				<div class="form_group">
					<input name="geboorteland_v" type="text" value="<?php echo $row["geboorteland_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboorteland: / Pais di Nacemento:</label>
				</div>
				<div class="form_group">
					<input name="nacionalidad_v" type="text" value="<?php echo $row["nacionalidad_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Nationaliteit: / Nacionalidad:</label>
				</div>
				<div class="form_group">
					<input name="bibando_v" type="text" value="<?php echo $row["bibando_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Woonachtig op Aruba sinds: / Bibando na Aruba for di:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">District: / Districto:</label>
					<?php if ($row["districto_v"] == 1) {
						$dis_v = "Noord";
					} else if ($row["districto_v"] == 2) {
						$dis_v = "Oranjestad";
					} else if ($row["districto_v"] == 3) {
						$dis_v = "Paradera";
					} else if ($row["districto_v"] == 4) {
						$dis_v = "San Nicolaas";
					} else if ($row["districto_v"] == 5) {
						$dis_v = "Santa Cruz";
					} else if ($row["districto_v"] == 6) {
						$dis_v = "Savaneta";
					} ?>
					<select name="districto_v" class="" >
						<option value="<?php echo $row["districto_v"]; ?>"> <?php echo $dis_v; ?></option>
						<option value="1">Noord</option>
						<option value="2">Oranjestad</option>
						<option value="3">Paradera</option>
						<option value="4">San Nicolaas</option>
						<option value="5">Santa Cruz</option>
						<option value="6">Savaneta</option>
					</select>
				</div>
				<div class="form_group">
					<input name="email_v" type="email" value="<?php echo $row["email_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Email:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Religie / Religion:</label>
					<?php if ($row["religion_v"] == 1) {
						$rel_v = "Adventist";
					} else if ($row["religion_v"] == 2) {
						$rel_v = "Anglican";
					} else if ($row["religion_v"] == 3) {
						$rel_v = "Boedisme";
					} else if ($row["religion_v"] == 4) {
						$rel_v = "Christelijk";
					} else if ($row["religion_v"] == 5) {
						$rel_v = "Diciple di Jesus";
					} else if ($row["religion_v"] == 6) {
						$rel_v = "Evangelist";
					} else if ($row["religion_v"] == 7) {
						$rel_v = "Geen";
					} else if ($row["religion_v"] == 8) {
						$rel_v = "Gereformeerd";
					} else if ($row["religion_v"] == 9) {
						$rel_v = "Hindoeisme";
					} else if ($row["religion_v"] == 10) {
						$rel_v = "Islam";
					} else if ($row["religion_v"] == 11) {
						$rel_v = "Jehova getuige";
					} else if ($row["religion_v"] == 12) {
						$rel_v = "Jodendom";
					} else if ($row["religion_v"] == 13) {
						$rel_v = "Mormonen";
					} else if ($row["religion_v"] == 14) {
						$rel_v = "Niet van toepassing";
					} else if ($row["religion_v"] == 15) {
						$rel_v = "Pentecostal";
					} else if ($row["religion_v"] == 16) {
						$rel_v = "Pinkstergemeente";
					} else if ($row["religion_v"] == 17) {
						$rel_v = "Protestant";
					} else if ($row["religion_v"] == 18) {
						$rel_v = "Rooms Katholiek";
					} ?>
					<select name="religion_v">
						<option value="<?php echo $row["religion_v"] ?>"> <?php echo $rel_v; ?> </option>
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
					<input name="ocupacion_v" type="text" value="<?php echo $row["ocupacion_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Beroep / Ocupacion:</label>
				</div>
				<div class="form_group">
					<input name="werkzaam_v" type="text" value="<?php echo $row["werkzaam_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Werkzaam bij / Ta traha na:</label>
				</div>
				<div class="form_group">
					<input name="teltra_v" type="text" value="<?php echo $row["teltra_v"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon op het werk / Telefon na trabou:</label>
				</div>

			</div>

			<!-- Inscripcion escuela primaria: --> 			

			<!-- <h2>Gezinsgegevens:</h2>
			<div class="info">
				<div class="form_group">
					<input name="naam_g1" type="text" value="<?php echo $row["naam_g1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g1" type="text" value="<?php echo $row["voornamen_g1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g1"] == 1) { ?>
							<input name="broer_g1" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g1" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g1"] == 2) { ?>
							<input name="broer_g1" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g1" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } else { ?>
							<input name="broer_g1" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g1" type="radio" value="2" ><span>Zus</span>
						<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g1" type="date" value="<?php echo $row["geboortedatum_g1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_g2" type="text" value="<?php echo $row["naam_g2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g2" type="text" value="<?php echo $row["voornamen_g2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g2"] == 1) { ?>
							<input name="broer_g2" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g2" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g2"] == 2) { ?>
							<input name="broer_g2" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g2" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } else { ?>
							<input name="broer_g2" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g2" type="radio" value="2" ><span>Zus</span>
						<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g2" type="date" value="<?php echo $row["geboortedatum_g2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_g3" type="text" value="<?php echo $row["naam_g3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g3" type="text" value="<?php echo $row["voornamen_g3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g3"] == 1) { ?>
							<input name="broer_g3" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g3" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g3"] == 2) { ?>
							<input name="broer_g3" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g3" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } else { ?>
							<input name="broer_g3" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g3" type="radio" value="2" ><span>Zus</span>
						<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g3" type="date" value="<?php echo $row["geboortedatum_g3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_g4" type="text" value="<?php echo $row["naam_g4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g4" type="text" value="<?php echo $row["voornamen_g4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g4"] == 1) { ?>
							<input name="broer_g4" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g4" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g4"] == 2) { ?>
							<input name="broer_g4" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g4" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } else { ?>
							<input name="broer_g4" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g4" type="radio" value="2" ><span>Zus</span>
						<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g4" type="date" value="<?php echo $row["geboortedatum_g4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_g5" type="text" value="<?php echo $row["naam_g5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g5" type="text" value="<?php echo $row["voornamen_g5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g5"] == 1) { ?>
							<input name="broer_g5" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g5" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g5"] == 2) { ?>
							<input name="broer_g5" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g5" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g5" type="date" value="<?php echo $row["geboortedatum_g5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_g6" type="text" value="<?php echo $row["naam_g6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam: / Fam:</label>
				</div>
				<div class="form_group">
					<input name="voornamen_g6" type="text" value="<?php echo $row["voornamen_g6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Voornamen voluit: / Nombernan completo:</label>
				</div>
				<div class="form_group">
						<?php if ($row["broer_g6"] == 1) { ?>
							<input name="broer_g6" type="radio" value="1"  checked><span>Broer</span>
							<input name="broer_g6" type="radio" value="2" ><span>Zus</span>
                    	<?php } else if ($row["broer_g6"] == 2) { ?>
							<input name="broer_g6" type="radio" value="1" ><span>Broer</span>
							<input name="broer_g6" type="radio" value="2"  checked><span>Zus</span>
                    	<?php } ?>
				</div>
				<div class="form_group">
					<input name="geboortedatum_g6" type="date" value="<?php echo $row["geboortedatum_g6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Geboortedatum: / Fecha di Nacemento:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group" id="bishita">
					<input name="gesproken" type="text" value="<?php echo $row["gesproken"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Welke taal wordt thuis gesproken?</label>
				</div>
				<div class="form_group" id="bishita">
					<input name="informatie" type="text" value="<?php echo $row["informatie"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Andere informatie over de gezinssituatie die van belang is:</label>
				</div>
			</div>

				<h2>Scol di ruman(nan):</h2>
				<div class="info">
				<div class="form_group">
					<input name="naam_b1" type="text" value="<?php echo $row["naam_b1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b1"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b1"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b1"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b1"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b1"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b1">
					<option value="<?php echo $row["basisschool_b1"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b1" type="text" value="<?php echo $row["school_b1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b1" type="text" value="<?php echo $row["klas_b1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_b2" type="text" value="<?php echo $row["naam_b2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b2"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b2"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b2"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b2"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b2"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b2">
					<option value="<?php echo $row["basisschool_b2"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b2" type="text" value="<?php echo $row["school_b2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b2" type="text" value="<?php echo $row["klas_b2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group"> 
					<input name="naam_b3" type="text" value="<?php echo $row["naam_b3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b3"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b3"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b3"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b3"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b3"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b3">
					<option value="<?php echo $row["basisschool_b3"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b3" type="text" value="<?php echo $row["school_b3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b3" type="text" value="<?php echo $row["klas_b3"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_b4" type="text" value="<?php echo $row["naam_b4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b4"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b4"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b4"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b4"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b4"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b4">
					<option value="<?php echo $row["basisschool_b4"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b4" type="text" value="<?php echo $row["school_b4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b4" type="text" value="<?php echo $row["klas_b4"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_b5" type="text" value="<?php echo $row["naam_b5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b5"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b5"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b5"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b5"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b5"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b5">
					<option value="<?php echo $row["basisschool_b5"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b5" type="text" value="<?php echo $row["school_b5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b5" type="text" value="<?php echo $row["klas_b5"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group">
					<input name="naam_b6" type="text" value="<?php echo $row["naam_b6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">
					<label class="form_label form-select">Scol:</label>
					<?php if ($row["basisschool_b6"] == 1) {
						$scol_b = "Peuter";
					} else if ($row["basisschool_b6"] == 2) {
						$scol_b = "Kleuter";
					} else if ($row["basisschool_b6"] == 3) {
						$scol_b = "Basisschool";
					} else if ($row["basisschool_b6"] == 4) {
						$scol_b = "Voortgezets";
					} else if ($row["basisschool_b6"] == 5) {
						$scol_b = "Onderwijs";
					} else {
						$scol_b = "";
					} ?>
					<select name="basisschool_b6">
					<option value="<?php echo $row["basisschool_b6"]; ?>"> <?php echo $scol_b; ?></option>
						<option value="1">Peuter</option>
						<option value="2">Kleuter</option>
						<option value="3">Basisschool</option>
						<option value="4">Voortgezet</option>
						<option value="5">Onderwijs</option>
					</select>
				</div>
				<div class="form_group">
					<input name="school_b6" type="text" value="<?php echo $row["school_b6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Naam van de school:</label>
				</div>
				<div class="form_group">
					<input name="klas_b6" type="text" value="<?php echo $row["klas_b6"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Klas:</label>
				</div>
			</div>
			<div class="info info-top">
				<div class="form_group" id="bishita">
					<input name="relevant" type="text" value="<?php echo $row["relevant"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Informatie die relevant is voor een goede start op school:</label>
				</div>
			</div> -->

				<!-- <h2>Medishe Gegevens</h2>
			<div class="info">
				<div class="form_group" id="bishita">
					<input name="azv" type="text" value="<?php echo $row["azv"]; ?>" class="input-text" placeholder=" " ><label class="form_label">AZV:</label>
				</div>
				<div class="form_group">
					<input name="huisarts" type="text" value="<?php echo $row["huisarts"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Huisarts:</label>
				</div>
				<div class="form_group">
					<input name="telefoon1" type="text" value="<?php echo $row["telefoon1"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon:</label>
				</div>
				<div class="form_group">
					<input name="tandarts" type="text" value="<?php echo $row["tandarts"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Tandarts:</label>
				</div>
				<div class="form_group">
					<input name="telefoon2" type="text" value="<?php echo $row["telefoon2"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Telefoon:</label>
				</div>
				<div class="form_group">
					<input name="medicijngebruik" type="text" value="<?php echo $row["medicijngebruik"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Medicijngebruik:</label>
				</div>
				<div class="form_group">
					<input name="allergieen" type="text" value="<?php echo $row["allergieen"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Allergieen:</label>
				</div>
				<div class="form_group">
					<input name="ruimte" type="text" value="<?php echo $row["ruimte"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Ruimte voor toelichting:</label>
				</div>
				<div class="form_group">
					<input name="logopedist" type="text" value="<?php echo $row["logopedist"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Logopedist:</label>
				</div>
				<div class="form_group">
					<input name="fysiotherapeut" type="text" value="<?php echo $row["fysiotherapeut"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Fysiotherapeut:</label>
				</div>
				<div class="form_group">
					<input name="behandeling" type="text" value="<?php echo $row["behandeling"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Behandeling specialist:</label>
				</div>
				<div class="form_group">
					<input name="voor" type="text" value="<?php echo $row["voor"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Ontwikkelingsvoorsprong:</label>
				</div>
				<div class="form_group">
					<input name="dyslexie_f" type="text" value="<?php echo $row["dyslexie_f"]; ?>" class="input-text" placeholder=" " ><label class="form_label">Dyslexie in familie:</label>
				</div>
				<div class="form_group">
					<label>Dyslexie:</label>
						<?php if ($row["dyslexie"] == 1) { ?>
							<input name="dyslexie" type="radio" value="1"  checked><span>Ja</span>
							<input name="dyslexie" type="radio" value="2" ><span>Nee</span>
                    	<?php } else if ($row["dyslexie"] == 2) { ?>
							<input name="dyslexie" type="radio" value="1" ><span>Ja</span>
							<input name="dyslexie" type="radio" value="2"  checked><span>Nee</span>
                    	<?php } ?>
				</div>
				<div class="form_group">
					<label>Dyscalculie:</label>
						<?php if ($row["dyscalculie"] == 1) { ?>
							<input name="dyscalculie" type="radio" value="1"  checked><span>Ja</span>
							<input name="dyscalculie" type="radio" value="2" ><span>Nee</span>
                    	<?php } else if ($row["dyscalculie"] == 2) { ?>
							<input name="dyscalculie" type="radio" value="1" ><span>Ja</span>
							<input name="dyscalculie" type="radio" value="2"  checked><span>ZNeeus</span>
                    	<?php } ?>
				</div>
			</div> -->

			<h2>Uploaden</h2>
			<div class="info">
				<div class="form_group">
					<?php if ($row["identiteitsbewijs"] != null && $row["identiteitsbewijs"] != "") { ?>
						<label>ID:</label><a href="ajax/<?php echo $row["identiteitsbewijs"]; ?>" download="id_<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>ID: <b>Empty.</b></label>
					<?php } ?>
				</div>
				<!-- <div class="form_group">
					<?php if ($row["verklaring"] != null && $row["verklaring"] != "") { ?>
						<label>Verklaring van censo:</label><a href="ajax/<?php echo $row["verklaring"]; ?>" download="verklaring_<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Verklaring van censo: <b>Empty.</b></label>
					<?php } ?>
				</div> -->

				<div class="form_group">
					<?php if ($row["verklaring"] != null && $row["verklaring"] != "") { ?>
						<label>Cijferlijst SE1, SE2, SE 3 Mavo 4:</label><a href="ajax/<?php echo $row["verklaring"]; ?>" download="se1_se2_se3_m4<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Cijferlijst SE1, SE2, SE 3 Mavo 4: <b>Empty.</b></label>
					<?php } ?>
				</div>


				<!-- <div class="form_group">
					<?php if ($row["profielh3"] != null && $row["profielh3"] != "") { ?>
						<label>Download profielkeuze:</label><a href="ajax/<?php echo $row["profielh3"]; ?>" download="Profiel_H3<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Download profielkeuze: <b>Empty.</b></label>
					<?php } ?>
				</div> -->
				<div class="form_group">
					<?php if ($row["klas5"] != null && $row["klas5"] != "") { ?>
						<label>Cijferlijst CSE TV1:</label><a href="ajax/<?php echo $row["klas5"]; ?>" download="Cijferlist_tv1<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Cijferlijst CSE TV1: <b>Empty.</b></label>
					<?php } ?>
				</div>

				<div class="form_group">
					<?php if ($row["schooladvies"] != null && $row["schooladvies"] != "") { ?>
						<label>Cijferlijst CSE TV2:</label><a href="ajax/<?php echo $row["schooladvies"]; ?>" download="Cijferlist_tv2<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Cijferlijst CSE TV2: <b>Empty.</b></label>
					<?php } ?>
				</div>

				<div class="form_group">
					<?php if ($row["profielh4"] != null && $row["profielh4"] != "") { ?>
						<label>Download profielkeuze havo:</label><a href="ajax/<?php echo $row["profielh4"]; ?>" download="Profiel_H4_<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Download profielkeuze havo: <b>Empty.</b></label>
					<?php } ?>
				</div>

				<!-- <div id="bishita" class="form_group">
					<h1 style="margin: 0;">MAVO 4</h1>
				</div>
				<div class="form_group">
					<?php if ($row["profielm4"] != null && $row["profielm4"] != "") { ?>
						<label>Download profielkeuze en cijferlijst:</label><a href="ajax/<?php echo $row["profielm4"]; ?>" download="Profiel_M4<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Download profielkeuze en cijferlijst: <b>Empty.</b></label>
					<?php } ?>
				</div>
				<div class="form_group">
					<?php if ($row["mavo4"] != null && $row["mavo4"] != "") { ?>
						<label>Cijferlijsten SE 1, SE 2 en SE 3:</label><a href="ajax/<?php echo $row["mavo4"]; ?>" download="Cijferlijsten_SE_<?php echo $row["voornamen"]; ?>_<?php echo $row["naam"]; ?>_<?php echo $id; ?>">Download.</a>
					<?php } else { ?>
						<label>Cijferlijsten SE 1, SE 2 en SE 3: <b>Empty.</b></label>
					<?php } ?>
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
					<select disabled name="profiel" class="" required id="select_profiel">
						<option value="1" <?php $row['profiel'] == 1 ? ' selected' : '' ?>>NW01</option>
						<option value="2" <?php $row['profiel'] == 2 ? ' selected' : '' ?>>NW02</option>
						<option value="3" <?php $row['profiel'] == 3 ? ' selected' : '' ?>>NW03</option>
						<option value="4" <?php $row['profiel'] == 4 ? ' selected' : '' ?>>MM01</option>
						<option value="5" <?php $row['profiel'] == 5 ? ' selected' : '' ?>>MM02</option>
						<option value="6" <?php $row['profiel'] == 6 ? ' selected' : '' ?>>HU01</option>
						<option value="7" <?php $row['profiel'] == 7 ? ' selected' : '' ?>>HU02</option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">3:</label>
					<select disabled name="profiel1" id="profiel1" class="" required>
						<option value="<?php echo $row['profiel1']; ?>">
                            <?php
							switch ($row['profiel']) {
								case 1:
									echo 'WB';
									break;
								case 2:
								case 3:
									echo $row['profiel1'] != 1 ? 'WB' : 'WA';
									break;
								case 4:
								case 5:
									echo 'WA';
									break;
								case 6:
								case 7:
									echo 'SP';
									break;
								default:
									break;
							}
							?>
                        </option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">4:</label>
					<select disabled name="profiel2" id="profiel2" class="" required>
						<option value="<?php echo $row['profiel2']; ?>">
                            <?php
							switch ($row['profiel']) {
								case 1:
								case 2:
								case 3:
									echo 'SK';
									break;
								case 4:
									echo 'GS';
									break;
								case 5:
									echo 'AK';
									break;
								case 6:
								case 7:
									echo 'CKV-pr**';
									break;
								default:
									break;
							}
							?>
                        </option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">5:</label>
					<select disabled name="profiel3" id="profiel3" class="" required>
						<option value="<?php echo $row['profiel3']; ?>">
                            <?php
							switch ($row['profiel']) {
								case 1:
								case 2:
									echo 'NA';
									break;
								case 3:
									echo 'BI';
									break;
								case 4:
									echo $row['profiel3'] == 1 ? 'AK' : 'EC';
									break;
								case 5:
									echo $row['profiel3'] == 1 ? 'GS' : 'EC';
									break;
								case 6:
								case 7:
									echo $row['profiel3'] == 1 ? 'AK' : 'GS';
									break;
								default:
									break;
							}
							?>
                        </option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">6:</label>
					<select disabled name="profiel4" id="profiel4" class="" required>
						<option value="<?php echo $row['profiel4']; ?>">
                            <?php
							switch ($row['profiel']) {
								case 1:
									echo $row['profiel4'] == 1 ? 'EC' : 'BI';
									break;
								case 2:
									if ($row['profiel4'] == 1) {
										echo 'BI';
									} else {
										echo $row['profiel4'] == 2 ? 'SP' : 'PAP';
									}
									break;
								case 3:
									if ($row['profiel4'] == 1) {
										echo 'EC';
									} else if ($row['profiel4'] == 2) {
										echo 'SP';
									} else {
										echo $row['profiel4'] == 3 ? 'PAP' : 'EC';
									}
									break;
								case 4:
									if ($row['profiel4'] == 1) {
										echo 'SP';
									} else if ($row['profiel4'] == 2) {
										echo 'PAP';
									} else if ($row['profiel4'] == 3) {
										echo 'BE';
									} else if ($row['profiel4'] == 4) {
										echo 'BI';
									} else {
										echo $row['profiel4'] == 5 ? 'AK' : 'EC';
									}
									break;
								case 5:
									if ($row['profiel4'] == 1) {
										echo 'SP';
									} else if ($row['profiel4'] == 2) {
										echo 'PAP';
									} else if ($row['profiel4'] == 3) {
										echo 'BE';
									} else if ($row['profiel4'] == 4) {
										echo 'BI';
									} else {
										echo $row['profiel4'] == 5 ? 'GS' : 'EC';
									}
									break;
								case 6:
									echo 'PAP';
									break;
								case 7:
									echo 'WA';
									break;
								default:
									break;
							}
							?>
                        </option>
					</select>
				</div>
				<div class="form_group">
					<label class="form_label form-select">7:</label>
					<select disabled name="profiel5" id="profiel5" class="" required>
						<option value="<?php echo $row['profiel5']; ?>">
                            <?php
							switch ($row['profiel']) {
								case 1:
									if ($row['profiel5'] == 1) {
										echo 'BE';
									} else if ($row['profiel5'] == 2) {
										echo 'EC';
									} else {
										echo 'INF**';
									}
									break;
								case 2:
									if ($row['profiel5'] == 1) {
										echo 'BE';
									} else if ($row['profiel5'] == 2) {
										echo 'SP';
									} else if ($row['profiel5'] == 3) {
										echo 'PAP';
									} else if ($row['profiel5'] == 4) {
										echo 'EC';
									} else if ($row['profiel5'] == 5) {
										echo 'AK';
									} else if ($row['profiel5'] == 6) {
										echo 'GS';
									} else {
										echo $row['profiel5'] == 7 ? 'FIL**' : 'INF**';
									}
									break;
								case 3:
									if ($row['profiel5'] == 1) {
										echo 'BE';
									} else if ($row['profiel5'] == 2) {
										echo 'SP';
									} else if ($row['profiel5'] == 3) {
										echo 'CKV-pr**';
									} else if ($row['profiel5'] == 4) {
										echo 'EC';
									} else if ($row['profiel5'] == 5) {
										echo 'AK';
									} else if ($row['profiel5'] == 6) {
										echo 'GS';
									} else {
										echo $row['profiel5'] == 7 ? 'FIL**' : 'INF**';
									}
									break;
								case 4:
								case 5:
									if ($row['profiel5'] == 1) {
										echo 'BE';
									} else if ($row['profiel5'] == 2) {
										echo 'SP';
									} else if ($row['profiel5'] == 3) {
										echo 'PAP';
									} else if ($row['profiel5'] == 4) {
										echo 'BI';
									} else if ($row['profiel5'] == 5) {
										echo 'FIL**';
									} else if ($row['profiel5'] == 6) {
										echo 'INF**';
									} else {
										echo 'CKV-pr**';
									}
									break;
								case 6:
									if ($row['profiel5'] == 1) {
										echo 'AK';
									} else if ($row['profiel5'] == 2) {
										echo 'GS';
									} else if ($row['profiel5'] == 3) {
										echo 'FIL**';
									}
									break;
								case 7:
									if ($row['profiel5'] == 1) {
										echo 'PAP';
									} else if ($row['profiel5'] == 2) {
										echo 'BE';
									} else if ($row['profiel5'] == 3) {
										echo 'EC';
									} else if ($row['profiel5'] == 4) {
										echo 'GS';
									} else if ($row['profiel5'] == 5) {
										echo 'AK';
									} else if ($row['profiel5'] == 6) {
										echo 'FIL**';
									} else {
										echo 'INF**';
									}
									break;
								default:
									break;
							}
							?>
                        </option>
					</select>
				</div>
			</div>

				<h2>Ouder/Verzorger 1:</h2>
			<div class="info">
				<div class="form_group">					
					<input name="naam_o1" type="text" value="<?php echo $row["naam_o1"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">	
					<input name="plaats_o1" type="text" value="<?php echo $row["plaats_o1"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Plaats:</label>
				</div>
				<div class="form_group">
					<input name="datum_o1" type="date" value="<?php echo $row["datum_o1"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Datum:</label>
				</div>
			</div>

				<h2>Ouder/Verzorger 2:</h2>
			<div class="info">
				<div class="form_group">					
					<input name="naam_o2" type="text" value="<?php echo $row["naam_o2"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Naam:</label>
				</div>
				<div class="form_group">	
					<input name="plaats_o2" type="text" value="<?php echo $row["plaats_o2"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Plaats:</label>
				</div>
				<div class="form_group">
					<input name="datum_o2" type="date" value="<?php echo $row["datum_o2"]; ?>" class="input-text" placeholder=" " required><label class="form_label">Datum:</label>
				</div>
				<div class="form_group" id="bishita">
					<input name="afleverende" type="text" class="input-text" value="<?php echo $row["afleverende"]; ?>" placeholder=" " required><label class="form_label">Afleverende school/van welke school komt de leerling:</label>
				</div>
			</div>

				<h2>Info:</h2>
			<div class="info">
                <div class="form_group">
                    <label>Status:</label>
                <?php if ($row["status"] == 2) { ?>
                    <input name="status" type="radio" value="1"  ><span>Accepted</span>
					<input name="status" type="radio" value="2"  checked><span>Rejected</span>
                <?php } else if ($row["status"] == '1') { ?>
                    <input name="status" type="radio" value="1"  checked><span>Accepted</span>
					<input name="status" type="radio" value="2" ><span>Rejected</span>
                <?php } else { ?>
                    <input name="status" type="radio" value="1"  ><span>Accepted</span>
					<input name="status" type="radio" value="2"  ><span>Rejected</span>
                <?php } ?>
                </div>

            </div>
                <input type="number" name="id" hidden value="<?php echo $id; ?>">
				<div><button type="submit">SEND</button></div>
			</form>
		</div>
</div>

</main>
<div class="qwihi">
		<a href="https://qwihi.com"><img id="logo" src="img/QwihiLogo.png" alt="qwihi" width="150px"></a>
		<label id="qwihi">Your Imagination is our Creation.</label>
</div>

<?php } else {
	echo $query;
} ?>