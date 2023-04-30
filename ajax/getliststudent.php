<?php

//echo "invoicepaymentschool = ".$_POST["invoicepaymentschool"];

//if (isset($_POST["invoicepaymentschool"]) &&  isset($_POST["invoicepaymentclass"]) &&  isset($_POST["invoicepaymentstudent"]) && isset($_POST["invoicepaymentnumber"]) && isset($_POST["invoicepaymentammount"]) && isset($_POST["invoicepaymentdate"]) && isset($_POST["invoicepaymentduedate"]) && isset($_POST["invoicepaymentmemo"]) &&  isset($_POST["invoicepaymentstatus"]))

//{

	require_once("../classes/spn_paymentinvoice.php");

	require_once("../classes/spn_utils.php");



	$i = new spn_paymentinvoice();

	$u = new spn_utils();



	// if(session_status() == PHP_SESSION_NONE)

	// {

	// 	session_start();

	// }





if(empty($_POST["idSchool"]) && empty($_POST["idClass"]))

{

	//echo $i->liststudent(1,"3A");

	echo $i->liststudent();



}else{



	// $idClass = isset($_POST['idClass']) ? $_POST['idClass'] : NULL;

	// $idSchool = isset($_POST['idSchool']) ? $_POST['idSchool'] : NULL;

	$idClass = isset($_POST['idClass']) ? $_POST['idClass'] : 0;

	$idSchool = isset($_POST['idSchool']) ? $_POST['idSchool'] : 0;







	echo  $i->liststudentbyschoolclass($idSchool,$idClass);





}

// }



?>



<script>

    $(function(){





        $("#invoicepaymentstudent").change(function () {





            varCboType = $("#invoicepaymenttype option:selected").text();





                if(varCboType == "Payment"){



                    $.post("ajax/getlistpaymentstudent.php",

                        {

                           idStudent : $("#invoicepaymentstudent option:selected").val()

                        },

                        function(data1){

                            $("#data_student_invoice").html(data1);

                        });



                }











        });





    })







</script>

