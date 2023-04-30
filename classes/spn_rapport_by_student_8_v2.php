<?php



/** Error reporting */

error_reporting(E_ALL);

ini_set('display_errors', TRUE);

ini_set('display_startup_errors', TRUE);

require_once("DBCreds.php");

class spn_rapport_by_student_8

{

  function createrapport($schoolid, $schooljaar, $studentid, $generate_all_rapporten)
  {

    $DBCreds = new DBCreds();
    $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
    $mysqli->set_charset('utf8');

    if ($schooljaar == 'All') {
      $sql_query = "SELECT distinct s.firstname,s.lastname, c.gemiddelde,
      v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
      FROM
      le_cijfers c
      inner join
      le_vakken v on
      c.vak = v.id
      inner join
      students s on
      s.id = c.studentid
      WHERE c.studentid = $studentid order by c.schooljaar, c.rapnummer, v.y_index asc;";
    } else {
      $sql_query = "SELECT distinct s.firstname,s.lastname, c.gemiddelde,
      v.volledigenaamvak, v.y_index, c.rapnummer, c.klas, c.schooljaar
      FROM
      le_cijfers c
      inner join
      le_vakken v on
      c.vak = v.id
      inner join
      students s on
      s.id = c.studentid
      WHERE c.studentid = $studentid and c.schooljaar = '$schooljaar' order by c.schooljaar, c.rapnummer, v.y_index asc;";
    }

    $resultado2 = mysqli_query($mysqli, $sql_query);
    if ($resultado2) {
      $row = mysqli_fetch_assoc($resultado2);
    } else {
      $row = null;
    }


    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=wittekart_cococo.xls");

?>
    <table border='0'>

      <tr>
        <td style='font-weight:bold;'>Scol Publico Primario</td>
      </tr>

      <tr>
        <td>Dato nan di scol: <?php echo $row["firstname"] . " " . $row["lastname"]; ?></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Ana Escolar</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($x <= 7 && $row1["schooljaar"] != $temp) { ?>
            <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'> <?php echo $row1["schooljaar"]; ?> </td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <?php $x++;
          }
          $temp = $row1["schooljaar"];
        }
        while ($x <= 7) { ?>
          <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
        <?php $x++;
        } ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Klas</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($x <= 7 && $row1["schooljaar"] != $temp) { ?>
            <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'> <?php echo $row1["klas"]; ?> </td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <?php $x++;
          }
          $temp = $row1["schooljaar"];
        }
        while ($x <= 7) { ?>
          <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
        <?php $x++;
        } ?>

      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Maestra (o)</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($x <= 7 && $row1["schooljaar"] != $temp) { ?>
            <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'> DOCENT </td>
            <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
            <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <?php $x++;
          }
          $temp = $row1["schooljaar"];
        }
        while ($x <= 7) { ?>
          <td style='border-left:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>
          <td style='border-right:1px solid #000; border-top:1px solid #000; border-bottom:1px solid #000;'></td>
        <?php $x++;
        } ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Rapport</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>1</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>2</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>3</td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'>4</td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Papiamento</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

      </tr>

      <tr>
        <td style='border:1px solid #000;'>Lesa comprensivo / Leesbegrip</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 8 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 8 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>

      </tr>

      <tr>
        <td style='border:1px solid #000;'>Lesa tecnico / technisch lezen</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 9 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 9 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Reflexion / Taalbeschouwing</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 10 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 10 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Vocabulario / Woordenschat</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 11 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 11 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Dictado / Dictee</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 12 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 12 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Scucha y papia / Luisteren en spreken</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 13 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 13 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Averahe / Gemiddelde</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Hulandes / Nederlands</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Lesa comprensivo / Leesbegrip</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 17 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 17 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Reflexion / Taalbeschouwing</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 18 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 18 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Vocabulario / Woordenschat</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 19 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 19 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Dictado / Dictee</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 20 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 20 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Scucha y papia / Luisteren en spreken</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Averahe / Gemiddelde</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Matematica / Rekenen</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Vision y comprencion / inzicht</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Nocion di number/getalbegrip</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 26 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 26 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Operacion basico y avansa / Basisvaardigheden</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 27 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 27 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Midi y Geometria / Meten en meetkunde</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 28 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 28 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Tabel / Tabel</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 29 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 29 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Averahe / Gemiddelde</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Orientacion riba mundo / Wereldoriëntatie</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 32 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 32 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Skirbi / Schrijven</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 34 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 34 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Ingles / Engels</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 35 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 35 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Spaño / Spaans</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 36 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 36 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Trafico / Verkeer</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 37 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 37 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Charla / Spreekbeurt</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 38 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 38 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Movecion / Bewegingsonderwijss</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Movecion Basico / Lichamelijk opvoeding</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 41 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 41 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Actitud positivo / Sportiviteit</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Arte/ Beeldende vorming</td>

        <?php $resultado2 = mysqli_query($mysqli, $sql_query);
        $x = 1;
        $counter = 0;
        $temp = null;
        while ($row1 = mysqli_fetch_assoc($resultado2)) {
          if ($row1["y_index"] == 44 && $row["schooljaar"] == $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter1 = 1;
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $counter2 = 2;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
            <?php $counter3 = 3;
              $x++;
            }
            $x++;
          } else if ($row1["y_index"] == 44 && $row["schooljaar"] != $temp) {
            if ($row1["rapnummer"] == 1) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php
            } else if ($row1["rapnummer"] == 2) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
            <?php $x++;
            } else if ($row1["rapnummer"] == 3) { ?>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'><?php echo $row1["gemiddelde"]; ?></td>
              <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
          <?php $x = $x + 3;
            }
            $temp = $row["schooljaar"];
            $x++;
          }
        }

        while ($x <= 28) { ?>
          <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <?php $x++;
        }
        ?>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Cooperacion / Samenwerking</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Mantene palabracion / zich aan afspraken houden</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Disponilidad pa yuda / Hulpvaardigheid</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Comunicacion / Communicatie</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Comunicacion social / Sociaal gedrag</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Cortesia / Beleefdheid</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Actitud pa Siña / Leerhouding</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Participacion activo / Actieve werkhouding</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Confiansa propio / Zelfvertrouwen</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Precision / Nauwkeurigheid</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Independencia / Zelfstandigheid</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Responsabilidad / Verantwoordelijkheid</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='border:1px solid #000;'>Perseverancia / Doorzettingsvermogen</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr></tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Liheresa / Werktempo</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Concentracion / Concentratie</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Tarea di cas / Huiswerk</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Yega laat / Te laat</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Ausencia / Verzuim</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

      <tr>
        <td style='font-weight:bold; border:1px solid #000;'>Bay over/keda sinta</td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>

        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
        <td style='font-weight:bold; border:1px solid #000;padding:20px;'></td>
      </tr>

    </table>
<?php
  }
}
