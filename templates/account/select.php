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
                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <button class="btn btn-primary create">
                            <span class="glyphicon glyphicon-plus"></span>
                            <?php echo $this->l10n('account.add') ?>
                        </button>
                    </div>
                </div>

                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <form action="<?php echo $this->url('account') ?>" method="post" class="form-inline" role="form">
                            <fieldset>
                                <legend data-content="legend-type"><?php echo $this->l10n('account.search') ?></legend>
                                <div class="form-group col-xs-11">
                                    <input type="text" name="search-txt" class="form-control" placeholder="<?php echo $this->l10n('account.search') ?>" />
                                </div>
                                <button type="submit" class="btn btn-default col-xs-1">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </div>


                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <form action="<?php echo $this->url('account_show', array('slug' => '--slug--')) ?>" method="get" role="form">
                            <fieldset>
                                <legend data-content="legend-type"><?php echo $this->l10n('account.select') ?></legend>
                                <div class="form-group col-xs-12">
                                    <select name="search" class="form-control"></select>
                                </div>
                            </fieldset>
                        </form>
                    </div>

                    <div class="col-md-12" id="account-details">
                    </div>
                </div>