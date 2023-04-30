<?php

	if(isset($_POST["username"]) && isset($_POST["password"]))
	{
		require_once("../classes/spn_authentication.php");
		$s = new spn_authentication();
		if($s->AuthenticateUser($_POST["username"],$_POST["password"]) == 1)
		{
			print (int)$s->CreateSession($_POST["username"]);	
		}
		else
		{
			print "0";
		}
		
	}
	else
	{
		print "-1";
	}

?>