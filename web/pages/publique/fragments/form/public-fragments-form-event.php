<form id="%1$s" method="post">
    <div id="newEvent" class="card">
        <div class="card-header border-transparent">
            <h3 class="card-title">Éditer un événement</h3>
        </div>

        <div class="card-body row" style="display: flex !important">
            <div id="mec_meta_box_categ_form" class="col">
                <div class="form-check mb-1">
                    <label class="form-check-label">&nbsp;</label>
                </div>
                
                <div class="form-group mb-1">
                    <label for="eventLibelle">Libellé</label>
                    <input type="text" class="form-control" id="eventLibelle" name="eventLibelle" placeholder="" value="%3$s" required>
                </div>

                <div class="form-group mb-1">
                    <label for="categorieId">Catégorie</label>
                    <select name="categorieId" id="categorieId" class="custom-select">%4$s</select>
                </div>
            </div>
            
            <div id="mec_meta_box_date_form" class="col">
                <div class="form-check mb-1">
                       <div class="col-8 offset-4">
                        <input type="checkbox" name="allDayEvent" id="event_allday" value="1" class="form-check-input" data-trigger="click" data-action="toggle" data-target=".cols-event-horaire"%5$s>
                        <label class="form-check-label" for="event_allday">All-day Event</label>
                    </div>
                </div>
            
                <div class="form-group mb-1">
                    <label for="dateDebut"><span class="fa-solid fa-calendar-days"></span> Date de début</label>
                    <div class="row">
                        <div class="col-4">
                              <input type="date" class="form-control" id="dateDebut" name="dateDebut" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%6$s" required>
                        </div>
                        <div class="col-8 cols-event-horaire"%7$s>
                              <select name="heureDebut" id="event_start_hour" title="Hours" class="custom-select" style="width: 65px;">%8$s</select>
                            <span class="time-dv">:</span>
                            <select name="minuteDebut" id="event_start_minutes" title="Minutes" class="custom-select" style="width: 65px;">%9$s</select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-1">
                    <label for="dateFin"><span class="fa-solid fa-calendar-days"></span> Date de début</label>
                    <div class="row">
                        <div class="col-4">
                              <input type="date" class="form-control" id="dateFin" name="dateFin" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%10$s" required>
                        </div>
                        <div class="col-8 cols-event-horaire"%7$s>
                              <select name="heureFin" id="event_end_hour" title="Hours" class="custom-select" style="width: 65px;">%11$s</select>
                            <span class="time-dv">:</span>
                            <select name="minuteDebut" id="event_end_minutes" title="Minutes" class="custom-select" style="width: 65px;">%12$s</select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body row" style="display: flex !important">
            <div class="form-check">
                <input type="checkbox" name="repeatStatus" id="event_repeat" value="1" class="form-check-input" class="form-check-input" data-trigger="click" data-action="toggle" data-target=".rows-event-repeat"%13$s>
                <label class="form-check-label" for="event_repeat">Événement récurrent</label>
            </div>
            
            <div id="mec_meta_box_repeat_form" class="col-12">
                <div class="rows-event-repeat row"%14$s>
                    <div class="col-3">
                        <label>Périodicité</label>
                        %15$s
                    </div>
                    <div class="col-9" id="tab_daily">
                        <div class="form-group row">
                            <div class="col-4">
                                <label for="repeatInterval">Intervalle de répétition</label>
                            </div>
                            <input class="col-8 form-control" type="text" name="repeatInterval" id="repeatInterval" placeholder="Intervalle de répétition" value="%16$s">
                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label>Fin de l'événement</label>
                            </div>
                            <div class="col-8">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="repeat_ends_never" name="repeatEnd" value="never"%17$s>
                                            <label for="repeat_ends_never" class="custom-control-label">Jamais</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="event_repeat_ends_date" name="repeatEnd" value="endDate"%18$s>
                                            <label for="event_repeat_ends_date" class="custom-control-label">Date de fin</label>
                                        </div>
                                    </div>
                                    <input class="col-6 hasDatepicker form-control" type="date" name="endDateValue" id="endDateValue" autocomplete="off" value="%19$s">
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="event_repeat_ends_occurrences" name="repeatEnd" value="endRepeat"%20$s>
                                            <label for="event_repeat_ends_occurrences" class="custom-control-label">Après X répétitions</label>
                                        </div>
                                    </div>
                                    <input class="col-6 form-control" type="text" name="endRepetitionValue" id="endRepetitionValue" autocomplete="off" value="%21$s">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer clearfix">
            <input type="hidden" name="writeAction">
            <input type="hidden" name="id" value="%2$s">
            <button class="btn btn-sm btn-primary float-start" data-trigger="click" data-action="submit" data-target="#%1$s">Envoyer</button>
        </div>
    </div>
</form>
