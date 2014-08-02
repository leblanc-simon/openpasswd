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
            <div id="manage-users" class="page hide" data-object="user">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var url_user_list      = '<?php echo $this->url('user') ?>';
                var url_user_get       = '<?php echo $this->url('user_get', array('slug' => '--slug--')) ?>';
                var url_user_add       = '<?php echo $this->url('user_add') ?>';
                var url_user_update    = '<?php echo $this->url('user_update', array('slug' => '--slug--')) ?>';
            </script>
            <script type="text/html" id="tpl-manage-users-edit"><?php include __DIR__.'/form.php' ?></script>
            <script type="text/html" id="tpl-manage-users-list"><?php include __DIR__.'/table.php' ?></script>
            <script type="text/html" id="tpl-manage-users-line"><?php include __DIR__.'/line.php' ?></script>
