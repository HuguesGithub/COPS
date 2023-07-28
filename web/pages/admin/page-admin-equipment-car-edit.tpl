<!-- @version v1.23.07.22 -->
<form method="post" class="row p-3 col-12">
    <div class="card col-12 mx-1 p-0">
        <div class="card-header">Éditer un véhicule</div>

        <!-- Card-body start -->
        <div class="card-body pb-0 px-0">
            <div class="row mx-2">
                <div id="mec_meta_box_categ_form" class="mt-3 col-12">
                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="id">Id</span>
                        <input type="text" name="id" id="id" class="form-control col-10" aria-label="Id" aria-describedby="Identifiant" value="%1$s" readonly="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="vehLabel">Libellé</span>
                        <input type="text" name="vehLabel" id="vehLabel" class="form-control col-4" aria-label="Libellé" aria-describedby="Libellé" value="%2$s" required="">
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehReference">Référence</span>
                        <input type="text" name="vehReference" id="vehReference" class="form-control col-3" aria-label="Référence" aria-describedby="Référence" value="%3$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="vehCategorie">Type de véhicule</span>
                        %4$s
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehSousCategorie">Sous Catégorie</span>
                        %5$s
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="vehPlaces">Nb d'occupants</span>
                        <input type="text" name="vehPlaces" id="vehPlaces" class="form-control col-1" aria-label="Nb d'occupants" aria-describedby="Nb d'occupants" value="%6$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehVitesse">Vitesse</span>
                        <input type="text" name="vehVitesse" id="vehVitesse" class="form-control col-1" aria-label="Vitesse" aria-describedby="Vitesse" value="%7$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehAcceleration">Accélération</span>
                        <input type="text" name="vehAcceleration" id="vehAcceleration" class="form-control col-1" aria-label="Accélération" aria-describedby="Accélération" value="%8$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="vehAutonomie">Autonomie</span>
                        <input type="text" name="vehAutonomie" id="vehAutonomie" class="form-control col-1" aria-label="Autonomie" aria-describedby="Autonomie" value="%9$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehCarburant">Carburant</span>
                        <input type="text" name="vehCarburant" id="vehCarburant" class="form-control col-1" aria-label="Carburant" aria-describedby="Carburant" value="%10$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehPrix">Prix</span>
                        <input type="text" name="vehPrix" id="vehPrix" class="form-control col-1" aria-label="Prix" aria-describedby="Prix" value="%11$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="vehPointStructure">Points de Structure</span>
                        <input type="text" name="vehPointStructure" id="vehPointStructure" class="form-control col-1" aria-label="Points de Structure" aria-describedby="Points de Structure" value="%12$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehLigneRouge">Ligne Rouge</span>
                        <input type="text" name="vehLigneRouge" id="vehLigneRouge" class="form-control col-1" aria-label="Ligne Rouge" aria-describedby="Ligne Rouge" value="%15$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="vehAnnee">Année</span>
                        <input type="text" name="vehAnnee" id="vehAnnee" class="form-control col-1" aria-label="Année" aria-describedby="Année" value="%13$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-3" for="vehOptions">Options</span>
                        <input type="text" name="vehOptions" id="vehOptions" class="form-control col-1" aria-label="Options" aria-describedby="Options" value="%14$s">
                    </div>
                </div>
            </div>

            <div class="card-footer col-12">
                <input type="hidden" name="writeAction" value="write">
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline p-0"><a href="%16$s" style="margin: .25rem .5rem;text-decoration: none; color: black;">Annuler</a></button>
                    <button class="btn btn-sm btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
        <!-- Card-body end -->

    </div>

</div></form>
        