<?php


class houding
{
    public $tablename_houding = "le_houding";
    public $exceptionvalue = "";
    public $mysqlierror = "";
    public $mysqlierrornumber = "";

	function createhouding($studentnumber,$schooljaar,$rapnummer,$klas,$user,$h1,$h2,$h3,$h4,$h5,$h6,$h7,$h8,$h9,$h10,$h11,$h12,$h13,$h14,$h15)
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

            if($stmt=$mysqli->prepare("insert into " . $this->tablename_houding . "(created,studentnumber,schooljaar,rapnummer,klas,user,h1,h2,h3,h4,h5,h6,h7,h8,h9,h10,h11,h12,h13,h14,h15) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))            
            {                
            	if($stmt->bind_param("sisissiiiiiiiiiiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$h1,$h2,$h3,$h4,$h5,$h6,$h7,$h8,$h9,$h10,$h11,$h12,$h13,$h14,$h15))
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

    function edithouding($houdingid,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$h1,$h2,$h3,$h4,$h5,$h6,$h7,$h8,$h9,$h10,$h11,$h12,$h13,$h14,$h15)
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

            if($stmt=$mysqli->prepare("update " . $this->tablename_houding . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, klas = ?, user =?, h1 = ?, h2 = ?, h3 = ?, h4 = ?, h5 = ?, h6 = ?, h7 = ?, h8 = ?, h9 = ?, h10 = ?, h11 = ?, h12 = ?, h13 = ?, h14 = ?, h15 = ? where id = ?"))            
            {                
                if($stmt->bind_param("sisissiiiiiiiiiiiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$klas,$user,$h1,$h2,$h3,$h4,$h5,$h6,$h7,$h8,$h9,$h10,$h11,$h12,$h13,$h14,$h15,$houdingid))
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