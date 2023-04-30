<?php

require_once("../classes/spn_contact.php");
require_once("../config/app.config.php");

$baseurl = appconfig::GetBaseURL();
 	$s = new spn_contact();
 	$id_family = $_GET["id"];
print $s->list_contacts($id_family,null);

	// 	print $s->list_contacts(5,NULL);
		//
		// $c = new spn_contact();
		// Print $c->list_contacts(5,NULL);

?>

<script type="text/javascript">

    $(function(){
      // CaribeDevelopers: Function to select a row form table contact

      $('#dataRequest-contact tr').click(function(){
        //alert($(this).find("i").eq(0).hasClass("fa fa-check"));
        $(this).closest('tr').siblings().children('td, th').css('background-color','');
        $(this).children('td, th').css('background-color','#cccccc');
        $('#id_contact').val($(this).find("[name='id_contact']").val());
        if($(this).find("i").eq(0).hasClass("fa fa-check"))
        {
          $('#tutor').val(1);
        }else {
          $('#tutor').val(0);
        }
        $('#type').val($(this).find("td").eq(1).text());
        $('#full_name').val($(this).find("td").eq(2).text());
        $('#mobile_phone').val($(this).find("td").eq(4).text());
        $('#address').val($(this).find("td").eq(5).text());
        $('#company').val($(this).find("td").eq(7).text());
        $('#position_company').val($(this).find("td").eq(8).text());
        $('#email').val($(this).find("td").eq(3).text());
        $('#home_phone').val($(this).find("[name='home_phone_contact']").val());
        $('#work_phone').val($(this).find("[name='work_phone_contact']").val());
        $('#work_phone_ext').val($(this).find("[name='work_phone_ext_contact']").val());
        $('#observation').val($(this).find("[name='observations_contact']").val());
        $('#id_number_contact').val($(this).find("[name='id_number_contact']").val());

        $('#btn-clear-contact').text("DELETE");

      });
    });

</script>
