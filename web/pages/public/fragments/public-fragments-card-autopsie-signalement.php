                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Signalement</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group row mb-3">
                          <label for="sexe" class="col-form-label-sm form-control col-2">Sexe</label>
                          <input type="text" class="form-control col-3" id="sexe" name="sexe" value="%1$s">
                          <label for="ethnie" class="col-form-label-sm form-control col-3">Ethnie</label>
                          <input type="text" class="form-control col-4" id="ethnie" name="ethnie" value="%2$s">
                        </div>
                        <div class="input-group row mb-3">
                          <label for="taille" class="col-form-label-sm form-control col-2">Taille</label>
                          <input type="text" class="form-control col-3" id="taille" name="taille" value="%3$s">
                          <label for="poids" class="col-form-label-sm form-control col-3">Poids</label>
                          <input type="text" class="form-control col-4" id="poids" name="poids" value="%4$s">
                        </div>
                        <div class="input-group row mb-3">
                          <label for="corpulence" class="col-form-label-sm form-control col-3">Corpulence</label>
                          <select id="corpulence" name="corpulence" class="form-control col-3">
                            <option></option>%5$s
                          </select>
                        </div>
    
                        <h5>Yeux</h5>
                        <div class="input-group row mb-3">
                            <label for="yeux_couleur" class="col-form-label-sm form-control col-3">Couleur</label>
                            <input type="text" class="form-control col-2" id="yeux_couleur" name="yeux_couleur" value="%6$s"/>
                            <label for="yeux_particularite" class="col-form-label-sm form-control col-4">Particularités</label>
                            <input type="text" class="form-control col-3" id="yeux_particularite" name="yeux_particularite" value="%7$s"/>
                        </div>
                        <div class="input-group row mb-3">
                        	<label for="lunettes" class="col-form-label-sm form-control col-3">Lunettes</label>
                            <div class="input-group-text form-control col-1">
                                <input class="form-check-input mt-0" type="checkbox" value="hasLunettes" id="lunettes" name="lunettes" style="margin-left: 0px;"%8$s>
                            </div>
                            <label for="formeLunettes" class="col-form-label-sm form-control col-3">Forme</label>
		                    <input type="text" class="form-control col-5" id="formeLunettes" name="formeLunettes" value="%9$s">
                        </div>
                        <div class="input-group row mb-3">
                          	<label for="lentilles" class="col-form-label-sm form-control col-3">Lentilles</label>
                          	<div class="input-group-text form-control col-1">
                            	<input class="form-check-input mt-0" type="checkbox" value="hasLentilles" id="lentilles" name="lentilles" style="margin-left: 0px;"%10$s>
                          	</div>
                        </div>
    
                        <h5>Cheveux</h5>
                        <div class="input-group row mb-3">
                            <label for="cheveux_couleur" class="col-form-label-sm form-control col-3">Couleur</label>
                            <input type="text" class="form-control col-2" id="cheveux_couleur" name="cheveux_couleur" value="%11$s"/>
                            <label for="cheveux_particularite" class="col-form-label-sm form-control col-4">Particularités</label>
                            <input type="text" class="form-control col-3" id="cheveux_particularite" name="cheveux_particularite" value="%12$s"/>
                        </div>
                        <div class="input-group row mb-3">
                          	<label class="col-form-label-sm form-control col-3" for="cheveux_longueur">Longueur</label>
                          	<select class="form-control col-3" name="cheveux_longueur">
                            	<option></option>%13$s
                          	</select>
                          	<label class="col-form-label-sm form-control col-3" for="cheveux_coiffure">Coiffure</label>
                          	<select class="form-control col-3" name="cheveux_coiffure">
                            	<option></option>%14$s
                          	</select>
                        </div>
    
                        <h5>Pilosité</h5>
                        <div class="input-group row mb-3">
                            <label for="pilosite_couleur" class="col-form-label-sm form-control col-3">Couleur</label>
                            <input type="text" class="form-control col-2" id="pilosite_couleur" name="pilosite_couleur" value="%15$s"/>
                            <label for="pilosite_particularite" class="col-form-label-sm form-control col-4">Particularités</label>
                            <input type="text" class="form-control col-3" id="pilosite_particularite" name="pilosite_particularite" value="%16$s"/>
                        </div>
                        <div class="input-group row mb-3">
                            <label for="barbe" class="col-form-label-sm form-control col-3">Barbe</label>
                            <div class="input-group-text form-control col-1">
                                <input class="form-check-input mt-0" type="checkbox" value="hasBarbe" id="barbe" name="barbe" style="margin-left: 0px;"%17$s>
                            </div>
                            <label for="moustache" class="col-form-label-sm form-control col-3">Moustache</label>
                            <div class="input-group-text form-control col-1">
                                <input class="form-check-input mt-0" type="checkbox" value="hasMoustache" id="moustache" name="moustache" style="margin-left: 0px;"%18$s>
                            </div>
                        </div>
                        
                        <h5>Signes particuliers</h5>
    
                    signeparticulier
                        liste
                            type/description/localisation    : triplet
                    </div>
                </div>
