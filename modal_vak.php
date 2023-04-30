<div class="modal fade" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header primary-bg-color">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-label">Modal title</h4>
      </div>
      <div class="modal-body sixth-bg-color">
        <form id="opslaan-toets" class="form-horizontal align-left" role="form">
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