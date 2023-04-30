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
                        <h1 class="primary-color">Birthday Student List</h1>
                        <?php include 'breadcrumb.php'; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 full-inset"></div>
                    <div class="col-md-10 full-inset">
                        <div class="primary-bg-color brd-full">
                            <div class="box">
                                <div class="box-title full-inset brd-bottom">
                                    <h3>Birthday Calendar</h3>
                                </div>
                                <div class="sixth-bg-color box content full-inset">
                                     <form class="form-horizontal align-left" role="form" name="form_birthday" id="form_birthday"> 
                                      <input type="hidden" id="UserGUID" name="UserGUID" value=<?php print $_SESSION["UserGUID"] ?>>
                                        <div role="tabpanel" class="tab-pane active" id="tab1">
										<fieldset>
                                            <div class="box-content full-inset sixth-bg-color">
                                              <div id="tbl_birthdaylist" class="dataRetrieverOnLoad" data-ajax-href="ajax/getbirthday_tabel.php">
                                              </div>
                                              <div>
                                                <div id="tbl_birthdaylist_result"></div>
                                              </div>
                                            </div>                                            
                                          </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 full-inset"></div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</div>

<?php include 'document_end.php'; ?>
