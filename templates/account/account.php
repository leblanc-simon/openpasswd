
            <div id="manage-accounts" class="page hide" data-object="account">
                <div class="row">
                    <div class="col-md-12">
                        
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var url_account_list      = '<?php echo $this->app['url_generator']->generate('account') ?>';
                var url_account_get       = '<?php echo $this->app['url_generator']->generate('account_get', array('slug' => '--slug--')) ?>';
                var url_account_add       = '<?php echo $this->app['url_generator']->generate('account_add') ?>';
                var url_account_update    = '<?php echo $this->app['url_generator']->generate('account_update', array('slug' => '--slug--')) ?>';
                var url_account_show      = '<?php echo $this->app['url_generator']->generate('account_show', array('slug' => '--slug--')) ?>';
            </script>
            <script type="text/html" id="tpl-manage-accounts-edit"><?php include __DIR__.'/form.php' ?></script>
            <script type="text/html" id="tpl-manage-accounts-list"><?php include __DIR__.'/select.php' ?></script>
            <script type="text/html" id="tpl-manage-accounts-line"><?php include __DIR__.'/option.php' ?></script>
            <script type="text/html" id="tpl-manage-accounts-input"><?php include __DIR__.'/inputs_input.php' ?></script>
            <script type="text/html" id="tpl-manage-accounts-textarea"><?php include __DIR__.'/inputs_textarea.php' ?></script>
            <script type="text/html" id="tpl-manage-accounts-show"><?php include __DIR__.'/show.php' ?></script>
