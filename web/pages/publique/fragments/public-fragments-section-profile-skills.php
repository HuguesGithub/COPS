<section class="content caracteristiques-panel">
  <div class="container-fluid">
    <form action="/admin?onglet=profile&subOnglet=skills" method="post">
      <div class="row col">
          <div id="card-caracs" class="card card-outline card-warning col">
            <div class="card-header text-center">
              <h2 class="title">En travaux</h2>
            </div>
            <div class="card-body row">
              <div class="col-4 text-center">
                <i class="fa-solid fa-person-digging text-warning fa-3x text-center"></i>
              </div>
              <div class="col-8">
                Ecran en cours de développement
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row col">
        <div class="col-12 col-md-4 input-group">
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-9">Arme de poing 1</span>
            <input type="number" class="form-control col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="skill_%2$s" id="skill_%2$s" value="%3$s"/>
          </div>
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-9">Arme de poing 2</span>
            <input type="number" class="form-control col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="skill_%2$s" id="skill_%2$s" value="%3$s"/>
          </div>
        </div>
        <div class="col-12 col-md-4 input-group">
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-9">Arme de poing 3</span>
            <input type="number" class="form-control col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="skill_%2$s" id="skill_%2$s" value="%3$s"/>
          </div>
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-9">Arme de poing 4</span>
            <input type="number" class="form-control col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="skill_%2$s" id="skill_%2$s" value="%3$s"/>
          </div>
        </div>
        <div class="col-12 col-md-4 input-group">
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-9">Arme de poing 5</span>
            <input type="number" class="form-control col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="skill_%2$s" id="skill_%2$s" value="%3$s"/>
          </div>
          <div class="col-12 input-group mb-3">
            <span class="input-group-text col-12">&nbsp;</span>
          </div>
        </div>
      </div>
      <!--
          <div class="card card-outline card-info">
            <div class="card-header">
              <div class="card-title">Compétences</div>
            </div>
            <div class="card-body row">
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Corps à corps</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">- Immobilisation</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Bureaucratie</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Conduite</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">- Voiture</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Instinct de flic</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Intimidation</span>
                  <input type="number" class="form-control col-3" readonly/>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div id="card-caracs" class="card card-outline card-info">
            <div class="card-header text-center">
              <h2 class="title">Stages</h2>
            </div>
            <div class="card-body row">
              <div class="col-4">
                <div id="card-caracs" class="card">
                  <div class="card-header">
                    <h2 class="title">Stage 1 <span class="float-end">[Niveau]</span></h2>
                  </div>
                  <div class="card-body">
                    <p><strong>Bonus</strong> : </p>
                    <p><strong>Capacité</strong> : Description sur quelques lignes.</p>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div id="card-caracs" class="card">
                  <div class="card-header">
                    <h2 class="title">Stage 2 <span class="float-end">[Niveau]</span></h2>
                  </div>
                  <div class="card-body">
                    <p><strong>Bonus</strong> : </p>
                    <p><strong>Capacité</strong> : Description sur quelques lignes.</p>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div id="card-caracs" class="card">
                  <div class="card-header">
                    <h2 class="title">Stage 3 <span class="float-end">[Niveau]</span></h2>
                  </div>
                  <div class="card-body">
                    <p><strong>Bonus</strong> : </p>
                    <p><strong>Capacité</strong> : Description sur quelques lignes.</p>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div id="card-caracs" class="card">
                  <div class="card-header">
                    <h2 class="title">Stage 4 <span class="float-end">[Niveau]</span></h2>
                  </div>
                  <div class="card-body">
                    <p><strong>Bonus</strong> : </p>
                    <p><strong>Capacité</strong> : Description sur quelques lignes.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      -->
    </form>
  </div>
  <!--/. container-fluid -->
</section>
