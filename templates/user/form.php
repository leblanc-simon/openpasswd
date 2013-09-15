
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <input type="hidden" name="groups" data-value="groups" />
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="users-add-name">Nom</label>
                            <input type="text" class="form-control" name="name" id="users-add-name" placeholder="Entrez le nom de l'utilisateur" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="users-add-username">Identifiant</label>
                            <input type="text" class="form-control" name="username" id="users-add-username" placeholder="Entrez l'identifiant de l'utilsateur" data-value="username">
                        </div>
                        <div class="form-group">
                            <label for="users-add-password">Mot de passe</label>
                            <input type="password" class="form-control" name="password" id="users-add-password" placeholder="Entrez le mot de passe de l'utilsateur">
                        </div>
                    </fieldset>
                    <fieldset class="margin-bottom">
                        <legend data-content="legend-group"></legend>
                        <div class="row">
                            <div class="enable-groups col-xs-6">
                                <h4>Actifs</h4>
                                <ul id="enable-groups" data-content="enable-groups" class="groups-dnd"></ul>
                            </div>
                            <div class="available-groups col-xs-6">
                                <h4>Disponibles</h4>
                                <ul id="available-groups" data-content="available-groups" class="groups-dnd"></ul>
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok"></span>
                        Enregistrer
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <span class="glyphicon glyphicon-remove"></span>
                        Annuler
                    </button>
                </form>