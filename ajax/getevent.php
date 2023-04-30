<?php
/**
 * Created by PhpStorm.
 * User: Fogo
 * Date: 03-Mar-16
 * Time: 2:02 PM
 */


require_once("../classes/spn_event.php");
require_once("../config/app.config.php");
require_once ("../classes/spn_utils.php");


$baseurl = appconfig::GetBaseURL();

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}
$e = new spn_event();
$u = new spn_utils();
$id_student = $_GET["id"];

print $e->list_event($_SESSION["SchoolJaar"],null,$id_student,appconfig::GetDummy());
?>
<script type="text/javascript">

    $(function(){
      // CaribeDevelopers: Function to select a row from table event

$('#dataRequest-event tr').click(function(){
  $(this).closest('tr').siblings().children('td, th').css('background-color','');
  $(this).children('td, th').css('background-color','#cccccc');
  $("#event_date").val($(this).find("td").eq(0).text());
  $("#due_date").val($(this).find("td").eq(1).text());
  $("#reason").val($(this).find("td").eq(2).text());
  $("#involved").val($(this).find("td").eq(3).text());
  $('#observation_event').val($(this).find("td").eq(4).text());
  if($(this).find("td").eq(5).text() == "1")
  {
    $('#event_private').prop('checked', true);
    $('#event_no_private').prop('checked', false);
  }else{
    $('#event_private').prop('checked', false);
    $('#event_no_private').prop('checked', true);
  }
  if($(this).find("td").eq(6).text() == "1")
  {
    $('#important_notice').prop('checked', true);
    $('#no_important_notice').prop('checked', false);
  }else {
    $('#important_notice').prop('checked', false);
    $('#no_important_notice').prop('checked', true);
  }
  $("#id_event").val($(this).find("td").eq(7).text());
  $("#id_student").val($(this).find("td").eq(8).text());

  $('#btn-clear-event').val("DELETE");
});
});

</script>
