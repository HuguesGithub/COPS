<form method="post" action="#">
    <div class="col-12 row">
        <div class="col-6">
            <div class="col-12">
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
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Médico-légal</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                          <span class="col-form-label-sm col-4">Coeur</span>
                          <input type="text" class="form-control col-2" name="poidsCoeur" value="%8$s">
                          <span class="col-form-label-smt col-4">Rate</span>
                          <input type="text" class="form-control col-2" name="poidsRate" value="%9$s">
                        </div>
                        <div class="input-group">
                          <span class="col-form-label-sm col-4">Encéphale</span>
                          <input type="text" class="form-control col-2" name="poidsEncephale" value="%10$s">
                          <span class="col-form-label-sm col-4">Foie</span>
                          <input type="text" class="form-control col-2" name="poidsFoie" value="%11$s">
                        </div>
                        <div class="input-group">
                          <span class="col-form-label-sm col-4">Poumon gauche</span>
                          <input type="text" class="form-control col-2" name="poidsPoumonG" value="%12$s">
                          <span class="col-form-label-sm col-4">Rein gauche</span>
                          <input type="text" class="form-control col-2" name="poidsReinG" value="%13$s">
                        </div>
                        <div class="input-group">
                          <span class="col-form-label-sm col-4">Poumon droit</span>
                          <input type="text" class="form-control col-2" name="poidsPoumonD" value="%14$s">
                          <span class="col-form-label-sm col-4">Rein droit</span>
                          <input type="text" class="form-control col-2" name="poidsReinD" value="%15$s">
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-center"><strong>Los Angeles Police Department</strong></h3>
                        <h5 class="col-form-label pt-0 text-center">Forensic Medicine Department</h5>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Enquête</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
						<div class="form-floating mb-3 col-6">
                        	<select name="idxEnquete" class="form-control">
                            	<option></option>
                                <option value="5">Meurtre à la boucherie 4</option>
                            </select>
                            <label for="idxEnquete" class="col-form-label col-form-label-sm">Enquête</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Photo</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label for="constatations" class="col-form-label">Constatations</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="constatations" rows="3" name="constatations">%17$s</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label for="ondontogramme" class="col-form-label">Ondotogramme</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body row pt-0">
                        <div class="input-group col-12 text-center">
                          <span class="col-form-label-sm col-12">Maxillaire</span>
                        </div>
                        <div class="input-group col-6 row">
                          <input type="text" class="form-control col-1 offset-2">
                          %18$s
                        </div>
                        <div class="input-group col-6 row">
                          <input type="text" class="form-control col-1 offset-2">
                          %19$s
                        </div>
                        <div class="input-group col-12 text-center">
                          <span class="col-form-label-sm col-12">Mandibule</span>
                        </div>
                        <div class="input-group col-6 row">
                          <input type="text" class="form-control col-1 offset-2">
                          %20$s
                        </div>
                        <div class="input-group col-6 row">
                          <input type="text" class="form-control col-1 offset-2">
                          %21$s
                        </div>
                        <div class="input-group col-12">
                          <span class="col-form-label-sm">C (Carrie) / S (Soins) / O (Composite) / M (Métal) / A (Cassée) / X (absente) / - (RAS)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="col-12 text-center">
                <img src="/wp-content/plugins/hj-cops/web/rsc/img/cops-fmd-logo-fond-transparent.png" alt="Logo FMD"/>
            </div>
        </div>
    </div>
        
    <div class="col-12 row">
        <div class="col-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label for="numDossier" class="col-sm-3 col-form-label">Dossier N°</label>
                        <div class="col-sm-7" style="display: inline-block;">
                            <input type="text" class="form-control" id="numDossier" name="numDossier" value="%1$s" readonly/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Signalement</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    sexe                : texte
                    taille                : texte
                    poids                : texte
                    ethnie                : texte
                        <div class="row">
                            <div class="form-floating mb-3 col-6">
                                <select class="form-control">
                                    <option selected></option>
                                    <option value="1">Maigre</option>
                                    <option value="2">Mince</option>
                                    <option value="3">Moyenne</option>
                                    <option value="4">Forte</option>
                                    <option value="5">Athlétique</option>
                                </select>
                                <label for="corpulence" class="col-form-label col-form-label-sm">Corpulence</label>
                            </div>
                        </div>
    
                        <fieldset>
                            <legend>Yeux</legend>
                            <div class="col-12 row">
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
                        lunettes        : 0/1
                        forme            : texte
                        lentilles        : 0/1
                        </fieldset>
    
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
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label class="col-form-label">Vêtements &amp; Objets</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    vetements            : 0/1
                    liste
                        desc/couleur/taille : triplet
                    chaussures            : 0/1
                    liste
                        desc/couleur/pointure : triplet
                    montre                : 0/1
                        marque
                        numSerie
                        forme
                        bracelet
                    bijoux                : 0/1
                        description
                    divers                : 0/1
                        description
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-center"><strong>Los Angeles Police Department</strong></h3>
                        <h5 class="col-form-label pt-0 text-center">Forensic Medicine Department</h5>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <label for="notes" class="col-form-label">Notes</label>
                        <div class="card-tools">
                              <button class="btn btn-tool" type="button" data-card-widget="collapse" style="height: 31px; margin-top: 0;"><i class="fa-solid fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" id="notes" rows="3" name="notes"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-8 offset-4">
                <div class="col-12 text-center">
                    <img src="/wp-content/plugins/hj-cops/web/rsc/img/cops-fmd-logo-fond-transparent.png" alt="Logo FMD"/>
                </div>
            </div>
        </div>
    </div>
	<div class="card-footer">
		<input type="hidden" name="writeAction"/>
		<input type="hidden" name="id" value=""/>
    	<div class="float-right">
      		<button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
    	</div>
    	<a href="/admin?onglet=autopsie" class="text-white"><button type="reset" class="btn btn-default" data-action="cancel"><i class="fa-solid fa-times"></i> Annuler</button></a>
	</div>
</form>
