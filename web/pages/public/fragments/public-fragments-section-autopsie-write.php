<form method="post" action="#">
    <div class="col-12 row">
        <div class="col-6">
        <!-- Card Dossier -->
            <div class="col-12">
%3$s
            </div>
        <!-- / Fin Card Dossier -->
        <!-- Card Médico-légal -->
            <div class="col-12">
%4$s
            </div>
        <!-- / Fin Card Médico-légal -->
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
%5$s
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
                        <textarea class="form-control" id="constatations" rows="3" name="constatations">%7$s</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="col-12">
%8$s
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
                            <input type="text" class="form-control" value="%2$s" readonly/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
%9$s
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
        <input type="hidden" name="id" value="%1$s"/>
        <div class="float-right">
              <button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
        </div>
        <a href="/admin?onglet=autopsie" class="text-white"><button type="reset" class="btn btn-default" data-action="cancel"><i class="fa-solid fa-times"></i> Annuler</button></a>
    </div>
</form>
