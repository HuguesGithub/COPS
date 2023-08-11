<form method="post" class=" col-4">
    <section class="card card-primary row p-0">
        <div class="card-header">Modifier le mot de passe</div>

        <!-- Card-body start -->
        <div class="card-body p-0">
            <div class="row mx-2">
                <div id="mec_meta_box_categ_form" class="mt-3 col-12">
                    <div class="input-group mb-3">
                        <span class="input-group-text col-4" for="scorePr">Actuel</span>
                        <input type="password" name="oldmdp" id="oldmdp" class="form-control col-8" aria-label="Actuel" aria-describedby="Actuel" value="">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text col-4" for="scorePr">Nouveau</span>
                        <input type="password" name="newmdp" id="newmdp" class="form-control col-8" aria-label="Nouveau" aria-describedby="Nouveau" value="">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text col-4" for="scorePr">Confirmation</span>
                        <input type="password" name="cfrmmdp" id="cfrmmdp" class="form-control col-8" aria-label="Confirmation" aria-describedby="Confirmation" value="">
                    </div>
                </div>
            </div>

            <div class="card-footer col-12">
                <input type="hidden" name="writeAction" value="changeMdp">
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
        <!-- Card-body end -->

    </form>
</section>