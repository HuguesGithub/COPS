<!-- @version v1.23.07.15 -->
<form method="post" class="row p-3 col-12">
    <div class="card col-12 mx-1 p-0">
        <div class="card-header">Éditer une arme</div>

        <!-- Card-body start -->
        <div class="card-body pb-0 px-0">
            <div class="row mx-2">
                <div id="mec_meta_box_categ_form" class="mt-3 col-12">
                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="id">Id</span>
                        <input type="text" name="id" id="id" class="form-control col-10" aria-label="Id" aria-describedby="Identifiant" value="%1$s" readonly="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="nomArme">Libellé</span>
                        <input type="text" name="nomArme" id="nomArme" class="form-control col-4" aria-label="Libellé" aria-describedby="Libellé" value="%2$s" required="">
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-2" for="tomeIdxId">Référence</span>
                        <input type="text" name="tomeIdxId" id="tomeIdxId" class="form-control col-3" aria-label="Référence" aria-describedby="Référence" value="%3$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="typeArme">Type d'arme</span>
                        %4$s
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-2" for="compUtilisee">Compétence</span>
                        %5$s
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="scorePr">Précision</span>
                        <input type="text" name="scorePr" id="scorePr" class="form-control col-1" aria-label="Précision" aria-describedby="Précision" value="%6$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="scorePu">Puissance</span>
                        <input type="text" name="scorePu" id="scorePu" class="form-control col-1" aria-label="Puissance" aria-describedby="Puissance" value="%7$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="scoreFa">Force d'arrêt</span>
                        <input type="text" name="scoreFa" id="scoreFa" class="form-control col-1" aria-label="Force d'arrêt" aria-describedby="Force d'arrêt" value="%8$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-2" for="scoreDis">Dissimulation</span>
                        <input type="text" name="scoreDis" id="scoreDis" class="form-control col-1" aria-label="Dissimulation" aria-describedby="Dissimulation" value="%9$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="portee">Portée</span>
                        <input type="text" name="portee" id="portee" class="form-control col-1" aria-label="Portée" aria-describedby="Portée" value="%10$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-2" for="prix">Prix</span>
                        <input type="text" name="prix" id="prix" class="form-control col-1" aria-label="Prix" aria-describedby="Prix" value="%11$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-3" for="scoreVrc">Valeur de Rafale Courte</span>
                        <input type="text" name="scoreVrc" id="scoreVrc" class="form-control col-1" aria-label="Valeur de Rafale Courte" aria-describedby="Valeur de Rafale Courte" value="%12$s">
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-3" for="scoreCt">Cadence de Tir</span>
                        <input type="text" name="scoreCt" id="scoreCt" class="form-control col-1" aria-label="Cadence de Tir" aria-describedby="Cadence de Tir" value="%13$s">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-3" for="scoreVc">Valeur de Couverture</span>
                        <input type="text" name="scoreVc" id="scoreVc" class="form-control col-1" aria-label="Valeur de Couverture" aria-describedby="Valeur de Couverture" value="%14$s">
                        <div class="col-2">&nbsp;</div>
                        <span class="input-group-text col-3" for="munitions">Munitions</span>
                        <input type="text" name="munitions" id="munitions" class="form-control col-1" aria-label="Munitions" aria-describedby="Munitions" value="%15$s">
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
        