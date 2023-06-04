<!-- @version v1.23.05.21 -->
<form method="post" class="row p-3 col-12">
    <div class="card col-12 mx-1 p-0">
        <div class="card-header">Éditer un événement</div>

        <!-- Card-body start -->
        <div class="card-body pb-0 px-0">
            <div class="row mx-2">
                %1$s

                %2$s
                        
                <div id="mec_meta_box_cb_recurrent_form" class="mt-3 col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-text pr-4 col-2">
                            <input class="form-check-input mt-0" type="checkbox" value="1" id="repeatStatus" name="repeatStatus" aria-label="Événement récurrent"%3$s>
                        </div>
                        <input type="text" for="repeatStatus" class="form-control col-10" aria-label="Événement récurrent" value="Événement récurrent" readonly>
                    </div>            
                </div>

                <div class="col-12"></div>

                <div id="mec_meta_box_period_form" class="mt-3 col-2"%4$s>
                    %5$s
                </div>

                <div id="mec_meta_box_recursive_form" class="row mt-3 col-6"%4$s>
                    %6$s
                </div>

                <div id="mec_meta_box_custom_form" class="row mt-3 col-4"%4$s>
                    %7$s
                </div>

            <div class="card-footer col-12">
                <input type="hidden" name="writeAction" value="write">
                <div class="btn-group">
                    <button class="btn btn-sm btn-danger p-0"><a href="%9$s" style="margin: .25rem .5rem;text-decoration: none; color: white;">Supprimer</a></button>
                    <button class="btn btn-sm btn-outline p-0"><a href="%8$s" style="margin: .25rem .5rem;text-decoration: none; color: black;">Annuler</a></button>
                    <button class="btn btn-sm btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
        <!-- Card-body end -->

    </div>
</form>
