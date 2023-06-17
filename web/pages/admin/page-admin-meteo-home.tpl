<!-- @version v1.23.04.30 -->
        <div class="row">
            <div class="col-3">

                <div class="card" style="max-width: initial; padding: 0;">
                    <div class="card-header">Récupération données</div>
                    <!-- Card-body start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12"><h6 class="m-b-30 m-t-20">Dernière mise à jour</h6>%2$s</div>
                            <div class="col-12">
                                <div class="btn-group">
                                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%3$s"><button class="btn btn-primary">Suivante</button></a>
                                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%6$s"><button class="btn btn-primary">Actuelle</button></a>
                                </div>
                            </div>
                            <div class="col-12">%4$s</div>
                        </div>
                    </div>
                    <!-- Card-body end -->
                </div>

                <div class="card" style="max-width: initial; padding: 0;">
                    <div class="card-header">Soleil</div>
                    <!-- Card-body start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">Lever : %9$s</div>
                            <div class="col-12">Coucher : %10$s</div>
                            <div class="col-12">Durée : %11$s</div>
                        </div>
                    </div>
                    <!-- Card-body end -->
                </div>

            </div>

            <div class="col-9">
                <div class="card" style="max-width: initial; padding: 0;">
                    <div class="card-header">Données de la journée</div>
                    <!-- Card-body start -->
                    <div class="card-body">
                        <table class="table table-striped table-sm">
                            <thead>%7$s</thead>
                            <tbody>%8$s</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
