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
        <header>
            <div class="navbar navbar-inverse" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Afficher/Cacher la navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $this->l10n('name') ?></a>
                </div>
                <?php if ($must_be_login === false) { ?>
                <div class="navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#accounts"><?php echo $this->l10n('account.accounts') ?></a></li>
                        <li><a href="#users"><?php echo $this->l10n('user.users') ?></a></li>
                        <li><a href="#groups"><?php echo $this->l10n('group.groups') ?></a></li>
                        <li><a href="#account-types"><?php echo $this->l10n('account_type.account_types') ?></a></li>
                        <li><a href="#fields"><?php echo $this->l10n('field.fields') ?></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?php echo $this->url('logout') ?>"><?php echo $this->l10n('main.logout') ?></a></li>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </header>