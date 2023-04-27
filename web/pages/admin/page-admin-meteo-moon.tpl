<!-- @version v1.23.04.30 -->
<link rel="stylesheet" href="https://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/feather.css" type="text/css" media="all">

<div class="main-body">
  <div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header mb-3">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <div class="d-inline">
              <h4>Météo</h4>
              <span>Données relatives à la table Lune de la lunaison du jour ingame ou passé en paramètre.</span>
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
              <li class="breadcrumb-item" style="float: left;"><a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&subOnglet=moon">Lune</a> </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Page-header end -->

    <!-- Page-body start -->
    <div class="page-body">
        %1$s

        <div class="card" style="max-width: initial; padding: 0;">
            <div class="card-header">Données de la semaine</div>
            <!-- Card-body start -->
            <div class="card-body">
                <table class="table table-striped table-sm">
                    <thead>%2$s</thead>
                    <tbody>%3$s</tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>
