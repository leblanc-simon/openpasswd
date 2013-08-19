
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="fields-add-name">Nom</label>
                            <input type="text" class="form-control" name="name" id="fields-add-name" placeholder="Entrez le nom du groupe" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="fields-add-description">Description</label>
                            <textarea class="form-control" name="description" id="fields-add-description" placeholder="Entrez la description du groupe (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            Enregistrer
                        </button>
                        <button type="reset" class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            Annuler
                        </button>
                    </fieldset>
                </form>
