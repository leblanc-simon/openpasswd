
        <header>
            <div class="navbar navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Afficher/Cacher la navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">OpenPasswd</a>
                </div>
                <?php if ($must_be_login === false) { ?>
                <div class="navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Accueil</a></li>
                        <li><a href="#users">Utilisateurs</a></li>
                        <li><a href="#groups">Groupes</a></li>
                        <li><a href="#account-types">Types de compte</a></li>
                        <li><a href="#fields">Champs</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo $this->app['url_generator']->generate('logout') ?>">Déconnexion</a></li>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </header>