<section class="content col-12 col-lg-4 offset-lg-4 pt-5 %1$s" style="position: absolute;">
  <div class="container-fluid">
      <div class="card card-outline card-warning">
        <div class="card-header text-center"><strong>Erreur</strong></div>
        <div class="card-body">%2$s</div>
      </div>
  </div>
</section>

<section class="content login-panel col-12 col-lg-4 offset-lg-4">
  <div class="container-fluid">
    <div class="login-box" style="width: inherit;">
      <div class="card card-outline card-primary">
        <div class="card-header text-center"><strong>Identification</strong></div>
        <div class="card-body">
          <form action="/admin/" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="matricule"/>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password"/>
              <div class="input-group-append">
                <div class="input-group-text" style="padding-top: 10px; padding-bottom: 10px;">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8 d-none d-xl-block"></div>
              <div class="col-xs-12 col-lg-4">
                <button type="submit" class="btn btn-primary btn-block">Valider</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
  <!--/. container-fluid -->
</section>
