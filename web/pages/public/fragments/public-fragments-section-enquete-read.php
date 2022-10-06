<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Visualiser une enquête</h3>
  </div>

  <div class="card-body p-0">
      <div class="mailbox-read-info">
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">Nom de l'enquête :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-10" value="%1$s" disabled/>
        </div>
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">Premier enquêteur :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-4" value="%2$s" disabled/>
          <span class="input-group-text col-12 col-md-2">District Attorney :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-4" value="%3$s" disabled/>
        </div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#resumeFaitsTab">Résumé des faits <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="resumeFaitsTab">%4$s</div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#descSceneDeCrimeTab">Scène de crime <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="descSceneDeCrimeTab">%5$s</div>
        <div class="card-footer">
          <div class="col-12 input-group mb-3">
              <div class="col-6">
                <label for="rapportFid" class="form-label">Rapport SID</label>
                %6$s
              </div>
              <div class="col-6">
                <label for="autopsie" class="form-label">Autopsie</label>
                %7$s
              </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#pistesDemarchesTab">Pistes / Démarches <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="pistesDemarchesTab">%8$s</div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#personnalitesTab">Enquêtes de Personnalités <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="personnalitesTab">%9$s</div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#temoignagesTab">Témoins / Suspects <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="temoignagesTab">%10$s</div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#chronologieTab">Chronologie <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="chronologieTab">%11$s</div>
      </div>

      <div class="card">
        <div class="card-header accordion-button" data-accordion="#notesDiversesTab">Notes diverses <i class="fa-solid fa-chevron-up"></i><i class="fa-solid fa-chevron-down"></i></div>
        <div class="card-body accordion-item" id="notesDiversesTab">%12$s</div>
      </div>
  </div>

</div>
