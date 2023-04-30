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
                        <?php if ($_SESSION["UserRights"]!="TEACHER"){include 'breadcrumb.php'; }?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 full-inset"></div>
                    <div class="col-md-10 full-inset">
                        <div class="primary-bg-color brd-full">
                            <div class="box">
                                <div class="box-title full-inset brd-bottom">
                                    <h3>Notifications</h3>
                                </div>
                                <div class="sixth-bg-color box content full-inset">
                                    <form class="form-horizontal align-left" role="form" name="form-add-notifications" id="form-notifications">
                                      <input type="hidden" id="UserGUID" name="UserGUID" value=<?php print $_SESSION["UserGUID"] ?>>
                                        <div role="tabpanel" class="tab-pane active" id="tab1">
                                          <fieldset>
                                            <div class="box-content full-inset sixth-bg-color">
                                              <div id="table_notifications" class="dataRetrieverOnLoad" data-ajax-href="ajax/getnotifications_tabel.php">
                                              </div>
                                              <div class="table-responsive">
                                                <div id="table_notifications_result"></div>
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
