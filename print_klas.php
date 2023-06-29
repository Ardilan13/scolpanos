<?php


include("config/app.config.php");


if (session_status() == PHP_SESSION_NONE)



  session_start();



include 'document_start.php';




$leerling_html = "";


$page_html = "";

$SchoolName = $_SESSION['schoolname'];



require_once("classes/spn_leerling.php");


$l = new spn_leerling();


$data_leerling_array = $l->get_all_students_array_by_klas($_GET["klas"], $_GET["schoolJaar"], 4);


// print($data_leerling_array);





require_once("classes/spn_cijfers.php");



$c = new spn_cijfers();





foreach ($data_leerling_array as $item) {



  if ($_SESSION["SchoolType"] == 1 && $_SESSION["SchoolID"] != 8 && $_SESSION["SchoolID"] != 18) {
    $print_table = $c->list_cijfers_by_student_ps($_GET["schoolJaar"], $item['studentid'], appconfig::GetDummy());
  } else {
    $print_table = $c->list_cijfers_by_student($_GET["schoolJaar"], $item['studentid'], appconfig::GetDummy());
  }



  $leerling_html .= "<table class='table table-bordered table-colored'>";


  $leerling_html .= "<tbody>
";

  $leerling_html .= "<tr>
";

  $leerling_html .= "<td><strong>Student nr.</strong></td>
";

  $leerling_html .= "<td>" . $item['studentnumber'] . "</td>
";

  $leerling_html .= "<td><strong>Klas</strong></td>";


  $leerling_html .= "<td id=\"klas\" name=\"klas\">
" . $item['klas'] . "</td>
";

  $leerling_html .= "</tr>
";

  $leerling_html .= "<tr>
";

  $leerling_html .= "<td><strong>Achternaam</strong></td>
";

  $leerling_html .= "<td> " . $item['achternaam'] . " </td>
";

  $leerling_html .= "<td><strong>Voornamen</strong></td>
";

  $leerling_html .= "<td> " . $item['voornamen'] . "</td>
";

  $leerling_html .= "</tr>
";

  $leerling_html .= "<tr>
";

  $leerling_html .= "<td><strong>Geslacht</strong></td>
";

  $leerling_html .= "<td>" . $item['geslacht'] . "</td>
";

  $leerling_html .= "<td><strong>Geboortedatum</strong></td>
";

  $leerling_html .= "<td>" . $item['geboortedatum'] . "</td>
";

  $leerling_html .= "</tr>
";

  $leerling_html .= "</tbody>";


  $leerling_html .= "</table>";










  $page_html .= "<div class='container'>";

  $page_html .= "<main id='main' role='main'>";

  $page_html .= "<section>";



  $page_html .= "<div class='row'>";

  $page_html .= "<div class='default-secondary-bg-color col-md-12 inset brd-bottom'>";

  $page_html .= "<div class='row'>";

  $page_html .= "<div class='col-md-12'>";

  $page_html .= "<table border='0'>";

  $page_html .= "<tr>";

  $page_html .= "<td width='400px'>";


  if ($SchoolName == 'Commandant Generaal Abraham de Veerschool') {
    $page_html .= "<img src='" . appconfig::GetBaseURL() . "/assets/img/logoschool13.jpeg' width='100px' height='100px' class='block-center img-responsive' alt='Scol pa Nos'>";
  } else {
    $page_html .= "<img src='" . appconfig::GetBaseURL() . "/assets/img/logo_spn_small.png' width='100px' height='100px' class='block-center img-responsive' alt='Scol pa Nos'>";
  }

  $page_html .= "<h5 class='col-md-12 secondary-color'>$SchoolName";

  $page_html .= "</h5>";

  $page_html .= "</td>";

  $page_html .= "<td style='padding-left: 70px;'>";

  $page_html .= "<h1 class='col-md-12 primary-color'>TUSSENRAPPORT</h1>";

  $page_html .= "</td>";

  $page_html .= "</tr>";

  $page_html .= "</table>";

  $page_html .= "</div>";

  $page_html .= "</div>";

  $page_html .= "</div>";

  $page_html .= "</div>";

  $page_html .= "<br>";

  $page_html .= "<div class='row'>";



  $page_html .= "<div id='div_content_leerling'>";

  $page_html .= ".$leerling_html.";

  // $page_html .="";

  // $page_html .="";

  // $page_html .="";

  // $page_html .="";



  $page_html .= "  </div>";

  $page_html .= "<h2 class='col-md-12 primary-color'>Cijfers: " . $_GET["schoolJaar"] . "</h2>";

  $page_html .= "<div id='div_data_table' class='col-md-12'>";

  $page_html .= "<table>" . $print_table . "</table>";

  $page_html .= "</div>";

  $page_html .= "<div id='div_data_grafiek' class='col-md-10'>";

  $page_html .= "</div>";

  $page_html .= "<h5 class='text-left'>User print by:" . $_SESSION['User'] . "</h5>";

  $page_html .= "<h5 class='text-left'><?php echo 'User Name: " . $_SESSION['FirstName'] . "' '" . $_SESSION['LastName'] . "</h5>";

  $page_html .= "</div>";

  $page_html .= "</section>";

  $page_html .= "</main>";

  $page_html .= "</div>";

  $page_html .= "</div>";



  $leerling_html = "";

  $page_html .= "<div class='page-break'></div>";
}



?>

<div id='container_print'>

  <?php echo $page_html ?>

</div>







<?php include 'document_end.php'; ?>


<script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/print.js"></script>


<script type="text/javascript">
  $(window).load(function() {


    setTimeout(function() {


      // $("#container_print").printThis();

      window.print();









    }, 4000);






  });
</script>