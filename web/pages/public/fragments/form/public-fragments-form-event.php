<form id="%1$s" method="post">
  <div id="newEvent" class="card" style="%2$s">
    <div class="card-header border-transparent">
      <h3 class="card-title">Nouvel Événement</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa-solid fa-times"></i></button>
      </div>
    </div>

    <div class="card-body row" style="display: flex !important">
      <div id="mec_meta_box_date_form" class="col">
        <div class="form-group mb-1">
          <label for="eventLibelle">Libellé</label>
          <input type="text" class="form-control" id="eventLibelle" name="eventLibelle" placeholder="" required>
        </div>

        <div class="form-group mb-1">
          <label for="dateDebut"><span class="fa-solid fa-calendar-days"></span> Date de début</label>
          <div class="row">
            <div class="col-4">
              <input type="text" class="form-control" id="dateDebut" name="dateDebut" placeholder="jj/mm/aaaa" required data-format="jj/mm/aaaa">
            </div>
            <div class="col-8 cols-event-horaire">
              <select name="heureDebut" id="event_start_hour" title="Hours" class="custom-select" style="width: 65px;">
                <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option selected="selected" value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
              </select>
              <span class="time-dv">:</span>
              <select name="minuteDebut" id="event_start_minutes" title="Minutes" class="custom-select" style="width: 65px;">
                <option selected="selected" value="0">00</option><option value="5">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group mb-1">
          <label for="dateFin"><span class="fa-solid fa-calendar-days"></span> Date de fin</label>
          <div class="row">
            <div class="col-4">
              <input type="datepicker" class="form-control" id="dateFin" name="dateFin" placeholder="jj/mm/aaaa" required>
            </div>
            <div class="col-8 cols-event-horaire">
              <select name="heureFin" id="event_end_hour" title="Hours" class="custom-select" style="width: 65px;">
                <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option selected="selected" value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
              </select>
              <span class="time-dv">:</span>
              <select name="minuteFin" id="event_end_minutes" title="Minutes" class="custom-select" style="width: 65px;">
                <option selected="selected" value="0">00</option><option value="5">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-check mb-1">
          <input type="checkbox" name="allDayEvent" id="event_allday" value="1" class="form-check-input" data-trigger="click" data-action="toggle" data-target=".cols-event-horaire">
          <label class="form-check-label" for="event_allday">All-day Event</label>
        </div>

        <div class="form-check">
          <input type="checkbox" name="repeatStatus" id="event_repeat" value="1" class="form-check-input" class="form-check-input" data-trigger="click" data-action="toggle" data-target=".rows-event-repeat">
          <label class="form-check-label" for="event_repeat">Événement récurrent</label>
        </div>
      </div>

      <div id="mec_meta_box_repeat_form" class="col">
        <div class="rows-event-repeat" style="display:none;">
          <h4>Répetition</h4>

          <div class="form-group row">
            <div class="col-6">
              <label for="repeatType">Récurrence</label>
            </div>
            <select name="repeatType" id="repeatType" class="col-6 custom-select">
              <option selected="selected" value="daily">Quotidienne</option>
              <option value="weekly">Hebdomadaire</option>
              <option value="monthly">Mensuelle</option>
              <option value="yearly">Annuelle</option>
            </select>
          </div>

          <div class="form-group row">
            <div class="col-6">
              <label for="repeatInterval">Intervalle de répétition</label>
            </div>
            <input class="col-6 form-control" type="text" name="repeatInterval" id="repeatInterval" placeholder="Intervalle de répétition" value="1">
          </div>

          <h4>Fin de l'événement</h4>

          <div class="form-group row">
            <div class="col-12">
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="repeat_ends_never" name="repeatEnd" value="never">
                <label for="repeat_ends_never" class="custom-control-label">Jamais</label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-6">
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="event_repeat_ends_date" name="repeatEnd" value="endDate">
                <label for="event_repeat_ends_date" class="custom-control-label">Date de fin</label>
              </div>
            </div>
            <input class="col-6 hasDatepicker form-control" type="text" name="endDateValue" id="endDateValue" autocomplete="off" value="">
          </div>

          <div class="form-group row">
            <div class="col-6">
              <div class="custom-control custom-radio">
                <input class="custom-control-input" type="radio" id="event_repeat_ends_occurrences" name="repeatEnd" value="endRepeat">
                <label for="event_repeat_ends_occurrences" class="custom-control-label">Après X répétitions</label>
              </div>
            </div>
            <input class="col-6 form-control" type="text" name="endRepetitionValue" id="endRepetitionValue" autocomplete="off" value="">
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer clearfix">
      <input type="hidden" name="writeAction">
      <button class="btn btn-sm btn-info float-left" data-trigger="click" data-action="submit" data-target="#creerNewEvent">Créer</a>
    </div>
  </div>
</form>
