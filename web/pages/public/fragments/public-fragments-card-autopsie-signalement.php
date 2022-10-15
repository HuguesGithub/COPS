                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Signalement</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group row">
                          <span class="col-form-label-sm col-4">Sexe</span>
                          <input type="text" class="form-control col-2" name="sexe" value="%1$s">
                          <span class="col-form-label-sm col-3">Ethnie</span>
                          <input type="text" class="form-control col-3" name="ethnie" value="%2$s">
                        </div>
                        <div class="input-group row mb-3">
                          <span class="col-form-label-sm col-4">Taille</span>
                          <input type="text" class="form-control col-2" name="taille" value="%3$s">
                          <span class="col-form-label-sm col-4">Poids</span>
                          <input type="text" class="form-control col-2" name="poids" value="%4$s">
                        </div>
                        <div class="row">
                            <div class="form-floating mb-3 col-6">
                                <select name="corpulence" class="form-control">
                                    <option></option>%5$s
                                </select>
                                <label for="corpulence" class="col-form-label col-form-label-sm">Corpulence</label>
                            </div>
                        </div>
    
						<h5>Yeux</h5>
						<div class="row">
							<div class="col-6">
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="yeux_couleur" name="yeux_couleur"/>
									<label for="yeux_couleur" class="col-form-label col-form-label-sm">Couleur</label>
								</div>
							</div>
							<div class="col-6">
								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="yeux_particularite" name="yeux_particularite"/>
									<label for="yeux_particularite" class="col-form-label col-form-label-sm">Particularités</label>
								</div>
							</div>
						</div>
						<div class="input-group row mb-3">
							<span class="col-form-label-sm col-3">Lunettes</span>
							<div class="input-group-text form-control col-1">
								<input class="form-check-input mt-0" type="checkbox" value="" name="lunettes" style="margin-left: 0px;">
							</div>
							<span class="col-1">&nbsp;</span>
							<span class="col-form-label-sm col-3 offset-1">Forme</span>
							<input type="text" class="form-control col-4" name="formeLunettes" value="">
						</div>
						<div class="input-group row mb-3">
						  <span class="col-form-label-sm col-3">Lentilles</span>
						  <div class="input-group-text form-control col-1">
							<input class="form-check-input mt-0" type="checkbox" value="" name="lentilles" style="margin-left: 0px;">
						  </div>
						  <div class="col-8">&nbsp;</div>
						</div>
    
                        <fieldset>
                            <legend>Cheveux</legend>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cheveux_couleur" name="cheveux_couleur"/>
                                        <label for="cheveux_couleur" class="col-form-label col-form-label-sm">Couleur</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cheveux_particularite" name="cheveux_particularite"/>
                                        <label for="cheveux_particularite" class="col-form-label col-form-label-sm">Particularités</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="form-floating mb-3 col-6">
                                  <select class="form-control">
                                    <option selected></option>
                                    <option value="1">Longs</option>
                                    <option value="2">Courts</option>
                                    <option value="3">Calvitie</option>
                                    <option value="4">Chauve</option>
                                  </select>
                                  <label class="col-form-label col-form-label-sm" for="inputGroupSelect01">Longueur</label>
                                </div>
                                <div class="form-floating mb-3 col-6">
                                  <select class="form-control">
                                    <option selected></option>
                                    <option value="1">Raides</option>
                                    <option value="2">Ondulés</option>
                                    <option value="3">Frisés</option>
                                  </select>
                                  <label class="col-form-label col-form-label-sm" for="inputGroupSelect01">Coiffure</label>
                                </div>
                            </div>
                        </fieldset>
    
                        <fieldset>
                            <legend>Pilosité</legend>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="pilosite_couleur" name="pilosite_couleur"/>
                                        <label for="pilosite_couleur" class="col-form-label col-form-label-sm">Couleur</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="pilosite_particularite" name="pilosite_particularite"/>
                                        <label for="pilosite_particularite" class="col-form-label col-form-label-sm">Particularités</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-floating mb-3 col-6">
                                    <div class="input-group mb-3 col-12">
                                      <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" value="" aria-label="Checkbox for following text input">
                                      </div>
                                      <span class="input-group-text" id="inputGroup-sizing-sm">Barbe</span>
                                    </div>
                                    <div class="input-group mb-3 col-12">
                                      <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="checkbox" value="" aria-label="Checkbox for following text input">
                                      </div>
                                      <span class="input-group-text" id="inputGroup-sizing-sm">Moustache</span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
    
                    signeparticulier
                        liste
                            type/description/localisation    : triplet
                    </div>
                </div>
