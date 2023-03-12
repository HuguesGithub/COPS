<section class="content caracteristiques-panel">
  <div class="container-fluid">
    <form action="/admin?onglet=profile&subOnglet=identity" method="post">
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
      <!--
      <div class="row">
          <div class="card card-outline card-info">
            <div class="card-body row">
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Nom</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Prénom</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Surnom</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="row">
          <div id="card-caracs" class="card card-outline card-info">
            <div class="card-header text-center">
              <h2 class="title">Caractéristiques</h2>
            </div>
            <div class="card-body row">
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Carrure</span>
                  <input type="number" class="form-control text-center col-3" name="carac-carrure" id="carac-carrure" value="%1$s" size="2" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%2$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Charme</span>
                  <input type="number" class="form-control text-center col-3" name="carac-charme" id="carac-charme" value="%3$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%4$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Coordination</span>
                  <input type="number" class="form-control text-center col-3" name="carac-coordination" id="carac-coordination" value="%5$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%6$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Education</span>
                  <input type="number" class="form-control text-center col-3" name="carac-education" id="carac-education" value="%7$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%8$s" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Perception</span>
                  <input type="number" class="form-control text-center col-3" name="carac-perception" id="carac-perception" value="%9$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%10$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Réflexes</span>
                  <input type="number" class="form-control text-center col-3" name="carac-reflexes" id="carac-reflexes" value="%11$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%12$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Init min.</span>
                  <input type="number" class="form-control text-center col-3" value="%13$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Sang-froid</span>
                  <input type="number" class="form-control text-center col-3" name="carac-sangfroid" id="carac-sangfroid" value="%14$s" readonly/>
                  <input type="number" class="form-control text-center col-3" value="%15$s" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Points de vie</span>
                  <input type="number" class="form-control text-center col-3" name="carac-perception" id="carac-perception" value="%16$s" readonly/>
                  <input type="number" class="form-control text-center col-3" name="carac-perception" id="carac-perception" value="%17$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Adrénaline</span>
                  <input type="number" class="form-control text-center col-3" name="carac-reflexes" id="carac-reflexes" value="%18$s" readonly/>
                  <input type="number" class="form-control text-center col-3" name="carac-reflexes" id="carac-reflexes" value="%19$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-6">Ancienneté</span>
                  <input type="number" class="form-control text-center col-3" name="carac-reflexes" id="carac-reflexes" value="%20$s" readonly/>
                  <input type="number" class="form-control text-center col-3" name="carac-reflexes" id="carac-reflexes" value="%21$s" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-9">Expérience</span>
                  <input type="number" class="form-control text-center col-3" name="carac-sangfroid" id="carac-sangfroid" value="%22$s" readonly/>
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="row">
          <div class="card card-outline card-info">
            <div class="card-body row">
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Grade</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Section</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Rang</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Lieutenant</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
              </div>
              <div class="col-4">
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Echelon</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
                </div>
                <div class="col-12 input-group mb-3">
                  <span class="input-group-text col-3">Affectation</span>
                  <input type="number" class="form-control col-8" readonly/>
                  <input type="number" class="form-control col-1" readonly/>
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
