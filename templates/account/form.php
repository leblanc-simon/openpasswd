
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <fieldset id="account-select-type">
                        <legend data-content="legend">Sélectionner le type de compte à ajouter</legend>
                        <div class="form-group">
                            <label for="account-type">Type de compte</label>
                            <select name="account-type" class="form-control" data-content="account-types"></select>
                        </div>
                    </fieldset>

                    <fieldset class="hide " id="account-main-form">
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="account_name">Nom du compte</label>
                            <input type="text" name="name" id="account_name" class="form-control" required="required" data-content="account-name" />
                        </div>
                        <div class="main-form"></div>
                        <div class="form-group">
                            <label for="account_description">Commentaires</label>
                            <textarea name="description" id="account_description" class="form-control" data-content="account-description">
                            </textarea>
                        </div>
                    </fieldset>

                    <fieldset>
                        <button type="submit" class="btn btn-success hide">
                            <span class="glyphicon glyphicon-ok"></span>
                            Enregistrer
                        </button>
                        <button type="reset" class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            Annuler
                        </button>
                    </fieldset>
                </form>