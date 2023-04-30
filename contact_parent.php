<?php
ob_start();

require_once("config/app.config.php");
require_once("classes/spn_authentication.php");

if(session_status() == PHP_SESSION_NONE)
{
  session_start();
}
$auth = new spn_authentication();

ob_flush();

?>
<?php $timer = appconfig::GetTimerCijfer_ls(); ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" dir="ltr" class="no-js lte9 lte8 lte7 ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" dir="ltr" class="no-js lte9 lte8 ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" dir="ltr" class="no-js lte9 ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" dir="ltr" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" dir="ltr" class="no-js"> <!--<![endif]-->
<head>

  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Roboto:400,300,500" type="text/css">
  <link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/app.css" type="text/css" />
  <link rel="stylesheet" href="<?php print appconfig::GetBaseURL(); ?>/assets/css/horizontal_tab.css" type="text/css" />
  <link href="mobile/css/bootstrap.min.css" rel="stylesheet">
  <link href="mobile/css/caribedev.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/calendar.css">
  <!-- <link href="mobile/css/caribedev.css" rel="stylesheet"> -->
  <title>SCOL PA NOS</title>

  <meta name="description" content=""/>
  <meta name="author" content="XA TECHNOLOGIES / rudycroes.com"/>

  <link rel="canonical" href="" />
  <link rel="shortcut icon" href="" />
  <script src="<?php print appconfig::GetBaseURL(); ?>/assets/js/lib/modernizr.min.js"></script>
  <body class='body-color'>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Parents SPN</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a id="student_link" href="" style= "padding-bottom: 21px; align=center">Student</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <!-- <li class="active"><a type='button' data-toggle="modal" data-target="#SecurePinModal" href="#" ><span class="fa fa-key fa-2x notification"></span><span></span></a></li> -->
            <li class="active"><a href="#" ><span class="fa fa-bell fa-2x notification"></span><span id="unread"></span></a></li>
            <li class="active"><a href="#" onclick="location.href='logout_parent.php'" ><span class="fa fa-power-off fa-2x"></span></a></li>
          </ul>
          <!-- <ul class="nav navbar-nav navbar-right">
          <li><a href="" class="fa fa-bell notification" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" id="unread"></a></i>

          <li class="fa fa-power-off" style="font-size:30px;color:#FFDC66;	position:relative; top: 10px;;" aria-hidden="true" onclick="location.href ='logout_parent.php'"</i>
        </ul> -->
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <div class="container">
    <br>
    <br>
    <br>
    <br>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron white">
      <div id='dataRequest-student_detail' name='dataRequest-student_detail'></div>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <div class="">
            <h3>New Contact</h3>
          </div>
          <div class="panel-body">
            <form action="#" id='frm_contact_parent'style="border-radius: 0px;" class="form-horizontal group-border-dashed">
              <input type="hidden" id="id_student" name="id_student" value=<?php  echo $_GET["id"]; ?> >
              <input type="hidden" id="id_family" name="id_family" value=<?php  echo $_GET["id_family"]; ?> >
              <input type="hidden" id="id_contact" name="id_contact" value="0">
              <div class="form-group">
                <label class="col-md-3 control-label" for="">Voogd*</label>
                <div class="col-md-7">
                  <select id="tutor" name="tutor" class="form-control" required>
                    <option selected value="">Selecteer Tutor</option>
                    <option  value=1>Ja</option>
                    <option  value=0>Nee</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="">Type*</label>
                <div class="col-md-7">
                  <select id="type" name="type" class="form-control" required>
                    <option  value="">Selecteer een Type</option>
                    <option  value="Mother">Moeder</option>
                    <option  value="Father">Vader</option>
                    <option  value="Uncle">Oom</option>
                    <option  value="Unt">Tante</option>
                    <option  value="Brother">Broer</option>
                    <option  value="Sister">Zuz</option>
                    <option  value="Grand Ma">Groot Moeder</option>
                    <option  value="Grand Pa">Groot Vader</option>
                    <option  value="Mother">Moeder</option>
                    <option  value="Other">Other</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">ID Nummer</label>
                <div class="col-md-7">
                  <input id="id_number_contact" class="form-control" type="text" name="id_number_contact"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Naam *</label>
                <div class="col-md-7">
                  <input id="full_name" class="form-control" type="text" name="full_name" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Addres</label>
                <div class="col-md-7">
                  <input id="address" class="form-control" type="text" name="address"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Email*</label>
                <div class="col-md-7">
                  <input id="email" class="form-control" type="email" name="email" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Mobiel</label>
                <div class="col-md-7">
                  <input id="mobile_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="mobile_phone"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Huis telefoon</label>
                <div class="col-md-7">
                  <input id="home_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="home_phone"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Werk Telefoon</label>
                <div class="col-md-7">
                  <input id="work_phone" class="form-control input-mask" data-mask="eg: (000) 000-0000" placeholder="000-00-0000000" type="text" name="work_phone"/>
                </div>
              </div>
              <div class="form-group fg-line">
                <label class="col-md-3 control-label">Werk Ext. </label>
                <div class="col-md-7">
                  <input id="work_phone_ext" class="form-control input-mask" data-mask="000-00-0000000" placeholder="0000" name="work_phone_ext" type="text" name= ="work_phone_ext" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Company/Work</label>
                <div class="col-md-7">
                  <input id="company" class="form-control" type="text" name="company"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Positie</label>
                <div class="col-md-7">
                  <input id="position_company" class="form-control" type="text" name="position_company"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Observations</label>
                <div class="col-md-7">
                  <textarea id="observation" class="form-control" name="observation" type="text " rows="7"  placeholder="Enter observation here..."></textarea>
                </div>
              </div>
              <div class="form-group col-md-10 full-inset">
                <button type="submit" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn_add_contact">OPSLAAN</button>
                <button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn_clear_contact">CLEAR</button>
              </div>
            </form>
          </div>
        </div>
      </div>


    </div>
    <!-- Modal -->
    <button id='btn_success_contact' class='' type='button' data-toggle="modal" data-target="#success_contact" href="#" ></button>
    <div class="modal fade" id="success_contact" tabindex="-1" role="dialog" aria-labelledby="success_contactTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon fa fa-close"></i></button>
            <h3 class="modal-title blue">Contact</h3>
          </div>
          <div class="modal-body">
            <h4 class='success-secure-pin'>The new contact was created successfully!! </h4>
            </div>

            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
          </div>
        </div>
      </div>


    </div>

  </body>




  <?php include 'document_end.php'; ?>
  <script type="text/javascript" src="assets/js/calendar.js"></script>
  <script type="text/javascript" src="assets/js/app_calendar.js"></script>
  <script type="text/javascript">
  function getParam(key) {
    // Find the key and everything up to the ampersand delimiter
    var value = RegExp("" + key + "[^&]+").exec(window.location.search);

    // Return the unescaped value minus everything starting from the equals sign or an empty string
    return unescape(!!value ? value.toString().replace(/^[^=]+./, "") : "");
  }

  var studentid = getParam("id");
  var familyid = getParam("id_family");

  $.post("ajax/get_number_unread_notifications_parent.php?",
  {
    id_student : studentid
  },
  function(data){
    $("#unread").html(data);
  });

  $("#unread").click(function(e) {
    e.preventDefault();
    window.location.replace("parent_notifications.php?id="+studentid+"&id_family="+familyid);
  });
  $("#student_link").click(function(e) {
    e.preventDefault();
    window.location.replace("home_parent.php?id="+studentid+"&id_family="+familyid);
  });



  $.get("ajax/getleerlingdetail_tabel_mobile_parent.php?id=" + studentid, {}, function(data) {
    $("#dataRequest-student_detail").html(data);
  });

  $("#btn_change_secure_pin").click(function(e) {
    e.preventDefault();

    if ($("#old_secure_pin").val().length > 0){

      if ($("#new_secure_pin").val() === $("#confirm_new_secure_pin").val()){

        $('#studentid').val(studentid);

        $.ajax({
          url: "ajax/change_secure_pin.php",
          data: $('#frm_change_secure_pin').serialize(),
          type: "POST",
          dataType: "text",
          success: function (text) {
            if (text == "1") {
              alert('ok cambiado');
            }
            else {
              alert('error al cambiar');
            }
          },
          error: function (xhr, status, errorThrown) {

          },
          complete: function (xhr, status) {

          }
        });

      }
      else{
        alert('the new pin and confirm do no matchn');
      }
    }
    else{
      alert('the old secure pin');
    }

  });


  $("#frm_contact_parent").submit(function(e) {
    e.preventDefault();

    if(   $("#tutor").val().length === 0   ||$("#type").val().length === 0 || $("#full_name").val().length === 0){
      //Show Alerts and back on top LB
      ('#frm_contact_parent').find('.alert-error').removeClass('hidden');
      // $("#contactpersoon").scroll();
      $('html, body').animate({scrollTop: $("#detaill_seccion_top").offset().top}, 2000);
      return false;

    }
    else {
      /* begin post */
      if ($('#id_contact').val() == "0")
      {
        $.ajax({
          url: "ajax/addcontact_parent.php",
          data: $('#frm_contact_parent').serialize(),
          type: "POST",
          dataType: "text",
          success: function(text) {

            if(text.trim() != "1") {
              ('#frm_contact_parent').find('.alert-error').removeClass('hidden');
              ('#frm_contact_parent').find('.alert-info').addClass('hidden');
              ('#frm_contact_parent').find('.alert-warning').addClass('hidden');
            } else if(text.trim() == "1"){
              $('#btn_success_contact').click();
              setTimeout(function () {
                window.location.href = "home_parent.php?id="+studentid+'&id_family='+familyid;
              }, 3000);
            }

          },
          error: function(xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function(xhr,status) {
            // $("#contactpersoon").scroll();
            $('html, body').animate({scrollTop: $("#detaill_seccion_top").offset().top}, 'fast');
            // $('html, body').animate({scrollTop:100}, 'fast');
            return false;

          }
        });
      }else {
        $.ajax({
          url: "ajax/updatecontact.php",
          data: $('#frm_contact_parent').serialize(),
          type: "POST",
          dataType: "text",
          success: function(text) {

            if(text.trim() != "1") {
              ('#frm_contact_parent').find('.alert-error').removeClass('hidden');
              ('#frm_contact_parent').find('.alert-info').addClass('hidden');
              ('#frm_contact_parent').find('.alert-warning').addClass('hidden');
            } else if(text.trim() == "1"){
              alert('Contact updated successfully!');
              $.get("ajax/getcontactlist_table.php", {id : $("#id_family").val()}, function(data) {
                $('#div_table_contact').empty();
                $("#div_table_contact").append(data);
              });
              $('#id_contact').val("0");
              $('#btn-clear-contact').text("CLEAR");
              // Clear all object of form
              $('input[type=text]').each(function()
              {
                $(this).val('');
              });
              $('#observation').val('');
              $('#tutor option:contains("Select Tutor")').attr('selected', 'selected')
              $('#type option:contains("Select Type")').attr('selected', 'selected');
              $('#email').val('');
              ('#frm_contact_parent').find('.alert-error').addClass('hidden');
              ('#frm_contact_parent').find('.alert-info').addClass('hidden');
              ('#frm_contact_parent').find('.alert-warning').addClass('hidden');
            }

          },
          error: function(xhr, status, errorThrown) {
            console.log("error");
          },

          complete: function(xhr,status) {
            // $("#contactpersoon").scroll();
            $('html, body').animate({scrollTop: $("#detaill_seccion_top").offset().top}, 'fast');
            // $('html, body').animate({scrollTop:100}, 'fast');
            return false;

          }
        });
      }
    }
  });



  </script>
