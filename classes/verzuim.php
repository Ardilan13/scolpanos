<?php


class verzuim
{
    public $tablename_verzuim = "le_verzuim";
    public $exceptionvalue = "";
    public $mysqlierror = "";
    public $mysqlierrornumber = "";

	function createverzuim($studentnumber,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk)
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
            
            $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

            if($stmt=$mysqli->prepare("insert into " . $this->tablename_verzuim . "(created,studentnumber,schooljaar,rapnummer,klas,datum,opmerking,user,telaat,absentie,LP,toetsinhalen,uitsturen,huiswerk) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))            
            {                
            	if($stmt->bind_param("sisissssiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk))
                {
                    if($stmt->execute())
                    {
                        /*
                            Need to check for errors on database side for primary key errors etc.                        
                        */

                            $result = 1;                            
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

    function editverzuim($verzuimid,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk)
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
            
            $mysqli=new mysqli($DBCreds->DBAddress, $DBCreds->DBUser, $DBCreds->DBPass, $DBCreds->DBSchema);

            if($stmt=$mysqli->prepare("update " . $this->tablename_verzuim . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, klas = ?, datum = ?, opmerking = ?, user =?, telaat= ?, absentie = ?, LP = ?, toetsinhalen = ?, uitsturen = ?, huiswerk = ? where id = ?"))            
            {                
                if($stmt->bind_param("sisissssiiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$datum,$opmerking,$user,$telaat,$absentie,$LP,$toetsinhalen,$uitsturen,$huiswerk,$verzuimid))
                {
                    if($stmt->execute())
                    {
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