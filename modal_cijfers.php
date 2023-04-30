<div class="modal fade" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header primary-bg-color">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-label">Modal title</h4>
      </div>
      <div class="modal-body sixth-bg-color">
        <form id="opslaan-toets" class="form-horizontal align-left" role="form">
          <input type="hidden" id="id_cijfersextra_modal" name="id_cijfersextra_modal" />
          <input type="hidden" id="klas_cijfersextra_modal" name="klas_cijfersextra_modal" />
          <input type="hidden" id="schooljaar_cijfersextra_modal" name="schooljaar_cijfersextra_modal" />
          <input type="hidden" id="rapnummer_cijfersextra_modal" name="rapnummer_cijfersextra_modal" />
          <input type="hidden" id="vak_cijfersextra_modal" name="vak_cijfersextra_modal" />
          <input type="hidden" id="index_modal" name="index_modal" />
          <div class="alert alert-error hidden">
            <p><i class="fa fa-warning"></i> Excuseer me, was er een fout in het verzenden van berichten!</p>
          </div>
          <div class="alert alert-ok hidden">
            <p><i class="fa fa-warning"></i> Saved!</p>
          </div>
          <fieldset>
            <div class="form-group">
              <label class="col-md-4 control-label calendar" for="date">Date</label>
              <div class="col-md-8">
                <div class="input-group date">
                  <input type="text" id="duedatum" name="duedatum" class="form-control input-sm calendar">
                  <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="extra-informatie">Extra informatie</label>
              <div class="col-md-8">
                <textarea id="extra-informatie" name="extra-informatie" rows="3" class="form-control"></textarea>
              </div>
            </div>
            <div class="form-group pull-right" style="margin-right:0px;">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
              <button type="submit" id="opslaan-toets-info" class="btn btn-primary">Opslaan</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
