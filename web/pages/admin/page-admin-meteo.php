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
      <div class="card" style="max-width: initial;">
        <!-- Card-body start -->
        <div class="card-block">
          <div class="row">
            <div class="col-xl-3 col-md-12">
              <h6 class="m-b-30 m-t-20">Dernière mise à jour</h6>
              %1$s
            </div>
            <div class="col-xl-1 col-md-12"></div>
            <div class="col-xl-3 col-md-12">
              <a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%2$s"><button class="btn btn-primary">Traiter la journée suivante</button></a>
              <br><br>
              <a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%1$s"><button class="btn btn-primary">Traiter la dernière journée</button></a>
            </div>
            <div class="col-xl-5 col-md-12">%3$s</div>
          </div>
        </div>
        <!-- Card-body end -->
      </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>

<div class="main-body">%4$s</div>
