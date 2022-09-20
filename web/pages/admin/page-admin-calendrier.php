<link rel="stylesheet" href="https://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/feather.css" type="text/css" media="all">

<div class="main-body">
  <div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <div class="d-inline">
              <h4>Calendrier</h4>
              <span>Gestion de la partie administrative du calendrier</span>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="page-header-breadcrumb">
            <ul class="breadcrumb-title">
              <li class="breadcrumb-item" style="float: left;">
                <a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php"> <i class="feather icon-home"></i> </a>
              </li>
              <li class="breadcrumb-item" style="float: left;"><a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=calendrier">Calendrier</a> </li>
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
			<div class="col-md-6">
			<form method="post">
				<div class="col-md-12 mb-3">
					<div class="input-group row mb-3">
					  <select class="form-control col-4" name="sel-d">%1$s</select>
					  <select class="form-control col-4" name="sel-m">%2$s</select>
					  <select class="form-control col-4" name="sel-y">%3$s</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="input-group row mb-3">
					  <select class="form-control col-4" name="sel-h">%4$s</select>
					  <select class="form-control col-4" name="sel-i">%5$s</select>
					  <select class="form-control col-4" name="sel-s">%6$s</select>
					</div>
				</div>
				<div class="col-md-12">
					<input class="btn btn-primary col-4 offset-4" type="submit" id="changeDate" name="changeDate" value="Valider">
				</div>
			</form>
            </div>
			<div class="col-md-6">
				<div class="col-md-11">
<div class="input-group mb-3">
  <span class="input-group-text col-4">Secondes</span>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=s&quantite=3">+3</a></button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
</div>				
<div class="input-group mb-3">
  <span class="input-group-text col-4">Minutes</span>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=1">+1</a></button>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=5">+5</a></button>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=15">+15</a></button>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=30">+30</a></button>
</div>				
<div class="input-group mb-3">
  <span class="input-group-text col-4">Heures</span>
  <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=h&quantite=1">+1</a></button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
  <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
</div>				
				</div>
            </div>
          </div>
        </div>
        <!-- Card-body end -->
      </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>

<div class="main-body">
</div>