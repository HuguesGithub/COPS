<!-- @version v1.23.08.05 -->
<section class="row tchatSection px-0">
    <div id="tchatDialog" class="col-10 tchatDialog">%1$s</div>
    <div id="tchatContact" class="col-2 tchatContact">%2$s</div>
    <div class="col-12 tchatInput">
        <div class="input-group">
            <input type="text" id="tchatInput" class="form-control ajaxAction" data-trigger="enter" placeholder="Saisie Tchat" aria-label="Saisie Tchat" aria-describedby="Saisie Tchat">
            <button type="button" id="tchatInputSend" class="btn btn-outline-secondary ajaxAction" data-ajax="tchat" data-trigger="click" data-target="#tchatInput"/><i class="fa-solid fa-paper-plane"></i></button>
            <button type="button" id="tchatRefresh" class="btn btn-outline-secondary ajaxAction" data-ajax="refresh" data-trigger="click" data-target="#tchatDialog"/><i class="fa-solid fa-rotate"></i></button>
        </div>
    </div>
</section>
