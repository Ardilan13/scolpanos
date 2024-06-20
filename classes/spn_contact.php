<?php



/**

* Created by PhpStorm.

* User: Fogo

* Date: 01-Mar-16

* Time: 10:35 AM

*/



class spn_contact{





  public $tablename_contact = "contact";

  public $tablename_le_contact = "le_contact";

  public $tablename_app_useraccounts = "app_useraccounts";

  public $exceptionvalue = "";

  public $mysqlierror = "";

  public $mysqlierrornumber = "";

  public $sp_read_contact = "sp_read_contact";



  public $debug = true;

  public $error = "";

  public $errormessage = "";





  public $sp_create_contact = "sp_create_contact";

  public $last_insert_id = "";





  /*Luis Bello by CaribeDevelopers*/

  function createcontact($tutor,$type,$id_number,$full_name,$address,$email,$mobile_phone,$home_phone,$work_phone,$work_phone_ext,$company,$position_company,$observations,$id_family,$dummy)

  {

    $result =0;

    if($dummy)

    {

      $result = 1;

    }else {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      date_default_timezone_set("America/Aruba");

      $_DateTime = date("Y-m-d H:i:s");

      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);



      try

      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        $mysqli->set_charset('utf8');

        if($stmt=$mysqli->prepare("CALL " . $this->sp_create_contact . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))

        {

          if($stmt->bind_param("isissssssisssi",$tutor, $type, $id_number, $full_name, $address, $email, $mobile_phone, $home_phone, $work_phone, $work_phone_ext, $company, $position_company, $observations, $id_family))

          {

            if($stmt->execute())

            {

              /*

              Need to check for errors on database side for primary key errors etc.



              */

              $result =1;

              $stmt->close();

              $mysqli->close();

            }

            else

            {

              $result =0;

              $this->mysqlierror = $mysqli->error;

              $this->mysqlierrornumber = $mysqli->errno;

            }



          }

          else

          {

            $result =0;

            $this->mysqlierror = $mysqli->error;

            $this->mysqlierrornumber = $mysqli->errno;

          }



        }

        else

        {

          $result =0;

          $this->mysqlierror = $mysqli->error;

          $this->mysqlierrornumber = $mysqli->errno;

        }





      }

      catch(Exception $e)

      {

        $result = -2;

        $this->exceptionvalue = $e->createcontact();

      }

    }



    return $result;

  }



  function list_contacts($id_family, $id_contact = null)

  {

    $returnvalue = "";

    $user_permission="";

    $sql_query = "";

    $htmlcontrol="";



    mysqli_report(MYSQLI_REPORT_STRICT);



    //  $sql_query = "select full_name,email,mobile_phone,home_phone,type,id_contact from contact where id_student=? order by full_name asc";



    require_once("spn_utils.php");

    $utils = new spn_utils();



    try

    {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $mysqli->set_charset('utf8');

      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);



      if($select=$mysqli->prepare("CALL ".$this->sp_read_contact." (?,?)"))

      {



        if($select->bind_param("ii",$id_family,$id_contact))

        {

          if($select->execute())

          {

            $this->error = false;

            $result=1;

            $select->bind_result($id_contact,$tutor,$type,$id_number,$full_name,$address,$email,$mobile_phone,$home_phone,$work_phone,$work_phone_ext,$company,$position_company,$observations,$id_family);

            $select->store_result();



            if($select->num_rows > 0)

            {

              /*$htmlcontrol .= "<table id=\"cstable\" class=\"table table-striped table-bordered\">"; */

              $htmlcontrol .= "<table id=\"dataRequest-contact\" class=\"table table-bordered table-colored\" data-table=\"yes\">";



              // $htmlcontrol .= "<thead><tr><th>Full Name</th><th>Email</th><th>Mobile Phone</th><th>Home Phone</th><th>Tutor</th><th>Details</th></tr></thead>";

              $htmlcontrol .= "<thead><tr><th>Tutor</th><th>Type</th><th>Full Name</th><th>Email</th><th>Mobile Phone</th><th>Address</th><th>Werk Telefoon</th><th>Company</th><th>Position</th></tr></thead>";

              $htmlcontrol .= "<tbody>";



              while($select->fetch())

              {

                $htmlcontrol .= "<tr><td>". ($tutor==1?"<i name='check_tutor' class='fa fa-check'></i>":"<i></i>") ."</td><td>". htmlentities($type) ."</td><td>". htmlentities($full_name) ."</td><td>". htmlentities($email) ."</td><td>". htmlentities($mobile_phone) ."</td><td>". htmlentities($address) ."</td><td>". htmlentities($work_phone) ."</td><td>". htmlentities($company) ."</td><td>". htmlentities($position_company);

                $htmlcontrol .= "<input type='hidden' name='email_contact' value='". htmlentities($email) . "'>";

                $htmlcontrol .= "<input type='hidden' name='home_phone_contact' value='". htmlentities($home_phone) . "'>";

                $htmlcontrol .= "<input type='hidden' name='work_phone_contact' value='". htmlentities($work_phone) . "'>";

                $htmlcontrol .= "<input type='hidden' name='work_phone_ext_contact' value='". htmlentities($work_phone_ext) . "'>";

                $htmlcontrol .= "<input type='hidden' name='observations_contact' value='". htmlentities(utf8_encode($observations)) . "'>";

                $htmlcontrol .= "<input type='hidden' name='id_contact' value='". htmlentities($id_contact) . "'>";

                $htmlcontrol .= "<input type='hidden' name='id_number_contact' value='". htmlentities($id_number) . "'>";

                $htmlcontrol .= "</td></tr>";

              }

              $htmlcontrol .= "</tbody>";

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

            print "error binding parameters". $this->errormessage = $mysqli->error;

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

          print "error preparing query".$this->errormessage = $mysqli->error;

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



  function get_contact($id_family,$idcontact)

  {



    $array_contact = array();



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();





      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);



      if($select = $mysqli->prepare("CALL sp_read_contact (?,?)"))

      {

        if($select->bind_param("ii",$id_family,$idcontact))

        {

          if($select->execute())

          {

            $this->error = false;

            $result = 1;

            $select->bind_result($id_contact,$tutor,$type,$id_number,$full_name,$address,$email,$mobile_phone,$home_phone,$work_phone,$work_phone_ext,$company,$position_company,$observations,$id_family);

			$select->store_result();

            //Audit by Caribe Developers

            require_once ("spn_audit.php");

            $spn_audit = new spn_audit();

            $spn_audit->create_audit($_SESSION['UserGUID'], 'Contact','Get Contact',false);





            if($select->num_rows > 0)

            {

              while($select->fetch())

              {

                $array_contact["id_contact"] = $id_contact;

                $array_contact["id_family"] = $id_family;

                $array_contact["type"] = $type;

                $array_contact["tutor"] = $tutor;

                $array_contact["id_number"] = $id_number;

                $array_contact["full_name"] = $full_name;

                $array_contact["email"] = $email;

                $array_contact["mobile_phone"] = $mobile_phone;

                $array_contact["home_phone"] = $home_phone;

                $array_contact["work_phone"] = $work_phone;

                $array_contact["work_phone_ext"] = $work_phone_ext;

                $array_contact["company"] = $company;

                $array_contact["position_company"] = $position_company;

                $array_contact["observations"] = $observations;

                $array_contact["address"] = $address;

              }



            }

            else

            {

              $this->error = true;

              $this->errormessage = $mysqli->error;

              echo  "No results to show". $this->errormessage = $mysqli->error;

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



          $array_contact .= $mysqli->error;

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





      return $array_contact;



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



    return $array_contact;

  }



  function update_contact(

  $tutor,

  $type,

  $id_number_contact,

  $full_name,

  $address,

  $email,

  $mobile_phone,

  $home_phone,

  $work_phone,

  $work_phone_ext,

  $company,

  $position_company,

  $observations,

  $id_contact)

  {

    $result = 0;



    require_once("DBCreds.php");

    $DBCreds = new DBCreds();

    date_default_timezone_set("America/Aruba");

    $_DateTime = date("Y-m-d H:i:s");

    $status = 1;



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      $mysqli->set_charset('utf8');

      if($stmt=$mysqli->prepare("CALL sp_update_contact (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))

      {

        if($stmt->bind_param("iissssssssisss",$id_contact,$tutor,$type,$id_number_contact,$full_name,$address,$email,$mobile_phone,$home_phone,$work_phone,$work_phone_ext,$company,$position_company,$observations))

        {

          if($stmt->execute())

          {   //Audit by Caribe Developers

            require_once ("spn_audit.php");

            $spn_audit = new spn_audit();

            $spn_audit->create_audit($_SESSION['UserGUID'], 'Contact','Update Contact',false);

            $result = 1;

            $stmt->close();

            $mysqli->close();

          }

          else

          {

            $result = 0;

            echo  $this->mysqlierror = $mysqli->error;

            echo $this->mysqlierrornumber = $mysqli->errno;

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



  function delete_contact($id_contact)

  {

    $result = 0;



    require_once("DBCreds.php");

    $DBCreds = new DBCreds();

    date_default_timezone_set("America/Aruba");

    $_DateTime = date("Y-m-d H:i:s");

    $status = 1;



    mysqli_report(MYSQLI_REPORT_STRICT);



    try

    {

      $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

      // $mysqli=new mysqli("127.0.0.1", "root", "", "wwwxluni_scolpanos", 3306);



      if($stmt=$mysqli->prepare("CALL sp_delete_contact (?)"))

      {

        if($stmt->bind_param("i",$id_contact)) {

          if($stmt->execute())

          {

            $result = 1;

            $stmt->close();

            $mysqli->close();

          }

          else

          {

            $result = 0;

            echo  $this->mysqlierror = $mysqli->error;

            echo $this->mysqlierrornumber = $mysqli->errno;

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



  function createcontact_parent($tutor,$type,$id_number,$full_name,$address,$email,$mobile_phone,$home_phone,$work_phone,$work_phone_ext,$company,$position_company,$observations,$id_family,$dummy)

  {

    $result =0;

    if($dummy)

    {

      $result = 1;

    }else {

      require_once("DBCreds.php");

      $DBCreds = new DBCreds();

      date_default_timezone_set("America/Aruba");

      $_DateTime = date("Y-m-d H:i:s");

      $status = 1;

      mysqli_report(MYSQLI_REPORT_STRICT);



      try

      {

        $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

        $mysqli->set_charset('utf8');

        if($stmt=$mysqli->prepare("CALL sp_create_contact_parent (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))

        {

          if($stmt->bind_param("issssssssissss",$tutor, $type, $id_number, $full_name, $address, $email, $mobile_phone, $home_phone, $work_phone, $work_phone_ext, $company, $position_company, $observations, $id_family))

          {

            if($stmt->execute())

            {

              /*

              Need to check for errors on database side for primary key errors etc.



              */

              $result =1;

              $stmt->close();

              $mysqli->close();

            }

            else

            {

              $result =0;

              $this->mysqlierror = $mysqli->error;

              $this->mysqlierrornumber = $mysqli->errno;

            }



          }

          else

          {

            $result =0;

            $this->mysqlierror = $mysqli->error;

            $this->mysqlierrornumber = $mysqli->errno;

          }



        }

        else

        {

          $result =0;

          $this->mysqlierror = $mysqli->error;

          $this->mysqlierrornumber = $mysqli->errno;

        }





      }

      catch(Exception $e)

      {

        $result = -2;

        $this->exceptionvalue = $e->createcontact();

      }

    }



    return $result;

  }





}

