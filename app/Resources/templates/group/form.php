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
                            <label for="groups-add-name"><?php echo $this->l10n('group.name') ?></label>
                            <input type="text" class="form-control" name="name" id="groups-add-name" placeholder="Entrez le nom du groupe" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="groups-add-description"><?php echo $this->l10n('group.description') ?></label>
                            <textarea class="form-control" name="description" id="groups-add-description" placeholder="Entrez la description du groupe (sera utilisée comme aide à la saisie)" data-content="description"></textarea>
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
