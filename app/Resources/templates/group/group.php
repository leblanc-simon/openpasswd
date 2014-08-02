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
            <div id="manage-groups" class="page hide" data-object="group">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var url_group_list      = '<?php echo $this->url('group') ?>';
                var url_group_get       = '<?php echo $this->url('group_get', array('slug' => '--slug--')) ?>';
                var url_group_add       = '<?php echo $this->url('group_add') ?>';
                var url_group_update    = '<?php echo $this->url('group_update', array('slug' => '--slug--')) ?>';
            </script>
            <script type="text/html" id="tpl-manage-groups-edit"><?php include __DIR__.'/form.php' ?></script>
            <script type="text/html" id="tpl-manage-groups-list"><?php include __DIR__.'/table.php' ?></script>
            <script type="text/html" id="tpl-manage-groups-line"><?php include __DIR__.'/line.php' ?></script>
