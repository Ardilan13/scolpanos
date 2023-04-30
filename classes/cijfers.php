<?php


class cijfers
{
    public $tablename_cijfers = "le_cijfers";
    public $exceptionvalue = "";
    public $mysqlierror = "";
    public $mysqlierrornumber = "";

	function createcijfers($studentnumber,$schooljaar,$rapnummer,$vak,$klas,$user,$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$gemiddelde)
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

            if($stmt=$mysqli->prepare("insert into " . $this->tablename_cijfers . "(created,studentnumber,schooljaar,rapnummer,vak,klas,user,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,c15,c16,c17,c18,c19,c20,gemiddelde) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))            
            {                
            	if($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$vak,$klas,$user,$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$gemiddelde))
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

    function editcijfers($cijfersid,$studentnumber,$schooljaar,$rapnummer,$vak,$klas,$user,$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$gemiddelde)
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

            if($stmt=$mysqli->prepare("update " . $this->tablename_cijfers . " set lastchanged = ?, studentnumber = ?, schooljaar = ?, rapnummer = ?, vak = ?, klas = ?, user =?, c1 = ?, c2 = ?, c3 = ?, c4 = ?, c5 = ?, c6 = ?, c7 = ?, c8 = ?, c9 = ?, c10 = ?, c11 = ?, c12 = ?, c13 = ?, c14 = ?, c15 = ?, c16 = ?, c17 = ?, c18 = ?, c19 = ?, c20 = ?, gemiddelde = ? where id = ?"))            
            {                
                if($stmt->bind_param("sisisssiiiiiiiiiiiiiiiiiiiiii",$_DateTime,$studentnumber,$schooljaar,$rapnummer,$vak,$klas,$user,$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$gemiddelde,$cijfersid))                   
                    
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