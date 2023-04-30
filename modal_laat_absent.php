

<div class="modal fade" id="laatabsent" tabindex="-1" role="dialog" aria-labelledby="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header primary-bg-color">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-label">Te Laat / Absent opmerkingen</h4>
      </div>
      <div class="modal-body sixth-bg-color">
        <form id="opslaan-absent" class="form-horizontal align-left" role="form">
          <fieldset>
            <div class="form-group">
              <label class="col-md-4 control-label" for="klas">Klas</label>
              <div class="col-md-8">
                <select id="klas" class="form-control" name="klas">
                  <option value="x">Klas 1A</option>
                  <option value="x">Klas 1B</option>
                  <option value="x">Klas 1C</option>
                </select>
              </div>              
            </div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="student">Student</label>
              <div class="col-md-8">
                <select id="student" class="form-control" name="student">
                  <option value="x">student</option>
                  <option value="x">student</option>
                  <option value="x">student</option>
                </select>
              </div>              
            </div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="reden">Reden</label>
              <div class="col-md-8">
                <input type="text" id="reden" name="reden" class="form-control" />
              </div>              
            </div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="duedatum">Due datum</label>
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
              <label class="col-md-4 control-label" for="aantekeningen">Aantekeningen</label>
              <div class="col-md-8">
                <textarea id="aantekeningen" name="aantekeningen" rows="3" class="form-control"></textarea>
              </div>              
            </div>
            <div class="form-group pull-right" style="margin-right:0px;">
              <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
              <button type="submit" id="opslaan-leerling-logboek" class="btn btn-primary">Opslaan</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>