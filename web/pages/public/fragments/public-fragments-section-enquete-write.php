<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Documenter une enquête</h3>
  </div>

  <div class="card-body p-0">
    <form id="writeForm" method="post">
      <div class="mailbox-read-info">
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">Nom de l'enquête :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-10" name="nomEnquete" value="%3$s" required/>
        </div>
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">Premier enquêteur :</span>
          <select class="input-group-text form-select text-left col-12 col-md-4" id="idxEnqueteur" name="idxEnqueteur">
            <option value=""></option>
            %4$s
          </select>
          <span class="input-group-text col-12 col-md-2">District Attorney :</span>
          <select class="input-group-text form-select text-left col-12 col-md-4" id="idxDistrictAttorney" name="idxDistrictAttorney">
            <option value=""></option>
            %5$s
          </select>
        </div>
      </div>
      
      <div class="enquete-main-info row col-12">
        <ul class="nav nav-pills nav-fill col-12">
            <li class="nav-item">
                <a class="nav-link bg-primary" aria-current="page" href="#" data-tab="#resumeFaitsTab">Résumé des faits</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#descSceneDeCrimeTab">Scène de crime</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#pistesDemarchesTab">Pistes / Démarches</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#personnalitesTab">Enquêtes de Personnalités</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#temoignagesTab">Témoins / Suspects</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#chronologieTab">Chronologie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-tab="#notesDiversesTab">Notes diverses</a>
            </li>
        </ul>
      </div>
      
      <div class="form-group">
        <div id="resumeFaitsTab" class="note-editor note-frame card">
          <div class="note-editing-area">
            <textarea id="resumeFaits" class="form-control" style="height: 300px; display: none;" name="resumeFaits"></textarea>
            <div class="note-editable card-block" data-input="#resumeFaits" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
                %6$s
            </div>
          </div>
        </div>
        <div id="descSceneDeCrimeTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="descSceneDeCrime" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
                %7$s
            </div>
          </div>
            <div class="col-12 input-group mb-3">
                <div class="col-6">
                  <label for="rapportFid" class="form-label">Rapport FID</label>
                  %8$s
                </div>
                <div class="col-6">
                  <label for="autopsie" class="form-label">Autopsie</label>
                  %9$s
                </div>
            </div>
        </div>
        <div id="pistesDemarchesTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="pistesDemarches" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
                %10$s
            </div>
          </div>
        </div>
        <div id="personnalitesTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="personnalites" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
            </div>
          </div>
        </div>
        <div id="temoignagesTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="temoignages" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
            </div>
          </div>
        </div>
        <div id="chronologieTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="chronologie" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
            </div>
          </div>
        </div>
        <div id="notesDiversesTab" class="note-editor note-frame card" style="display: none;">
          <div class="note-editing-area">
            <textarea id="notesDiverses" class="form-control" style="height: 300px; display: none;" name="compose-textarea"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true">
                %11$s
            </div>
          </div>
        </div>
      </div>

        <input type="hidden" id="writeAction" name="writeAction" value="send"/>
        <input type="hidden" id="id" name="id" value="%1$s"/>
    </form>
  </div>

  <div class="card-footer">
    <div class="float-right">
      <button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
    </div>
    <button type="reset" class="btn btn-default" data-action="cancel"><a href="%2$s" class="text-white"><i class="fa-solid fa-times"></i> Annuler</a></button>
  </div>

</div>
