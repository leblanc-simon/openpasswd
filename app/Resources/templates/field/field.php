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
            <div id="manage-fields" class="page hide" data-object="field">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var url_field_list      = '<?php echo $this->url('field') ?>';
                var url_field_get       = '<?php echo $this->url('field_get', array('slug' => '--slug--')) ?>';
                var url_field_add       = '<?php echo $this->url('field_add') ?>';
                var url_field_update    = '<?php echo $this->url('field_update', array('slug' => '--slug--')) ?>';
            </script>
            <script type="text/html" id="tpl-manage-fields-edit"><?php include __DIR__.'/form.php' ?></script>
            <script type="text/html" id="tpl-manage-fields-list"><?php include __DIR__.'/table.php' ?></script>
            <script type="text/html" id="tpl-manage-fields-line"><?php include __DIR__.'/line.php' ?></script>
