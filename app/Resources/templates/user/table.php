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
                <div class="title"><?php echo $this->l10n('user.list') ?></div>

                <div class="row margin-bottom">
                    <div class="col-md-12">
                        <button class="btn btn-primary new">
                            <span class="glyphicon glyphicon-plus"></span>
                            <?php echo $this->l10n('user.add') ?>
                        </button>
                    </div>
                </div>

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->l10n('user.name') ?></th>
                            <th><?php echo $this->l10n('user.login') ?></th>
                            <th><?php echo $this->l10n('edit') ?></th>
                        </tr>
                    <thead>
                    <tbody>
                    </tbody>
                </table>
            