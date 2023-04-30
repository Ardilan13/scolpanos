<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$week = $_POST["planner_week"];
$user = $_POST["user"];
$schooljaar = $_SESSION["SchoolJaar"]; ?>

<style>
    #table_planner,
    #table_planner th {
        border: 2px solid black;
    }

    #table_planner {
        width: 100%;
        height: 50rem;
    }

    .dias {
        width: 100%;
        height: 99%;
        border: 2px solid black;
        padding: 1%;
    }

    .title {
        background-color: skyblue;
        text-align: center;
        width: 15%;
        font-size: medium;
    }

    .group {
        display: flex;
        justify-content: space-around;
        margin-top: 3%;
    }

    .form_label {
        padding-right: 5%;
        font-size: medium;
        font-weight: 700;
    }

    .form_group {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .input-text {
        border: 1px solid black;
        border-radius: 5px;
        height: 5rem;
        width: 80%;
        font-size: 16px;
    }

    .klas {
        width: 30%;
    }

    .submit_form {
        margin: 3% 45% 0;
        background-color: yellow;
        cursor: pointer;
        padding: 1% 2%;
        border-radius: 7px;
        border: 1px solid black;
    }

    .submit_form:hover {
        background-color: orange;
        transition: 100ms;
    }
</style>

<?php $query = "SELECT * FROM planning WHERE user = '$user' and week = '$week' and schooljaar = '$schooljaar';";
$resultado = mysqli_query($mysqli, $query);
if (mysqli_num_rows($resultado) != 0) {
    $row = mysqli_fetch_assoc($resultado); ?>
    <form id="planning">
        <table id="table_planner">
            <tr>
                <th class="title">DIALUNA</th>
                <th><textarea class="dias" type="text" name="dialuna" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>> <?php echo $row["dialuna"]; ?> </textarea></th>
            </tr>
            <tr>
                <th class="title">DIAMARS</th>
                <th><textarea class="dias" type="text" name="diamars" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>><?php echo $row["diamars"]; ?></textarea></th>
            </tr>
            <tr>
                <th class="title">DIARANSON</th>
                <th><textarea class="dias" type="text" name="diaranson" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>><?php echo $row["diaranson"]; ?></textarea></th>
            </tr>
            <tr>
                <th class="title">DIAHUEBS</th>
                <th><textarea class="dias" type="text" name="diahuebs" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>><?php echo $row["diahuebs"]; ?></textarea></th>
            </tr>
            <tr>
                <th class="title">DIABIERNA</th>
                <th><textarea class="dias" type="text" name="diabierna" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>><?php echo $row["diabierna"]; ?></textarea></th>
            </tr>
        </table>
        <div class="group">
            <div class="form_group klas">
                <label class="form_label">Klas:</label><input id="klas" name="klas" class="input-text" type="text" value="<?php echo $row["klas"]; ?>" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>>
            </div>
            <div class="form_group">
                <label class="form_label">Evaluacion:</label><input id="evaluacion" name="evaluacion" class="input-text" type="text" value="<?php echo $row["evaluacion"]; ?>" <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?>>
            </div>
        </div>
        <input type="hidden" id="week" name="week" value="<?php echo $week; ?>">

        <?php if ($_SESSION['UserRights'] != "BEHEER") { ?>
            <button class="submit_form">UPDATE</button>
        <?php } ?>
    </form>
<?php } else if ($_SESSION['UserRights'] != "BEHEER") { ?>
    <form id="planning">
        <table id="table_planner">
            <tr>
                <th class="title">DIALUNA</th>
                <th><textarea class="dias" type="text" name="dialuna"></textarea></th>
            </tr>
            <tr>
                <th class="title">DIAMARS</th>
                <th><textarea class="dias" type="text" name="diamars"></textarea></th>
            </tr>
            <tr>
                <th class="title">DIARANSON</th>
                <th><textarea class="dias" type="text" name="diaranson"></textarea></th>
            </tr>
            <tr>
                <th class="title">DIAHUEBS</th>
                <th><textarea class="dias" type="text" name="diahuebs"></textarea></th>
            </tr>
            <tr>
                <th class="title">DIABIERNA</th>
                <th><textarea class="dias" type="text" name="diabierna"></textarea></th>
            </tr>
        </table>
        <div class="group">
            <div class="form_group klas">
                <label class="form_label">Klas:</label><input id="klas" name="klas" class="input-text" type="text">
            </div>
            <div class="form_group">
                <label class="form_label">Evaluacion:</label><input id="evaluacion" name="evaluacion" class="input-text" type="text">
            </div>
        </div>
        <input type="hidden" id="week" name="week" value="<?php echo $week; ?>">
        <?php if ($_SESSION['UserRights'] != "BEHEER") { ?>
            <button class="submit_form">CREATE</button>
        <?php } ?>
    </form>
<?php } else {
    echo "No results to show.";
} ?>
<script>
    $(document).ready(function() {

    });

    $(".submit_form").click(function(e) {
        e.preventDefault()
        $.ajax({
            url: "ajax/insert_planner.php",
            data: $('#planning').serialize(),
            type: "POST",
            dataType: "text",
            success: function(text) {
                if (text == 1) {
                    alert("PLANNER CREATED")
                } else if (text == 2) {
                    alert("PLANNER UPDATED")
                } else {
                    alert("ERROR")
                }
            },
            error: function(xhr, status, errorThrown) {
                alert("error");
            },
        });
    })
</script>