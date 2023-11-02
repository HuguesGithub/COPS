<!-- @version v1.23.07.22 -->
<form method="post" class="row p-3 col-12">
    <div class="card col-12 mx-1 p-0">
        <div class="card-header">Éditer une personne</div>

        <!-- Card-body start -->
        <div class="card-body pb-0 px-0">
            <div class="row mx-2">
                <div id="mec_meta_box_categ_form" class="mt-3 col-12">
                    <div class="input-group mb-3">
                        <span class="input-group-text col-1" for="id">Id</span>
                        <input type="text" name="id" id="id" class="form-control col-2" aria-label="Id" aria-describedby="Identifiant" value="%1$s" readonly="">
                        <div class="col-9">&nbsp;</div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-1" for="title">Titre</span>
                        %2$s
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-1" for="firstName">Prénom</span>
                        <input type="text" name="firstName" id="firstName" class="form-control col-2" aria-label="Prénom" aria-describedby="Prénom" value="%3$s" required="">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-1" for="lastName">Nom</span>
                        <input type="text" name="lastName" id="lastName" class="form-control col-2" aria-label="Nom" aria-describedby="Nom" value="%4$s" required="">
                        <div class="col-1">&nbsp;</div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-1" for="gender">Genre</span>
                        %5$s
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-1" for="nameSet">Ethnie</span>
                        %6$s
                        <div class="col-1">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-2">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-1" for="emailAdress">Email</span>
                        <input type="text" name="emailAdress" id="emailAdress" class="form-control col-2" aria-label="Email" aria-describedby="Email" value="%7$s">
                        <div class="col-1">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-2">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-2">&nbsp;</div>
                        <div class="col-1">&nbsp;</div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text col-1" for="telephoneNumber">Téléphone</span>
                        <input type="text" name="telephoneNumber" id="telephoneNumber" class="form-control col-2 ajaxAction" data-trigger="keyup" data-ajax="findAddress" aria-label="Téléphone" aria-describedby="Téléphone" value="%8$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-1" for="zipCode">Code Postal</span>
                        <input type="text" name="zipCode" id="zipCode" class="form-control col-2 ajaxAction" data-trigger="keyup" data-ajax="findAddress" aria-label="Code Postal" aria-describedby="Code Postal" value="%9$s">
                        <div class="col-1">&nbsp;</div>
                        <span class="input-group-text col-1" for="city">Ville</span>
                        <input type="text" name="city" id="city" class="form-control col-2 ajaxAction" data-trigger="keyup" data-ajax="findAddress" aria-label="Ville" aria-describedby="Ville" value="%10$s">
                        <div class="col-1">&nbsp;</div>
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
        