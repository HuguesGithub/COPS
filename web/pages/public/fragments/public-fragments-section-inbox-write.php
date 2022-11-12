<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Rédiger un message</h3>
  </div>

  <div class="card-body p-0">
    <form id="writeForm" method="post">
      <div class="mailbox-read-info">
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">De :</span>
          <select class="input-group-text form-select text-left col-12 col-md-10" id="mailFrom" name="mailFrom"%1$s>
            <option value=""></option>
            %2$s
          </select>
        </div>
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">À :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-10" name="mailTo" value="%3$s"/>
        </div>
        <div class="col-12 input-group mb-3">
          <span class="input-group-text col-12 col-md-2">Sujet :</span>
          <input type="text" class="input-group-text text-left col-12 col-md-10" name="mailSubject" value="%4$s"/>
        </div>
      </div>

      <div class="form-group">
        <textarea id="compose-textarea" class="form-control" style="height: 300px; display: none;" name="compose-textarea">%5$s</textarea>
        <div class="note-editor note-frame card">
          <div class="note-toolbar card-header" role="toolbar">
            <div class="btn-group note-view float-right">
              <!--
              <button type="button" class="note-btn btn btn-light btn-sm btn-fullscreen note-codeview-keep" tabindex="-1" title="" aria-label="Full Screen" data-original-title="Full Screen"><i class="note-icon-arrows-alt"></i></button>
              <button type="button" class="note-btn btn btn-light btn-sm btn-codeview note-codeview-keep" tabindex="-1" title="" aria-label="Code View" data-original-title="Code View"><i class="note-icon-code"></i></button>
              -->
              <button type="button" class="note-btn btn btn-dark btn-sm" tabindex="-1" title="" aria-label="Help" data-original-title="Help"><i class="fa-solid fa-circle-question"></i></button>
            </div>
          </div>
          <div class="note-editing-area">
            <textarea class="note-codable" aria-multiline="true" style="height: 973px;" style="display: none;" id="mailContent" name="mailContent"></textarea>
            <div class="note-editable card-block" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" autocorrect="true" id="noteEditable">
              %6$s
            </div>
          </div>
        </div>
      </div>
      <!--
      <div class="form-group">
        <div class="btn btn-default btn-file">
          <i class="fa-solid fa-paperclip"></i> Attachment <input type="file" name="attachment">
        </div>
        <p class="help-block">Max. 32MB</p>
      </div>
      -->
        <input type="hidden" id="writeAction" name="writeAction" value="send"/>
        <input type="hidden" id="id" name="id" value="%7$s"/>
    </form>
  </div>

  <div class="card-footer">
    <div class="float-right">
      <button type="button" class="btn btn-default" data-action="draft"><i class="fa-solid fa-file-lines"></i> Brouillon</button>
      <button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
    </div>
    <button type="reset" class="btn btn-default" data-action="cancel"><a href="/admin?onglet=inbox" class="text-white"><i class="fa-solid fa-times"></i> Annuler</a></button>
  </div>

</div>
