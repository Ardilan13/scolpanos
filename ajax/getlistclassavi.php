<?php
	require_once("../classes/spn_authentication.php");
	require_once("../classes/spn_avi.php");

	$i = new spn_avi();
	$m= new spn_authentication();
	if(session_status() == PHP_SESSION_NONE)
		session_start();

	$userGUID = $_SESSION["UserGUID"];
	$school_id = $_SESSION["SchoolID"];
	$UserRights =$_SESSION["UserRights"];

	if ($UserRights=="BEHEER" || $UserRights=="ADMINISTRATIE" || $UserRights=="ONDERSTEUNING" ||$UserRights=="ADMIN" )
		$allClass=true;
	else
		$allClass=false;

	echo  $i->list_class($allClass, $userGUID, $school_id, false);
?>

<script>
  $(function(){
        $("#list_class_avi").change(function () {
          var varCClass = $("#list_class_avi option:selected").val();
                $.post("ajax/getliststudentbyclass.php",
                   {
                      class : varCClass
                   },
                   function(data){
										 $("#data_student_by_class").html(data);
                   });
        });
	})
</script>
