<?php


class avi
{
    public $tablename_avi = "le_avi";
    public $exceptionvalue = "";
    public $mysqlierror = "";
    public $mysqlierrornumber = "";

	function createavi($studentnumber,$schooljaar,$rapnummer,$klas,$user,$avicijfer1,$aantalfouten1,$tijd1,$opmerking1,$avicijfer2,$aantalfouten2,$tijd2,$opmerking2,$avicijfer3,$aantalfouten3,$tijd3,$opmerking3)
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

            if($stmt=$mysqli->prepare("insert into " . $this->tablename_avi . "(created,studentnumber,schooljaar,rapnummer,klas,user,avicijfer1,aantalfouten1,tijd1,opmerking1,avicijfer2,aantalfouten2,tijd2,opmerking2,avicijfer3,aantalfouten3,tijd3,opmerking3) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))            
            {                
            	if($stmt->bind_param("sisissiissiissiiss",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$avicijfer1,$aantalfouten1,$tijd1,$opmerking1,$avicijfer2,$aantalfouten2,$tijd2,$opmerking2,$avicijfer3,$aantalfouten3,$tijd3,$opmerking3))
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

    function editavi($AVIid,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$avicijfer1,$aantalfouten1,$tijd1,$opmerking1,$avicijfer2,$aantalfouten2,$tijd2,$opmerking2,$avicijfer3,$aantalfouten3,$tijd3,$opmerking3)
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

            if($stmt=$mysqli->prepare("update " . $this->tablename_avi . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, klas = ?, user =?, avicijfer1 = ?, aantalfouten1 = ?, tijd1 = ?, opmerking1 = ?, avicijfer2 = ?, aantalfouten2 = ?, tijd2 = ?, opmerking2 = ?, avicijfer3 = ?, aantalfouten3 = ?, tijd3 = ?, opmerking3 = ? where id = ?"))            
            {                
                if($stmt->bind_param("sisissiissiissiissi",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$avicijfer1,$aantalfouten1,$tijd1,$opmerking1,$avicijfer2,$aantalfouten2,$tijd2,$opmerking2,$avicijfer3,$aantalfouten3,$tijd3,$opmerking3,$AVIid))
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