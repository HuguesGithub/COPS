<link rel="stylesheet" href="http://cops.jhugues.fr/wp-content/plugins/hj-cops/web/rsc/feather.css" type="text/css" media="all">
<style>
  fieldset.collapsible > form {
    display: none;
  }
  fieldset.collapsible.collapsed > form {
    display: block;
  }
</style>

<div class="main-body">
  <div class="page-wrapper">
    <!-- Page-header start -->
    <div class="page-header">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <div class="d-inline">
              <h4>Index</h4>
              <span>Gestion de la partie administrative de l'index</span>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="page-header-breadcrumb">
            <ul class="breadcrumb-title">
              <li class="breadcrumb-item" style="float: left;">
                <a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php"> <i class="feather icon-home"></i> </a>
              </li>
              <li class="breadcrumb-item" style="float: left;"><a href="/wp-admin/admin.php?page=hj-cops/admin_manage.php&onglet=index">Index</a> </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Page-header end -->

    <!-- Page-body start -->
    <div class="page-body mb-3">
      <div class="card" style="max-width: initial;">
        <fieldset id="creation" class="collapsible">
          <legend><i class="feather icon-chevron-right"></i> Création</legend>
          <!-- Card-body fieldset start -->
          <form method="post">
          <div class="card-block">
            <div class="row">
              <div class="col-12">
                <button class="btn btn-primary" style="position: absolute; right: 0; top: -40px;">Valider</button>
              </div>
              <div class="col-xl-6 col-md-12">
                <div class="mb-3 row">
                  <label for="nomIdx" class="form-label col-md-3">Nom</label>
                  <div class="input-group mb-3 col-md-9">
                    <input type="text" class="form-control" id="nomIdx" name="nomIdx" placeholder="Nom">
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="natureId" class="form-label col-md-3">Nature</label>
                  <div class="input-group mb-3 col-md-9">
                    %1$s
                    <input type="text" class="form-control" id="text-nature" name="text-nature" placeholder="Nouvelle nature">
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="reference" class="form-label col-md-3">Référence</label>
                  <div class="input-group mb-3 col-md-9">
                    <input type="text" class="form-control" id="reference" name="reference" placeholder="Ref1, Ref2...">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="visually-hidden">Références</span>
                    </button>
                    <ul class="dropdown-menu" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 40px);">
                      %2$s
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-md-12">
                <div class="mb-3 row">
                  <label for="descriptionMJ" class="form-label col-md-3">Description MJ</label>
                  <div class="input-group mb-3 col-md-9">
                    <textarea class="form-control" id="descriptionMJ" name="descriptionMJ" rows="3"></textarea>
                  </div>
                </div>
                <div class="mb-3 row">
                  <label for="descriptionPJ" class="form-label col-md-3">Description PJ</label>
                  <div class="input-group mb-3 col-md-9">
                    <textarea class="form-control" id="descriptionPJ" name="descriptionPJ" rows="3"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </form>
          <!-- Card-body fieldset end -->
        </fieldset>
        <fieldset id="filters" class="collapsible">
          <legend><i class="feather icon-chevron-right"></i> Filtres</legend>
          <!-- Card-body fieldset start -->
          <form method="post">
          <div class="card-block">
            <div class="row">
              <div class="col-2">Nom</div>
              <div class="col-2">Nature</div>
              <div class="col-2">Référence</div>
              <div class="col-6">Description</div>
            </div>
            <div class="input-group mb-3 row">
                <input type="text" class="form-control col-2" id="filter-nomIdx" name="filter-nomIdx">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split col-2" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="visually-hidden">Nature</span>
                </button>
                <ul class="multiselect-container dropdown-menu" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 40px);">
                  %4$s
                </ul>
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split col-2" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="visually-hidden">Référence</span>
                </button>
                <ul class="multiselect-container dropdown-menu" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 40px);">
                  %5$s
                </ul>
                <input type="text" class="form-control col-6" id="filter-description" name="filter-description">
            </div>
          </div>
          </form>
          <!-- Card-body fieldset end -->
        </fieldset>
      </div>
    </div>
    <!-- Page-body end -->
  </div>
</div>

<div class="main-body">
  <table class="table table-striped table-sm">
    <thead class="text-center bg-dark text-white">
      <tr>
        <td class="col-2">Nom</td>
        <td class="col-2">Nature</td>
        <td class="col-2">Référence</td>
        <td class="col-6">Description</td>
      </tr>
    </thead>
    <tbody>
    %3$s
    </tbody>
  </table>
</div>
