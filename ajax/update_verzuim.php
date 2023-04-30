<?php




require_once("../classes/spn_verzuim.php");


require_once("../config/app.config.php");




$baseurl = appconfig::GetBaseURL();




if(session_status() == PHP_SESSION_NONE)


{


	session_start();


}


$s = new spn_verzuim();



var_dump($_POST);


if(isset($_SESSION["UserRights"]) && isset($_SESSION["SchoolID"]) && isset($_POST["studentid"]) && isset($_POST["klas"]) && isset($_POST["datum"]) && isset($_POST["telaat"]))


{


	if($_SESSION["UserRights"] == "DOCENT" || $_SESSION["UserRights"] == "ASSISTENT")


	{


		if(isset($_SESSION["Class"]))


		{


			if ($_SESSION['SchoolType'] == 1){
				print $s->saveverzuim($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"]);
			}
			else{
				if (isset($_POST["period_hs"])){


					if ($_POST["period_hs"]=='99'){


						for ($x = 1; $x <= 9; $x++) {


							$s->saveverzuim_hs($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"], $x, $_POST["period_hs"], $_POST["verzuimid"]);


						}


						print 1;



					}


					else{


						print $s->saveverzuim_hs($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"], $_POST["period_hs"], $_POST["verzuimid"]);


					}


				}


				else {


					print $s->saveverzuim($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"]);


				}


			}


		}
	}


	else if($_SESSION["UserRights"] == "BEHEER" || $_SESSION["UserRights"] == "ADMINISTRATIE" || $_SESSION["UserRights"] == "ONDERSTEUNING")


	{



		if ($_SESSION['SchoolType'] == 1){
			print $s->saveverzuim($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"]);
		}
		else{
			if (isset($_POST["period_hs"])){
				if ($_POST["period_hs"]=='99'){
					$s->editverzuim_hs_allday($_POST["verzuimid"],$_POST["studentid"],$_SESSION["SchoolJaar"],$_POST["klas"],$_POST["datum"],$_POST["opmerking"],'SPN',$_POST["telaat"],$_POST["absentie"],$_POST["lp"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"], $_POST["period_hs"]);
				}
				else {
					print $s->saveverzuim_hs($_SESSION["SchoolJaar"],$_SESSION["SchoolID"],$_POST["studentid"],$_POST["telaat"],$_POST["absentie"],$_POST["toetsinhalen"],$_POST["uitsturen"],$_POST["huiswerk"],$_POST["lp"],$_POST["opmerking"],$_POST["klas"],$_POST["datum"], $_POST["period_hs"], $_POST["verzuimid"]);
				}
			}
		}
	}


}




?>


