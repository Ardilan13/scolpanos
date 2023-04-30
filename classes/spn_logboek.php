<?php


class spn_logboek
{
    public $tablename_logboek = "le_logboek";
    public $exceptionvalue = "";
    public $mysqlierror = "";
    public $mysqlierrornumber = "";

	function createlogboek($studentnumber,$schooljaar,$rapnummer,$klas,$datum,$reden,$aanwezigen,$user,$opmerking,$ouderaanwezig,$oudermaglezen,$flag,$duedatum)
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

            if($stmt=$mysqli->prepare("insert into " . $this->tablename_logboek . "(created,studentnumber,schooljaar,rapnummer,klas,datum,reden,aanwezigen,user,opmerking,ouderaanwezig,oudermaglezen,flag,duedatum) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))
            {
            	if($stmt->bind_param("sisissssssiiis",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$reden,$aanwezigen,$user,$opmerking,$ouderaanwezig,$oudermaglezen,$flag,$duedatum))
                {
                    if($stmt->execute())
                    {
                        /*
                            Need to check for errors on database side for primary key errors etc.
                        */

                            $result = 1;
                            $stmt->close();
                            $mysqli->close();

                            //Audit by Caribe Developers
                            require_once ("spn_audit.php");
                            $spn_audit = new spn_audit();
                            $UserGUID = $_SESSION['UserGUID'];
                            $spn_audit->create_audit($UserGUID, 'Logboek','Create Logboek',false);
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

    function editlogboek($logboekid,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$reden,$aanwezigen,$user,$opmerking,$ouderaanwezig,$oudermaglezen,$flag,$duedatum)
    {
        $result = 0;

        require_once("DBCreds.php");
        $DBCreds = new DBCreds();
        date_default_timezone_set("America/Aruba");
        $_DateTime = date("Y-m-d H:i:s");
        $status = 0;

        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {

            $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema, $DBCreds->DBPort);

            if($stmt=$mysqli->prepare("update " . $this->tablename_logboek . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, klas = ?, datum = ?, reden = ?, aanwezigen = ?, user = ?, opmerking = ?, ouderaanwezig = ?, oudermaglezen = ?, flag = ?, duedatum = ?  where id = ?"))
            {
                if($stmt->bind_param("sisissssssiiisi",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$reden,$aanwezigen,$user,$opmerking,$ouderaanwezig,$oudermaglezen,$flag,$duedatum,$logboekid))
                {
                    if($stmt->execute())
                    {

                      //Audit by Caribe Developers
                      require_once ("spn_audit.php");
                      $spn_audit = new spn_audit();
                      $UserGUID = $_SESSION['UserGUID'];
                      $spn_audit->create_audit($UserGUID, 'Logboek','Edit Logboek',false);
                        /*
                            Need to check for errors on database side for primary key errors etc.
                        */
                            if($mysqli->affected_rows >= 1)
                            {
                                $result = 1;
                            }
                            $stmt->close();
                            $mysqli->close();
                        }
                        else
                        {
                            $result = 0;
                            $this->mysqlierror = $mysqli->error;
                        }

                    }
                    else
                    {
                       $result = 0;
                       $this->mysqlierror = $mysqli->error;
                   }

               }
               else
               {
                   $result = 0;
                   $this->mysqlierror = $mysqli->error;
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

?>
