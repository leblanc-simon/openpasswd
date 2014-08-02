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
                    <input type="hidden" name="groups" data-value="groups" />
                    <fieldset>
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="users-add-name"><?php echo $this->l10n('user.name') ?></label>
                            <input type="text" class="form-control" name="name" id="users-add-name" placeholder="Entrez le nom de l'utilisateur" data-value="name">
                        </div>
                        <div class="form-group">
                            <label for="users-add-username"><?php echo $this->l10n('user.login') ?></label>
                            <input type="text" class="form-control" name="username" id="users-add-username" placeholder="Entrez l'identifiant de l'utilsateur" data-value="username">
                        </div>
                        <div class="form-group">
                            <label for="users-add-password"><?php echo $this->l10n('user.password') ?></label>
                            <input type="password" class="form-control" name="password" id="users-add-password" placeholder="Entrez le mot de passe de l'utilsateur">
                        </div>
                    </fieldset>
                    <fieldset class="margin-bottom">
                        <legend data-content="legend-group"></legend>
                        <div class="row">
                            <div class="enable-groups col-xs-6">
                                <h4><?php echo $this->l10n('user.group_actives') ?></h4>
                                <ul id="enable-groups" data-content="enable-groups" class="groups-dnd"></ul>
                            </div>
                            <div class="available-groups col-xs-6">
                                <h4><?php echo $this->l10n('user.group_availables') ?></h4>
                                <ul id="available-groups" data-content="available-groups" class="groups-dnd"></ul>
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