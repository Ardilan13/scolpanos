<?php

require_once("spn_setting.php");

class spn_leerling
{
  public $tablename_leerling = "students";
  public $exceptionvalue = "";
  public $mysqlierror = "";
  public $mysqlierrornumber = "";
  public $last_insert_id = "";
  public $sp_read_student_detail = "sp_read_student_detail";
  public $sp_get_pic_leerling = "sp_get_pic_leerling";
  public $sp_read_student_birthday = "sp_read_student_birthday";
  public $sp_read_families = "sp_read_families";
  public $sp_create_student = "sp_create_student";
  public $sp_update_student = "sp_update_student";
  public $sp_read_class = "sp_read_class";
  public $sp_read_change_class = "sp_read_change_class";
  public $sp_change_class = "sp_change_class";
  public $sp_get_huiswerk_by_class = "sp_get_huiswerk_by_class";
  public $sp_get_leerling_by_fullname = "sp_get_leerling_by_fullname";
  public $sp_update_securepin_student = "sp_update_securepin_student";
  public $debug = false;
  public $error = "";
  public $errormessage = "";
  // public $sp_get_huiswerk_by_class ="sp_get_huiswerk_by_class";
  public $sp_get_huiswerk_by_student = "sp_get_huiswerk_by_student";



  function createleerling($schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    $_dob = null;
    if ($utils->converttomysqldate($dob) != false) {
      $_dob = $utils->converttomysqldate($dob);
    } else {
      $_dob = null;
    }
    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($stmt = $mysqli->prepare("insert into " . $this->tablename_leerling . "(created,schoolid,idnumber,studentnumber,class,enrollmentdate,firstname,lastname,sex,dob,birthplace,address,azvnumber,azvexpiredate,phone1,phone2,colorcode,status) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sisssssssssssssssi", $_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status)) {
          if ($stmt->execute()) {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            $result = 1;
            $this->last_insert_id = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();

            //  Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling', 'Create Leerling', false);
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function liststudents($schoolid_in, $klas_in, $baseurl, $detailpage)
  {

    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";
    $query_HS = false;
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "SELECT id, studentnumber, firstname, lastname, sex, dob, class";
    $sql_query .= ", uuid, created, updated, schoolid, idnumber, enrollmentdate,  upper(birthplace), address, azvnumber, azvexpiredate, ";
    $sql_query .= "phone1, phone2,profiel, colorcode, status, roepnaam, nationaliteit, vezorger, email, ne, en, sp, pa, voertaalanders, spraak, gehoor, gezicht, motoriek, medicatieanders, ";
    $sql_query .= "bijzonderemedisch, huisarts, huisartsnr, persoonlijkeeigenschappen, thuissituatie, fysiekebijzonderheden, schoolleereigenschappen, socialeeigenschappen, anders, voorletter, phone3, vorigeschool, bijzondermedischeindicatie, notas, id_family, securepin, outschooldate";
    if ($klas_in == "ALL")
      $sql_query .= " FROM students WHERE schoolid=? order by";
    else
      $sql_query .= " FROM students WHERE class=? and schoolid=? order by";

    $s = new spn_setting();
    $s->getsetting_info($schoolid_in, false);

    // End change settings (laldana@caribedev)
    //print($sql_query);
    $sql_order = " lastname " . $s->_setting_sort . ", firstname";
    if ($s->_setting_mj) {
      $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
    } else {
      $sql_query .=  $sql_order;
    }

    if ($_SESSION['SchoolType'] == 2 && $_SESSION["UserRights"] == 'DOCENT') {
      $sql_query =  "select distinct s.id, s.studentnumber, s.firstname, s.lastname, s.sex, s.dob, s.class, s.uuid, s.created, s.updated, s.schoolid,";
      $sql_query .= "s.idnumber, s.enrollmentdate, upper(s.birthplace), s.address, s.azvnumber, s.azvexpiredate, s.phone1, s.phone2,s.profiel, s.colorcode, s.status, ";
      $sql_query .= "s.roepnaam, s.nationaliteit, s.vezorger, s.email, s.ne, s.en, s.sp, s.pa, s.voertaalanders, s.spraak, s.gehoor, s.gezicht, s.motoriek, ";
      $sql_query .= "s.medicatieanders, s.bijzonderemedisch, s.huisarts, s.huisartsnr, ";
      $sql_query .= "s.persoonlijkeeigenschappen, s.thuissituatie, s.fysiekebijzonderheden, s.schoolleereigenschappen, s.socialeeigenschappen, ";
      $sql_query .= "s.anders, s.voorletter, s.phone3, s.vorigeschool, s.bijzondermedischeindicatie, s.notas, s.id_family, s.securepin, s.outschooldate ";
      $sql_query .= "FROM students s ";
      $sql_query .= "inner join user_hs u on s.class= u.klas and s.schoolid = u.SchoolId ";
      $sql_query .= "WHERE u.user_GUID = ? and s.schoolid= ? and u.SchoolJaar = ? and s.status = 1 order by ";
      $query_HS = true;

      $sql_order = " s.lastname " . $s->_setting_sort . ", s.firstname";
      if ($s->_setting_mj) {
        $sql_query .= " s.sex " . $s->_setting_sort . ", " . $sql_order;
      } else {
        $sql_query .=  $sql_order;
      }
    }
    // End change settings (laldana@caribedev)

    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      //print($_SESSION['SchoolID']);
      //print('<br>');
      //print($sql_query);
      if ($select = $mysqli->prepare($sql_query)) {
        //print($sql_query);
        if (!$query_HS) {
          if ($klas_in == "ALL") {
            if ($select->bind_param("i", $schoolid_in)) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($studentid, $studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas, $uuid, $created, $updated, $schoolid, $idnumber, $enrollmentdate, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $profiel, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $voertaalanders, $spraak, $gehoor, $gezicht, $motoriek, $medicatieanders, $bijzonderemedisch, $huisarts, $huisartsnr, $persoonlijkeeigenschappen, $thuissituatie, $fysiekebijzonderheden, $schoolleereigenschappen, $socialeeigenschappen, $anders, $voorletter, $phone3,  $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $securepin, $outschooldate);
                $select->store_result();

                //Audit by Caribe Developers
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling', 'Beheer List Students', false);

                if ($select->num_rows > 0) {
                  /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
                  $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th><th>Docent Connect</th></tr></thead>";
                  $htmlcontrol .= "<tbody>";
                  while ($select->fetch()) {
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td>" . htmlentities($studentnumber) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($voornamen) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($achternaam) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($geslacht) . "</td>";
                    $htmlcontrol .= "<td>" . ($utils->convertfrommysqldate($geboortedatum) != false && $geboortedatum != "" ? htmlentities($utils->convertfrommysqldate($geboortedatum)) : "") . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";

                    $htmlcontrol .= "<td><a href=\"$baseurl/$detailpage" . "?id=" . htmlentities($studentid) . "&id_family=" . htmlentities($id_family) . "\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a>";
                    // Hide controls
                    $htmlcontrol .= "<input type='hidden' name='id' value='" . $studentid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='uuid' value='" . $uuid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='created' value='" . ($utils->convertfrommysqldate($created) != false && $created != "" ? htmlentities($utils->convertfrommysqldate($created)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='updated' value='" . ($utils->convertfrommysqldate($updated) != false && $updated != "" ? htmlentities($utils->convertfrommysqldate($updated)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='schoolid' value='" . $schoolid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='idnumber' value='" . $idnumber . "'>";
                    $htmlcontrol .= "<input type='hidden' name='enrollmentdate' value='" . ($utils->convertfrommysqldate($enrollmentdate) != false && $enrollmentdate != "" ? htmlentities($utils->convertfrommysqldate($enrollmentdate)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='birthplace' value='" . $birthplace . "'>";
                    $htmlcontrol .= "<input type='hidden' name='address' value='" . $address . "'>";
                    $htmlcontrol .= "<input type='hidden' name='azvnumber' value='" . $azvnumber . "'>";
                    $htmlcontrol .= "<input type='hidden' name='azvexpiredate' value='" . ($utils->convertfrommysqldate($azvexpiredate) != false && $azvexpiredate != "" ? htmlentities($utils->convertfrommysqldate($azvexpiredate)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone1' value='" . $phone1 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone2' value='" . $phone2 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='profiel' value='" . $profiel . "'>";
                    $htmlcontrol .= "<input type='hidden' name='colorcode' value='" . $colorcode . "'>";
                    $htmlcontrol .= "<input type='hidden' name='status' value='" . $status . "'>";
                    $htmlcontrol .= "<input type='hidden' name='roepnaam' value='" . $roepnaam . "'>";
                    $htmlcontrol .= "<input type='hidden' name='nationaliteit' value='" . $nationaliteit . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vezorger' value='" . $vezorger . "'>";
                    $htmlcontrol .= "<input type='hidden' name='email' value='" . $email . "'>";
                    $htmlcontrol .= "<input type='hidden' name='ne' value='" . $ne . "'>";
                    $htmlcontrol .= "<input type='hidden' name='en' value='" . $en . "'>";
                    $htmlcontrol .= "<input type='hidden' name='sp' value='" . $sp . "'>";
                    $htmlcontrol .= "<input type='hidden' name='pa' value='" . $pa . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vo' value='" . $voertaalanders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='spraak' value='" . $spraak . "'>";
                    $htmlcontrol .= "<input type='hidden' name='gehoor' value='" . $gehoor . "'>";
                    $htmlcontrol .= "<input type='hidden' name='gezicht' value='" . $gezicht . "'>";
                    $htmlcontrol .= "<input type='hidden' name='motoriek' value='" . $motoriek . "'>";
                    $htmlcontrol .= "<input type='hidden' name='medicatieanders' value='" . $medicatieanders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='bijzonderemedisch' value='" . $bijzonderemedisch . "'>";
                    $htmlcontrol .= "<input type='hidden' name='huisarts' value='" . $huisarts . "'>";
                    $htmlcontrol .= "<input type='hidden' name='huisartsnr' value='" . $huisartsnr . "'>";
                    $htmlcontrol .= "<input type='hidden' name='persoonlijkeeigenschappen' value='" . $persoonlijkeeigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='thuissituatie' value='" . $thuissituatie . "'>";
                    $htmlcontrol .= "<input type='hidden' name='fysiekebijzonderheden' value='" . $fysiekebijzonderheden . "'>";
                    $htmlcontrol .= "<input type='hidden' name='schoolleereigenschappen' value='" . $schoolleereigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='socialeeigenschappen' value='" . $socialeeigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='anders' value='" . $anders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='voorletter' value='" . $voorletter . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone3' value='" . $phone3 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vorigeschool' value='" . $vorigeschool . "'>";
                    $htmlcontrol .= "<input type='hidden' name='bijzondermedischeindicatie' value='" . $bijzondermedischeindicatie . "'>";
                    $htmlcontrol .= "<input type='hidden' name='notasval' value='" . $notas . "'>";
                    $htmlcontrol .= "<input type='hidden' name='securepin' value='" . $securepin . "'>";
                    $htmlcontrol .= "<input type='hidden' name='id_family' value='" . $id_family . "'>";
                    $htmlcontrol .= "<input type='hidden' name='outschooldate' value='" . ($utils->convertfrommysqldate($outschooldate) != false && $outschooldate != "" ? htmlentities($utils->convertfrommysqldate($outschooldate)) : "") . "'>";
                    // End hide controls
                    $htmlcontrol .= "</td>";
                    $htmlcontrol .= "<td>" . "<button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($schoolid) . "\",\"" . htmlentities($studentid) . "\",\"" . htmlentities($uuid) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Docent Connect</button>" . "</td>";
                    $htmlcontrol .= "</tr>";
                  }
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result = 0;
                if ($this->debug) {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            } else {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;
              if ($this->debug) {
                print "error binding parameters";
              }
            }
          } else {
            if ($select->bind_param("si", $klas_in, $schoolid_in)) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($studentid, $studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas, $uuid, $created, $updated, $schoolid, $idnumber, $enrollmentdate, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $profiel, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $voertaalanders, $spraak, $gehoor, $gezicht, $motoriek, $medicatieanders, $bijzonderemedisch, $huisarts, $huisartsnr, $persoonlijkeeigenschappen, $thuissituatie, $fysiekebijzonderheden, $schoolleereigenschappen, $socialeeigenschappen, $anders, $voorletter, $phone3,  $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $securepin, $outschooldate);
                $select->store_result();

                //Audit by Caribe Developers
                require_once("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling', 'Docent List Students', false);
                if ($select->num_rows > 0) {
                  /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
                  $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<thead><tr><th>Leerling#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th><th>Docent Connect</th></tr></thead>";
                  $htmlcontrol .= "<tbody>";
                  while ($select->fetch()) {
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td>" . htmlentities($studentnumber) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($voornamen) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($achternaam) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($geslacht) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($utils->convertfrommysqldate($geboortedatum)) . "</td>";
                    $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                    $htmlcontrol .= "<td><a href=\"$baseurl/$detailpage" . "?id=" . htmlentities($studentid) . "&id_family=" . htmlentities($id_family) . "\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a>";
                    $htmlcontrol .= "<input type='hidden' name='id' value='" . $studentid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='uuid' value='" . $uuid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='created' value='" . ($utils->convertfrommysqldate($created) != false && $created != "" ? htmlentities($utils->convertfrommysqldate($created)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='updated' value='" . ($utils->convertfrommysqldate($updated) != false && $updated != "" ? htmlentities($utils->convertfrommysqldate($updated)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='schoolid' value='" . $schoolid . "'>";
                    $htmlcontrol .= "<input type='hidden' name='idnumber' value='" . $idnumber . "'>";
                    $htmlcontrol .= "<input type='hidden' name='enrollmentdate' value='" . ($utils->convertfrommysqldate($enrollmentdate) != false && $enrollmentdate != "" ? htmlentities($utils->convertfrommysqldate($enrollmentdate)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='birthplace' value='" . $birthplace . "'>";
                    $htmlcontrol .= "<input type='hidden' name='address' value='" . $address . "'>";
                    $htmlcontrol .= "<input type='hidden' name='azvnumber' value='" . $azvnumber . "'>";
                    $htmlcontrol .= "<input type='hidden' name='azvexpiredate' value='" . ($utils->convertfrommysqldate($azvexpiredate) != false && $azvexpiredate != "" ? htmlentities($utils->convertfrommysqldate($azvexpiredate)) : "") . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone1' value='" . $phone1 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone2' value='" . $phone2 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='profiel' value='" . $profiel . "'>";
                    $htmlcontrol .= "<input type='hidden' name='colorcode' value='" . $colorcode . "'>";
                    $htmlcontrol .= "<input type='hidden' name='status' value='" . $status . "'>";
                    $htmlcontrol .= "<input type='hidden' name='roepnaam' value='" . $roepnaam . "'>";
                    $htmlcontrol .= "<input type='hidden' name='nationaliteit' value='" . $nationaliteit . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vezorger' value='" . $vezorger . "'>";
                    $htmlcontrol .= "<input type='hidden' name='email' value='" . $email . "'>";
                    $htmlcontrol .= "<input type='hidden' name='ne' value='" . $ne . "'>";
                    $htmlcontrol .= "<input type='hidden' name='en' value='" . $en . "'>";
                    $htmlcontrol .= "<input type='hidden' name='sp' value='" . $sp . "'>";
                    $htmlcontrol .= "<input type='hidden' name='pa' value='" . $pa . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vo' value='" . $voertaalanders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='spraak' value='" . $spraak . "'>";
                    $htmlcontrol .= "<input type='hidden' name='gehoor' value='" . $gehoor . "'>";
                    $htmlcontrol .= "<input type='hidden' name='gezicht' value='" . $gezicht . "'>";
                    $htmlcontrol .= "<input type='hidden' name='motoriek' value='" . $motoriek . "'>";
                    $htmlcontrol .= "<input type='hidden' name='medicatieanders' value='" . $medicatieanders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='bijzonderemedisch' value='" . $bijzonderemedisch . "'>";
                    $htmlcontrol .= "<input type='hidden' name='huisarts' value='" . $huisarts . "'>";
                    $htmlcontrol .= "<input type='hidden' name='huisartsnr' value='" . $huisartsnr . "'>";
                    $htmlcontrol .= "<input type='hidden' name='persoonlijkeeigenschappen' value='" . $persoonlijkeeigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='thuissituatie' value='" . $thuissituatie . "'>";
                    $htmlcontrol .= "<input type='hidden' name='fysiekebijzonderheden' value='" . $fysiekebijzonderheden . "'>";
                    $htmlcontrol .= "<input type='hidden' name='schoolleereigenschappen' value='" . $schoolleereigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='socialeeigenschappen' value='" . $socialeeigenschappen . "'>";
                    $htmlcontrol .= "<input type='hidden' name='anders' value='" . $anders . "'>";
                    $htmlcontrol .= "<input type='hidden' name='voorletter' value='" . $voorletter . "'>";
                    $htmlcontrol .= "<input type='hidden' name='phone3' value='" . $phone3 . "'>";
                    $htmlcontrol .= "<input type='hidden' name='vorigeschool' value='" . $vorigeschool . "'>";
                    $htmlcontrol .= "<input type='hidden' name='bijzondermedischeindicatie' value='" . $bijzondermedischeindicatie . "'>";
                    $htmlcontrol .= "<input type='hidden' name='notasval' value='" . $notas . "'>";
                    $htmlcontrol .= "<input type='hidden' name='securepin' value='" . $securepin . "'>";
                    $htmlcontrol .= "<input type='hidden' name='id_family' value='" . $id_family . "'>";
                    $htmlcontrol .= "<input type='hidden' name='outschooldate' value='" . ($utils->convertfrommysqldate($outschooldate) != false && $outschooldate != "" ? htmlentities($utils->convertfrommysqldate($outschooldate)) : "") . "'>";
                    $htmlcontrol .= "<td>" . "<button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($schoolid) . "\",\"" . htmlentities($studentid) . "\",\"" . htmlentities($uuid) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Docent Connect</button>" . "</td>";


                    $htmlcontrol .= "</td></tr>";
                  }
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result = 0;
                if ($this->debug) {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            } else {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;
              if ($this->debug) {
                print "error binding parameters";
              }
            }
          }
        } else {
          //TUTOR QUERY
          if ($select->bind_param("sis", $_SESSION['UserGUID'], $_SESSION['SchoolID'], $_SESSION['SchoolJaar'])) {
            if ($select->execute()) {
              //print($sql_query);
              $this->error = false;
              $result = 1;
              $select->bind_result($studentid, $studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas, $uuid, $created, $updated, $schoolid, $idnumber, $enrollmentdate, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $voertaalanders, $spraak, $gehoor, $gezicht, $motoriek, $medicatieanders, $bijzonderemedisch, $huisarts, $huisartsnr, $persoonlijkeeigenschappen, $thuissituatie, $fysiekebijzonderheden, $schoolleereigenschappen, $socialeeigenschappen, $anders, $voorletter, $phone3,  $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $securepin, $outschooldate);
              $select->store_result();


              if ($select->num_rows > 0) {
                /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */
                $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                $htmlcontrol .= "<thead><tr><th>Leerling#</th><th>Voornamen</th><th>Achternaam</th><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th><th>Details</th><th>Docent Connect</th></tr></thead>";
                $htmlcontrol .= "<tbody>";
                while ($select->fetch()) {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>" . htmlentities($studentnumber) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($voornamen) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($achternaam) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($geslacht) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($utils->convertfrommysqldate($geboortedatum)) . "</td>";
                  $htmlcontrol .= "<td>" . htmlentities($klas) . "</td>";
                  $htmlcontrol .= "<td><a href=\"$baseurl/$detailpage" . "?id=" . htmlentities($studentid) . "&id_family=" . htmlentities($id_family) . "\" class=\"link quaternary-color\">MEER <i class=\"fa fa-angle-double-right quaternary-color\"></i></a>";
                  $htmlcontrol .= "<input type='hidden' name='id' value='" . $studentid . "'>";
                  $htmlcontrol .= "<input type='hidden' name='uuid' value='" . $uuid . "'>";
                  $htmlcontrol .= "<input type='hidden' name='created' value='" . ($utils->convertfrommysqldate($created) != false && $created != "" ? htmlentities($utils->convertfrommysqldate($created)) : "") . "'>";
                  $htmlcontrol .= "<input type='hidden' name='updated' value='" . ($utils->convertfrommysqldate($updated) != false && $updated != "" ? htmlentities($utils->convertfrommysqldate($updated)) : "") . "'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolid' value='" . $schoolid . "'>";
                  $htmlcontrol .= "<input type='hidden' name='idnumber' value='" . $idnumber . "'>";
                  $htmlcontrol .= "<input type='hidden' name='enrollmentdate' value='" . ($utils->convertfrommysqldate($enrollmentdate) != false && $enrollmentdate != "" ? htmlentities($utils->convertfrommysqldate($enrollmentdate)) : "") . "'>";
                  $htmlcontrol .= "<input type='hidden' name='birthplace' value='" . $birthplace . "'>";
                  $htmlcontrol .= "<input type='hidden' name='address' value='" . $address . "'>";
                  $htmlcontrol .= "<input type='hidden' name='azvnumber' value='" . $azvnumber . "'>";
                  $htmlcontrol .= "<input type='hidden' name='azvexpiredate' value='" . ($utils->convertfrommysqldate($azvexpiredate) != false && $azvexpiredate != "" ? htmlentities($utils->convertfrommysqldate($azvexpiredate)) : "") . "'>";
                  $htmlcontrol .= "<input type='hidden' name='phone1' value='" . $phone1 . "'>";
                  $htmlcontrol .= "<input type='hidden' name='phone2' value='" . $phone2 . "'>";
                  $htmlcontrol .= "<input type='hidden' name='colorcode' value='" . $colorcode . "'>";
                  $htmlcontrol .= "<input type='hidden' name='status' value='" . $status . "'>";
                  $htmlcontrol .= "<input type='hidden' name='roepnaam' value='" . $roepnaam . "'>";
                  $htmlcontrol .= "<input type='hidden' name='nationaliteit' value='" . $nationaliteit . "'>";
                  $htmlcontrol .= "<input type='hidden' name='vezorger' value='" . $vezorger . "'>";
                  $htmlcontrol .= "<input type='hidden' name='email' value='" . $email . "'>";
                  $htmlcontrol .= "<input type='hidden' name='ne' value='" . $ne . "'>";
                  $htmlcontrol .= "<input type='hidden' name='en' value='" . $en . "'>";
                  $htmlcontrol .= "<input type='hidden' name='sp' value='" . $sp . "'>";
                  $htmlcontrol .= "<input type='hidden' name='pa' value='" . $pa . "'>";
                  $htmlcontrol .= "<input type='hidden' name='vo' value='" . $voertaalanders . "'>";
                  $htmlcontrol .= "<input type='hidden' name='spraak' value='" . $spraak . "'>";
                  $htmlcontrol .= "<input type='hidden' name='gehoor' value='" . $gehoor . "'>";
                  $htmlcontrol .= "<input type='hidden' name='gezicht' value='" . $gezicht . "'>";
                  $htmlcontrol .= "<input type='hidden' name='motoriek' value='" . $motoriek . "'>";
                  $htmlcontrol .= "<input type='hidden' name='medicatieanders' value='" . $medicatieanders . "'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzonderemedisch' value='" . $bijzonderemedisch . "'>";
                  $htmlcontrol .= "<input type='hidden' name='huisarts' value='" . $huisarts . "'>";
                  $htmlcontrol .= "<input type='hidden' name='huisartsnr' value='" . $huisartsnr . "'>";
                  $htmlcontrol .= "<input type='hidden' name='persoonlijkeeigenschappen' value='" . $persoonlijkeeigenschappen . "'>";
                  $htmlcontrol .= "<input type='hidden' name='thuissituatie' value='" . $thuissituatie . "'>";
                  $htmlcontrol .= "<input type='hidden' name='fysiekebijzonderheden' value='" . $fysiekebijzonderheden . "'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolleereigenschappen' value='" . $schoolleereigenschappen . "'>";
                  $htmlcontrol .= "<input type='hidden' name='socialeeigenschappen' value='" . $socialeeigenschappen . "'>";
                  $htmlcontrol .= "<input type='hidden' name='anders' value='" . $anders . "'>";
                  $htmlcontrol .= "<input type='hidden' name='voorletter' value='" . $voorletter . "'>";
                  $htmlcontrol .= "<input type='hidden' name='phone3' value='" . $phone3 . "'>";
                  $htmlcontrol .= "<input type='hidden' name='vorigeschool' value='" . $vorigeschool . "'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzondermedischeindicatie' value='" . $bijzondermedischeindicatie . "'>";
                  $htmlcontrol .= "<input type='hidden' name='notasval' value='" . $notas . "'>";
                  $htmlcontrol .= "<input type='hidden' name='securepin' value='" . $securepin . "'>";
                  $htmlcontrol .= "<input type='hidden' name='id_family' value='" . $id_family . "'>";
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "<td>" . "<button id='btn_chat_room' name='btn_chat_room' onclick='ChatRoom(\"" . htmlentities($schoolid) . "\",\"" . htmlentities($studentid) . "\",\"" . htmlentities($uuid) . "\")' type='button' class='btn btn-primary btn-m-w btn-m-h'>Docent Connect</button>" . "</td>";
                  $htmlcontrol .= "</td></tr>";
                }

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;
              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            /* error binding parameters */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error binding parameters";
            }
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $htmlcontrol;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }

  function liststudentdetail($studentdbid)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where id=?";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("i", $studentdbid)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($studentid, $studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas);
            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $returnarr["results"] = 1;
                $returnarr["studentid"] = $studentid;
                $returnarr["studentnumber"] = $studentnumber;
                $returnarr["voornamen"] = utf8_encode($voornamen);
                $returnarr["achternaam"] = utf8_encode($achternaam);
                $returnarr["geslacht"] = $geslacht;
                $returnarr["geboortedatum"] = $geboortedatum;
                $returnarr["klas"] = $klas;
              }
            } else {
              $returnarr["results"] = 0;
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function createextra($Xstudentid, $Xklas, $Xschoolid)
  {
    require_once("spn_avi.php");
    $a = new spn_avi();
    /* TODO: change nill to null */
    $a->createavi($Xstudentid, "2015-2016", 0, $Xklas, "spn", nill, nill, nill, "", "", nill, nill, nill, "", "", nill, nill, nill, "", "");
    //  Audit by Caribe Developers
    $spn_audit = new spn_audit();
    $spn_audit->create_audit($_SESSION['UserGUID'], 'AVI', 'Change Nill to Null', false);

    require_once("spn_houding.php");
    $h = new spn_houding();
    $h->createhouding($Xstudentid, "2015-2016", 1, $Xklas, "spn", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $h->createhouding($Xstudentid, "2015-2016", 2, $Xklas, "spn", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $h->createhouding($Xstudentid, "2015-2016", 3, $Xklas, "spn", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    //  Audit by Caribe Developers
    $spn_audit = new spn_audit();
    $spn_audit->create_audit($_SESSION['UserGUID'], 'Houding', 'Create Houding in (0)', false);

    require_once("spn_cijfers.php");
    $sql_query = "select distinct (vak) from le_vakken where schoolid=? and klas=?";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("is", $Xschoolid, $Xklas)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($Xvak);
            $select->store_result();
            //  Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Vak', 'Select Vak For Id Class and Id School', false);

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $c = new spn_cijfers();
                $c->createcijfers($Xstudentid, "2015-2016", 1, $Xvak, $Xklas, "spn", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
                $c->createcijfers($Xstudentid, "2015-2016", 2, $Xvak, $Xklas, "spn", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
                $c->createcijfers($Xstudentid, "2015-2016", 3, $Xvak, $Xklas, "spn", null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);

                //  Audit by Caribe Developers
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($_SESSION['UserGUID'], 'Cijfers', 'Create Cijfer in NULL', false);
              }
            } else {
              $returnarr["results"] = 0;
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function read_leerling_detail_info($studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcontrolContact = "";
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $mysqli->set_charset('utf8');
        if ($select = $mysqli->prepare("CALL " . $this->sp_read_student_detail . " (?)")) {
          // $class = '';
          if ($select->bind_param("i", $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;
              $select->bind_result($roepnaam, $voorletter, $address, $phone1, $phone2, $vezorger, $phone3, $birthplace, $nationaliteit, $azvnumber, $azvexpiredate, $idnumber, $Voertaal, $email, $created, $vorigeschool, $Lichamelijkegebrek, $anders, $bijzondermedischeindicatie, $huisarts, $huisartsnr, $notas);
              $select->store_result();
              //  Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling', 'Get all student info', false);

              if ($select->num_rows > 0) {
                require_once("classes/spn_contact.php");
                $c = new spn_contact();
                $htmlcontrolContact .= $c->list_contacts($_GET["id_family"], null);

                require_once("classes/spn_leerling.php");
                $l = new spn_leerling();
                $data_leerling = $l->liststudentdetail($_GET["id"]);
                if ($data_leerling["results"] == 1) {
                  $studentid = $data_leerling["studentid"];
                  $studentnumber = $data_leerling["studentnumber"];
                  $voornamen = $data_leerling["voornamen"];
                  $achternaam = $data_leerling["achternaam"];
                  $geslacht = $data_leerling["geslacht"];
                  $geboortedatum = $data_leerling["geboortedatum"];
                  $klas = $data_leerling["klas"];
                }
                $leerling_html = "<table class=\"table table-bordered table-colored\">
                                <tbody>
                                    <tr>
                                        <td><strong>Student nr.</strong></td>
                                        <td>" . $studentnumber . "</td>
                                        <td><strong>Klas</strong></td>
                                        <td id=\"klas\" name=\"klas\">.
                                        " . $klas . "</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Achternaam</strong></td>
                                        <td> " . $achternaam . " </td>
                                        <td><strong>Voornamen</strong></td>
                                        <td> " . $voornamen . "</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Geslacht</strong></td>
                                        <td>" . $geslacht . "</td>
                                       <td><strong>Geboortedatum</strong></td>
                                            <td>" . $geboortedatum . "</td>
                                    </tr>
                                </tbody>
                            </table>";

                while ($select->fetch()) {
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Persoonlijke gegevens</h2>";
                  $htmlcontrol .= $leerling_html;
                  $htmlcontrol .= "<div class=\"table-responsive\">";
                  $htmlcontrol .= "<table id=\"dataRequest\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<tbody>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Roepnaam</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $roepnaam . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voorletter</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . utf8_encode($voorletter) . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Adres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $address . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Mobiel</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone1 . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Tel. nr (thuis)</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone2 . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Verzorger na schooltijd</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $vezorger . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Telefoon noodgeval</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone3 . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Geboorteplaats</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $birthplace . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Nationaliteiten</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . utf8_encode($nationaliteit) . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">AZV nr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $azvnumber . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Vervaldatum</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $azvexpiredate . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Identiteitsnr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $idnumber . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voertaal</td>";
                  $htmlcontrol .= "<td>";
                  $htmlcontrol .= "<span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">";
                  #$htmlcontrol .= "<div class=\"form-inline\">"; //$Voertaal

                  $htmlcontrol .= "<span id=\"lblNamet3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $Voertaal . "</span>";
                  /*
                  $htmlcontrol .= "<table><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Ned"? "checked":"") ."> Ned ";
                  $htmlcontrol .= " </td><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Pap"? "checked":"") ."> Pap ";
                  $htmlcontrol .= "</td></tr><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "En"? "checked":"") ."> En ";
                  $htmlcontrol .= "</td><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Sp"? "checked":"") ."> Sp ";
                  $htmlcontrol .= "</td></tr><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Vo"? "checked":"") ."> Sp ";
                  $htmlcontrol .= "</td></tr></table>";
                  */

                  #$htmlcontrol .= "</div>";
                  $htmlcontrol .= "</span>";
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">-</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">E-mailadres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $email . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Datum inschrijving</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . substr($created, 0, 10) . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Vorige school</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $vorigeschool . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Medische Gegevens</h2>";
                  $htmlcontrol .= "<div class=\"table-responsive\">";
                  $htmlcontrol .= "<table class=\"table table-bordered table-colored\">";
                  $htmlcontrol .= "<tbody>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Lichamelijke gebrek</td>"; //$Lichamelijkegebrek,
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Spraak" ? "checked" : "") . "> Spraak</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Gehoor" ? "checked" : "") . "> Gehoor</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Gezicht" ? "checked" : "") . "> Gezicht</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Motoriek" ? "checked" : "") . "> Motoriek</div></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Anders n.l.</td>";
                  $htmlcontrol .= "<td colspan=\"4\">" . $anders . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Bijzonder medische indicatie</td>";
                  $htmlcontrol .= "<td colspan=\"4\">" . $bijzondermedischeindicatie . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Huisarts</td>";
                  $htmlcontrol .= "<td colspan=\"2\">" . $huisarts . "</td>";
                  $htmlcontrol .= "<td>Tel nr.</td>";
                  $htmlcontrol .= "<td>" . $huisartsnr . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Nota's</h2>";
                  $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                  $htmlcontrol .= "<div class=\"col-md-12\">";
                  #$htmlcontrol .= "<textarea class=\"form-control\" disabled>". $notas ."</textarea>";
                  $htmlcontrol .= "<span id=\"lblNamet3notas\">" . $notas . "</span>";
                  $htmlcontrol .= "<br/>";
                  $htmlcontrol .= "<br/>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Contact's</h2>";

                  $htmlcontrol .= $htmlcontrolContact;
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                }
              } else
                $htmlcontrol .= "No results to show";
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $htmlcontrol = $mysqli->error;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function read_leerling_detail_info_merge($studentid, $dummy, $id_family)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $htmlcontrolContact = "";
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $mysqli->set_charset('utf8');
        if ($select = $mysqli->prepare("CALL " . $this->sp_read_student_detail . " (?)")) {
          // $class = '';
          if ($select->bind_param("i", $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;
              $select->bind_result($roepnaam, $voorletter, $address, $phone1, $phone2, $vezorger, $phone3, $birthplace, $nationaliteit, $azvnumber, $azvexpiredate, $idnumber, $Voertaal, $email, $created, $vorigeschool, $Lichamelijkegebrek, $anders, $bijzondermedischeindicatie, $huisarts, $huisartsnr, $notas);
              $select->store_result();
              //  Audit by Caribe Developers
              $spn_audit = new spn_audit();
              $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling', 'Get all student info', false);

              if ($select->num_rows > 0) {
                require_once("classes/spn_contact.php");
                $c = new spn_contact();
                $htmlcontrolContact .= $c->list_contacts($id_family, null);

                require_once("classes/spn_leerling.php");
                $l = new spn_leerling();
                $data_leerling = $l->liststudentdetail($_GET["id"]);
                if ($data_leerling["results"] == 1) {
                  $studentid = $data_leerling["studentid"];
                  $studentnumber = $data_leerling["studentnumber"];
                  $voornamen = $data_leerling["voornamen"];
                  $achternaam = $data_leerling["achternaam"];
                  $geslacht = $data_leerling["geslacht"];
                  $geboortedatum = $data_leerling["geboortedatum"];
                  $klas = $data_leerling["klas"];
                }
                $leerling_html = "<table class=\"table table-bordered table-colored\">
                                <tbody>
                                    <tr>
                                        <td><strong>Student nr.</strong></td>
                                        <td>" . $studentnumber . "</td>
                                        <td><strong>Klas</strong></td>
                                        <td id=\"klas\" name=\"klas\">.
                                        " . $klas . "</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Achternaam</strong></td>
                                        <td> " . $achternaam . " </td>
                                        <td><strong>Voornamen</strong></td>
                                        <td> " . $voornamen . "</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Geslacht</strong></td>
                                        <td>" . $geslacht . "</td>
                                       <td><strong>Geboortedatum</strong></td>
                                            <td>" . $geboortedatum . "</td>
                                    </tr>
                                </tbody>
                            </table>";

                while ($select->fetch()) {
                  $htmlcontrol .= "<div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\" style='float: left;'>Persoonlijke gegevens</h2>";
                  $htmlcontrol .= $leerling_html;
                  $htmlcontrol .= "<div class=\"table-responsive\">";
                  $htmlcontrol .= "<table id=\"dataRequest\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<tbody>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Roepnaam</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $roepnaam . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voorletter</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . utf8_encode($voorletter) . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Adres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $address . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Mobiel</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone1 . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Tel. nr (thuis)</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone2 . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Verzorger na schooltijd</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $vezorger . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Telefoon noodgeval</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $phone3 . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Geboorteplaats</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $birthplace . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Nationaliteiten</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . utf8_encode($nationaliteit) . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">AZV nr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $azvnumber . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Vervaldatum</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $azvexpiredate . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Identiteitsnr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $idnumber . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voertaal</td>";
                  $htmlcontrol .= "<td>";
                  $htmlcontrol .= "<span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">";
                  #$htmlcontrol .= "<div class=\"form-inline\">"; //$Voertaal

                  $htmlcontrol .= "<span id=\"lblNamet3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $Voertaal . "</span>";
                  /*
                  $htmlcontrol .= "<table><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Ned"? "checked":"") ."> Ned ";
                  $htmlcontrol .= " </td><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Pap"? "checked":"") ."> Pap ";
                  $htmlcontrol .= "</td></tr><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "En"? "checked":"") ."> En ";
                  $htmlcontrol .= "</td><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Sp"? "checked":"") ."> Sp ";
                  $htmlcontrol .= "</td></tr><tr><td>";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Vo"? "checked":"") ."> Sp ";
                  $htmlcontrol .= "</td></tr></table>";
                  */

                  #$htmlcontrol .= "</div>";
                  $htmlcontrol .= "</span>";
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">-</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">E-mailadres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $email . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Datum inschrijving</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . substr($created, 0, 10) . "</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Vorige school</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">" . $vorigeschool . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Medische Gegevens</h2>";
                  $htmlcontrol .= "<div class=\"table-responsive\">";
                  $htmlcontrol .= "<table class=\"table table-bordered table-colored\">";
                  $htmlcontrol .= "<tbody>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Lichamelijke gebrek</td>"; //$Lichamelijkegebrek,
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Spraak" ? "checked" : "") . "> Spraak</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Gehoor" ? "checked" : "") . "> Gehoor</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Gezicht" ? "checked" : "") . "> Gezicht</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"" . ($Lichamelijkegebrek == "Motoriek" ? "checked" : "") . "> Motoriek</div></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Anders n.l.</td>";
                  $htmlcontrol .= "<td colspan=\"4\">" . $anders . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Bijzonder medische indicatie</td>";
                  $htmlcontrol .= "<td colspan=\"4\">" . $bijzondermedischeindicatie . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Huisarts</td>";
                  $htmlcontrol .= "<td colspan=\"2\">" . $huisarts . "</td>";
                  $htmlcontrol .= "<td>Tel nr.</td>";
                  $htmlcontrol .= "<td>" . $huisartsnr . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Nota's</h2>";
                  $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                  $htmlcontrol .= "<div class=\"col-md-12\">";
                  #$htmlcontrol .= "<textarea class=\"form-control\" disabled>". $notas ."</textarea>";
                  $htmlcontrol .= "<span id=\"lblNamet3notas\">" . $notas . "</span>";
                  $htmlcontrol .= "<br/>";
                  $htmlcontrol .= "<br/>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Contact's</h2>";
                  $htmlcontrol .= $htmlcontrolContact;
                  $htmlcontrol .= "<div class=\"col-md-6\">";
                  $htmlcontrol .= "<div class=\"col-md-4\" style='border-style: solid;border-width: 1px;'><label style='opacity:.50;'>handtekening</label></div>";
                  $htmlcontrol .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                  $htmlcontrol .= "<div class=\"col-md-4\" style='border-style: solid;border-width: 1px;'><label style='opacity:.50;'>handtekening</label></div>";
                  $htmlcontrol .= "</div>";

                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                }
              } else
                $htmlcontrol .= "No results to show";
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $htmlcontrol = $mysqli->error;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function create_leerling($userguid, $_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $vo, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $profiel)
  {
    $result = 1;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($stmt = $mysqli->prepare("CALL " . $this->sp_create_student . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
        if ($stmt->bind_param("sssssssssssssssssssssssiiiisiiiissssssssis", $userguid, $_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $vo, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $profiel)) {
          if ($stmt->execute()) {
            $result = 1;
            $this->last_insert_id = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();
            //Audit by Caribe Developers
            require_once("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling', 'Create New Leerling', false);
          } else {
            $result = 1;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 1;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 1;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = 1;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function edit_leerling($studentid, $_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $pa, $en, $sp, $vo, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family, $datum_uitschijving, $profiel)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      $sql_query_update = "CALL " . $this->sp_update_student . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      if ($stmt = $mysqli->prepare($sql_query_update)) {
        //if($stmt->bind_param("si",$_DateTime, $studentid))
        if ($stmt->bind_param(
          "sisssssssssssssssissssiiiisiiiissssssssiiss",
          $_DateTime,
          $schoolid,
          $idnumber,
          $studentnumber,
          $class,
          $enrollmentdate,
          $firstname,
          $lastname,
          $sex,
          $_dob,
          $birthplace,
          $address,
          $azvnumber,
          $azvexpiredate,
          $phone1,
          $phone2,
          $colorcode,
          $status,
          $roepnaam,
          $nationaliteit,
          $vezorger,
          $email,
          $ne,
          $en,
          $sp,
          $pa,
          $vo,
          $spraak,
          $gehoor,
          $gezicht,
          $motoriek,
          $huisarts,
          $huisartsnr,
          $anders,
          $voorletter,
          $phone3,
          $vorigeschool,
          $bijzondermedischeindicatie,
          $notas,
          $id_family,
          $studentid,
          $datum_uitschijving,
          $profiel
        )) {
          if ($stmt->execute()) {
            if ($mysqli->affected_rows >= 1) {
              $result = 1;
              //Audit by Caribe Developers
              require_once("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'leerling', 'Update Leerling Info', false);
            } else
              $result = $sql_query_update;
            $stmt->close();
            $mysqli->close();
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function liststudents_picture($schoolID, $class, $baseurl, $detailpage, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);
        $sp_get_pic_leerling = "sp_get_pic_leerling";
        if ($stmt = $mysqli->prepare("CALL " . $this->$sp_get_pic_leerling . " (?,?)")) {
          if ($stmt->bind_param("is", $schoolID, $class)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($id, $studentnumber, $firstname, $lastname, $id_family);
              $stmt->store_result();
              //Audit by Caribe Developers
              require_once("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'leerling', 'List Students by Picture', false);

              $url = $_SERVER['HTTP_REFERER'];
              $path = parse_url($url, PHP_URL_PATH);
              $pathFragments = explode('/', $path);
              $page = end($pathFragments);

              if ($page == 'change_class.php') {


                if ($stmt->num_rows > 0)
                  while ($stmt->fetch()) {
                    $shortname = explode(' ', $firstname);
                    $htmlcontrol .= "<div name=\"drag\"draggable=\"true\" ondragstart=\"drag(event)\" id='" . $id . "'class=\"col-md-3 ui-draggable\" onclick=\"leerlingSelected(this)\">";
                    $htmlcontrol .= "<div value='" . $studentnumber . "' class=\"thumbnail primary-bg-color $id\">";
                    $htmlcontrol .= "<img draggable=\"false\" src=\"profile_students/" . htmlentities($studentnumber) . "-" . htmlentities($schoolID) . "\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-responsive\" >";
                    $htmlcontrol .= "<div class=\"caption\">";
                    $htmlcontrol .= "<small>" . htmlentities(utf8_encode($shortname[0])) . " </small>";
                    $htmlcontrol .= "<br>";
                    $htmlcontrol .= "<small><strong>" . htmlentities(utf8_encode($lastname)) . "</strong></small>";
                    //  $htmlcontrol .= "<input class='pic_profile' type='hidden' name='". $id ."'id='". $id ."' value='". $studentnumber ."'>";
                    $htmlcontrol .= "</div>";
                    $htmlcontrol .= "</div>";
                    $htmlcontrol .= "</div>";
                  }
                else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                if ($stmt->num_rows > 0)
                  while ($stmt->fetch()) {
                    $htmlcontrol .= "<div id=\"dataRequest-student_pic\" class=\"col-md-2\">";
                    $htmlcontrol .= "<a class=\"thumbnail primary-bg-color\" href=\"$baseurl/$detailpage" . "?id=" . htmlentities($id) . "&id_family=" . htmlentities($id_family) . "\">";
                    $htmlcontrol .= "<img src=\"profile_students/" . htmlentities($studentnumber) . "-" . htmlentities($schoolID) . "\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-responsive\">";
                    $htmlcontrol .= "<div class=\"caption\">";
                    $htmlcontrol .= "<h3>" . htmlentities(utf8_encode($firstname)) . " " . htmlentities(utf8_encode($lastname)) . "</h3>";
                    $htmlcontrol .= "<input class='pic_profile' type='hidden' name='" . $id . "'id='" . $id . "' value='" . $studentnumber . "'>";
                    $htmlcontrol .= "</div>";
                    $htmlcontrol .= "</a>";
                    $htmlcontrol .= "</div>";
                  }
                else {
                  $htmlcontrol .= "No results to show";
                }
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function birthday_student_list($_schoolid, $_class, $days, $dummy)
  {

    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $htmlcontrol = "";

    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try {

        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if ($select = $mysqli->prepare("CALL " . $this->sp_read_student_birthday . " (?,?,?)")) {
          if ($_class == "") {
            if ($select->bind_param("isi", $_schoolid, $_class, $days)) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($studentnumber, $firstname, $lastname, $class, $dob, $birthday, $f_birthday, $sex, $date);
                $select->store_result();

                //Audit by Caribe Developers
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling', 'list birthday', false);

                if ($select->num_rows > 0) {
                  $htmlcontrol .= "<table style='background-color:white' class='table'>";
                  while ($select->fetch()) {
                    $htmlcontrol .= "<tbody>";
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td width='10%'><img src=\"profile_students/" . htmlentities($studentnumber) . "\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-thumbnail img-responsive\"></td>";
                    $htmlcontrol .= "<td><h7>" . htmlentities(($date == '1' ? "Tomorrow is " : ($date == '0' ? "Today is " : ""))) . "<b> " . htmlentities(utf8_encode($firstname)) . " " . htmlentities(utf8_encode($lastname)) . " </b>birthday (Class: <b>" . htmlentities($class) . "</b>)<br>";
                    $htmlcontrol .=  "<i class='fa fa-birthday-cake' aria-hidden='true'></i>&nbsp; &nbsp;" . htmlentities($f_birthday) . "<br>";
                    $htmlcontrol .= "" . htmlentities($birthday - $dob) . " years old</h7></td>";
                    $htmlcontrol .= "</tr>";
                    $htmlcontrol .= "</tbody>";
                  }
                  $htmlcontrol .= "</table>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result = 0;
                if ($this->debug) {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            } else {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;
              if ($this->debug) {
                print "error binding parameters";
              }
            }
          } else {
            if ($select->bind_param("isi", $_schoolid, $_class, $days)) {
              if ($select->execute()) {
                $this->error = false;
                $result = 1;
                $select->bind_result($studentnumber, $firstname, $lastname, $class, $dob, $birthday, $f_birthday, $sex, $date);
                $select->store_result();
                //Audit by Caribe Developers
                require_once("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling', 'list birthday', false);
                if ($select->num_rows > 0) {
                  $htmlcontrol .= "<table style='background-color:white' class='table'>";
                  while ($select->fetch()) {
                    $htmlcontrol .= "<tbody>";
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td width='10%'><img src=\"profile_students/" . htmlentities($studentnumber) . "\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-thumbnail img-responsive\"></td>";
                    $htmlcontrol .= "<td><h7>" . htmlentities(($date == '1' ? "Tomorrow is " : ($date == '0' ? "Today is " : ""))) . "<b> " . htmlentities(utf8_encode($firstname)) . " " . htmlentities(utf8_encode($lastname)) . " </b>birthday (Class: <b>" . htmlentities($class) . "</b>)<br>";
                    $htmlcontrol .= "<i class='fa fa-birthday-cake' aria-hidden='true'></i>&nbsp; &nbsp;" . htmlentities($f_birthday) . "<br>";
                    $htmlcontrol .= "" . htmlentities($birthday - $dob) . " years old</h7></td>";
                    $htmlcontrol .= "</tr>";
                    $htmlcontrol .= "</tbody>";
                  }
                  $htmlcontrol .= "</table>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result = 0;
                if ($this->debug) {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            } else {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;
              if ($this->debug) {
                print "error binding parameters";
              }
            }
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error preparing query";
          }
        }
        $returnvalue = $htmlcontrol;
        return $returnvalue;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;
        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }
    return $htmlcontrol . $returnvalue;
  }
  function getfamilies($_schoolid, $dummy)
  {
    $result = 0;

    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    $htmlcontrol = "";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {

      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $mysqli->set_charset('utf8');
      if ($select = $mysqli->prepare("CALL " . $this->sp_read_families . " (?)")) {
        if ($select->bind_param("i", $_schoolid)) {
          $htmlcontrol .= "<option value='0'>New</option>";
          if ($select->execute()) {
            $this->error = false;
            $result = 1;

            $select->bind_result($id_family, $family_name);
            $select->store_result();
            //Audit by Caribe Developers
            require_once("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Family', 'familyasdd', false);

            // $htmlcontrol .= "<select id=\"leerling_family\" name=\"leerling_family\" class=\"form-control\">";

            if ($select->num_rows > 0) {

              while ($select->fetch()) {
                $htmlcontrol .= "<option value='" . $id_family . "'>" . $family_name . "</option>";
              }
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;

            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }
  function list_target_class($school_id, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

        if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)")) {
          if ($stmt->bind_param("i", $school_id)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<select id=\"class_target_list\" name=\"class_target_list\" class=\"form-control\">";
                $htmlcontrol .= "<option selected>Select One Class</option>";
                while ($stmt->fetch()) {
                  if ($class == "Aanmelding") {
                    $htmlcontrol .= "<option value='AF'>AF</option>";
                  } else {
                    $htmlcontrol .= "<option value=" . htmlentities($class) . ">" . htmlentities($class) . "</option>";
                  }
                }
                $htmlcontrol .= "<option value='SN'>SN</option>";
                $htmlcontrol .= "</select>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }

      return $htmlcontrol;
    }
  }
  function list_from_class($school_id, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";

    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

        if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)")) {
          if ($stmt->bind_param("i", $school_id)) {
            if ($stmt->execute()) {
              $this->error = false;
              $result = 1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $htmlcontrol .= "<select id=\"list_from_class\" name=\"list_from_class\" class=\"form-control\">";
                $htmlcontrol .= "<option selected>Select One Class</option>";
                while ($stmt->fetch()) {
                  if ($class == "Aanmelding") {
                    $htmlcontrol .= "<option value='AF'>AF</option>";
                    $htmlcontrol .= "<option value='Aanmelding'>Aanmelding</option>";
                  } else {
                    $htmlcontrol .= "<option value=" . htmlentities($class) . ">" . htmlentities($class) . "</option>";
                  }
                }
                $htmlcontrol .= "<option value='SN'>SN</option>";
                $htmlcontrol .= "</select>";
              } else {
                $htmlcontrol .= "No results to show";
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }

      return $htmlcontrol;
    }
  }
  function change_leerling_class($id_student, $from_class, $to_class, $schooljaar, $comments, $dummy)
  {
    $result = 0;
    if ($dummy)
      $result = 1;
    else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);
      try {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        $level_from = substr($from_class, 0, 1);
        $level_to = substr($to_class, 0, 1);
        if (($level_from == 4 || $level_to == 4) && $_SESSION["SchoolType"] == 2) {
          $sql = "UPDATE students s SET s.class = '$to_class' WHERE s.id =  $id_student;";
          if ($mysqli->query($sql)) {
            $houding = "UPDATE le_houding h SET h.klas = '$to_class' WHERE h.studentid =  '$id_student' and h.schooljaar = '$schooljaar';";
            if ($mysqli->query($houding)) {
              $result = 1;
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
          }
        } else {
          if ($select = $mysqli->prepare("CALL " . $this->sp_change_class . " (?,?,?,?,?)")) {
            if ($select->bind_param("issss", $id_student, $from_class, $to_class, $schooljaar, $comments)) {
              if ($select->execute()) {
                require_once("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'change class', 'change student class', appconfig::GetDummy());
                /* Need to check for errors on database side for primary key errors etc.*/
                if ($_SESSION["SchoolType"] == 2 && $level_from != 4 && $level_to != 4) {
                  $schoolid = $_SESSION["SchoolID"];
                  $change_vaks = "SELECT c.id,(SELECT ID FROM le_vakken WHERE volledigenaamvak = v.volledigenaamvak AND klas = '$to_class' and SchoolID = $schoolid) as ID FROM le_cijfers c INNER JOIN le_vakken v ON v.ID = c.vak WHERE c.schooljaar = '$schooljaar' and c.studentid = $id_student";
                  $result_vaks = $mysqli->query($change_vaks);
                  while ($row_vaks = $result_vaks->fetch_assoc()) {
                    $ID = $row_vaks["ID"];
                    $cijfer = $row_vaks["id"];
                    if ($ID != null && $ID != "" && $ID != 0) {
                      $update_vaks = "UPDATE le_cijfers SET vak = $ID WHERE id = $cijfer";
                      if ($mysqli->query($update_vaks)) {
                        $result = 1;
                      } else {
                        $result = 0;
                        $this->mysqlierror = $mysqli->error;
                      }
                    } else {
                      $result = 0;
                    }
                  }
                } else {
                  $result = 1;
                }
                $select->close();
                $mysqli->close();
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
      } catch (Exception $e) {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;
    }
    function list_target_class($school_id, $dummy)
    {
      $returnvalue = "";
      $sql_query = "";
      $htmlcontrol = "";

      $result = 0;
      if ($dummy)
        $result = 1;
      else {
        mysqli_report(MYSQLI_REPORT_STRICT);
        require_once("spn_utils.php");
        $utils = new spn_utils();

        try {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();
          $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

          if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)")) {
            if ($stmt->bind_param("i", $school_id)) {
              if ($stmt->execute()) {
                $this->error = false;
                $result = 1;
                $stmt->bind_result($class);
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                  $htmlcontrol .= "<select id=\"class_target_list\" name=\"class_target_list\" class=\"form-control\">";
                  $htmlcontrol .= "<option selected>Select One Class</option>";
                  while ($stmt->fetch()) {
                    $htmlcontrol .= "<option value=" . htmlentities($class) . ">" . htmlentities($class) . "</option>";
                  }
                  $htmlcontrol .= "</select>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } catch (Exception $e) {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
          $result = $e->getMessage();
        }

        return $htmlcontrol;
      }
    }
    function list_from_class($school_id, $dummy)
    {
      $returnvalue = "";
      $sql_query = "";
      $htmlcontrol = "";

      $result = 0;
      if ($dummy)
        $result = 1;
      else {
        mysqli_report(MYSQLI_REPORT_STRICT);
        require_once("spn_utils.php");
        $utils = new spn_utils();

        try {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();
          $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort, $dummy);

          if ($stmt = $mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)")) {
            if ($stmt->bind_param("i", $school_id)) {
              if ($stmt->execute()) {
                $this->error = false;
                $result = 1;
                $stmt->bind_result($class);
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                  $htmlcontrol .= "<select id=\"list_from_class\" name=\"list_from_class\" class=\"form-control\">";
                  $htmlcontrol .= "<option selected>Select One Class</option>";
                  while ($stmt->fetch()) {
                    $htmlcontrol .= "<option value=" . htmlentities($class) . ">" . htmlentities($class) . "</option>";
                  }
                  $htmlcontrol .= "</select>";
                } else {
                  $htmlcontrol .= "No results to show";
                }
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } catch (Exception $e) {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
          $result = $e->getMessage();
        }

        return $htmlcontrol;
      }
    }
    function change_leerling_class($id_student, $from_class, $to_class, $schooljaar, $comments, $dummy)
    {
      $result = 0;
      if ($dummy)
        $result = 1;
      else {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $status = 1;

        mysqli_report(MYSQLI_REPORT_STRICT);
        try {
          $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if ($select = $mysqli->prepare("CALL " . $this->sp_change_class . " (?,?,?,?,?)")) {
            if ($select->bind_param("issss", $id_student, $from_class, $to_class, $schooljaar, $comments)) {
              if ($select->execute()) {
                require_once("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'change class', 'change student class', appconfig::GetDummy());
                /* Need to check for errors on database side for primary key errors etc.*/
                $result = 1;
                $select->close();
                $mysqli->close();
              } else {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } catch (Exception $e) {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
        }
        return $result;
      }
    }
  }
  function get_huiswerk_by_class($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_huiswerk_by_class (?,?, ?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_huiswerk_by_student($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_huiswerk_by_student (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_uitgestuurd_by_class($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_uitgestuurd_by_class (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_uitgestuurd_by_student($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_uitgestuurd_by_student (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_laat_by_class($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_telaat_by_class (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_laat_by_student($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_telaat_by_student (?,?, ?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_absentie_by_class($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_absentie_by_class (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_absentie_by_student($schooljaar, $schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $returnvalue = "";

    if ($dummy) {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    } else {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select = $mysqli->prepare("CALL sp_get_absentie_by_student (?,?,?)")) {
          if ($select->bind_param("sii", $schooljaar, $schoolID, $studentid)) {
            if ($select->execute()) {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $htmlcontrol = $value;
                }
              } else
                $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;
            } else {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result = 0;

              if ($this->debug) {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;

          print "error del sql: " . $this->errormessage;

          if ($this->debug) {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      } catch (Exception $e) {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result = 0;

        if ($this->debug) {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;
  }
  function get_leerling_by_full_name($name_leerling, $SchoolID, $class, $baseurl, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol = "";
    $indexvaluetoshow = 3;
    $result = 0;

    mysqli_report(MYSQLI_REPORT_STRICT);
    require_once("spn_utils.php");
    $utils = new spn_utils();
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare("CALL " . $this->sp_get_leerling_by_fullname . "(?,?,?)")) {
        // $class = '';
        if ($select->bind_param("sis", $name_leerling, $SchoolID, $class)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($id_student, $id_family);
            $select->store_result();

            if ($select->num_rows > 1) {
              $detailpage = "leerling.php";
              $htmlcontrol .= "<input class=\"hidden\" id=\"redirect_leerling\" value=\"$baseurl/$detailpage\">";
            } else {
              if ($select->num_rows > 0) {
                while ($select->fetch()) {
                  $detailpage = "leerlingdetail.php";
                  $htmlcontrol .= "<input class=\"hidden\" id=\"redirect_leerling\" value=\"$baseurl/$detailpage" . "?id=" . htmlentities($id_student) . "&id_family=" . htmlentities($id_family) . "\">";
                }
              } else {
                $htmlcontrol = 0;
              }
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
        $htmlcontrol = $mysqli->error;
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
      $result = $e->getMessage();
    }
    return $htmlcontrol;
  }
  function update_securepin_student($studentdUUID, $securepin, $dummy)
  {
    $result = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
      if ($dummy)
        $result = 1;
      else {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($stmt = $mysqli->prepare("CALL " . $this->sp_update_securepin_student . " (?,?)")) {

          if ($stmt->bind_param("si", $studentdUUID, $securepin)) {
            if ($stmt->execute()) {
              $stmt->close();
              $mysqli->close();

              $StudentInfo = $this->liststudentdetail_by_uuid($studentdUUID);
              $contacts = $this->list_contacts_with_email($StudentInfo['id_family']);
              require_once("spn_email.php");
              $email = new spn_email();

              if (count($contacts) > 0) {
                $email->SendSecurePINResetEmail($StudentInfo, $securepin, "no-reply@qwihi.com", "Scol Pa Nos", $contacts);
              }

              $result = 1;
            } else {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          } else {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
    } catch (Exception $e) {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    return $result;
  }
  function liststudentdetail_by_uuid($studentUUID)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class, id_family from students where uuid=?";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("s", $studentUUID)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($studentid, $studentnumber, $firstname, $lastname, $sex, $geboortedatum, $klas, $id_family);
            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $returnarr["results"] = 1;
                $returnarr["studentid"] = $studentid;
                $returnarr["studentnumber"] = $studentnumber;
                $returnarr["firstname"] = utf8_encode($firstname);
                $returnarr["lastname"] = utf8_encode($lastname);
                $returnarr["sex"] = $sex;
                $returnarr["geboortedatum"] = $geboortedatum;
                $returnarr["klas"] = $klas;
                $returnarr["id_family"] = $id_family;
              }
            } else {
              $returnarr["results"] = 0;
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function list_contacts_with_email($id_family)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "SELECT email from contact where id_family = ? and email is not null;";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("i", $id_family)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($email);
            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                array_push($returnarr, $email);
              }
            } else {
              $returnarr = array();
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function check_student_securepin($studentUUID, $securepin)
  {
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select id from students where uuid= ? and securepin = ? ";
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("si", $studentUUID, $securepin)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($studentid);
            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $result = 1;
              }
            } else {

              $result = -2;
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function get_all_students_array_by_klas($klas, $schooljaar, $rapnumer)
  {
    if ($_SESSION['SchoolType'] == 2 && substr($klas, 0, 1) == 4) {
      $rapnumer = 4;
    }
    $returnvalue = "";
    $user_permission = "";
    $sql_query = "";
    $returnarr = array();
    $studentlist = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $schoolId = $_SESSION['SchoolID'];
    $s = new spn_setting();
    $s->getsetting_info($schoolId, false);
    if ($schooljaar == "2017-2018" && $klas == "5A" && $_SESSION["SchoolID"] == 9) {
      $sql_query = "SELECT DISTINCT c.studentid,s.studentnumber,s.firstname,s.lastname,s.sex,s.dob,s.class FROM le_cijfers c LEFT JOIN students s ON c.studentid = s.id where c.klas = ? and c.schooljaar = '2017-2018' and s.schoolid = ? ORDER BY s.lastname,s.firstname";
    } else {
      $schooljaar_array = explode("-", $schooljaar);
      $schooljaar_pasado = $schooljaar_array[0];
      $type = ($_SESSION['SchoolType'] == 2 || $_SESSION["SchoolID"] == 8 || $_SESSION['SchoolID'] == 18 || $schooljaar_pasado <= 2021) ? "le_cijfers" : "le_cijfers_ps";
      $sql_query = "SELECT DISTINCT s.id,s.studentnumber,s.firstname,s.lastname,s.sex,s.dob,c.klas as class,s.profiel,s.birthplace FROM students s INNER JOIN " . $type . " c ON s.id = c.studentid where c.klas = ? and s.schoolid = ? and s.status = 1 and c.rapnummer <= ? and c.schooljaar = ? ORDER BY";
      $sql_order = " lastname " . $s->_setting_sort . ", firstname";
      if ($s->_setting_mj) {
        $sql_query .= " sex " . $s->_setting_sort . ", " . $sql_order;
      } else {
        $sql_query .=  $sql_order;
      }
    }

    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if ($select = $mysqli->prepare($sql_query)) {
        if ($select->bind_param("siis", $klas, $schoolId, $rapnumer, $schooljaar)) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($studentid, $studentnumber, $voornamen, $achternaam, $geslacht, $geboortedatum, $klas, $profiel, $birthplace);
            $select->store_result();

            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $returnarr["studentid"] = $studentid;
                $returnarr["studentnumber"] = $studentnumber;
                $returnarr["voornamen"] = utf8_encode($voornamen);
                $returnarr["achternaam"] = utf8_encode($achternaam);
                $returnarr["geslacht"] = $geslacht;
                $returnarr["geboortedatum"] = $geboortedatum;
                $returnarr["klas"] = $klas;
                $returnarr["profiel"] = $profiel;
                $returnarr["birthplace"] = $birthplace;

                array_push($studentlist, $returnarr);
              }
            } else {
              $returnarr["results"] = 0;
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result = 0;
          if ($this->debug) {
            print "error binding parameters";
          }
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      $returnvalue = $studentlist;
      return $returnvalue;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;
      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }

  function check_is_student_by_studentnumber($studentnumber, $dummy)
  {
    require_once("spn_utils.php");
    $sql_query = "";
    $htmlcontrol = "";
    $result = 0;
    $dummy = 0;
    require_once("DBCreds.php");
    $DBCreds = new DBCreds();
    date_default_timezone_set("America/Aruba");
    $_DateTime = date("Y-m-d H:i:s");
    $status = 1;
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      if ($select = $mysqli->prepare("CALL sp_check_student_by_student_number (?,?)")) {
        if ($select->bind_param("ss", $studentnumber, $_SESSION["SchoolID"])) {
          if ($select->execute()) {
            $this->error = false;
            $result = 1;
            $select->bind_result($count);
            $select->store_result();
            if ($select->num_rows > 0) {
              while ($select->fetch()) {
                $htmlcontrol .= $count;
              }
            } else {
              $htmlcontrol .= "No results to show";
            }
          } else {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result = 0;
            if ($this->debug) {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        } else {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      } else {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result = 0;
        print "error del sql: " . $this->errormessage;
        if ($this->debug) {
          print "error preparing query";
        }
      }
      // Cierre del prepare
      $returnvalue = $htmlcontrol;
    } catch (Exception $e) {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result = 0;

      if ($this->debug) {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
}
