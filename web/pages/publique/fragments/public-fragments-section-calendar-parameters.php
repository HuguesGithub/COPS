<form method="post">
  <div id="parameters" class="card">
    <div class="card-header border-transparent">
      <h3 class="card-title">Paramètres</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
      </div>
    </div>

    <div class="card-body row" style="display: flex !important">
      <div id="mec_meta_box_date_form" class="col">

        <div class="form-group mb-1">
          <label for="dateDebut"><span class="fa-solid fa-calendar-days"></span> Date Ingame</label>
          <div class="row">
            <div class="col-4">
              <input type="text" class="form-control" id="dateIngame" name="dateIngame" placeholder="jj/mm/aaaa" value="%1$s">
            </div>
            <div class="col-8 cols-event-horaire">
              <select name="heureIngame" id="heureIngame" title="Hours" class="custom-select" style="width: 65px;">
                %2$s
              </select>
              <span class="time-dv">:</span>
              <select name="minuteIngame" id="minuteIngame" title="Minutes" class="custom-select" style="width: 65px;">
                %3$s
              </select>
            </div>
          </div>
        </div>

      </div>
    </div>


    <div class="card-footer clearfix">
      <input type="submit" class="btn btn-sm btn-info float-start" value="Mettre à jour"/>
    </div>
  </div>
</form>
