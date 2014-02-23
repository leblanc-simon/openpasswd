
                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <button class="btn btn-primary create">
                            <span class="glyphicon glyphicon-plus"></span>
                            Ajouter un nouveau compte
                        </button>
                    </div>
                </div>

                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <form action="<?php echo $this->app['url_generator']->generate('account') ?>" method="post" class="form-inline" role="form">
                            <fieldset>
                                <legend data-content="legend-type">Rechercher</legend>
                                <div class="form-group col-xs-11">
                                    <input type="text" name="search-txt" class="form-control" placeholder="Rechercher" />
                                </div>
                                <button type="submit" class="btn btn-default col-xs-1">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>


                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <form action="<?php echo $this->app['url_generator']->generate('account') ?>" method="post" role="form">
                            <fieldset>
                                <legend data-content="legend-type">Sélectionner un compte</legend>
                                <div class="form-group col-xs-12">
                                    <select name="search" class="form-control"></select>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>