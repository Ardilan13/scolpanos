<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');
$schooljaar = $_SESSION["SchoolJaar"];

$studentid = $_POST["opmerking_student_name"];
$klas = $_POST["houding_klassen_lijst"];
$periode = $_POST["periode"];
?>
<style>
    .box_form {
        background-color: white;
    }

    #formulario {
        padding: 0 40px;
        background-color: white;
    }

    .form_box h2 {
        font-size: 30px;
        text-align: center;
        margin-bottom: 20px;
        margin-top: 20px;
        font-weight: 400;
    }

    .form_group {
        margin-bottom: 15px;
    }

    .form_txt .form_group {
        margin: 30px 20px;

    }

    .form_group input {
        margin-right: 5px;
        top: 30px;
    }

    .form_group label {
        margin-right: 10px;
        font-size: 16px;
    }

    .form_group span {
        margin-right: 5px;
        font-family: 'Open Sans', 'sans-serif';
        font-size: 13px;
        line-height: 26px;
        font-weight: 600;
        color: #435670;
    }

    .form_group {
        position: relative;
        color: #4f4f4f;
    }

    .input-text {
        background: none;
        font-size: 1.5rem;
        padding: 1.3em 0.4em;
        width: 250px;
        height: 30px;
        border: 1px solid #1d407a;
        border-radius: 5px;
        width: 100%;
        outline: none;
    }

    .input-text:focus+.form_label,
    .input-text:not(:placeholder-shown)+.form_label {
        transform: translateY(-17px) scale(1.1);
        transform-origin: left top;
        color: #1d407a;
    }

    .form_label {
        font-size: 1rem;
        background-color: white;
        position: absolute;
        top: 0;
        left: 10px;
        transform: translateY(7px);
        transition: transform .5s, color .3s;
    }

    #formulario button {
        margin: 0 20px;
        background-color: yellow;
        font-size: large;
        cursor: pointer;
        padding: 1% 4%;
        border-radius: 7px;
        border: 2px solid black;
    }

    #formulario button:hover {
        background-color: orange;
        transition: 100ms;
    }
</style>
<?php
$query = "SELECT * FROM montessori WHERE student_id = $studentid and klas = '$klas' and period = $periode and schooljaar = '$schooljaar';";
$resultado = mysqli_query($mysqli, $query);
if (mysqli_num_rows($resultado) != 0) {
    $row = mysqli_fetch_assoc($resultado); ?>
    <form id="formulario">
        <div class="form_box">
            <h2>Zelfstandigheid:</h2>
            <div class="form_group">
                <label>Doet mee met schoonmaken / opruimen:</label>
                <input name="zelf1" type="radio" <?php if ($row["zelf1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="zelf1" type="radio" <?php if ($row["zelf1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="zelf1" type="radio" <?php if ($row["zelf1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Zelfstandig handen wassen, neus snuiten en in de elleboog niezen:</label>
                <input name="zelf2" type="radio" <?php if ($row["zelf2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="zelf2" type="radio" <?php if ($row["zelf2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="zelf2" type="radio" <?php if ($row["zelf2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan veters strikken:</label>
                <input name="zelf3" type="radio" <?php if ($row["zelf3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="zelf3" type="radio" <?php if ($row["zelf3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="zelf3" type="radio" <?php if ($row["zelf3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Maakt eigen werkkeuze / kan zelf eigen werk kiezen:</label>
                <input name="zelf4" type="radio" <?php if ($row["zelf4"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="zelf4" type="radio" <?php if ($row["zelf4"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="zelf4" type="radio" <?php if ($row["zelf4"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kiest gevarieerd werkjes van verschillende vakken / gebieden:</label>
                <input name="zelf5" type="radio" <?php if ($row["zelf5"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="zelf5" type="radio" <?php if ($row["zelf5"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="zelf5" type="radio" <?php if ($row["zelf5"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Werkhouding:</h2>

            <div class="form_group">
                <label>Is sterk geïnteresseerd in verschillende vakken:</label>
                <input name="werk1" type="radio" <?php if ($row["werk1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk1" type="radio" <?php if ($row["werk1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk1" type="radio" <?php if ($row["werk1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Werkt geconcentreerd in zijn gekozen werk:</label>
                <input name="werk2" type="radio" <?php if ($row["werk2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk2" type="radio" <?php if ($row["werk2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk2" type="radio" <?php if ($row["werk2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan eigen tempo beheren:</label>
                <input name="werk3" type="radio" <?php if ($row["werk3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk3" type="radio" <?php if ($row["werk3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk3" type="radio" <?php if ($row["werk3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Maakt gekozen werk af:</label>
                <input name="werk4" type="radio" <?php if ($row["werk4"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk4" type="radio" <?php if ($row["werk4"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk4" type="radio" <?php if ($row["werk4"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont interesse in nieuwe lessen:</label>
                <input name="werk5" type="radio" <?php if ($row["werk5"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk5" type="radio" <?php if ($row["werk5"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk5" type="radio" <?php if ($row["werk5"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan een presentatie van nieuw materiaal volgen:</label>
                <input name="werk6" type="radio" <?php if ($row["werk6"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk6" type="radio" <?php if ($row["werk6"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk6" type="radio" <?php if ($row["werk6"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan een les herhalen na de presentie:</label>
                <input name="werk7" type="radio" <?php if ($row["werk7"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk7" type="radio" <?php if ($row["werk7"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk7" type="radio" <?php if ($row["werk7"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan nieuwe informatie onthouden:</label>
                <input name="werk8" type="radio" <?php if ($row["werk8"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="werk8" type="radio" <?php if ($row["werk8"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="werk8" type="radio" <?php if ($row["werk8"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Sociaal Gedrag:</h2>

            <div class="form_group">
                <label>Is bevriend met een gevarieerde groep (kinderen):</label>
                <input name="social1" type="radio" <?php if ($row["social1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social1" type="radio" <?php if ($row["social1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social1" type="radio" <?php if ($row["social1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Doet goed mee met binnen en buiten activiteiten met anderen:</label>
                <input name="social2" type="radio" <?php if ($row["social2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social2" type="radio" <?php if ($row["social2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social2" type="radio" <?php if ($row["social2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Is flexibel in zijn / haar rol in een spel(vb.met altijd de leider willen zijn):</label>
                <input name="social3" type="radio" <?php if ($row["social3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social3" type="radio" <?php if ($row["social3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social3" type="radio" <?php if ($row["social3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Speelt (bewust)voorzichtig met anderen:</label>
                <input name="social4" type="radio" <?php if ($row["social4"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social4" type="radio" <?php if ($row["social4"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social4" type="radio" <?php if ($row["social4"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Wil gesprekken houden met andere leerlingen en groepleiders:</label>
                <input name="social5" type="radio" <?php if ($row["social5"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social5" type="radio" <?php if ($row["social5"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social5" type="radio" <?php if ($row["social5"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Doet actief en constructief mee met groepsleiders:</label>
                <input name="social6" type="radio" <?php if ($row["social6"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social6" type="radio" <?php if ($row["social6"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social6" type="radio" <?php if ($row["social6"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont respect voor de werk en werkruimte van anderen:</label>
                <input name="social7" type="radio" <?php if ($row["social7"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social7" type="radio" <?php if ($row["social7"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social7" type="radio" <?php if ($row["social7"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Lost problemen constructief op:</label>
                <input name="social8" type="radio" <?php if ($row["social8"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social8" type="radio" <?php if ($row["social8"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social8" type="radio" <?php if ($row["social8"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Biedt anderen hulp aan:</label>
                <input name="social9" type="radio" <?php if ($row["social9"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social9" type="radio" <?php if ($row["social9"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social9" type="radio" <?php if ($row["social9"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Deelt informatie uit en geeft lesjes:</label>
                <input name="social10" type="radio" <?php if ($row["social10"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="social10" type="radio" <?php if ($row["social10"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="social10" type="radio" <?php if ($row["social10"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

        </div>

        <div class="form_box">
            <h2>Gedrag:</h2>

            <div class="form_group">
                <label>Kan met eigen sterkte emoties omgaan:</label>
                <input name="gedrag1" type="radio" <?php if ($row["gedrag1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag1" type="radio" <?php if ($row["gedrag1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag1" type="radio" <?php if ($row["gedrag1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont gemiddeld passend emotionele reacties op dagelijkse situaties:</label>
                <input name="gedrag2" type="radio" <?php if ($row["gedrag2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag2" type="radio" <?php if ($row["gedrag2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag2" type="radio" <?php if ($row["gedrag2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Gebruikt / heeft stem modulatie:</label>
                <input name="gedrag3" type="radio" <?php if ($row["gedrag3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag3" type="radio" <?php if ($row["gedrag3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag3" type="radio" <?php if ($row["gedrag3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan wisselen van opdracht / les aan:</label>
                <input name="gedrag4" type="radio" <?php if ($row["gedrag4"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag4" type="radio" <?php if ($row["gedrag4"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag4" type="radio" <?php if ($row["gedrag4"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan zorgvuldig met materiaal omgaan:</label>
                <input name="gedrag5" type="radio" <?php if ($row["gedrag5"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag5" type="radio" <?php if ($row["gedrag5"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag5" type="radio" <?php if ($row["gedrag5"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Houdt zich aan de afgesproken regels:</label>
                <input name="gedrag6" type="radio" <?php if ($row["gedrag6"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="gedrag6" type="radio" <?php if ($row["gedrag6"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="gedrag6" type="radio" <?php if ($row["gedrag6"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Buitenspel: Omgeving(Environment) + Natuur.</h2>

            <div class="form_group">
                <label>Toont interesse in zijn omgeving in de natuur:</label>
                <input name="buit1" type="radio" <?php if ($row["buit1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="buit1" type="radio" <?php if ($row["buit1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="buit1" type="radio" <?php if ($row["buit1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Behandelt dieren voorzichtig / met voorzichtigheid:</label>
                <input name="buit2" type="radio" <?php if ($row["buit2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="buit2" type="radio" <?php if ($row["buit2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="buit2" type="radio" <?php if ($row["buit2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Zorgt voor planten:</label>
                <input name="buit3" type="radio" <?php if ($row["buit3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="buit3" type="radio" <?php if ($row["buit3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="buit3" type="radio" <?php if ($row["buit3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Motoriek</h2>

            <div class="form_group">
                <label>Vertoont voldoende motoriele binnenspel:</label>
                <input name="motor1" type="radio" <?php if ($row["motor1"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="motor1" type="radio" <?php if ($row["motor1"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="motor1" type="radio" <?php if ($row["motor1"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Vertoont voldoende coördinatie tijdens buitenspel:</label>
                <input name="motor2" type="radio" <?php if ($row["motor2"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="motor2" type="radio" <?php if ($row["motor2"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="motor2" type="radio" <?php if ($row["motor2"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Vertoont voldoende fijne motoriek tijdens gebruik van materiaal:</label>
                <input name="motor3" type="radio" <?php if ($row["motor3"] == 1) { ?> checked <?php } ?> value="1"><span>nog niet</span>
                <input name="motor3" type="radio" <?php if ($row["motor3"] == 2) { ?> checked <?php } ?> value="2"><span>soms</span>
                <input name="motor3" type="radio" <?php if ($row["motor3"] == 3) { ?> checked <?php } ?> value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box form_txt">
            <h2>Academisch Verslag</h2>
            <div class="form_group">
                <input <?php if ($row["mondelinge"] != " " && $row["mondelinge"] != null) { ?> value="<?php echo $row['mondelinge']; ?>" <?php } ?> name="mondelinge" class="input-text" type="text"><label class="form_label">Mondelinge taalgebruik:</label>
            </div>
            <!-- <div class="form_group">
                <input <?php if ($row["taalgebruik"] != " " && $row["taalgebruik"] != null) { ?> value="<?php echo $row['taalgebruik']; ?>" <?php } ?> name="taalgebruik" type="text" class="input-text"><label class="form_label">Taalgebruik:</label>
            </div> -->
            <div class="form_group">
                <input <?php if ($row["lezen"] != " " && $row["lezen"] != null) { ?> value="<?php echo $row['lezen']; ?>" <?php } ?> name="lezen" type="text" class="input-text"><label class="form_label">Lezen:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["schrijven"] != " " && $row["schrijven"] != null) { ?> value="<?php echo $row['schrijven']; ?>" <?php } ?> name="schrijven" type="text" class="input-text"><label class="form_label">Schrijven:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["rekenen"] != " " && $row["rekenen"] != null) { ?> value="<?php echo $row['rekenen']; ?>" <?php } ?> name="rekenen" type="text" class="input-text"><label class="form_label">Rekenen:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["wereldorientatie"] != " " && $row["wereldorientatie"] != null) { ?> value="<?php echo $row['wereldorientatie']; ?>" <?php } ?> name="wereldorientatie" type="text" class="input-text"><label class="form_label">Wereldoriëntatie:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["verkeer"] != " " && $row["verkeer"] != null) { ?> value="<?php echo $row['verkeer']; ?>" <?php } ?> name="verkeer" type="text" class="input-text"><label class="form_label">Verkeer:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["muziek"] != " " && $row["muziek"] != null) { ?> value="<?php echo $row['muziek']; ?>" <?php } ?> name="muziek" type="text" class="input-text"><label class="form_label">Muziek:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["art"] != " " && $row["art"] != null) { ?> value="<?php echo $row['art']; ?>" <?php } ?> name="art" type="text" class="input-text"><label class="form_label">Art:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["gymnastiek"] != " " && $row["gymnastiek"] != null) { ?> value="<?php echo $row['gymnastiek']; ?>" <?php } ?> name="gymnastiek" type="text" class="input-text"><label class="form_label">Gymnastiek:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["zwemmen"] != " " && $row["zwemmen"] != null) { ?> value="<?php echo $row['zwemmen']; ?>" <?php } ?> name="zwemmen" type="text" class="input-text"><label class="form_label">Zwemmen:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["laat"] != " " && $row["laat"] != null) { ?> value="<?php echo $row['laat']; ?>" <?php } ?> name="laat" type="text" class="input-text"><label class="form_label">Te laat:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["verzuim"] != " " && $row["verzuim"] != null) { ?> value="<?php echo $row['verzuim']; ?>" <?php } ?> name="verzuim" type="text" class="input-text"><label class="form_label">Verzuim:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["sociale"] != " " && $row["sociale"] != null) { ?> value="<?php echo $row['sociale']; ?>" <?php } ?> name="sociale" type="text" class="input-text"><label class="form_label">Sociale vaardigheden en karakter:</label>
            </div>
            <div class="form_group">
                <input <?php if ($row["conclusie"] != " " && $row["conclusie"] != null) { ?> value="<?php echo $row['conclusie']; ?>" <?php } ?> name="conclusie" type="text" class="input-text"><label class="form_label">Conclusie:</label>
            </div>
            <input name="id" id="id" type="text" hidden value="<?php echo $studentid; ?>">
            <input name="klas" id="klas" type="text" hidden value="<?php echo $klas; ?>">
            <input name="periode" id="periode" type="text" hidden value="<?php echo $periode; ?>">
            <input name="status" id="status" type="text" hidden value="<?php echo $row["status"]; ?>">
            <input name="exist" type="text" hidden value="1">
        </div>
        <button class="submit_form">UPDATE</button>
        <button class="finish_form">FINISH</button>
    </form>
<?php } else { ?>
    <form id="formulario">
        <div class="form_box">
            <h2>Zelfstandigheid:</h2>
            <div class="form_group">
                <label>Doet mee met schoonmaken / opruimen:</label>
                <input name="zelf1" type="radio" value="1"><span>nog niet</span>
                <input name="zelf1" type="radio" value="2"><span>soms</span>
                <input name="zelf1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Zelfstandig handen wassen, neus snuiten en in de elleboog niezen:</label>
                <input name="zelf2" type="radio" value="1"><span>nog niet</span>
                <input name="zelf2" type="radio" value="2"><span>soms</span>
                <input name="zelf2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan veters strikken:</label>
                <input name="zelf3" type="radio" value="1"><span>nog niet</span>
                <input name="zelf3" type="radio" value="2"><span>soms</span>
                <input name="zelf3" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Maakt eigen werkkeuze / kan zelf eigen werk kiezen:</label>
                <input name="zelf4" type="radio" value="1"><span>nog niet</span>
                <input name="zelf4" type="radio" value="2"><span>soms</span>
                <input name="zelf4" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kiest gevarieerd werkjes van verschillende vakken / gebieden:</label>
                <input name="zelf5" type="radio" value="1"><span>nog niet</span>
                <input name="zelf5" type="radio" value="2"><span>soms</span>
                <input name="zelf5" type="radio" value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Werkhouding:</h2>

            <div class="form_group">
                <label>Is sterk geïnteresseerd in verschillende vakken:</label>
                <input name="werk1" type="radio" value="1"><span>nog niet</span>
                <input name="werk1" type="radio" value="2"><span>soms</span>
                <input name="werk1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Werkt geconcentreerd in zijn gekozen werk:</label>
                <input name="werk2" type="radio" value="1"><span>nog niet</span>
                <input name="werk2" type="radio" value="2"><span>soms</span>
                <input name="werk2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan eigen tempo beheren:</label>
                <input name="werk3" type="radio" value="1"><span>nog niet</span>
                <input name="werk3" type="radio" value="2"><span>soms</span>
                <input name="werk3" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Maakt gekozen werk af:</label>
                <input name="werk4" type="radio" value="1"><span>nog niet</span>
                <input name="werk4" type="radio" value="2"><span>soms</span>
                <input name="werk4" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont interesse in nieuwe lessen:</label>
                <input name="werk5" type="radio" value="1"><span>nog niet</span>
                <input name="werk5" type="radio" value="2"><span>soms</span>
                <input name="werk5" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan een presentatie van nieuw materiaal volgen:</label>
                <input name="werk6" type="radio" value="1"><span>nog niet</span>
                <input name="werk6" type="radio" value="2"><span>soms</span>
                <input name="werk6" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan een les herhalen na de presentie:</label>
                <input name="werk7" type="radio" value="1"><span>nog niet</span>
                <input name="werk7" type="radio" value="2"><span>soms</span>
                <input name="werk7" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan nieuwe informatie onthouden:</label>
                <input name="werk8" type="radio" value="1"><span>nog niet</span>
                <input name="werk8" type="radio" value="2"><span>soms</span>
                <input name="werk8" type="radio" value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Sociaal Gedrag:</h2>

            <div class="form_group">
                <label>Is bevriend met een gevarieerde groep (kinderen):</label>
                <input name="social1" type="radio" value="1"><span>nog niet</span>
                <input name="social1" type="radio" value="2"><span>soms</span>
                <input name="social1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Doet goed mee met binnen en buiten activiteiten met anderen:</label>
                <input name="social2" type="radio" value="1"><span>nog niet</span>
                <input name="social2" type="radio" value="2"><span>soms</span>
                <input name="social2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Is flexibel in zijn / haar rol in een spel(vb.met altijd de leider willen zijn):</label>
                <input name="social3" type="radio" value="1"><span>nog niet</span>
                <input name="social3" type="radio" value="2"><span>soms</span>
                <input name="social3" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Speelt (bewust)voorzichtig met anderen:</label>
                <input name="social4" type="radio" value="1"><span>nog niet</span>
                <input name="social4" type="radio" value="2"><span>soms</span>
                <input name="social4" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Wil gesprekken houden met andere leerlingen en groepleiders:</label>
                <input name="social5" type="radio" value="1"><span>nog niet</span>
                <input name="social5" type="radio" value="2"><span>soms</span>
                <input name="social5" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Doet actief en constructief mee met groepsleiders:</label>
                <input name="social6" type="radio" value="1"><span>nog niet</span>
                <input name="social6" type="radio" value="2"><span>soms</span>
                <input name="social6" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont respect voor de werk en werkruimte van anderen:</label>
                <input name="social7" type="radio" value="1"><span>nog niet</span>
                <input name="social7" type="radio" value="2"><span>soms</span>
                <input name="social7" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Lost problemen constructief op:</label>
                <input name="social8" type="radio" value="1"><span>nog niet</span>
                <input name="social8" type="radio" value="2"><span>soms</span>
                <input name="social8" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Biedt anderen hulp aan:</label>
                <input name="social9" type="radio" value="1"><span>nog niet</span>
                <input name="social9" type="radio" value="2"><span>soms</span>
                <input name="social9" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Deelt informatie uit en geeft lesjes:</label>
                <input name="social10" type="radio" value="1"><span>nog niet</span>
                <input name="social10" type="radio" value="2"><span>soms</span>
                <input name="social10" type="radio" value="3"><span>vaak</span>
            </div>

        </div>

        <div class="form_box">
            <h2>Gedrag:</h2>

            <div class="form_group">
                <label>Kan met eigen sterkte emoties omgaan:</label>
                <input name="gedrag1" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag1" type="radio" value="2"><span>soms</span>
                <input name="gedrag1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Toont gemiddeld passend emotionele reacties op dagelijkse situaties:</label>
                <input name="gedrag2" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag2" type="radio" value="2"><span>soms</span>
                <input name="gedrag2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Gebruikt / heeft stem modulatie:</label>
                <input name="gedrag3" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag3" type="radio" value="2"><span>soms</span>
                <input name="gedrag3" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan wisselen van opdracht / les aan:</label>
                <input name="gedrag4" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag4" type="radio" value="2"><span>soms</span>
                <input name="gedrag4" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Kan zorgvuldig met materiaal omgaan:</label>
                <input name="gedrag5" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag5" type="radio" value="2"><span>soms</span>
                <input name="gedrag5" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Houdt zich aan de afgesproken regels:</label>
                <input name="gedrag6" type="radio" value="1"><span>nog niet</span>
                <input name="gedrag6" type="radio" value="2"><span>soms</span>
                <input name="gedrag6" type="radio" value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Buitenspel: Omgeving(Environment) + Natuur.</h2>

            <div class="form_group">
                <label>Toont interesse in zijn omgeving in de natuur:</label>
                <input name="buit1" type="radio" value="1"><span>nog niet</span>
                <input name="buit1" type="radio" value="2"><span>soms</span>
                <input name="buit1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Behandelt dieren voorzichtig / met voorzichtigheid:</label>
                <input name="buit2" type="radio" value="1"><span>nog niet</span>
                <input name="buit2" type="radio" value="2"><span>soms</span>
                <input name="buit2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Zorgt voor planten:</label>
                <input name="buit3" type="radio" value="1"><span>nog niet</span>
                <input name="buit3" type="radio" value="2"><span>soms</span>
                <input name="buit3" type="radio" value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box">
            <h2>Motoriek</h2>

            <div class="form_group">
                <label>Vertoont voldoende motoriele binnenspel:</label>
                <input name="motor1" type="radio" value="1"><span>nog niet</span>
                <input name="motor1" type="radio" value="2"><span>soms</span>
                <input name="motor1" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Vertoont voldoende coördinatie tijdens buitenspel:</label>
                <input name="motor2" type="radio" value="1"><span>nog niet</span>
                <input name="motor2" type="radio" value="2"><span>soms</span>
                <input name="motor2" type="radio" value="3"><span>vaak</span>
            </div>

            <div class="form_group">
                <label>Vertoont voldoende fijne motoriek tijdens gebruik van materiaal:</label>
                <input name="motor3" type="radio" value="1"><span>nog niet</span>
                <input name="motor3" type="radio" value="2"><span>soms</span>
                <input name="motor3" type="radio" value="3"><span>vaak</span>
            </div>
        </div>

        <div class="form_box form_txt">
            <h2>Academisch Verslag</h2>
            <div class="form_group">
                <input name="mondelinge" class="input-text" type="text"><label class="form_label">Mondelinge taalgebruik:</label>
            </div>
            <!-- <div class="form_group">
                <input name="taalgebruik" type="text" class="input-text"><label class="form_label">Taalgebruik:</label>
            </div> -->
            <div class="form_group">
                <input name="lezen" type="text" class="input-text"><label class="form_label">Lezen:</label>
            </div>
            <div class="form_group">
                <input name="schrijven" type="text" class="input-text"><label class="form_label">Schrijven:</label>
            </div>
            <div class="form_group">
                <input name="rekenen" type="text" class="input-text"><label class="form_label">Rekenen:</label>
            </div>
            <div class="form_group">
                <input name="wereldorientatie" type="text" class="input-text"><label class="form_label">Wereldoriëntatie:</label>
            </div>
            <div class="form_group">
                <input name="verkeer" type="text" class="input-text"><label class="form_label">Verkeer:</label>
            </div>
            <div class="form_group">
                <input name="muziek" type="text" class="input-text"><label class="form_label">Muziek:</label>
            </div>
            <div class="form_group">
                <input name="art" type="text" class="input-text"><label class="form_label">Art:</label>
            </div>
            <div class="form_group">
                <input name="gymnastiek" type="text" class="input-text"><label class="form_label">Gymnastiek:</label>
            </div>
            <div class="form_group">
                <input name="zwemmen" type="text" class="input-text"><label class="form_label">Zwemmen:</label>
            </div>
            <div class="form_group">
                <input name="laat" type="text" class="input-text"><label class="form_label">Te laat:</label>
            </div>
            <div class="form_group">
                <input name="verzuim" type="text" class="input-text"><label class="form_label">Verzuim:</label>
            </div>
            <div class="form_group">
                <input name="sociale" type="text" class="input-text"><label class="form_label">Sociale vaardigheden en karakter:</label>
            </div>
            <div class="form_group">
                <input name="conclusie" type="text" class="input-text"><label class="form_label">Conclusie:</label>
            </div>
            <input name="id" type="text" hidden value="<?php echo $studentid; ?>">
            <input name="klas" type="text" hidden value="<?php echo $klas; ?>">
            <input name="periode" type="text" hidden value="<?php echo $periode; ?>">
            <input name="exist" type="text" hidden value="0">

        </div>
        <button class="submit_form">CREATE</button>
    </form>
<?php } ?>
<script>
    $(document).ready(function() {
        if ($("#status").val() == 1) {
            $(".submit_form").hide()
            $(".finish_form").hide()
            $("input").attr("disabled", true);
        }
    });

    $(".submit_form").click(function(e) {
        e.preventDefault()
        $.ajax({
            url: "ajax/insert_montessori.php",
            data: $('#formulario').serialize(),
            type: "POST",
            dataType: "text",
            success: function(text) {
                if (text == 1) {
                    alert("STUDENT FORM CREATED")
                } else if (text == 2) {
                    alert("STUDENT FORM UPDATED")
                } else {
                    alert("ERROR FORM")
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    })

    $(".finish_form").click(function(e) {
        e.preventDefault()
        $(".submit_form").click()
        id = $("#id").val()
        klas = $("#klas").val()
        periode = $("#periode").val()
        $.ajax({
            url: "ajax/insert_montessori.php",
            data: {
                finish: 1,
                id: id,
                klas: klas,
                periode: periode
            },
            type: "POST",
            dataType: "text",
            success: function(text) {
                if (text == 1) {
                    alert("STUDENT FORM FINISH")
                } else if (text == 2) {
                    alert("THE FORM MUST BE COMPLETE")
                } else {
                    alert("ERROR FORM")
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    })
</script>