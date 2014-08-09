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
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="fields-add-name"><?php echo $this->l10n('field.name') ?></label>
                            <input type="text" class="form-control" name="name" id="fields-add-name" value="" placeholder="Entrez le nom du champs" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="fields-add-description"><?php echo $this->l10n('field.description') ?></label>
                            <textarea class="form-control" name="description" id="fields-add-description" placeholder="Entrez la description du champs (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" name="crypt" value="1" data-checked="crypt" />
                                <?php echo $this->l10n('field.crypt') ?>
                            </label>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" name="required" value="1" data-checked="required" />
                                <?php echo $this->l10n('field.required') ?>
                            </label>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->l10n('field.type') ?></label>
                            <?php foreach ($this->getFormTypes()->getAllNames() as $form_type_name) { ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="type" value="<?php echo $form_type_name ?>" data-checked="type_<?php echo $form_type_name ?>" />
                                    <?php echo $this->l10n('field.'.$form_type_name) ?>
                                </label>
                            </div>
                            <?php } ?>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            <?php echo $this->l10n('save') ?>
                        </button>
                        <button type="reset" class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?php echo $this->l10n('cancel') ?>
                        </button>
                    </fieldset>
                </form>
