<?php
  include 'document_start.php';
  include 'sub_nav.php';
?>

<div class="push-content-220">
    <main id="main" role="main">
        <?php include 'header.php'; ?>
        <section>
            <div class="container container-fs">
                <div class="row">
                    <div class="default-secondary-bg-color col-md-12 inset brd-bottom">
                        <h1 class="primary-color">Notifications</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 full-inset">
                      <div class="primary-bg-color brd-full">
        								<div class="box">
        									<div class="box-title full-inset brd-bottom">
        										<div class="row">
        											<h2 class="col-md-2">Notifications </h2>
        											<div class="col-md-10">
        											</div>
        										</div>
        									</div>
        									<div class="box-content full-inset sixth-bg-color">
        										<div id="table_notifications" class="dataRetrieverOnLoad" data-ajax-href="ajax/getnotificationslist_tabel.php"></div>
        										<div class="table-responsive">
        											<div id="table_notifications_result"></div>
        										</div>
        									</div>
        								</div>
        							</div>
        						</div>
                    <div class="col-md-4 full-inset">
                        <div class="primary-bg-color brd-full">
                            <div class="box">
                                <div class="box-title full-inset brd-bottom">
                                    <h3>New Notifications</h3>
                                </div>
                                <div class="sixth-bg-color box content full-inset">
                                    <form class="form-horizontal align-left" role="form" name="form-add-notifications" id="form-notifications">
                                      <div class="alert alert-danger hidden">
                												<p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
                											</div>
                											<div class="alert alert-info hidden">
                												<p><i class="fa fa-check"></i> Bedankt voor het toevoegen van een nieuwe oproeping u!</p>
                											</div>

                                        <div role="tabpanel" class="tab-pane active" id="tab1">
                                           <fieldset>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="">Subject:</label>

                                                    <div class="col-md-8">
                                                        <select id="notification_subject" name="notification_subject" class="form-control">
                                                            <option value="General">General</option>
                                                            <option value="Exam">Exam</option>
                                                            <option value="Homework">Homework</option>
                                                            <option value="Test">Test</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- School -->
                                                <div id="lblto" class="form-group">
                                                    <label class="col-md-4 control-label" for="">To:</label>
                                                    <div class="col-md-8">
                                                        <select id="cboTo" name="cbo_to" class="form-control">
                                                            <option value="all_s">All students</option>
                                                            <option value="all_u">All users</option>
                                                            <option value="all_s_all_u">All users & Students</option>
                                                            <option value="class">Class</option>
                                                            <option value="m_team">Management team</option>
                                                            <option value="t_team">Teachers team</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- school -->
                                               <input id="idSchool" name="idSchool" type="hidden" value="<?php print $_SESSION["SchoolID"] ?>">
                                               <!-- Class -->
                                               <div id="lbl_Class" class="form-group">
                                                   <label class="col-md-4 control-label" for="">Class:</label>
                                                   <div id="data_class" class="col-md-8">

                                                   </div>
                                               </div>
                                               <div id="lbl_txtMessage" class="form-group">
                                                   <label class="col-md-4 control-label" for="">Message:</label>
                                                   <div id="text_message"  class="col-md-8">
                                                     <textarea id="message" name="message" rows="8" cols="31" maxlength="140"></textarea>
                                                   </div>
                                               </div>

                                                <div class="form-group full-inset">
                                                    <button type="button" class="btn btn-primary btn-m-w pull-right mrg-left" id="btn-add-notificaction">Create</button>
                                                    <button type="reset" class="btn btn-danger btn-m-w pull-right mrg-left" id="btn-clear">Clear</button>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-2 full-inset"></div> -->
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>


<script type="text/javascript">
$( document ).ready(function() {
  $('#table_notifications_result').css({"overflow-y": "auto", "height": "335px"})
});
    $(function(){
      $("#lbl_Class").hide();
      $("#cboTo").change(
        function()
        {
            var combo_to_text =  $("#cboTo option:selected").text();
            var id_school_val =  $("#idSchool").val();
            if (combo_to_text == "Class")
            {
              $.post("ajax/getlistclass.php",
                  {
                      idSchool : id_school_val
                  },
                  function(data1)
                  {
                      $("#lbl_Class").show();
                      $("#data_class").html(data1);
                  }
              );
            }
            else {
              $("#lbl_Class").hide();
            }
        }
      );
      $('#btn-add-notificaction').click(function()
      {
        // alert($("#cboTo option:selected").text());
        //
        $.post("ajax/sendmessagenotification.php",
          {
            xtype_subject : $("#notification_subject option:selected").val(),
            xtsubject : $("#cboTo option:selected").text(),
            xmessage : $("#message").val(), // Message
            xoption_selected : $("#cboTo option:selected").val(), // To
            xclass: ($("#invoicepaymentclass").length > 0 ? $("#invoicepaymentclass").val() : "0"),
            xschoolID : $("#idSchool").val()
          },
          function (data)
            {
              if(data == "1")
              {
                $('.alert-info').removeClass('hidden').delay(3000).queue(function(next){$(this).addClass('hidden');});
                $('.alert-danger').addClass('hidden');
                setTimeout(function(){$('.alert-info').addClass('hidden');}, 3000);
                $('#message').val('');
                $.get("ajax/getnotificationslist_tabel.php", function(htmlresult) {
                    $('#table_notifications_result').empty();
                    $("#table_notifications_result").append(htmlresult);
                });
              }
              else
              {
                $('.alert-info').addClass('hidden');
                $('.alert-danger').removeClass('hidden').delay(3000).queue(function(next){$(this).addClass('hidden');});
                setTimeout(function(){$('.alert-danger').addClass('hidden');}, 3000);
              }
            }
          );
      });
      $('#btn-clear').click(function ()
      {
        $('.alert-info').fadeOut();
        $('.alert-danger').fadeOut();
      }
      );
      });
</script>