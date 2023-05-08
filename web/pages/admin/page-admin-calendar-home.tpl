<!-- @version v1.23.05.07 -->
            <div class="card col mx-1 p-0">
                <div class="card-header">Date Ingame</div>
                <!-- Card-body start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form method="post">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group row mb-3">
                                        <select class="form-control col-4" name="sel-d">%1$s</select>
                                        <select class="form-control col-4" name="sel-m">%2$s</select>
                                        <select class="form-control col-4" name="sel-y">%3$s</select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group row mb-3">
                                        <select class="form-control col-4" name="sel-h">%4$s</select>
                                        <select class="form-control col-4" name="sel-i">%5$s</select>
                                        <select class="form-control col-4" name="sel-s">%6$s</select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input class="btn btn-primary col-4 offset-4" type="submit" id="changeDate" name="changeDate" value="Valider">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Card-body end -->
            </div>
            <div class="card col mx-1 p-0">
                <div class="card-header">Ajustements</div>
                <!-- Card-body start -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text col-4">Secondes</span>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=s&quantite=3">+3</a></button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text col-4">Minutes</span>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=1">+1</a></button>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=5">+5</a></button>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=15">+15</a></button>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=m&quantite=30">+30</a></button>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text col-4">Heures</span>
                                <button class="btn btn-outline-secondary col-2" type="button"><a href="%7$s&action=add&unite=h&quantite=1">+1</a></button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                                <button class="btn btn-outline-secondary disabled col-2" type="button">&nbsp;</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card-body end -->
            </div>
