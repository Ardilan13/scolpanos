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
        border: 1px solid black;
    }

    #table_planner {
        width: 100%;
        height: 70rem;
    }

    .dias {
        width: 100%;
        height: 100%;
        padding: 1%;
    }

    .title {
        background-color: skyblue;
        text-align: center;
        width: 15%;
        font-size: medium;
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

    .pause {
        text-align: center;
        background-color: darkgrey;
    }
</style>

<?php $query = "SELECT * FROM verwerk WHERE user = '$user' and week = '$week' and schooljaar = '$schooljaar';";
$resultado = mysqli_query($mysqli, $query);
if (mysqli_num_rows($resultado) != 0) {
    $row = mysqli_fetch_assoc($resultado); ?>
    <form id="planning">
        <table id="table_planner">
            <tr>
                <th class="title">DIALUNA</th>
                <th class="title">DIAMARS</th>
                <th class="title">DIARANSON</th>
                <th class="title">DIAHUEBS</th>
                <th class="title">DIABIERNA</th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_1"><?php echo $row["l1"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_1"><?php echo $row["m1"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_1"><?php echo $row["x1"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_1"><?php echo $row["j1"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_1"><?php echo $row["v1"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_2"><?php echo $row["l2"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_2"><?php echo $row["m2"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_2"><?php echo $row["x2"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_2"><?php echo $row["j2"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_2"><?php echo $row["v2"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_3"><?php echo $row["l3"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_3"><?php echo $row["m3"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_3"><?php echo $row["x3"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_3"><?php echo $row["j3"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_3"><?php echo $row["v3"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_4"><?php echo $row["l4"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_4"><?php echo $row["m4"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_4"><?php echo $row["x4"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_4"><?php echo $row["j4"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_4"><?php echo $row["v4"]; ?></textarea></th>
            </tr>
            <tr>
                <th class="pause" colspan="5">
                    PAUZE 10.00 - 10.30
                </th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_5"><?php echo $row["l5"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_5"><?php echo $row["m5"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_5"><?php echo $row["x5"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_5"><?php echo $row["j5"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_5"><?php echo $row["v5"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_6"><?php echo $row["l6"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_6"><?php echo $row["m6"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_6"><?php echo $row["x6"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_6"><?php echo $row["j6"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_6"><?php echo $row["v6"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_7"><?php echo $row["l7"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_7"><?php echo $row["m7"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_7"><?php echo $row["x7"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_7"><?php echo $row["j7"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_7"><?php echo $row["v7"]; ?></textarea></th>
            </tr>
            <tr>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="l_8"><?php echo $row["l8"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="m_8"><?php echo $row["m8"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="x_8"><?php echo $row["x8"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="j_8"><?php echo $row["j8"]; ?></textarea></th>
                <th><textarea <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> class="dias" type="text" name="v_8"><?php echo $row["v8"]; ?></textarea></th>
            </tr>
        </table>
        <div class="form_group">
            <label class="form_label">Notes:</label><input <?php if ($_SESSION['UserRights'] == "BEHEER") { ?> disabled <?php } ?> id="notes" name="notes" value="<?php echo $row["notes"]; ?>" class="input-text" type="text">
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
                <th class="title">DIAMARS</th>
                <th class="title">DIARANSON</th>
                <th class="title">DIAHUEBS</th>
                <th class="title">DIABIERNA</th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_1"></textarea></th>
                <th><textarea class="dias" type="text" name="m_1"></textarea></th>
                <th><textarea class="dias" type="text" name="x_1"></textarea></th>
                <th><textarea class="dias" type="text" name="j_1"></textarea></th>
                <th><textarea class="dias" type="text" name="v_1"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_2"></textarea></th>
                <th><textarea class="dias" type="text" name="m_2"></textarea></th>
                <th><textarea class="dias" type="text" name="x_2"></textarea></th>
                <th><textarea class="dias" type="text" name="j_2"></textarea></th>
                <th><textarea class="dias" type="text" name="v_2"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_3"></textarea></th>
                <th><textarea class="dias" type="text" name="m_3"></textarea></th>
                <th><textarea class="dias" type="text" name="x_3"></textarea></th>
                <th><textarea class="dias" type="text" name="j_3"></textarea></th>
                <th><textarea class="dias" type="text" name="v_3"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_4"></textarea></th>
                <th><textarea class="dias" type="text" name="m_4"></textarea></th>
                <th><textarea class="dias" type="text" name="x_4"></textarea></th>
                <th><textarea class="dias" type="text" name="j_4"></textarea></th>
                <th><textarea class="dias" type="text" name="v_4"></textarea></th>
            </tr>
            <tr>
                <th class="pause" colspan="5">
                    PAUZE 10.00 - 10.30
                </th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_5"></textarea></th>
                <th><textarea class="dias" type="text" name="m_5"></textarea></th>
                <th><textarea class="dias" type="text" name="x_5"></textarea></th>
                <th><textarea class="dias" type="text" name="j_5"></textarea></th>
                <th><textarea class="dias" type="text" name="v_5"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_6"></textarea></th>
                <th><textarea class="dias" type="text" name="m_6"></textarea></th>
                <th><textarea class="dias" type="text" name="x_6"></textarea></th>
                <th><textarea class="dias" type="text" name="j_6"></textarea></th>
                <th><textarea class="dias" type="text" name="v_6"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_7"></textarea></th>
                <th><textarea class="dias" type="text" name="m_7"></textarea></th>
                <th><textarea class="dias" type="text" name="x_7"></textarea></th>
                <th><textarea class="dias" type="text" name="j_7"></textarea></th>
                <th><textarea class="dias" type="text" name="v_7"></textarea></th>
            </tr>
            <tr>
                <th><textarea class="dias" type="text" name="l_8"></textarea></th>
                <th><textarea class="dias" type="text" name="m_8"></textarea></th>
                <th><textarea class="dias" type="text" name="x_8"></textarea></th>
                <th><textarea class="dias" type="text" name="j_8"></textarea></th>
                <th><textarea class="dias" type="text" name="v_8"></textarea></th>
            </tr>
        </table>
        <div class="form_group">
            <label class="form_label">Notes:</label><input id="notes" name="notes" class="input-text" type="text">
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
    $(".submit_form").click(function(e) {
        e.preventDefault()
        $.ajax({
            url: "ajax/insert_verwerk.php",
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