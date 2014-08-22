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
                    <fieldset id="account-select-type">
                        <legend data-content="legend"><?php echo $this->l10n('account.select_type') ?></legend>
                        <div class="form-group">
                            <label for="account-type"><?php echo $this->l10n('account.type') ?></label>
                            <select name="account-type" class="form-control" data-content="account-types"></select>
                        </div>
                    </fieldset>

                    <fieldset class="hide " id="account-main-form">
                        <legend data-content="legend"></legend>
                        <div class="form-group">
                            <label for="account_name"><?php echo $this->l10n('account.name') ?></label>
                            <input type="text" name="name" id="account_name" class="form-control" required="required" data-content="account-name" />
                        </div>
                        <div class="main-form"></div>
                        <div class="form-group">
                            <label for="account_description"><?php echo $this->l10n('account.description') ?></label>
                            <textarea name="description" id="account_description" class="form-control" data-content="account-description">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <legend data-content="legend"><?php echo $this->l10n('account.groups') ?></legend>
                            <div class="form-group-checkbox">
                            </div>
                        </div>
                    </fieldset>


                    <fieldset>
                        <button type="submit" class="btn btn-success hide">
                            <span class="glyphicon glyphicon-ok"></span>
                            <?php echo $this->l10n('save') ?>
                        </button>
                        <button type="reset" class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span>
                            <?php echo $this->l10n('cancel') ?>
                        </button>
                    </fieldset>
                </form>