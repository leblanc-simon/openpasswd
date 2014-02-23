
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="fields-add-name">Nom</label>
                            <input type="text" class="form-control" name="name" id="fields-add-name" value="" placeholder="Entrez le nom du champs" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="fields-add-description">Description</label>
                            <textarea class="form-control" name="description" id="fields-add-description" placeholder="Entrez la description du champs (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" name="crypt" value="1" data-checked="crypt" />
                                Chiffrer le champ en base de données
                            </label>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" name="required" value="1" data-checked="required" />
                                Requis
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Type de champs</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="text" data-checked="type_text" />
                                    Texte
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="textarea" data-checked="type_textarea" />
                                    Texte long
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="date" data-checked="type_date" />
                                    Date
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="numeric" data-checked="type_numeric" />
                                    Nombre
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="email" data-checked="type_email" />
                                    Adresse e-mail
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="url" data-checked="type_url" />
                                    URL
                                </label>
                            </div>
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
