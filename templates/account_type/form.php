
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <input type="hidden" name="fields" data-value="fields" />
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="account-types-add-name">Nom</label>
                            <input type="text" class="form-control" name="name" id="account-types-add-name" placeholder="Entrez le nom du type de compte" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="account-types-add-description">Description</label>
                            <textarea class="form-control" name="description" id="account-types-add-description" placeholder="Entrez la description du type de compte (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="margin-bottom">
                        <legend data-content="legend-field"></legend>
                        <div class="row">
                            <div class="enable-fields col-xs-6">
                                <h4>Actifs</h4>
                                <ul id="enable-fields" data-content="enable-fields" class="fields-dnd"></ul>
                            </div>
                            <div class="available-fields col-xs-6">
                                <h4>Disponibles</h4>
                                <ul id="available-fields" data-content="available-fields" class="fields-dnd"></ul>
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