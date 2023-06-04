                <div id="mec_meta_box_date_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-1 offset-7">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="allDayEvent" name="allDayEvent" aria-label="Événement continu"%3$s>
                        </div>
                        <input type="text" for="allDayEvent" class="form-control col-4" aria-label="Événement continu" value="Toute la journée" readonly>
                    </div>            

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateDebut">Début</span>
                        <input type="date" class="form-control col-5" id="dateDebut" name="dateDebut" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%1$s" required>
                        <input type="time" class="form-control col-5" id="heureDebut" name="heureDebut" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%4$s" %6$s>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateFin">Fin</span>
                        <input type="date" class="form-control col-5" id="dateFin" name="dateFin" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%2$s" required>
                        <input type="time" class="form-control col-5" id="heureFin" name="heureFin" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%5$s" %6$s>
                    </div>
                </div>
