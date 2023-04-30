<?php
	
	
	class spn_suggestions
	{
		public $tablename_suggestions = "suggestions";
		public $exceptionvalue = "";
		public $mysqlierror = "";
		public $mysqlierrornumber = "";
		
		public $last_insert_id = "";
		
		public $debug = false;
		public $error = "";
		public $errormessage = "";
		
		function createsuggestion($schoolid,$user,$userguid,$subject,$suggestion)
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
				
				if($stmt=$mysqli->prepare("insert into " . $this->tablename_suggestions . "(created,schoolid,user,userguid,subject,suggestion) values (?,?,?,?,?,?)"))            
				{                
					if($stmt->bind_param("ssssss",$_DateTime,$schoolid,$user,$userguid,$subject,$suggestion))
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
							
							/* Audit by Caribe Developers */
							require_once ("spn_audit.php");
							$spn_audit = new spn_audit();
							$UserGUID = $_SESSION['UserGUID'];
							$spn_audit->create_audit($UserGUID, 'suggestions','create suggestions',appconfig::GetDummy());
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
				
				?>				