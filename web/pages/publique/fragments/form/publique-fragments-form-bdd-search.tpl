<!-- @version v1.23.11.25 -->
<form id="writeForm" method="post">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Rechercher un individu</h3>
        </div>

        <div class="card-body p-2">%2$s</div>

        <div class="card-footer">
            <div class="float-end">
                <button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Rechercher</button>
            </div>
            <input type="hidden" id="searchAction" name="searchAction" value="1"/>
            <button type="reset" class="btn btn-default" data-action="cancel"><a href="%1$s" class="text-white"><i class="fa-solid fa-times"></i> Annuler</a></button>
        </div>
    </div>
</form>

<section class="card card-primary p-0 %3$s">
    <!-- Card-body start -->
    <div class="card-body p-0">
        <!-- Détail. Non renseigné si aucun résultat à la recherche -->
        <div class="row mx-2">%4$s</div>

        <!-- Liste des résultats, avec pagination si nécessaire -->
        <div class="row mx-2">%5$s</div>
    </div>
    <!-- Card-body end -->
</section>
