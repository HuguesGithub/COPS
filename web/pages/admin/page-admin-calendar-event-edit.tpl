<!-- @version v1.23.05.21 -->
<form method="post" class="row p-3 col-12">
    <div class="card col-12 mx-1 p-0">
        <div class="card-header">Éditer un événement</div>

        <!-- Card-body start -->
        <div class="card-body pb-0 px-0">
            <div class="row mx-2">
                <div id="mec_meta_box_categ_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="id">Id</span>
                        <input type="text" name="id" id="id" class="form-control col-10" aria-label="Id" aria-describedby="Identifiant" value="%1$s" readonly>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="eventLibelle">Libellé</span>
                        <input type="text" name="eventLibelle" id="eventLibelle" class="form-control col-10" aria-label="Libellé" aria-describedby="Libellé" value="%2$s" required/>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="categorieId">Catégorie</span>
                        <select name="categorieId" id="categorieId" class="custom-select col-10">%3$s</select>
                    </div>
                </div>
                        
                <div id="mec_meta_box_date_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-1 offset-7">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="allDayEvent" name="allDayEvent" aria-label="Événement continu"%6$s>
                        </div>
                        <input type="text" for="allDayEvent" class="form-control col-4" aria-label="Événement continu" value="Toute la journée" readonly>
                    </div>            

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateDebut">Début</span>
                        <input type="date" class="form-control col-5" id="dateDebut" name="dateDebut" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%4$s" required>
                        <input type="time" class="form-control col-5" id="heureDebut" name="heureDebut" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%7$s" %9$s>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="dateFin">Fin</span>
                        <input type="date" class="form-control col-5" id="dateFin" name="dateFin" placeholder="jj/mm/aaaa" data-format="jj/mm/aaaa" value="%5$s" required>
                        <input type="time" class="form-control col-5" id="heureFin" name="heureFin" placeholder="hh:mm:ss" data-format="hh:mm:ss" value="%8$s" %9$s>
                    </div>
                </div>

                <div id="mec_meta_box_cb_recurrent_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="repeatStatus" name="repeatStatus" aria-label="Événement récurrent"%10$s>
                        </div>
                        <input type="text" for="repeatStatus" class="form-control col-10" aria-label="Événement récurrent" value="Événement récurrent" readonly>
                    </div>            
                </div>

                <div class="col-12"></div>

                <div id="mec_meta_box_period_form" class="mt-3 col-2"%11$s>
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="radio" value="daily" id="repeatTypeDaily" name="repeatType" aria-label="Quotidien"%12$s>
                        </div>
                        <input type="text" for="repeatTypeDaily" class="form-control col-10" aria-label="Quotidien" value="Quotidien" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="radio" value="weekly" id="repeatTypeWeekly" name="repeatType" aria-label="Hebdomadaire"%13$s>
                        </div>
                        <input type="text" for="repeatTypeWeekly" class="form-control col-10" aria-label="Hebdomadaire" value="Hebdomadaire" readonly>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="radio" value="monthly" id="repeatTypeMonthly" name="repeatType" aria-label="Mensuel"%14$s>
                        </div>
                        <input type="text" for="repeatTypeMonthly" class="form-control col-10" aria-label="Mensuel" value="Mensuel" readonly>
                    </div>            
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="radio" value="yearly" id="repeatTypeYearly" name="repeatType" aria-label="Annuel"%15$s>
                        </div>
                        <input type="text" for="repeatTypeYearly" class="form-control col-10" aria-label="Annuel" value="Annuel" readonly>
                    </div>            
                </div>

                <div id="mec_meta_box_recursive_form" class="row mt-3 col-6"%11$s>

                    <div class="col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text col-5" for="repeatInterval">Intervalle de répétition</span>
                            <input type="text" name="repeatInterval" id="repeatInterval" class="form-control col-7" aria-label="Intervalle de répétition" aria-describedby="Intervalle de répétition" value="%17$s"/>
                        </div>
                    </div>

                    <div class="col-4">
                        <label>Fin de l'événement</label>
                    </div>

                    <div class="col-8">
                        <div class="input-group mb-3">
                            <div class="input-group-text pr-4 col-1">
                                <input class="form-check-input mt-0" type="radio" value="never" id="repeatEndNever" name="repeatEnd" aria-label="Jamais"%18$s>
                            </div>
                            <input type="text" for="repeatEndNever" class="form-control col-11" aria-label="Jamais" value="Jamais" readonly>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-text pr-4 col-1">
                                <input class="form-check-input mt-0" type="radio" value="endDate" id="repeatEndDate" name="repeatEnd" aria-label="Date de fin"%19$s>
                            </div>
                            <input type="text" for="repeatEndDate" class="form-control col-5" aria-label="Date de fin" value="Date de fin" readonly>
                            <input class="form-control col-6" type="date" name="endDateValue" id="endDateValue" value="%20$s"%21$s>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-text pr-4 col-1">
                                <input class="form-check-input mt-0" type="radio" value="endRepeat" id="repeatEndValue" name="repeatEnd" aria-label="Après X occurrences"%22$s>
                            </div>
                            <input type="text" for="repeatEndValue" class="form-control col-5" aria-label="Après X occurrences" value="Après X occurrences" readonly>
                            <input class="form-control col-6" type="text" name="endRepetitionValue" id="endRepetitionValue" value="%23$s"%24$s>
                        </div>
                    </div>

                </div>

                <div id="mec_meta_box_custom_form" class="row mt-3 col-4"%11$s>
                    <div class="input-group mb-3 col-12">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="checkbox" value="custom" id="customEvent" name="customEvent" aria-label="Personnalisé">
                        </div>
                        <input type="text" for="customEvent" class="form-control col-10" aria-label="Personnalisé" value="Personnalisé" readonly="">
                    </div>            

                    <div class="input-group mb-3 col-12">
                        <span class="input-group-text col-4" for="categorieId">Tous les</span>
                        <select name="customDay" id="customDay" class="custom-select col-8">
                            <option value="1">1er</option>
                            <option value="2">2è</option>
                            <option value="3">3è</option>
                            <option value="4">4è</option>
                            <option value="-1">Dernier</option>
                        </select>
                    </div>

                    <div class="input-group mb-3 col-12">
                        <select name="customDayWeek" id="customDayWeek" class="custom-select col-8 offset-4">
                            <option value="1">Lundi</option>
                            <option value="2">Mardi</option>
                            <option value="3">Mercredi</option>
                            <option value="4">Jeudi</option>
                            <option value="5">Vendredi</option>
                            <option value="6">Samedi</option>
                            <option value="7">Dimanche</option>
                        </select>
                    </div>

                    <div class="input-group mb-3 col-12">
                        <span class="input-group-text col-4">de</span>
                        <select name="customMonth" id="customMonth" class="custom-select col-8">
                            <option value="9">Janvier</option>
                            <option value="8">Février</option>
                            <option value="1">Mars</option>
                            <option value="17">Avril</option>
                            <option value="11">Mai</option>
                            <option value="10">Juin</option>
                            <option value="13">Juillet</option>
                            <option value="6">Août</option>
                            <option value="2" selected="selected">Septembre</option>
                            <option value="12">Octobre</option>
                            <option value="14">Novembre</option>
                            <option value="21">Décembre</option>
                        </select>
                    </div>

                </div>

            <div class="card-footer col-12">
                <input type="hidden" name="writeAction" value="write">
                <div class="btn-group">
                    <button class="btn btn-sm btn-danger p-0"><a href="%26$s" style="margin: .25rem .5rem;text-decoration: none; color: white;">Supprimer</a></button>
                    <button class="btn btn-sm btn-outline p-0"><a href="%25$s" style="margin: .25rem .5rem;text-decoration: none; color: black;">Annuler</a></button>
                    <button class="btn btn-sm btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
        <!-- Card-body end -->

    </div>
</form>
