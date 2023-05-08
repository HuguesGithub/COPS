<!-- @version v1.23.05.07 -->
<link rel="stylesheet" href="https://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/css/all.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="https://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/feather.css" type="text/css" media="all">

<div class="main-body">
  <div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <div class="d-inline">
              <h4>Météo</h4>
              <span>Gestion de la partie administrative de la météo</span>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="page-header-breadcrumb">
            <ul class="breadcrumb-title">
              <li class="breadcrumb-item" style="float: left;">
                <a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php"> <i class="feather icon-home"></i> </a>
              </li>
              <li class="breadcrumb-item" style="float: left;"><a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo">Météo</a> </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Page-header end -->

    <!-- Page-body start -->
    <div class="page-body">
        <div class="row">
            <div class="col-3">

                <div class="card" style="max-width: initial; padding: 0;">
                    <div class="card-header">Récupération données</div>
                    <!-- Card-body start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12"><h6 class="m-b-30 m-t-20">Dernière mise à jour</h6>%1$s</div>
                            <div class="col-12">
                                <div class="btn-group">
                                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%2$s"><button class="btn btn-primary">Suivante</button></a>
                                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%5$s"><button class="btn btn-primary">Actuelle</button></a>
                                </div>
                            </div>
                            <div class="col-12">%3$s</div>
                        </div>
                    </div>
                    <!-- Card-body end -->
                </div>

                <div class="card" style="max-width: initial; padding: 0;">
                    <div class="card-header">Soleil</div>
                    <!-- Card-body start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">Lever : %8$s</div>
                            <div class="col-12">Coucher : %9$s</div>
                            <div class="col-12">Durée : %10$s</div>
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
                            <thead>%6$s</thead>
                            <tbody>%7$s</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>

<div class="main-body">%4$s</div>
