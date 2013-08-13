        <div class="row">
            <div class="col-4 col-offset-4">
                <form action="<?php echo $this->app['url_generator']->generate('check_path') ?>" method="post">
                    <fieldset>
                        <legend>Connexion à OpenPasswd</legend>
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur</label>
                            <input type="text" class="form-control" name="_username" id="username" placeholder="Entrez votre nom d'utilisateur">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="form-control" name="_password" id="password" placeholder="Entrez votre mot de passe">
                        </div>
                        <button type="submit" class="btn btn-success">Se connecter</button>
                        <button type="reset" class="btn btn-danger">Annuler</button>
                    </fieldset>
                </form>
            </div>
        </div>