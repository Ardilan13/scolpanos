<?php
require_once "DBCreds.php";

session_start();

class spn_montessori
{
    function print_montessori($student, $klas, $period)
    {
        $DBCreds = new DBCreds();
        date_default_timezone_set("America/Aruba");
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $schooljaar = $_SESSION["SchoolJaar"];
        $query = "SELECT * FROM montessori WHERE student_id = $student AND period = $period AND klas = '$klas' AND schooljaar = '$schooljaar'";
        $resultado123 = mysqli_query($mysqli, $query);
        if ($resultado123) {
            $row = mysqli_fetch_assoc($resultado123);
            $print = '
        <style>
            .montessori td,th {
                border: black 1px solid;
                }
            .montessori{
                width:100%;
                }
            .centrado{
                text-align:center;
                font-size: 15px;
                }
        </style>
        <table class="montessori">
        <thead>
            <th style="width:75%;">ZELFSTANDIGHEID</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Doet mee met schoonmaken / opruimen</td>
                <td class="centrado">'. (($row["zelf1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Zelfstandig handen wassen, neus snuiten en in de elleboog niezen</td>
                <td class="centrado">'. (($row["zelf2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan veters strikken</td>
                <td class="centrado">'. (($row["zelf3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf3"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Maakt eigen werkkeuze / kan zelf eigen werk kiezen</td>
                <td class="centrado">'. (($row["zelf4"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf4"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf4"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kiest gevarieerd werkjes van verschillende vakken / gebieden</td>
                <td class="centrado">'. (($row["zelf5"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf5"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["zelf5"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <br>
    <table class="montessori">
        <thead>
            <th style="width:75%;">WERKHOUDING</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Is sterk geïnteresseerd in verschillende vakken</td>
                <td class="centrado">'. (($row["werk1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Werkt geconcentreerd in zijn gekozen werk</td>
                <td class="centrado">'. (($row["werk2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan eigen tempo beheren</td>
                <td class="centrado">'. (($row["werk3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk3"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Maakt gekozen werk af</td>
                <td class="centrado">'. (($row["werk4"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk4"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk4"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Toont interesse  in nieuwe lessen</td>
                <td class="centrado">'. (($row["werk5"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk5"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk5"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan een presentatie van nieuw materiaal volgen</td>
                <td class="centrado">'. (($row["werk6"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk6"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk6"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan een les herhalen na de presentie</td>
                <td class="centrado">'. (($row["werk7"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk7"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk7"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan nieuwe informatie onthouden</td>
                <td class="centrado">'. (($row["werk8"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk8"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["werk8"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <br>
    <table class="montessori">
        <thead>
            <th style="width:75%;">SOCIAAL GEDRAG</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Is bevriend met een gevarieerde groep (kinderen)</td>
                <td class="centrado">'. (($row["social1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Doet goed mee met binnen en buiten activiteiten met anderen</td>
                <td class="centrado">'. (($row["social2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Is flexibel in zijn / haar rol in een spel</td>
                <td class="centrado">'. (($row["social3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social3"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Speelt (bewust)voorzichtig met anderen</td>
                <td class="centrado">'. (($row["social4"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social4"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social4"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Wil gesprekken houden met andere leerlingen en groepleiders</td>
                <td class="centrado">'. (($row["social5"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social5"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social5"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Doet actief en constructief mee met groepsleiders</td>
                <td class="centrado">'. (($row["social6"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social6"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social6"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Toont respect voor de werk en werkruimte van anderen</td>
                <td class="centrado">'. (($row["social7"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social7"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social7"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Lost problemen constructief op</td>
                <td class="centrado">'. (($row["social8"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social8"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social8"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Biedt anderen hulp aan</td>
                <td class="centrado">'. (($row["social9"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social9"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social9"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Deelt informatie uit en geeft lesjes</td>
                <td class="centrado">'. (($row["social0"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["social0"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["social0"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <br>
    <table class="montessori">
        <thead>
            <th style="width:75%;">GEDRAG</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Kan met eigen sterkte emoties omgaan</td>
                <td class="centrado">'. (($row["gedrag1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Toont gemiddeld passend emotionele reacties op dagelijkse situaties</td>
                <td class="centrado">'. (($row["gedrag2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Gebruikt / heeft stem modulatie</td>
                <td class="centrado">'. (($row["gedrag3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag3"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan wisselen van opdracht / les aan</td>
                <td class="centrado">'. (($row["gedrag4"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag4"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag4"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Kan zorgvuldig met materiaal omgaan</td>
                <td class="centrado">'. (($row["gedrag5"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag5"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag5"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Houdt zich aan de afgesproken regels</td>
                <td class="centrado">'. (($row["gedrag6"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag6"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["gedrag6"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <br>
    <table class="montessori">
        <thead>
            <th style="width:75%;">BUITENSPEL</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Toont interesse in zijn omgeving in de natuur</td>
                <td class="centrado">'. (($row["buit1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Behandelt dieren voorzichtig / met voorzichtigheid</td>
                <td class="centrado">'. (($row["buit2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Zorgt voor planten</td>
                <td class="centrado">'. (($row["buit3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["buit3"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <br>
    <table class="montessori">
        <thead>
            <th style="width:75%;">MOTORIEK</th>
            <th>nog niet</th>
            <th>soms</th>
            <th>vaak</th>
        </thead>
        <tbody>
            <tr>
                <td>*Vertoont voldoende motoriele binnenspel</td>
                <td class="centrado">'. (($row["motor1"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor1"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor1"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Vertoont voldoende coördinatie tijdens buitenspel</td>
                <td class="centrado">'. (($row["motor2"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor2"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor2"]==3)?'X':"") .'</td>
            </tr>
            <tr>
                <td>*Vertoont voldoende fijne motoriek tijdens gebruik van materiaal</td>
                <td class="centrado">'. (($row["motor3"]==1)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor3"]==2)?'X':"") .'</td>
                <td class="centrado">'. (($row["motor3"]==3)?'X':"") .'</td>
            </tr>
        </tbody>
    </table class="montessori">
    <table class="montessori">
        <thead>
            <th colspan="2">ACADEMISCH VERSLAG</th>
        </thead>
        <tbody>
            <tr>
                <td>Mondelinge taalgebruik:</td>
                <td class="centrado">'. $row["mondelinge"] .'</td>
            </tr>
            <tr>
                <td>Lezen:</td>
                <td class="centrado">'. $row["lezen"] .'</td>
            </tr>
            <tr>
                <td>Schrijven:</td>
                <td class="centrado">'. $row["schrijven"] .'</td>
            </tr>
            <tr>
                <td>Rekenen:</td>
                <td class="centrado">'. $row["rekenen"] .'</td>
            </tr>
            <tr>
                <td>Wereldoriëntatie:</td>
                <td class="centrado">'. $row["wereldorientatie"] .'</td>
            </tr>
            <tr>
                <td>Verkeer:</td>
                <td class="centrado">'. $row["verkeer"] .'</td>
            </tr>
            <tr>
                <td>Muziek:</td>
                <td class="centrado">'. $row["muziek"] .'</td>
            </tr>
            <tr>
                <td>Art:</td>
                <td class="centrado">'. $row["art"] .'</td>
            </tr>
            <tr>
                <td>Gymnastiek:</td>
                <td class="centrado">'. $row["gymnastiek"] .'</td>
            </tr>
            <tr>
                <td>Zwemmen:</td>
                <td class="centrado">'. $row["zwemmen"] .'</td>
            </tr>
            <tr>
                <td>Te laat:</td>
                <td class="centrado">'. $row["laat"] .'</td>
            </tr>
            <tr>
                <td>Verzuim:</td>
                <td class="centrado">'. $row["verzuim"] .'</td>
            </tr>
            <tr>
                <td>Sociale vaardigheden en karakter:</td>
                <td class="centrado">'. $row["sociale"] .'</td>
            </tr>
            <tr>
                <td>Conclusie:</td>
                <td class="centrado">'. $row["conclusie"] .'</td>
            </tr>
        </tbody>
    </table class="montessori">';
        }
        return $print;
    }
}
