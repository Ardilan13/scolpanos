<?php
require_once "../classes/DBCreds.php";
session_start();
$DBCreds = new DBCreds();
$mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
$mysqli->set_charset('utf8');

$schoolid = $_SESSION["SchoolID"];
$klas = $_GET["klas"];
$group = $_GET["group"] == "all" ? "" : "AND g.name = '" . $_GET["group"] . "'";
?>
<style>
    .opmerking {
        width: 50%;
    }

    .opmerking_input {
        width: 100%;
    }

    .definitiet {
        width: 3% !important;
    }

    .definitiet_input {
        width: 5rem;
    }
</style>
<?php
$groups = array();
$get_students = "SELECT g.id,g.name FROM groups g INNER JOIN le_vakken v ON g.vak = v.ID WHERE g.schoolid = $schoolid AND v.Klas = '$klas' $group;";
$result = mysqli_query($mysqli, $get_students);
while ($row1 = mysqli_fetch_assoc($result)) {
    $groups[$row1['id']] = $row1['name'];
}
echo json_encode($groups);
?>

<!-- <table class="table table-bordered table-colored table-houding">
    <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $get_students = "SELECT g.id,g.name,v.volledigenaamvak as vak,v.Klas FROM groups g INNER JOIN le_vakken v ON g.vak = v.ID WHERE g.schoolid = '$schoolid';";
        $result = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result)) { ?>
            <tr class="group" id="<?php echo $row1['id'] ?>" vak="<?php echo $row1['vak'] ?>" klas="<?php echo $row1['Klas'] ?>">
                <td><?php echo $row1['id'] ?></td>
                <td><?php echo $row1['name'] ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $(".group").click(function() {
            var id = $(this).attr("id");
            var vak = $(this).attr("vak");
            var klas = $(this).attr("klas");
            $("#group_klas option[value=" + klas + "]").attr("selected", "selected");

            $.getJSON("ajax/getvakken_json.php", {
                klas: klas
            }, function(result) {
                var vak_s = $(".group_vak");
                vak_s.empty();
                vak_s.append($("<option/>"));
                $.each(result, function() {
                    vak_s.append($("<option />").val(this.id).text(this.vak).attr("selected", this.vak == vak));
                });
            });

            $("#group_preffix_name").val($(this).children().eq(1).text().slice(0, -1));
            $("#group_suffix_name").val($(this).children().eq(1).text().slice(-1));

            $("#btn_create_group_hs").hide();
            $("#btn_update_group_hs").removeClass("hidden");
            $("#btn_delete_group_hs").removeClass("hidden");
            $("#vakid").attr("value", id);

            console.log(id);
        });
    });
</script> -->