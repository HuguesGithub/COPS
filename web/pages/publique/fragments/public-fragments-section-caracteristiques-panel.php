<section class="content caracteristiques-panel pt-4">
  <div class="container-fluid">
    <form action="/admin/" method="post">
      <div class="row">
        <div class="col-4">
          <div id="card-caracs" class="card card-outline card-info">
            <div class="card-header text-center">
              <h2 class="title">Caractéristiques</h2>
            </div>
            <div class="card-body row">
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Carrure</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-carrure" id="carac-carrure" value="%1$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Charme</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-charme" id="carac-charme" value="%2$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Coordination</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-coordination" id="carac-coordination" value="%3$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Education</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-education" id="carac-education" value="%4$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Perception</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-perception" id="carac-perception" value="%5$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Réflexes</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-reflexes" id="carac-reflexes" value="%6$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Sang-froid</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-sangfroid" id="carac-sangfroid" value="%7$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
            </div>
          </div>
        </div>
        <div class="col-8 row">
          <div class="col-6">
          <div class="card card-outline card-info">
            <div class="card-header text-center">
              <h2 class="title">Secondaires</h2>
            </div>
            <div class="card-body row">
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Points de vie</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-health-points-cur" id="carac-health-points-cur" value="%8$s" readonly/>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-health-points" id="carac-health-points" value="%15$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Adrénaline</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-adrenaline-points-cur" id="carac-adrenaline-points-cur" value="%9$s" readonly/>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-adrenaline-points" id="carac-adrenaline-points" value="%16$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Ancienneté</span>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-anciennete-points-cur" id="carac-anciennete-points-cur" value="%10$s" readonly/>
                <input type="number" class="form-control ajaxAction col-3" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-anciennete-points" id="carac-anciennete-points" value="%17$s" readonly/>
<!--                <span class="input-group-text">.00</span>-->
              </div>
            </div>
          </div>
          </div>
          <div class="col-6">
            <div id="card-langues" class="card card-outline card-info">
              <div class="card-header text-center">
                <h2 class="title">Langues</h2>
              </div>
              <div class="card-body row">
                <div class="col-12 mb-3">
                  <input type="text" class="form-control" name="carac-langue-01" id="carac-langue-01" value="Anglais" readonly/>
                </div>
                <div class="col-12 mb-3">
                  <input type="text" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkLangue" name="carac-langue-02" id="carac-langue-02" value="%11$s"/>
                </div>
                <div class="col-12 mb-3">
                  <input type="text" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkLangue" name="carac-langue-03" id="carac-langue-03" value="%12$s" style="display: none;"/>
                </div>
                <div class="col-12 mb-3">
                  <input type="text" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkLangue" name="carac-langue-04" id="carac-langue-04" value="%13$s" style="display: none;"/>
                </div>
                <div class="col-12 mb-3">
                  <input type="text" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkLangue" name="carac-langue-05" id="carac-langue-05" value="%14$s" style="display: none;"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
  <!--/. container-fluid -->
</section>
