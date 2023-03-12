<section class="content caracteristiques-panel">
  <div class="container-fluid">
    <form action="/admin?onglet=profile&subOnglet=abilities" method="post">
      <div class="row">
        <div id="card-caracs" class="card card-outline card-info col">
          <div class="card-body row">
            <div class="col-12 col-md-4">
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Carrure</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_carrure" id="field_carac_carrure" value="%2$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%9$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Charme</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_charme" id="field_carac_charme" value="%3$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%10$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Coordination</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_coordination" id="field_carac_coordination" value="%4$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%11$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Education</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_education" id="field_carac_education" value="%5$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%12$s" readonly/>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Perception</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_perception" id="field_carac_perception" value="%6$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%13$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Réflexes</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_reflexes" id="field_carac_reflexes" value="%7$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%14$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Init min.</span>
                <input type="number" class="form-control text-center col-3" value="%16$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Sang-froid</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData" name="field_carac_sangfroid" id="field_carac_sangfroid" value="%8$s" size="2"/>
                <input type="number" class="form-control text-center col-3" value="%15$s" readonly/>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Points de vie</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="field_pv_cur" id="field_pv_cur" value="%17$s"/>
                <input type="number" class="form-control text-center col-3" name="field_pv_max" id="field_pv_max" value="%18$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Adrénaline</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="field_pad_cur" id="field_pad_cur" value="%19$s"/>
                <input type="number" class="form-control text-center col-3" name="field_pad_max" id="field_pad_max" value="%20$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-6">Ancienneté</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="field_pan_cur" id="field_pan_cur" value="%21$s"/>
                <input type="number" class="form-control text-center col-3" name="field_pan_max" id="field_pan_max" value="%22$s" readonly/>
              </div>
              <div class="col-12 input-group mb-3">
                <span class="input-group-text col-9">Expérience</span>
                <input type="number" class="form-control text-center col-3 ajaxAction" data-objid="%1$s" data-trigger="change" data-ajax="saveData"  name="field_px_cur" id="field_px_cur" value="%23$s"/>
              </div>
        </div>
          </div>
        </div>
      </div>
    </form>

  </div>
  <!--/. container-fluid -->
</section>
