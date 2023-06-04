                    <div class="col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text col-5" for="repeatInterval">Intervalle de répétition</span>
                            <input type="text" name="repeatInterval" id="repeatInterval" class="form-control col-7" aria-label="Intervalle de répétition" aria-describedby="Intervalle de répétition" value="%1$s"/>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text col-4">Fin de l'événement</span>
                            <div class="input-group-text pr-4 col-1">
                                <input class="form-check-input mt-0" type="radio" value="never" id="repeatEndNever" name="repeatEnd" aria-label="Jamais"%2$s>
                            </div>
                            <input type="text" for="repeatEndNever" class="form-control col-7" aria-label="Jamais" value="Jamais" readonly>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-text pr-4 col-1 offset-4">
                                <input class="form-check-input mt-0" type="radio" value="endDate" id="repeatEndDate" name="repeatEnd" aria-label="Date de fin"%3$s>
                            </div>
                            <input type="text" for="repeatEndDate" class="form-control col-3" aria-label="Date de fin" value="Date de fin" readonly>
                            <input class="form-control col-4" type="date" name="endDateValue" id="endDateValue" value="%4$s"%5$s>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-text pr-4 col-1 offset-4">
                                <input class="form-check-input mt-0" type="radio" value="endRepeat" id="repeatEndValue" name="repeatEnd" aria-label="Après X occurrences"%6$s>
                            </div>
                            <input type="text" for="repeatEndValue" class="form-control col-3" aria-label="Après X occurrences" value="Après X occurrences" readonly>
                            <input class="form-control col-4" type="text" name="endRepetitionValue" id="endRepetitionValue" value="%7$s"%8$s>
                        </div>
                    </div>
