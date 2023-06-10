                <div id="mec_meta_box_date_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-1 offset-2">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="continuEvent" name="continuEvent" aria-label="Événement continu"%1$s>
                        </div>
                        <input type="text" for="continuEvent" class="form-control col-3" aria-label="Événement continu" value="Événement continu" readonly>

                        <div class="col-1">&nbsp;</div>

                        <div class="input-group-text pr-4 col-1">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="allDayEvent" name="allDayEvent" aria-label="Toute la journée"%4$s>
                        </div>
                        <input type="text" for="allDayEvent" class="form-control col-3" aria-label="Toute la journée" value="Toute la journée" readonly>
                    </div>            

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateDebut">Début</span>
                        <input type="date" class="form-control col-5" id="dateDebut" name="dateDebut" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%2$s" required>
                        <input type="time" class="form-control col-5" id="heureDebut" name="heureDebut" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%5$s" %7$s>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateFin">Fin</span>
                        <input type="date" class="form-control col-5" id="dateFin" name="dateFin" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%3$s" required>
                        <input type="time" class="form-control col-5" id="heureFin" name="heureFin" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%6$s" %7$s>
                    </div>
                </div>
