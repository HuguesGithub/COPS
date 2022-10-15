                <div class="card">
                    <div class="card-header">
                        <label for="numDossier" class="col-sm-3 col-form-label">Dossier N°</label>
                        <div class="col-sm-7" style="display: inline-block;">
                            <input type="text" class="form-control" id="numDossier" name="numDossier" value="%1$s"/>
                        </div>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="dateHeureExamen" name="dateHeureExamen" value="%2$s"/>
                            <label for="dateHeureExamen" class="col-form-label col-form-label-sm">Date &amp; heure de l'examen</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="praticiensMedicoLegaux" rows="3" name="praticiensMedicoLegaux">%3$s</textarea>
                            <label for="praticiensMedicoLegaux" class="col-form-label col-form-label-sm">Praticiens médico-légaux</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nomPrenomVictime" name="nomPrenomVictime" value="%4$s"/>
                            <label for="nomPrenomVictime" class="col-form-label col-form-label-sm">Nom &amp; Prénom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="ageApparent" name="ageApparent" value="%5$s"/>
                            <label for="ageApparent" class="col-form-label col-form-label-sm">Âge apparent</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="circDecouverte" rows="3" name="circDecouverte">%6$s</textarea>
                            <label for="circDecouverte" class="col-form-label col-form-label-sm">Circonstances de découverte</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="dateHeureDeces" name="dateHeureDeces" value="%7$s">
                            <label for="dateHeureDeces" class="col-form-label col-form-label-sm">Date &amp; heure du décès</label>
                        </div>
                    </div>
                </div>
