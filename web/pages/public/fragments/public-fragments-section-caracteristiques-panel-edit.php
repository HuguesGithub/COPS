<section class="content caracteristiques-panel pt-4">
  <div class="container-fluid">
    <form action="/admin/" method="post">
      <div class="row">
        <div class="col-4">
          <div id="card-caracs" class="card card-outline card-warning">
            <div class="card-header text-center">
              <h2 class="title">Caractéristiques</h2>
            </div>
            <div class="card-body row">
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-carrure" id="carac-carrure" value="%1$s"/>
                <label for="carac-carrure">Carrure</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-charme" id="carac-charme" value="%2$s"/>
                <label for="carac-charme">Charme</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-coordination" id="carac-coordination" value="%3$s"/>
                <label for="carac-coordination">Coordination</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-education" id="carac-education" value="%4$s"/>
                <label for="carac-education">Education</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-perception" id="carac-perception" value="%5$s"/>
                <label for="carac-perception">Perception</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-reflexes" id="carac-reflexes" value="%6$s"/>
                <label for="carac-reflexes">Réflexes</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control ajaxAction" data-trigger="change" data-ajax="saveData,checkCarac" name="carac-sangfroid" id="carac-sangfroid" value="%7$s"/>
                <label for="carac-sangfroid">Sang-froid</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-8 row">
          <div class="col-6">
          <div class="card card-outline card-success">
            <div class="card-header text-center">
              <h2 class="title">Secondaires</h2>
            </div>
            <div class="card-body row">
              <div class="col-12 mb-3">
                <input type="number" class="form-control" name="carac-health-points" id="carac-health-points" value="%8$s" readonly/>
                <label for="carac-health-points">Points de vie</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control" name="carac-adrenaline-points" id="carac-adrenaline-points" value="%9$s" readonly/>
                <label for="carac-adrenaline-points">Points d'adrénaline</label>
              </div>
              <div class="col-12 mb-3">
                <input type="number" class="form-control" name="carac-anciennete-points" id="carac-anciennete-points" value="%10$s" readonly/>
                <label for="carac-anciennete-points">Points d'ancienneté</label>
              </div>
            </div>
          </div>
          </div>
          <div class="col-6">
            <div id="card-langues" class="card card-outline card-warning">
              <div class="card-header text-center">
                <h2 class="title">Langues</h2>
              </div>
              <div class="card-body row">
                <div class="col-12 mb-3">
                  <input type="text" class="form-control" name="carac-langue-01" id="carac-langue-01" value="Anglais" readonly/>
                </div>
                <div class="col-12 mb-3">%11$s</div>
                <div class="col-12 mb-3">%12$s</div>
                <div class="col-12 mb-3">%13$s</div>
                <div class="col-12 mb-3">%14$s</div>
              </div>
            </div>
          </div>
          <div class="col-12 row">
            <div class="col-8">
              <div class="card card-outline card-info">
                <div class="card-body row">
                  <div class="col-12 mb-3">
                    <p>Vous avez 21 points à vous répartir sur les 7 caractéristiques. Au moins 2 points doivent être assignés à une caractéristique. Vous ne pouvez avoir qu'une seule caractéristique à 5.</p>
                  </div>
                  <div class="col-12 mb-3">
                    <p>Vous disposez de 20 + 3*Carrure points de vie. Vous débutez avec 0 points d'adrénaline et d'ancienneté.</p>
                  </div>
                  <div class="col-12 mb-3">
                    <p>Vous parlez, lisez et écrivez autant de langues que votre score d'Education ou de Charme (le plus élevé), dont obligatoirement l'anglais.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div id="card-submit" class="card card-outline card-danger">
                <div class="card-body row">
                  <div class="col-12 mb-3">
                    <p>Saisie incomplète ou erronnée.</p>
                  </div>
                  <div class="col-12 mb-3">
                    <button type="submit" class="btn btn-primary btn-block disabled">Valider</button>
                  </div>
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
