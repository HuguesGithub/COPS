<form id="writeForm" method="post">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Documenter une entrée</h3>
        </div>

        <div class="card-body p-2">
            <div class="input-group mb-3">
                   <label class="col-form-label-sm form-control col-3" for="nomIdx">Nom de l'entrée</label>
                <input type="text" class="form-control col-3" id="nomIdx" name="nomIdx" value="%3$s" required/>
                <label class="col-form-label-sm form-control col-3" for="natureId">Nature</label>
                <select class="form-control col-3" id="natureId" name="natureId">
                    <option></option>%4$s
                </select>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="descriptionPJ" rows="3" name="descriptionPJ">%5$s</textarea>
                <label for="descriptionPJ" class="col-form-label col-form-label-sm">Description</label>
            </div>
            <div class="form-floating mb-3%8$s">
                <textarea class="form-control" id="descriptionMJ" rows="3" name="descriptionMJ">%6$s</textarea>
                <label for="descriptionMJ" class="col-form-label col-form-label-sm">Description MJ</label>
            </div>
            <div class="input-group mb-3%8$s">
                   <label class="col-form-label-sm form-control col-3" for="reference">Référence</label>
                <input type="text" class="form-control col-9" id="reference" name="reference" value="%7$s"/>
            </div>
            <div class="%8$s">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="code" id="code2" value="2"%9$s>
  					<label class="form-check-label" for="code2">Code Rouge</label>
				</div>
				<div class="form-check form-check-inline">
  					<input class="form-check-input" type="radio" name="code" id="code1" value="1"%10$s>
  					<label class="form-check-label" for="code1">Code Bleu</label>
				</div>
				<div class="form-check form-check-inline">
  					<input class="form-check-input" type="radio" name="code" id="code0" value="0"%11$s>
  					<label class="form-check-label" for="code0">Standard</label>
				</div>
				<div class="form-check form-check-inline">
  					<input class="form-check-input" type="radio" name="code" id="codeHS" value="-1"%12$s>
  					<label class="form-check-label" for="codeHS">Hors Storyline</label>
				</div>
			</div>
        </div>

        <div class="card-footer">
            <div class="float-right">
                <button type="submit" class="btn btn-primary" data-action="send"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
            </div>
            <button type="reset" class="btn btn-default" data-action="cancel"><a href="%2$s" class="text-white"><i class="fa-solid fa-times"></i> Annuler</a></button>
            <input type="hidden" id="writeAction" name="writeAction" value="true"/>
            <input type="hidden" id="id" name="id" value="%1$s"/>
        </div>
    </div>
</form>
