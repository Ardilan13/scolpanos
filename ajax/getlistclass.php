<?php



//if (isset($_POST["invoicepaymentschool"]) &&  isset($_POST["invoicepaymentclass"]) &&  isset($_POST["invoicepaymentstudent"]) && isset($_POST["invoicepaymentnumber"]) && isset($_POST["invoicepaymentammount"]) && isset($_POST["invoicepaymentdate"]) && isset($_POST["invoicepaymentduedate"]) && isset($_POST["invoicepaymentmemo"]) &&  isset($_POST["invoicepaymentstatus"]))

//{
	require_once("../classes/spn_paymentinvoice.php");
	require_once("../classes/spn_avi.php");
	require_once("../classes/spn_utils.php");

	$i = new spn_paymentinvoice();
	$u = new spn_utils();

	// if(session_status() == PHP_SESSION_NONE)
	// {
	// 	session_start();
	// }

if(empty($_POST["idSchool"])){
    echo $i->listclass();
}else{

    $idSchool = isset($_POST['idSchool']) ? $_POST['idSchool'] : NULL;
    echo  $i->listclassbyschool($idSchool);
}
?>

<script type="text/javascript">
    $(function(){
        $("#invoicepaymentclass").change(function(){
					var varCClass = $("#invoicepaymentclass option:selected").val();
          $.post("ajax/getliststudentbyclass.php",
              {
                  class : varCClass
              },
              function(data){
                  $("#data_student_by_class").html(data);
              });
      });
    });
</script>
