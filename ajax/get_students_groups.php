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
            $get_students = "SELECT s.id,CONCAT(s.lastname,', ',s.firstname) as nombre FROM students s INNER JOIN group_student g ON s.id = g.student_id WHERE s.schoolid = '$schoolid' AND s.class = '$klas' AND g.group_id = $group AND g.schooljaar = '$schooljaar' ORDER BY s.lastname,s.firstname;";
        } else {
            $get_students = "SELECT s.id,CONCAT(s.lastname,', ',s.firstname) as nombre FROM students s WHERE s.schoolid = '$schoolid' AND s.class = '$klas' ORDER BY s.lastname,s.firstname;";
        }
        $result1 = mysqli_query($mysqli, $get_students);
        while ($row1 = mysqli_fetch_assoc($result1)) { ?>
            <tr>
                <td><?php echo $s ?></td>
                <td class="name"><?php echo $row1['nombre'] ?></td>
                <?php foreach ($groups as $id => $name) {
                    $get_student_group = "SELECT id FROM group_student WHERE student_id = '" . $row1['id'] . "' AND group_id = '$id';";
                    $result2 = mysqli_query($mysqli, $get_student_group);
                    $row2 = mysqli_fetch_assoc($result2);
                    $checked = $row2['id'] != "" ? "checked" : "";
                ?>
                    <td class="text-center"><input <?php echo $checked; ?> class="student" id="<?php echo $row1['id'] ?>" type="checkbox" value="<?php echo $id ?>"></td>
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
            $.ajax({
                url: "ajax/add_group_student.php",
                data: {
                    id: id,
                    group: group,
                    check: check
                },
                type: "POST",
                dataType: "text",
                success: function(text) {
                    console.log(text);
                },
                error: function(xhr, status, errorThrown) {
                    console.log("error");
                },
            });
        });
    });
</script>