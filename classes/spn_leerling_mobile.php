<?php

require_once ("spn_setting.php");

class spn_leerling_mobile
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
  public $debug = false;
  public $error = "";
  public $errormessage = "";
  // public $sp_get_huiswerk_by_class ="sp_get_huiswerk_by_class";
  public $sp_get_huiswerk_by_student ="sp_get_huiswerk_by_student";

  function createleerling($schoolid,$idnumber,$studentnumber,$class,$enrollmentdate,$firstname,$lastname,$sex,$dob,$birthplace,$address,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode)
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
    if($utils->converttomysqldate($dob) != false)
    {
      $_dob = $utils->converttomysqldate($dob);
    }
    else
    {
      $_dob = null;
    }
    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($stmt=$mysqli->prepare("insert into " . $this->tablename_leerling . "(created,schoolid,idnumber,studentnumber,class,enrollmentdate,firstname,lastname,sex,dob,birthplace,address,azvnumber,azvexpiredate,phone1,phone2,colorcode,status) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sisssssssssssssssi",$_DateTime,$schoolid,$idnumber,$studentnumber,$class,$enrollmentdate,$firstname,$lastname,$sex,$_dob,$birthplace,$address,$azvnumber,$azvexpiredate,$phone1,$phone2,$colorcode,$status))
        {
          if($stmt->execute())
          {
            /*
            Need to check for errors on database side for primary key errors etc.
            */
            $result = 1;
            $this->last_insert_id = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();

            //  Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling','Create Leerling',false);

          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function liststudents($studentid,$baseurl,$detailpage)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "SELECT id, studentnumber, firstname, lastname, sex, dob, class";
    $sql_query .= ", uuid, created, updated, schoolid, idnumber, enrollmentdate,  birthplace, address, azvnumber, azvexpiredate, ";
    $sql_query .= "phone1, phone2, colorcode, status, roepnaam, nationaliteit, vezorger, email, ne, en, sp, pa, voertaalanders, spraak, gehoor, gezicht, motoriek, medicatieanders, ";
    $sql_query .= "bijzonderemedisch, huisarts, huisartsnr, persoonlijkeeigenschappen, thuissituatie, fysiekebijzonderheden, schoolleereigenschappen, socialeeigenschappen, anders, voorletter, phone3, vorigeschool, bijzondermedischeindicatie, notas, id_family ";
    //if($klas_in == "ALL")
    $sql_query .= " FROM students WHERE id=?";
    //else
   // $sql_query .= " FROM students WHERE class=? and schoolid=? order by";


    require_once("spn_utils.php");
    $utils = new spn_utils();
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select=$mysqli->prepare($sql_query))
      {

          if($select->bind_param("i",$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result=1;
              $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas, $uuid, $created, $updated, $schoolid, $idnumber, $enrollmentdate, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit , $vezorger, $email, $ne, $en, $sp, $pa, $voertaalanders , $spraak , $gehoor, $gezicht, $motoriek, $medicatieanders, $bijzonderemedisch, $huisarts, $huisartsnr, $persoonlijkeeigenschappen, $thuissituatie, $fysiekebijzonderheden, $schoolleereigenschappen, $socialeeigenschappen, $anders, $voorletter, $phone3,  $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family);
              $select->store_result();

              //Audit by Caribe Developers
              // $spn_audit = new spn_audit();
              // $UserGUID = $_SESSION['UserGUID'];
              // $spn_audit->create_audit($UserGUID, 'leerling','Beheer List Students',false);

              if($select->num_rows > 0)
              {


				$htmlcontrol .= "<div id=\"dataRequest-student_detail\" class=\"col-xs-12 table-striped\">";

				$htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-striped\" data-table=\"yes\">";
                $htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th></thead>";

				$htmlcontrol .= "<tbody>";
                while($select->fetch())
                {
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>". htmlentities($studentnumber) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities(utf8_encode($voornamen)) ."</td>";
                  $htmlcontrol .= "<td>". htmlentities(utf8_encode($achternaam)) ."</td>";

                  // Hide controls
                  $htmlcontrol .= "<input type='hidden' name='id' value='". $studentid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='uuid' value='". $uuid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='created' value='". ($utils->convertfrommysqldate($created) != false && $created != "" ? htmlentities($utils->convertfrommysqldate($created)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='updated' value='". ($utils->convertfrommysqldate($updated) != false && $updated != "" ? htmlentities($utils->convertfrommysqldate($updated)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolid' value='". $schoolid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='idnumber' value='". $idnumber ."'>";
                  $htmlcontrol .= "<input type='hidden' name='enrollmentdate' value='". $enrollmentdate ."'>";
                  $htmlcontrol .= "<input type='hidden' name='birthplace' value='". $birthplace ."'>";
                  $htmlcontrol .= "<input type='hidden' name='address' value='". $address ."'>";
                  $htmlcontrol .= "<input type='hidden' name='azvnumber' value='". $azvnumber ."'>";
                  $htmlcontrol .= "<input type='hidden' name='azvexpiredate' value='". ($utils->convertfrommysqldate($azvexpiredate) != false && $azvexpiredate != "" ? htmlentities($utils->convertfrommysqldate($azvexpiredate)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone1' value='". $phone1 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone2' value='". $phone2 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='colorcode' value='". $colorcode ."'>";
                  $htmlcontrol .= "<input type='hidden' name='status' value='". $status ."'>";
                  $htmlcontrol .= "<input type='hidden' name='roepnaam' value='". $roepnaam ."'>";
                  $htmlcontrol .= "<input type='hidden' name='nationaliteit' value='". utf8_encode($nationaliteit) ."'>";
                  $htmlcontrol .= "<input type='hidden' name='vezorger' value='". $vezorger ."'>";
                  $htmlcontrol .= "<input type='hidden' name='email' value='". $email ."'>";
                  $htmlcontrol .= "<input type='hidden' name='ne' value='". $ne ."'>";
                  $htmlcontrol .= "<input type='hidden' name='en' value='". $en ."'>";
                  $htmlcontrol .= "<input type='hidden' name='sp' value='". $sp ."'>";
                  $htmlcontrol .= "<input type='hidden' name='pa' value='". $pa ."'>";
                  $htmlcontrol .= "<input type='hidden' name='voertaalanders' value='". $voertaalanders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='spraak' value='". $spraak ."'>";
                  $htmlcontrol .= "<input type='hidden' name='gehoor' value='". $gehoor ."'>";
                  $htmlcontrol .= "<input type='hidden' name='gezicht' value='". $gezicht ."'>";
                  $htmlcontrol .= "<input type='hidden' name='motoriek' value='". $motoriek ."'>";
                  $htmlcontrol .= "<input type='hidden' name='medicatieanders' value='". $medicatieanders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzonderemedisch' value='". $bijzonderemedisch ."'>";
                  $htmlcontrol .= "<input type='hidden' name='huisarts' value='". $huisarts ."'>";
                  $htmlcontrol .= "<input type='hidden' name='huisartsnr' value='". $huisartsnr ."'>";
                  $htmlcontrol .= "<input type='hidden' name='persoonlijkeeigenschappen' value='". $persoonlijkeeigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='thuissituatie' value='". $thuissituatie ."'>";
                  $htmlcontrol .= "<input type='hidden' name='fysiekebijzonderheden' value='". $fysiekebijzonderheden ."'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolleereigenschappen' value='". $schoolleereigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='socialeeigenschappen' value='". $socialeeigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='anders' value='". $anders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='voorletter' value='". $voorletter ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone3' value='". $phone3 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='vorigeschool' value='". $vorigeschool ."'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzondermedischeindicatie' value='". $bijzondermedischeindicatie ."'>";
                  $htmlcontrol .= "<input type='hidden' name='notasval' value='". $notas ."'>";
                  // End hide controls
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "</tr>";
                }
				$htmlcontrol .= "<thead><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th></tr></thead>";
				$htmlcontrol .= "<td>". htmlentities($geslacht) ."</td>";
                $htmlcontrol .= "<td>". ($utils->convertfrommysqldate($geboortedatum) != false && $geboortedatum != "" ? htmlentities($utils->convertfrommysqldate($geboortedatum)) : "") ."</td>";
                $htmlcontrol .= "<td>". htmlentities($klas) ."</td>";

                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";



				$htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Persoonlijke gegevens</h2>";
                $htmlcontrol .= "<div class=\"table-responsive\">";
                $htmlcontrol .= "<table id=\"dataRequest\" class=\"table table-striped\" data-table=\"yes\">";
                $htmlcontrol .= "<tbody>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td class=\"bold\">Roepnaam</td>";
                $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $roepnaam ."</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Voorletter</td>";
                $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". utf8_encode($voorletter) ."</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td class=\"bold\">Adres</td>";
                $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $address ."</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Tel. nr (thuis)</td>";
                $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone1 ."</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td class=\"bold\">Mobiel</td>";
                $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone2 ."</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Verzorger na schooltijd</td>";
                $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $vezorger ."</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td class=\"bold\">Telefoon noodgeval</td>";
                $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone3 ."</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Geboorte plaats</td>";
                $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $birthplace ."</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td class=\"bold\">Nationaliteiten</td>";
                $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". utf8_encode($nationaliteit) ."</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Expire datum AZV</td>";
                $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $azvexpiredate ."</span></td>";

				//$htmlcontrol .= "<td class=\"bold\">-</td>";
                // $htmlcontrol .= "<td></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
				$htmlcontrol .= "<td class=\"bold\">Datum inschrijving</td>";
                $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $created ."</span></td>";

                $htmlcontrol .= "<td class=\"bold\">AZV nr.</td>";
                $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $azvnumber ."</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
				$htmlcontrol .= "<td class=\"bold\">Vorige school</td>";
                $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $vorigeschool ."</span></td>";

                $htmlcontrol .= "<td class=\"bold\">Identiteits nr.</td>";
                $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $idnumber ."</span></td>";
                // $htmlcontrol .= "<td class=\"bold\">Voertaal</td>";
                // $htmlcontrol .= "<td>";
                //$htmlcontrol .= "<span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">";
                //$htmlcontrol .= "<div class=\"form-inline\">"; //$Voertaal
                // $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Ned"? "checked":"") ."> Ned ";
                // $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Pap"? "checked":"") ."> Pap ";
                // $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "En"? "checked":"") ."> En ";
                // $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Sp"? "checked":"") ."> Sp ";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "</span>";
                $htmlcontrol .= "</td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                //$htmlcontrol .= "<td class=\"bold\">-</td>";
                //$htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">-</span></td>";
                $htmlcontrol .= "<td class=\"bold\">Email adres</td>";
                $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $email . "</span></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Medische Gegevens</h2>";
                $htmlcontrol .= "<div class=\"table-responsive\">";
                $htmlcontrol .= "<table class=\"table table-striped\">";
                $htmlcontrol .= "<tbody>";
                $htmlcontrol .= "<tr>";
                //$htmlcontrol .= "<td>Lichamelijke gebrek</td>"; //$Lichamelijkegebrek,
                // $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Spraak"? "checked":"") ."> Spraak</div></td>";
                // $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Gehoor"? "checked":"") ."> Gehoor</div></td>";
                // $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Gezicht"? "checked":"") ."> Gezicht</div></td>";
                // $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Motoriek"? "checked":"") ."> Motoriek</div></td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td>Anders n.l.</td>";
                $htmlcontrol .= "<td colspan=\"4\">" . $anders . "</td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td>Bijzonder medische indicatie</td>";
                $htmlcontrol .= "<td colspan=\"4\">". $bijzondermedischeindicatie ."</td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "<tr>";
                $htmlcontrol .= "<td>Huis Arts</td>";
                $htmlcontrol .= "<td colspan=\"2\">". $huisarts ."</td>";
                $htmlcontrol .= "<td>Tel nr.</td>";
                $htmlcontrol .= "<td>". $huisartsnr ."</td>";
                $htmlcontrol .= "</tr>";
                $htmlcontrol .= "</tbody>";
                $htmlcontrol .= "</table>";
                $htmlcontrol .= "</div>";
                $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Nota's</h2>";
                $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                $htmlcontrol .= "<div class=\"col-md-12\">";
				$htmlcontrol .= "<textarea class=\"form-control\" disabled>". $notas ."</textarea>";
				$htmlcontrol .= "<br/>";
				$htmlcontrol .= "</div>";
				$htmlcontrol .= "</div>";

				/*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */


				$htmlcontrol .= "</div>";

              }
              else
              {
                $htmlcontrol .= "No results to show";
              }
            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;
              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            /* error binding parameters */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;
            if($this->debug)
            {
              print "error binding parameters";
            }
          }

      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;
        if($this->debug)
        {
          print "error preparing query";
        }
      }
      $returnvalue = $htmlcontrol;
      return $returnvalue;
    }
    catch(Exception $e)
    {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result=0;
      if($this->debug)
      {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function liststudentdetail($studentdbid)
  {
    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $returnarr = array();
    mysqli_report(MYSQLI_REPORT_STRICT);
    $sql_query = "select id,studentnumber,firstname,lastname,sex,dob,class from students where id=?";
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("i",$studentdbid))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;
            $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas);
            $select->store_result();
            //  Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling','List leerling Details',false);

            if($select->num_rows > 0)
            {
              while($select->fetch())
              {
                $returnarr["results"] = 1;
                $returnarr["studentid"] = $studentid;
                $returnarr["studentnumber"] = $studentnumber;
                $returnarr["voornamen"] = utf8_encode($voornamen);
                $returnarr["achternaam"] = utf8_encode($achternaam);
                $returnarr["geslacht"] = $geslacht;
                $returnarr["geboortedatum"] = $geboortedatum;
                $returnarr["klas"] = $klas;
              }
            }
            else
            {
              $returnarr["results"] = 0;
            }
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;
            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;
          if($this->debug)
          {
            print "error binding parameters";
          }
        }
      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;
        if($this->debug)
        {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    }
    catch(Exception $e)
    {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result=0;
      if($this->debug)
      {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }
  function createextra($Xstudentid,$Xklas,$Xschoolid)
  {
    require_once("spn_avi.php");
    $a = new spn_avi();
    /* TODO: change nill to null */
    $a->createavi($Xstudentid,"2015-2016",0,$Xklas,"spn",nill,nill,nill,"","",nill,nill,nill,"","",nill,nill,nill,"","");
    //  Audit by Caribe Developers
    $spn_audit = new spn_audit();
    $spn_audit->create_audit($_SESSION['UserGUID'], 'AVI','Change Nill to Null',false);

    require_once("spn_houding.php");
    $h = new spn_houding();
    $h->createhouding($Xstudentid,"2015-2016",1,$Xklas,"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    $h->createhouding($Xstudentid,"2015-2016",2,$Xklas,"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    $h->createhouding($Xstudentid,"2015-2016",3,$Xklas,"spn",0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    //  Audit by Caribe Developers
    $spn_audit = new spn_audit();
    $spn_audit->create_audit($_SESSION['UserGUID'], 'Houding','Create Houding in (0)',false);

    require_once("spn_cijfers.php");
    $sql_query = "select distinct (vak) from le_vakken where schoolid=? and klas=?";
    try
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select=$mysqli->prepare($sql_query))
      {
        if($select->bind_param("is",$Xschoolid,$Xklas))
        {
          if($select->execute())
          {
            $this->error = false;
            $result=1;
            $select->bind_result($Xvak);
            $select->store_result();
            //  Audit by Caribe Developers
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Vak','Select Vak For Id Class and Id School',false);

            if($select->num_rows > 0)
            {
              while($select->fetch())
              {
                $c = new spn_cijfers();
                $c->createcijfers($Xstudentid,"2015-2016",1,$Xvak,$Xklas,"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
                $c->createcijfers($Xstudentid,"2015-2016",2,$Xvak,$Xklas,"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
                $c->createcijfers($Xstudentid,"2015-2016",3,$Xvak,$Xklas,"spn",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);

                //  Audit by Caribe Developers
                $spn_audit = new spn_audit();
                $spn_audit->create_audit($_SESSION['UserGUID'], 'Cijfers','Create Cijfer in NULL',false);

              }
            }
            else
            {
              $returnarr["results"] = 0;
            }
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;
            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          /* error binding parameters */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;
          if($this->debug)
          {
            print "error binding parameters";
          }
        }
      }
      else
      {
        /* error preparing query */
        $this->error = true;
        $this->errormessage = $mysqli->error;
        $result=0;
        if($this->debug)
        {
          print "error preparing query";
        }
      }
      $returnvalue = $returnarr;
      return $returnvalue;
    }
    catch(Exception $e)
    {
      $this->error = true;
      $this->errormessage = $e->getMessage();
      $result=0;
      if($this->debug)
      {
        print "exception: " . $e->getMessage();
      }
    }
    return $returnvalue;
  }

  function read_leerling_detail_info_basic($studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if ($select =$mysqli->prepare("CALL " . $this->sp_read_student_detail ." (?)"))
        {
          // $class = '';
          if ($select->bind_param("i", $studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result=1;
              $select->bind_result($roepnaam, $voorletter, $address, $phone1, $phone2, $vezorger, $phone3, $birthplace, $nationaliteit, $azvnumber, $azvexpiredate, $idnumber, $Voertaal, $email, $created, $vorigeschool, $Lichamelijkegebrek, $anders, $bijzondermedischeindicatie, $huisarts, $huisartsnr, $notas);
              $select->store_result();
              //  Audit by Caribe Developers
              //$spn_audit = new spn_audit();
              //$spn_audit->create_audit($_SESSION['UserGUID'], 'leerling','Get all student info',false);

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
				  $htmlcontrol .= "<div id=\"dataRequest-student_detail\" class=\"col-md-3 col-xs-6 col-sm-4\">";
                  $htmlcontrol .= "<div class=\"box-title full-inset brd-bottom\">";
				  $htmlcontrol .= "<div class=\"row\">";
				  $htmlcontrol .= "<h2 class=\"col-md-12\"><?php print $voornamen . chr(32). $achternaam ?></h2>";
				  $htmlcontrol .= "</div>";
				  $htmlcontrol .= "</div>";



				  $htmlcontrol .= "</div>";
                }
              }
              else
              $htmlcontrol .= "No results to show";
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $htmlcontrol = $mysqli->error;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function read_leerling_detail_info($studentid, $dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $indexvaluetoshow = 3;
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if ($select =$mysqli->prepare("CALL " . $this->sp_read_student_detail ." (?)"))
        {
          // $class = '';
          if ($select->bind_param("i", $studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result=1;
              $select->bind_result($roepnaam, $voorletter, $address, $phone1, $phone2, $vezorger, $phone3, $birthplace, $nationaliteit, $azvnumber, $azvexpiredate, $idnumber, $Voertaal, $email, $created, $vorigeschool, $Lichamelijkegebrek, $anders, $bijzondermedischeindicatie, $huisarts, $huisartsnr, $notas);
              $select->store_result();
              //  Audit by Caribe Developers
              //$spn_audit = new spn_audit();
              //$spn_audit->create_audit($_SESSION['UserGUID'], 'leerling','Get all student info',false);

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {

                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Persoonlijke gegevens</h2>";
                  $htmlcontrol .= "<div class=\"table-responsive\">";
                  $htmlcontrol .= "<table id=\"dataRequest\" class=\"table table-bordered table-colored\" data-table=\"yes\">";
                  $htmlcontrol .= "<tbody>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Roepnaam</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $roepnaam ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voorletter</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". utf8_encode($voorletter) ."</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Adres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $address ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Tel. nr (thuis)</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone1 ."</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Mobiel</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone2 ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Verzorger na schooltijd</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $vezorger ."</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Telefoon noodgeval</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $phone3 ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Geboorte plaats</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $birthplace ."</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Nationaliteiten</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". utf8_encode($nationaliteit) ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">AZV nr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $azvnumber ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Expire datum AZV</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $azvexpiredate ."</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Identiteits nr.</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $idnumber ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Voertaal</td>";
                  $htmlcontrol .= "<td>";
                  $htmlcontrol .= "<span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">";
                  $htmlcontrol .= "<div class=\"form-inline\">"; //$Voertaal
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Ned"? "checked":"") ."> Ned ";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Pap"? "checked":"") ."> Pap ";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "En"? "checked":"") ."> En ";
                  $htmlcontrol .= "<input type=\"checkbox\" class=\"form-control\"". ($Voertaal == "Sp"? "checked":"") ."> Sp ";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</span>";
                  $htmlcontrol .= "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">-</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">-</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Email adres</td>";
                  $htmlcontrol .= "<td><span id=\"lblName1\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $email . "</span></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td class=\"bold\">Datum inschrijving</td>";
                  $htmlcontrol .= "<td><span id=\"lblName2\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $created ."</span></td>";
                  $htmlcontrol .= "<td class=\"bold\">Vorige school</td>";
                  $htmlcontrol .= "<td><span id=\"lblName3\" data-student-id=\"" . $studentid . "\" data-column=\"8\" data-row=\"2\" class=\"editable\">". $vorigeschool ."</span></td>";
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
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Spraak"? "checked":"") ."> Spraak</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Gehoor"? "checked":"") ."> Gehoor</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Gezicht"? "checked":"") ."> Gezicht</div></td>";
                  $htmlcontrol .= "<td><div class=\"form-inline\"><input type=\"checkbox\" class=\"form-control\"". ($Lichamelijkegebrek == "Motoriek"? "checked":"") ."> Motoriek</div></td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Anders n.l.</td>";
                  $htmlcontrol .= "<td colspan=\"4\">" . $anders . "</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Bijzonder medische indicatie</td>";
                  $htmlcontrol .= "<td colspan=\"4\">". $bijzondermedischeindicatie ."</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "<tr>";
                  $htmlcontrol .= "<td>Huis Arts</td>";
                  $htmlcontrol .= "<td colspan=\"2\">". $huisarts ."</td>";
                  $htmlcontrol .= "<td>Tel nr.</td>";
                  $htmlcontrol .= "<td>". $huisartsnr ."</td>";
                  $htmlcontrol .= "</tr>";
                  $htmlcontrol .= "</tbody>";
                  $htmlcontrol .= "</table>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<h2 class=\"primary-color mrg-bottom\">Nota's</h2>";
                  $htmlcontrol .= "<div class=\"row mrg-bottom\">";
                  $htmlcontrol .= "<div class=\"col-md-12\">";
                  $htmlcontrol .= "<textarea class=\"form-control\" disabled>". $notas ."</textarea>";
                  $htmlcontrol .= "<br/>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                }
              }
              else
              $htmlcontrol .= "No results to show";
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
          $htmlcontrol = $mysqli->error;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }
  function create_leerling($_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family)
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
    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($stmt=$mysqli->prepare("CALL " . $this->sp_create_student . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
      {
        if($stmt->bind_param("sisssssssssssssssissssiiiiiiiissssssssi",$_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family))
        {
          if($stmt->execute())
          {
            $result = 1;
            $this->last_insert_id = $mysqli->insert_id;
            $stmt->close();
            $mysqli->close();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'leerling','Create New Leerling',false);

          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        $result = 0;
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function edit_leerling($studentid, $_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family)
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
    try
    {
      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      $sql_query_update = "CALL " . $this->sp_update_student . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      if($stmt=$mysqli->prepare($sql_query_update))
      {
        //if($stmt->bind_param("si",$_DateTime, $studentid))
        if($stmt->bind_param("sisssssssssssssssissssiiiiiiiissssssssii",$_DateTime, $schoolid, $idnumber, $studentnumber, $class, $enrollmentdate, $firstname, $lastname, $sex, $_dob, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit, $vezorger, $email, $ne, $en, $sp, $pa, $spraak, $gehoor, $gezicht, $motoriek, $huisarts, $huisartsnr, $anders, $voorletter, $phone3, $vorigeschool, $bijzondermedischeindicatie, $notas,$id_family, $studentid))
        {
          if($stmt->execute())
          {
            if($mysqli->affected_rows >= 1)
            {
              $result = 1;
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'leerling','Update Leerling Info',false);
            }
            else
            $result = $sql_query_update;
            $stmt->close();
            $mysqli->close();
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      else
      {
        $this->mysqlierror = $mysqli->error;
        $this->mysqlierrornumber = $mysqli->errno;
      }
    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }
    return $result;
  }
  function liststudents_picture_mobile($schoolID,$class,$baseurl,$detailpage,$dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {

      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {

        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);
        $sp_get_pic_leerling = "sp_get_pic_leerling";
        if ($stmt =$mysqli->prepare("CALL " . $this->$sp_get_pic_leerling ." (?,?)"))
        {

          if ($stmt->bind_param("is",$schoolID,$class))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($id,$studentnumber, $firstname,$lastname, $id_family);
              $stmt->store_result();
              //Audit by Caribe Developers
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'leerling','List Students by Picture',false);

              $url=$_SERVER['HTTP_REFERER'];
              $path = parse_url($url, PHP_URL_PATH);
              $pathFragments = explode('/', $path);
              $page = end($pathFragments);



			if($stmt->num_rows > 0)
				while($stmt->fetch())
				{
				  $htmlcontrol .= "<div id=\"dataRequest-student_pic\" class=\"col-md-3 col-xs-6 col-sm-3\">";
				  $htmlcontrol .= "<a class=\"thumbnail primary-bg-color\" href=\"$baseurl/mobile/$detailpage" . "?id=" . htmlentities($id) ."&id_family=" . htmlentities($id_family) ."\">";
				  //$htmlcontrol .= "<a class=\"thumbnail primary-bg-color\" href=\"\">";
				  $htmlcontrol .= "<img src=\"../profile_students/".htmlentities($studentnumber)."\" onerror=\"this.src='../profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-responsive\">";
				  $htmlcontrol .= "<div class=\"caption\">";
				  $htmlcontrol .= "<h4>" . htmlentities(utf8_encode($firstname))." " .htmlentities(utf8_encode($lastname))."</h4>";
				  $htmlcontrol .= "<input class='pic_profile' type='hidden' name='". $id ."'id='". $id ."' value='". $studentnumber ."'>";
				  $htmlcontrol .= "</div>";
				  $htmlcontrol .= "</a>";
				  $htmlcontrol .= "</div>";
				}
			else
			{
			  $htmlcontrol .= "No results to show";
			}


            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }
      return $htmlcontrol;
    }
  }

  function birthday_student_list ($_schoolid,$_class, $days, $dummy)
  {

    $returnvalue = "";
    $user_permission="";
    $sql_query = "";
    $htmlcontrol="";

    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {

        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select=$mysqli->prepare("CALL " . $this->sp_read_student_birthday . " (?,?,?)"))
        {
          if($_class == "")
          {
            if($select->bind_param("isi",$_schoolid,$_class, $days))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;
                $select->bind_result($studentnumber,$firstname,$lastname,$class,$dob,$birthday,$f_birthday,$sex, $date);
                $select->store_result();

                //Audit by Caribe Developers
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling','list birthday',false);

                if($select->num_rows > 0)
                {
                  $htmlcontrol .= "<table style='background-color:white' class='table'>";
                  while($select->fetch())
                  {
                    $htmlcontrol .= "<tbody>";
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td width='10%'><img src=\"profile_students/".htmlentities($studentnumber)."\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-thumbnail img-responsive\"></td>";
                    $htmlcontrol .= "<td><h7>". htmlentities(($date == '1'? "Tomorrow is ": ($date == '0'? "Today is ": ""))) ."<b> " .htmlentities(utf8_encode($firstname))." " .htmlentities(utf8_encode($lastname))." </b>birthday (Class: <b>" .htmlentities($class)."</b>)<br>";
                    $htmlcontrol .=	"<i class='fa fa-birthday-cake' aria-hidden='true'></i>&nbsp; &nbsp;" .htmlentities($f_birthday). "<br>";
                    $htmlcontrol .= "".htmlentities($birthday - $dob)." years old</h7></td>";
                    $htmlcontrol .= "</tr>";
                    $htmlcontrol .= "</tbody>";
                  }
                  $htmlcontrol .= "</table>";
                }
                else
                {
                  $htmlcontrol .= "No results to show";
                }
              }
              else
              {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result=0;
                if($this->debug)
                {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            }
            else
            {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;
              if($this->debug)
              {
                print "error binding parameters";
              }
            }
          }
          else
          {
            if($select->bind_param("isi",$_schoolid,$_class,$days))
            {
              if($select->execute())
              {
                $this->error = false;
                $result=1;
                $select->bind_result($studentnumber,$firstname,$lastname,$class,$dob,$birthday,$f_birthday,$sex, $date);
                $select->store_result();
                //Audit by Caribe Developers
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'leerling','list birthday',false);
                if($select->num_rows > 0)
                {
                  $htmlcontrol .= "<table style='background-color:white' class='table'>";
                  while($select->fetch())
                  {
                    $htmlcontrol .= "<tbody>";
                    $htmlcontrol .= "<tr>";
                    $htmlcontrol .= "<td width='10%'><img src=\"profile_students/".htmlentities($studentnumber)."\" onerror=\"this.src='profile_students/unknow.png';\" alt=\"Student Profile\" class=\"img-thumbnail img-responsive\"></td>";
                    $htmlcontrol .= "<td><h7>". htmlentities(($date == '1'? "Tomorrow is ": ($date == '0'? "Today is ": ""))) ."<b> " .htmlentities(utf8_encode($firstname))." " .htmlentities(utf8_encode($lastname))." </b>birthday (Class: <b>" .htmlentities($class)."</b>)<br>";
                    $htmlcontrol .= "<i class='fa fa-birthday-cake' aria-hidden='true'></i>&nbsp; &nbsp;" .htmlentities($f_birthday). "<br>";
                    $htmlcontrol .= "".htmlentities($birthday - $dob)." years old</h7></td>";
                    $htmlcontrol .= "</tr>";
                    $htmlcontrol .= "</tbody>";
                  }
                  $htmlcontrol .= "</table>";
                }
                else
                {
                  $htmlcontrol .= "No results to show";
                }
              }
              else
              {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result=0;
                if($this->debug)
                {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            }
            else
            {
              /* error binding parameters */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;
              if($this->debug)
              {
                print "error binding parameters";
              }
            }
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;
          if($this->debug)
          {
            print "error preparing query";
          }
        }
        $returnvalue = $htmlcontrol;
        return $returnvalue;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;
        if($this->debug)
        {
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
    $htmlcontrol="";

    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
      if($select=$mysqli->prepare("CALL " . $this->sp_read_families . " (?)"))

      {
        if($select->bind_param("i",$_schoolid))
        {
          $htmlcontrol .= "<option value='0'>New</option>";
          if($select->execute())
          {
            $this->error = false;
            $result = 1;

            $select->bind_result($id_family,$family_name);
            $select->store_result();
            //Audit by Caribe Developers
            require_once ("spn_audit.php");
            $spn_audit = new spn_audit();
            $spn_audit->create_audit($_SESSION['UserGUID'], 'Family','familyasdd',false);

            // $htmlcontrol .= "<select id=\"leerling_family\" name=\"leerling_family\" class=\"form-control\">";

            if($select->num_rows > 0)
            {

              while($select->fetch())
              {
                $htmlcontrol .= "<option value='".$id_family."'>".$family_name."</option>";
              }
            }
          }
          else
          {
            /* error executing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            if($this->debug)
            {
              print "error executing query" . "<br />";
              print "error" . $mysqli->error;
            }
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }

    }
    catch(Exception $e)
    {
      $result = -2;
      $this->exceptionvalue = $e->getMessage();
    }

    //return $result;
    return $htmlcontrol;
  }
  function list_target_class($school_id,$dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)"))
        {
          if ($stmt->bind_param("i",$school_id))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $htmlcontrol .= "<select id=\"class_target_list\" name=\"class_target_list\" class=\"form-control\">";
                $htmlcontrol .= "<option selected>Select One Class</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }
                $htmlcontrol .= "</select>";
              }
              else
              {
                $htmlcontrol .= "No results to show";
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }

      return $htmlcontrol;
    }
  }
  function list_from_class($school_id,$dummy)
  {
    $returnvalue = "";
    $sql_query = "";
    $htmlcontrol="";

    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      mysqli_report(MYSQLI_REPORT_STRICT);
      require_once("spn_utils.php");
      $utils = new spn_utils();

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);

        if($stmt=$mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)"))
        {
          if ($stmt->bind_param("i",$school_id))
          {
            if($stmt->execute())
            {
              $this->error = false;
              $result=1;
              $stmt->bind_result($class);
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $htmlcontrol .= "<select id=\"list_from_class\" name=\"list_from_class\" class=\"form-control\">";
                $htmlcontrol .= "<option selected>Select One Class</option>";
                while($stmt->fetch())
                {
                  $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                }
                $htmlcontrol .= "</select>";
              }
              else
              {
                $htmlcontrol .= "No results to show";
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
        $result = $e->getMessage();
      }

      return $htmlcontrol;
    }
  }
  function change_leerling_class($id_student, $from_class, $to_class, $schooljaar,$comments, $dummy)
  {
    $result = 0;
    if ($dummy)
    $result = 1;
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);
      try
      {
        $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if ($select =$mysqli->prepare("CALL " . $this->sp_change_class . " (?,?,?,?,?)"))
        {
          if ($select->bind_param("issss",$id_student, $from_class, $to_class, $schooljaar,$comments))
          {
            if($select->execute())
            {
              require_once ("spn_audit.php");
              $spn_audit = new spn_audit();
              $UserGUID = $_SESSION['UserGUID'];
              $spn_audit->create_audit($UserGUID, 'change class','change student class',appconfig::GetDummy());
              /* Need to check for errors on database side for primary key errors etc.*/
              $result = 1;
              $select->close();
              $mysqli->close();
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          $result = 0;
          $this->mysqlierror = $mysqli->error;
          $this->mysqlierrornumber = $mysqli->errno;
        }
      }
      catch(Exception $e)
      {
        $result = -2;
        $this->exceptionvalue = $e->getMessage();
      }
      return $result;
    }
    function list_target_class($school_id,$dummy)
    {
      $returnvalue = "";
      $sql_query = "";
      $htmlcontrol="";

      $result = 0;
      if ($dummy)
      $result = 1;
      else
      {
        mysqli_report(MYSQLI_REPORT_STRICT);
        require_once("spn_utils.php");
        $utils = new spn_utils();

        try
        {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();
          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);

          if($stmt=$mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)"))
          {
            if ($stmt->bind_param("i",$school_id))
            {
              if($stmt->execute())
              {
                $this->error = false;
                $result=1;
                $stmt->bind_result($class);
                $stmt->store_result();

                if($stmt->num_rows > 0)
                {
                  $htmlcontrol .= "<select id=\"class_target_list\" name=\"class_target_list\" class=\"form-control\">";
                  $htmlcontrol .= "<option selected>Select One Class</option>";
                  while($stmt->fetch())
                  {
                    $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                  }
                  $htmlcontrol .= "</select>";
                }
                else
                {
                  $htmlcontrol .= "No results to show";
                }
              }
              else
              {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        catch(Exception $e)
        {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
          $result = $e->getMessage();
        }

        return $htmlcontrol;
      }
    }
    function list_from_class($school_id,$dummy)
    {
      $returnvalue = "";
      $sql_query = "";
      $htmlcontrol="";

      $result = 0;
      if ($dummy)
      $result = 1;
      else
      {
        mysqli_report(MYSQLI_REPORT_STRICT);
        require_once("spn_utils.php");
        $utils = new spn_utils();

        try
        {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();
          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort,$dummy);

          if($stmt=$mysqli->prepare("CALL " . $this->sp_read_change_class . "(?)"))
          {
            if ($stmt->bind_param("i",$school_id))
            {
              if($stmt->execute())
              {
                $this->error = false;
                $result=1;
                $stmt->bind_result($class);
                $stmt->store_result();

                if($stmt->num_rows > 0)
                {
                  $htmlcontrol .= "<select id=\"list_from_class\" name=\"list_from_class\" class=\"form-control\">";
                  $htmlcontrol .= "<option selected>Select One Class</option>";
                  while($stmt->fetch())
                  {
                    $htmlcontrol .= "<option value=". htmlentities($class) .">". htmlentities($class) ."</option>";
                  }
                  $htmlcontrol .= "</select>";
                }
                else
                {
                  $htmlcontrol .= "No results to show";
                }
              }
              else
              {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        catch(Exception $e)
        {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
          $result = $e->getMessage();
        }

        return $htmlcontrol;
      }
    }
    function change_leerling_class($id_student, $from_class, $to_class, $schooljaar,$comments, $dummy)
    {
      $result = 0;
      if ($dummy)
      $result = 1;
      else
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $status = 1;

        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
          $mysqli = new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if ($select =$mysqli->prepare("CALL " . $this->sp_change_class . " (?,?,?,?,?)"))
          {
            if ($select->bind_param("issss",$id_student, $from_class, $to_class, $schooljaar,$comments))
            {
              if($select->execute())
              {
                require_once ("spn_audit.php");
                $spn_audit = new spn_audit();
                $UserGUID = $_SESSION['UserGUID'];
                $spn_audit->create_audit($UserGUID, 'change class','change student class',appconfig::GetDummy());
                /* Need to check for errors on database side for primary key errors etc.*/
                $result = 1;
                $select->close();
                $mysqli->close();
              }
              else
              {
                $result = 0;
                $this->mysqlierror = $mysqli->error;
                $this->mysqlierrornumber = $mysqli->errno;
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        catch(Exception $e)
        {
          $result = -2;
          $this->exceptionvalue = $e->getMessage();
        }
        return $result;
      }
    }
  }

  function get_huiswerk_by_class($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_huiswerk_by_class (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }

  function get_huiswerk_by_student($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_huiswerk_by_student (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }

  function get_uitgestuurd_by_class($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_uitgestuurd_by_class (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }

  function get_uitgestuurd_by_student($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_uitgestuurd_by_student (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }
  function get_laat_by_class($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_telaat_by_class (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }

  function get_laat_by_student($schoolID, $studentid, $dummy)
  {
    require_once("spn_utils.php");

    $sql_query = "";
    $htmlcontrol="";
    $result = 0;
    $returnvalue = "";

    if ($dummy)
    {
      $htmlcontrol .= "";
      $returnvalue = $htmlcontrol;
    }
    else
    {
      require_once("DBCreds.php");
      $DBCreds = new DBCreds();
      date_default_timezone_set("America/Aruba");
      $_DateTime = date("Y-m-d H:i:s");
      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);

      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        if($select = $mysqli->prepare("CALL sp_get_telaat_by_student (?, ?)"))
        {
          if($select->bind_param("ii",$schoolID,$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result = 1;

              $select->bind_result($value);
              $select->store_result();

              if($select->num_rows > 0)
              {
                while($select->fetch())
                {
                  $htmlcontrol = $value;
                }
              }
              else
              $htmlcontrol .= "No results to show";

              $returnvalue = $htmlcontrol;

            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;

              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            $result = 0;
            $this->mysqlierror = $mysqli->error;
            $this->mysqlierrornumber = $mysqli->errno;
          }
        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;

          print "error del sql: ". $this->errormessage;

          if($this->debug)
          {
            print "error preparing query";
          }
        }
        // Cierre del prepare

        $returnvalue = $htmlcontrol;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;

        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
    }

    return $returnvalue;

  }
  function get_absentie_by_class($schoolID, $studentid, $dummy)
  {
      require_once("spn_utils.php");

      $sql_query = "";
      $htmlcontrol="";
      $result = 0;
      $returnvalue = "";

      if ($dummy)
      {
        $htmlcontrol .= "";
        $returnvalue = $htmlcontrol;
      }
      else
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        date_default_timezone_set("America/Aruba");
        $_DateTime = date("Y-m-d H:i:s");
        $status = 1;

        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();

          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if($select = $mysqli->prepare("CALL sp_get_absentie_by_class (?, ?)"))
          {
            if($select->bind_param("ii",$schoolID,$studentid))
            {
              if($select->execute())
              {
                $this->error = false;
                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $htmlcontrol = $value;
                  }
                }
                else
                $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;

              }
              else
              {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result=0;

                if($this->debug)
                {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            /* error preparing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            print "error del sql: ". $this->errormessage;

            if($this->debug)
            {
              print "error preparing query";
            }
          }
          // Cierre del prepare

          $returnvalue = $htmlcontrol;
        }
        catch(Exception $e)
        {
          $this->error = true;
          $this->errormessage = $e->getMessage();
          $result=0;

          if($this->debug)
          {
            print "exception: " . $e->getMessage();
          }
        }
      }

      return $returnvalue;

    }

  function get_absentie_by_student($schoolID, $studentid, $dummy)
    {
      require_once("spn_utils.php");

      $sql_query = "";
      $htmlcontrol="";
      $result = 0;
      $returnvalue = "";

      if ($dummy)
      {
        $htmlcontrol .= "";
        $returnvalue = $htmlcontrol;
      }
      else
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        date_default_timezone_set("America/Aruba");
        $_DateTime = date("Y-m-d H:i:s");
        $status = 1;

        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
          require_once("DBCreds.php");
          $DBCreds = new DBCreds();

          $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

          if($select = $mysqli->prepare("CALL sp_get_absentie_by_student (?, ?)"))
          {
            if($select->bind_param("ii",$schoolID,$studentid))
            {
              if($select->execute())
              {
                $this->error = false;
                $result = 1;

                $select->bind_result($value);
                $select->store_result();

                if($select->num_rows > 0)
                {
                  while($select->fetch())
                  {
                    $htmlcontrol = $value;
                  }
                }
                else
                $htmlcontrol .= "No results to show";

                $returnvalue = $htmlcontrol;

              }
              else
              {
                /* error executing query */
                $this->error = true;
                $this->errormessage = $mysqli->error;
                $result=0;

                if($this->debug)
                {
                  print "error executing query" . "<br />";
                  print "error" . $mysqli->error;
                }
              }
            }
            else
            {
              $result = 0;
              $this->mysqlierror = $mysqli->error;
              $this->mysqlierrornumber = $mysqli->errno;
            }
          }
          else
          {
            /* error preparing query */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;

            print "error del sql: ". $this->errormessage;

            if($this->debug)
            {
              print "error preparing query";
            }
          }
          // Cierre del prepare

          $returnvalue = $htmlcontrol;
        }
        catch(Exception $e)
        {
          $this->error = true;
          $this->errormessage = $e->getMessage();
          $result=0;

          if($this->debug)
          {
            print "exception: " . $e->getMessage();
          }
        }
      }

      return $returnvalue;

    }
    function list_students_parent_mobile($studentid,$dummy)
    {
      $returnvalue = "";
      $user_permission="";
      $sql_query = "";
      $htmlcontrol="";
      mysqli_report(MYSQLI_REPORT_STRICT);
      $sql_query = "SELECT id, studentnumber, firstname, lastname, sex, dob, class";
      $sql_query .= ", uuid, created, updated, schoolid, idnumber, enrollmentdate,  birthplace, address, azvnumber, azvexpiredate, ";
      $sql_query .= "phone1, phone2, colorcode, status, roepnaam, nationaliteit, vezorger, email, ne, en, sp, pa, voertaalanders, spraak, gehoor, gezicht, motoriek, medicatieanders, ";
      $sql_query .= "bijzonderemedisch, huisarts, huisartsnr, persoonlijkeeigenschappen, thuissituatie, fysiekebijzonderheden, schoolleereigenschappen, socialeeigenschappen, anders, voorletter, phone3, vorigeschool, bijzondermedischeindicatie, notas, id_family ";
      //if($klas_in == "ALL")
      $sql_query .= " FROM students WHERE uuid=?";
      //else
      // $sql_query .= " FROM students WHERE class=? and schoolid=? order by";


      require_once("spn_utils.php");
      $utils = new spn_utils();
      try
      {
        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);
        if($select=$mysqli->prepare($sql_query))
        {

          if($select->bind_param("s",$studentid))
          {
            if($select->execute())
            {
              $this->error = false;
              $result=1;
              $select->bind_result($studentid,$studentnumber,$voornamen,$achternaam,$geslacht,$geboortedatum,$klas, $uuid, $created, $updated, $schoolid, $idnumber, $enrollmentdate, $birthplace, $address, $azvnumber, $azvexpiredate, $phone1, $phone2, $colorcode, $status, $roepnaam, $nationaliteit , $vezorger, $email, $ne, $en, $sp, $pa, $voertaalanders , $spraak , $gehoor, $gezicht, $motoriek, $medicatieanders, $bijzonderemedisch, $huisarts, $huisartsnr, $persoonlijkeeigenschappen, $thuissituatie, $fysiekebijzonderheden, $schoolleereigenschappen, $socialeeigenschappen, $anders, $voorletter, $phone3,  $vorigeschool, $bijzondermedischeindicatie, $notas, $id_family);
              $select->store_result();

              if($select->num_rows > 0)
              {





                // $htmlcontrol .= "<div id=\"dataRequest-student_detail\" class=\"col-xs-12 table-striped table-responsive\">";
                //
                // $htmlcontrol .= "<table id=\"dataRequest-student\" class=\"table table-striped tabl\" data-table=\"yes\">";
                // $htmlcontrol .= "<thead><tr><th>Student#</th><th>Voornamen</th><th>Achternaam</th></thead>";
                //
                // $htmlcontrol .= "<tbody>";
                while($select->fetch())
                {

                  $htmlcontrol .= "<div class=\"row\">";
                  $htmlcontrol .= "<div class=\"col-xs-6 col-md-6\">";
                  $htmlcontrol .= "<h2>". htmlentities(utf8_encode($voornamen)) ."". chr(32) ."". htmlentities(utf8_encode($achternaam)) ."</h2>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-xs-6 col-md-6\">";
                  $htmlcontrol .= "<h2>Klas: ". htmlentities($klas) ."</h2>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<hr>";
                  $htmlcontrol .= "<div class=\"row\">";
                  $htmlcontrol .= "<div class=\"col-xs-12 col-md-3\">";
                  $htmlcontrol .= "<b>Student# </b>". htmlentities($studentnumber) ."";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-xs-12 col-md-3\">";
                  $htmlcontrol .= "<b>Geslacht: </b>". htmlentities($geslacht) ."";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "<div class=\"col-xs-12 col-md-6 pull-right\">";
                  $htmlcontrol .= "<b>Geboortedatum: </b>". htmlentities($geboortedatum) ."";
                  $htmlcontrol .= "</div>";
                  $htmlcontrol .= "</div>";

                  // $htmlcontrol .= "<tr>";
                  // $htmlcontrol .= "<td>". htmlentities($studentnumber) ."</td>";
                  // $htmlcontrol .= "<td>". htmlentities(utf8_encode($voornamen)) ."</td>";
                  // $htmlcontrol .= "<td>". htmlentities(utf8_encode($achternaam)) ."</td>";

                  // Hide controls
                  $htmlcontrol .= "<input type='hidden' name='id' value='". $studentid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='uuid' value='". $uuid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='created' value='". ($utils->convertfrommysqldate($created) != false && $created != "" ? htmlentities($utils->convertfrommysqldate($created)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='updated' value='". ($utils->convertfrommysqldate($updated) != false && $updated != "" ? htmlentities($utils->convertfrommysqldate($updated)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolid' value='". $schoolid ."'>";
                  $htmlcontrol .= "<input type='hidden' name='idnumber' value='". $idnumber ."'>";
                  $htmlcontrol .= "<input type='hidden' name='enrollmentdate' value='". $enrollmentdate ."'>";
                  $htmlcontrol .= "<input type='hidden' name='birthplace' value='". $birthplace ."'>";
                  $htmlcontrol .= "<input type='hidden' name='address' value='". $address ."'>";
                  $htmlcontrol .= "<input type='hidden' name='azvnumber' value='". $azvnumber ."'>";
                  $htmlcontrol .= "<input type='hidden' name='azvexpiredate' value='". ($utils->convertfrommysqldate($azvexpiredate) != false && $azvexpiredate != "" ? htmlentities($utils->convertfrommysqldate($azvexpiredate)) : "") ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone1' value='". $phone1 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone2' value='". $phone2 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='colorcode' value='". $colorcode ."'>";
                  $htmlcontrol .= "<input type='hidden' name='status' value='". $status ."'>";
                  $htmlcontrol .= "<input type='hidden' name='roepnaam' value='". $roepnaam ."'>";
                  $htmlcontrol .= "<input type='hidden' name='nationaliteit' value='". utf8_encode($nationaliteit) ."'>";
                  $htmlcontrol .= "<input type='hidden' name='vezorger' value='". $vezorger ."'>";
                  $htmlcontrol .= "<input type='hidden' name='email' value='". $email ."'>";
                  $htmlcontrol .= "<input type='hidden' name='ne' value='". $ne ."'>";
                  $htmlcontrol .= "<input type='hidden' name='en' value='". $en ."'>";
                  $htmlcontrol .= "<input type='hidden' name='sp' value='". $sp ."'>";
                  $htmlcontrol .= "<input type='hidden' name='pa' value='". $pa ."'>";
                  $htmlcontrol .= "<input type='hidden' name='voertaalanders' value='". $voertaalanders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='spraak' value='". $spraak ."'>";
                  $htmlcontrol .= "<input type='hidden' name='gehoor' value='". $gehoor ."'>";
                  $htmlcontrol .= "<input type='hidden' name='gezicht' value='". $gezicht ."'>";
                  $htmlcontrol .= "<input type='hidden' name='motoriek' value='". $motoriek ."'>";
                  $htmlcontrol .= "<input type='hidden' name='medicatieanders' value='". $medicatieanders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzonderemedisch' value='". $bijzonderemedisch ."'>";
                  $htmlcontrol .= "<input type='hidden' name='huisarts' value='". $huisarts ."'>";
                  $htmlcontrol .= "<input type='hidden' name='huisartsnr' value='". $huisartsnr ."'>";
                  $htmlcontrol .= "<input type='hidden' name='persoonlijkeeigenschappen' value='". $persoonlijkeeigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='thuissituatie' value='". $thuissituatie ."'>";
                  $htmlcontrol .= "<input type='hidden' name='fysiekebijzonderheden' value='". $fysiekebijzonderheden ."'>";
                  $htmlcontrol .= "<input type='hidden' name='schoolleereigenschappen' value='". $schoolleereigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='socialeeigenschappen' value='". $socialeeigenschappen ."'>";
                  $htmlcontrol .= "<input type='hidden' name='anders' value='". $anders ."'>";
                  $htmlcontrol .= "<input type='hidden' name='voorletter' value='". $voorletter ."'>";
                  $htmlcontrol .= "<input type='hidden' name='phone3' value='". $phone3 ."'>";
                  $htmlcontrol .= "<input type='hidden' name='vorigeschool' value='". $vorigeschool ."'>";
                  $htmlcontrol .= "<input type='hidden' name='bijzondermedischeindicatie' value='". $bijzondermedischeindicatie ."'>";
                  $htmlcontrol .= "<input type='hidden' name='notasval' value='". $notas ."'>";
                  // // End hide controls
                  // $htmlcontrol .= "</td>";
                  // $htmlcontrol .= "</tr>";
                }
                // $htmlcontrol .= "<thead><th>Geslacht</th><th>Geboortedatum</th></th><th>klas</th></tr></thead>";
                // $htmlcontrol .= "<td>". htmlentities($geslacht) ."</td>";
                // $htmlcontrol .= "<td>". ($utils->convertfrommysqldate($geboortedatum) != false && $geboortedatum != "" ? htmlentities($utils->convertfrommysqldate($geboortedatum)) : "") ."</td>";
                // $htmlcontrol .= "<td>". htmlentities($klas) ."</td>";
                //
                // $htmlcontrol .= "</tbody>";
                // $htmlcontrol .= "</table>";
                // $htmlcontrol .= "</div>";


              }
              else
              {
                $htmlcontrol .= "No results to show";
              }
            }
            else
            {
              /* error executing query */
              $this->error = true;
              $this->errormessage = $mysqli->error;
              $result=0;
              if($this->debug)
              {
                print "error executing query" . "<br />";
                print "error" . $mysqli->error;
              }
            }
          }
          else
          {
            /* error binding parameters */
            $this->error = true;
            $this->errormessage = $mysqli->error;
            $result=0;
            if($this->debug)
            {
              print "error binding parameters";
            }
          }

        }
        else
        {
          /* error preparing query */
          $this->error = true;
          $this->errormessage = $mysqli->error;
          $result=0;
          if($this->debug)
          {
            print "error preparing query";
          }
        }
        $returnvalue = $htmlcontrol;
        return $returnvalue;
      }
      catch(Exception $e)
      {
        $this->error = true;
        $this->errormessage = $e->getMessage();
        $result=0;
        if($this->debug)
        {
          print "exception: " . $e->getMessage();
        }
      }
      return $returnvalue;
    }

}



?>
