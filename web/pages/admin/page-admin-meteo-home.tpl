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
              <span>Bilan des données relatives à la Météo.</span>
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
              <li class="breadcrumb-item" style="float: left;"><a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&subOnglet=home">Accueil</a> </li>
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
            <div class="card-header">Table Météo</div>
            <!-- Card-body start -->
            <div class="card-body">
                <h6 class="m-b-30 m-t-20">Dernière mise à jour : %2$s</h6>
                <div class="btn-group">
                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%3$s"><button class="btn btn-primary">Suivante</button></a>
                    <a class="btn" href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=meteo&date=%4$s"><button class="btn btn-primary">Actuelle</button></a>
                </div>
                <p>%5$s</p>
            </div>
        </div>

        <div class="card" style="max-width: initial; padding: 0;">
            <div class="card-header">Table Soleil</div>
            <!-- Card-body start -->
            <div class="card-body">
            </div>
        </div>

        <div class="card" style="max-width: initial; padding: 0;">
            <div class="card-header">Table Lune</div>
            <!-- Card-body start -->
            <div class="card-body">
            </div>
        </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>

<div class="main-body">%4$s</div>
