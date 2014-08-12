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
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form action="<?php echo $this->url('login_check') ?>" method="post">
                    <fieldset>
                        <legend><?php echo $this->l10n('main.connect') ?></legend>
                        <div class="form-group">
                            <label for="username"><?php echo $this->l10n('main.username') ?></label>
                            <input type="text" class="form-control" name="_username" id="username" placeholder="<?php echo $this->l10n('main.username_placeholder') ?>" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password"><?php echo $this->l10n('main.password') ?></label>
                            <input type="password" class="form-control" name="_password" id="password" placeholder="<?php echo $this->l10n('main.password_placeholder') ?>">
                        </div>
                        <button type="submit" class="btn btn-success"><?php echo $this->l10n('main.login') ?></button>
                        <button type="reset" class="btn btn-danger"><?php echo $this->l10n('main.cancel') ?></button>
                    </fieldset>
                </form>
            </div>
        </div>