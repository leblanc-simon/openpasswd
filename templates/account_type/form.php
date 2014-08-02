<?php
/**
 * This file is part of the OpenPasswd package.
 *
 * (c) Simon Leblanc <contact@leblanc-simon.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
                <form data-template-bind='[{"attribute": "action", "value": "action"}]' method="post">
                    <input type="hidden" name="fields" data-value="fields" />
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="account-types-add-name"><?php echo $this->l10n('account_type.name') ?></label>
                            <input type="text" class="form-control" name="name" id="account-types-add-name" placeholder="Entrez le nom du type de compte" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="account-types-add-description"><?php echo $this->l10n('account_type.description') ?></label>
                            <textarea class="form-control" name="description" id="account-types-add-description" placeholder="Entrez la description du type de compte (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="margin-bottom">
                        <legend data-content="legend-field"></legend>
                        <div class="row">
                            <div class="enable-fields col-xs-6">
                                <h4><?php echo $this->l10n('account_type.field_actives') ?></h4>
                                <ul id="enable-fields" data-content="enable-fields" class="fields-dnd"></ul>
                            </div>
                            <div class="available-fields col-xs-6">
                                <h4><?php echo $this->l10n('account_type.field_availables') ?></h4>
                                <ul id="available-fields" data-content="available-fields" class="fields-dnd"></ul>
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok"></span>
                        <?php echo $this->l10n('save') ?>
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <span class="glyphicon glyphicon-remove"></span>
                        <?php echo $this->l10n('cancel') ?>
                    </button>
                </form>