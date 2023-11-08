<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schoolid = $_SESSION["SchoolID"];
$klas = $_GET["klas"];
$group = $_GET["group"] == "all" ? "" : $_GET["group"];
?>
<style>
    .opmerking {
        width: 50%;
    }

    .opmerking_input {
        width: 100%;
    }

    .definitiet {
        width: 5% !important;
        min-width: 45px !important;
    }

    .name {
        position: sticky;
        left: 0;
        z-index: 2;
        min-width: 250px !important;
    }

    .table-responsive {
        height: 600px !important;
    }

    .group {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table-responsive {
        height: 200px;
        overflow: scroll;
    }

    .definitiet_input {
        width: 5rem;
    }
</style>
<?php
$groups = array();
$get_groups = "SELECT g.id,g.name FROM groups g INNER JOIN le_vakken v ON g.vak = v.ID WHERE g.schoolid = $schoolid ORDER BY g.id;";
$result = mysqli_query($mysqli, $get_groups);
while ($row = mysqli_fetch_assoc($result)) {
    $groups[$row['id']] = $row['name'];
}
?>

<table class="table table-bordered table-colored table-houding table-responsive">
    <thead>
        <tr>
            <th class="definitiet">ID</th>
            <th class="name">Naam</th>
            <th>Paket</th>
            <?php foreach ($groups as $id => $name) { ?>
                <th class="definitiet group"><?php echo $name; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $schooljaar = $_SESSION["SchoolJaar"];
        $s = 1;
        if ($group != "" && $group != "all" && $group != null) {
            $get_students = "SELECT s.id,s.profiel,CONCAT(s.lastname,', ',s.firstname) as nombre FROM students s INNER JOIN group_student g ON s.id = g.student_id WHERE s.schoolid = '$schoolid' AND s.class = '$klas' AND g.group_id = $group AND g.schooljaar = '$schooljaar' ORDER BY s.lastname,s.firstname;";
        } else {
            $get_students = "SELECT s.id,s.profiel,CONCAT(s.lastname,', ',s.firstname) as nombre FROM students s WHERE s.schoolid = '$schoolid' AND s.class = '$klas' ORDER BY s.lastname,s.firstname;";
        }
        $result1 = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
            <tr>
                <td><?php echo $s ?></td>
                <td class="name"><?php echo $row1['nombre'] ?></td>
                <td>
                    <select class="profiel <?php echo $row1["profiel"] . ' ' . $row1['id']; ?>" name="profiel" class="form-control" id="p<?php echo $s; ?>">
                        <option value=""></option>
                        <option value="MM01">MM01</option>
                        <option value="MM02">MM02</option>
                        <option value="MM03">MM03</option>
                        <option value="MM04">MM04</option>
                        <option value="MM05">MM05</option>
                        <option value="MM06">MM06</option>
                        <option value="MM07">MM07</option>
                        <option value="MM08">MM08</option>
                        <option value="MM09">MM09</option>
                        <option value="MM10">MM10</option>
                        <option value="MM11">MM11</option>
                        <option value="MM12">MM12</option>
                        <option value="MM13">MM13</option>
                        <option value="MM14">MM14</option>
                        <option value="MM15">MM15</option>
                        <option value="MM16">MM16</option>
                        <option value="MM17">MM17</option>
                        <option value="MM18">MM18</option>
                        <option value="MM19">MM19</option>
                        <option value="MM20">MM20</option>
                        <option value="MM21">MM21</option>
                        <option value="MM22">MM22</option>
                        <option value="MM23">MM23</option>
                        <option value="MM24">MM24</option>
                        <option value="MM25">MM25</option>
                        <option value="MM26">MM26</option>
                        <option value="NW01">NW01</option>
                        <option value="NW02">NW02</option>
                        <option value="NW03">NW03</option>
                        <option value="NW04">NW04</option>
                        <option value="NW05">NW05</option>
                        <option value="NW06">NW06</option>
                        <option value="NW07">NW07</option>
                        <option value="NW08">NW08</option>
                        <option value="NW09">NW09</option>
                        <option value="NW10">NW10</option>
                        <option value="NW11">NW11</option>
                        <option value="NW12">NW12</option>
                        <option value="NW13">NW13</option>
                        <option value="NW14">NW14</option>
                        <option value="NW15">NW15</option>
                        <option value="NW16">NW16</option>
                        <option value="HU01">HU01</option>
                        <option value="HU02">HU02</option>
                        <option value="HU03">HU03</option>
                        <option value="HU04">HU04</option>
                        <option value="HU05">HU05</option>
                        <option value="HU06">HU06</option>
                        <option value="HU07">HU07</option>
                        <option value="HU08">HU08</option>
                        <option value="HU09">HU09</option>
                        <option value="HU10">HU10</option>
                        <option value="HU11">HU11</option>
                        <option value="HU12">HU12</option>
                        <option value="HU13">HU13</option>
                        <option value="HU14">HU14</option>
                        <option value="HU15">HU15</option>
                        <option value="HU16">HU16</option>
                        <option value="HU17">HU17</option>
                        <option value="HU18">HU18</option>
                        <option value="HU19">HU19</option>
                        <option value="HU20">HU20</option>
                        <option value="HU21">HU21</option>
                        <option value="HU22">HU22</option>
                    </select>
                </td>
                <?php foreach ($groups as $id => $name) {
                    $get_student_group = "SELECT id FROM group_student WHERE student_id = '" . $row1['id'] . "' AND group_id = '$id';";
                    $result2 = mysqli_query($mysqli, $get_student_group);
                    $row2 = mysqli_fetch_assoc($result2);
                    $checked = $row2['id'] != "" ? "checked" : "";
                ?>
                    <td class="text-center"><input <?php echo $checked; ?> class="student" select="p<?php echo $s; ?>" id="<?php echo $row1['id'] ?>" type="checkbox" value="<?php echo $id ?>" vak="<?php echo $name ?>"></td>
                <?php } ?>
            </tr>
        <?php $s++;
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var stickyWidth = $('.name').outerWidth();
        $('th:not(.name), td:not(.name)').css('margin-left', stickyWidth + 'px');


        $(".student").change(function() {
            var id = $(this).attr("id");
            var group = $(this).val();
            var check = $(this).prop('checked');
            var vak = $(this).attr("vak");
            var select = $(this).attr("select");
            $.ajax({
                url: "ajax/add_group_student.php",
                data: {
                    id: id,
                    group: group,
                    check: check,
                    vak: vak
                },
                type: "POST",
                dataType: "text",
                success: function(text) {
                    console.log(text);
                    if (text != null && text != "") {
                        $("#" + select + " option:selected").prop("selected", false);
                        $("#" + select).val(text);
                    }
                },
                error: function(xhr, status, errorThrown) {
                    console.log("error");
                },
            });
        });

        $(document).ready(function() {
            $(".profiel").each(function() {
                var value = $(this).attr("class").split(" ")[1];
                var id = $(this).attr("id");
                if (value != null && value != "") {
                    $("#" + id + ' option[value="' + value + '"]').attr("selected", true);
                }
            });
        });

        $(".profiel").change(function() {
            var id = $(this).attr("class").split(" ")[2];
            var value = $(this).children("option:selected").val();
            $.ajax({
                url: "ajax/save_eba_ex.php",
                type: "POST",
                data: {
                    id: id,
                    value: value,
                    ex: "profiel",
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
    });
</script>