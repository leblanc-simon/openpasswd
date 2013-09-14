
            <div id="manage-account-types" class="page hide" data-object="account_type">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var url_account_type_list      = '<?php echo $this->app['url_generator']->generate('accounttype') ?>';
                var url_account_type_get       = '<?php echo $this->app['url_generator']->generate('accounttype_get', array('slug' => '--slug--')) ?>';
                var url_account_type_add       = '<?php echo $this->app['url_generator']->generate('accounttype_add') ?>';
                var url_account_type_update    = '<?php echo $this->app['url_generator']->generate('accounttype_update', array('slug' => '--slug--')) ?>';
            </script>
            <script type="text/html" id="tpl-manage-account-types-edit"><?php include __DIR__.'/form.php' ?></script>
            <script type="text/html" id="tpl-manage-account-types-list"><?php include __DIR__.'/table.php' ?></script>
            <script type="text/html" id="tpl-manage-account-types-line"><?php include __DIR__.'/line.php' ?></script>
