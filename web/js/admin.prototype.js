var simple_admin_prototype = {
    reset: function() {
        this.container.html('');
    },

    wait: function() {
        wait();
    },

    unWait: function() {
        unWait();
    },

    list: function() {
        this.wait();
        this.reset();
        that = this;

        $.ajax({
            url: this.url_list,
            type: 'GET',
            dataType: 'json',
            success: function(data, text_status, jq_xhr) {
                that.container.loadTemplate(
                    that.tpl_admin_list,
                    {},
                    {
                        success: function() {
                            that.container.find('tbody').loadTemplate(
                                that.tpl_admin_line,
                                data,
                                {
                                    success: that.top_container.removeClass('hide'),
                                    error: function() { alert('error'); },
                                    complete: that.unWait()
                                }
                            );
                        },
                        error: function() { alert('error'); },
                        complete: that.unWait(),
                        beforeInsert: function() {
                            if (typeof that.beforeInsertList != "undefined") {
                                that.beforeInsertList();
                            }
                        },
                        afterInsert: function() {
                            if (typeof that.afterInsertList != "undefined") {
                                that.afterInsertList();
                            }
                        }
                    }
                );
            },
            error: function(jq_xhr, text_status, error_thrown) {
                showError(jq_xhr.responseText);
                that.unWait()
            }
        });
    },

    addForm: function() {
        this.wait();
        this.reset();
        that = this;

        this.container.loadTemplate(
            this.tpl_admin_edit,
            this.data_add,
            {
                success: function() {
                    that.top_container.removeClass('hide');
                    onSubmitForm(that.container.find('form'), function(data, text_status, jq_xhr) {
                        that.updateForm(data.object.slug);
                    });
                },
                error: function() { alert('error'); },
                complete: this.unWait(),
                beforeInsert: function() {
                    if (typeof that.beforeInsertAdd != "undefined") {
                        that.beforeInsertAdd();
                    }
                },
                afterInsert: function() {
                    if (typeof that.afterInsertAdd != "undefined") {
                        that.afterInsertAdd();
                    }
                }
            }
        );
    },

    updateForm: function(slug) {
        this.wait();
        this.reset();
        that = this;

        $.ajax({
            url: this.url_get.replace(/--slug--/, slug),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                that.container.loadTemplate(
                    that.tpl_admin_edit,
                    that.data_update(slug, data),
                    {
                        success: function() {
                            that.top_container.removeClass('hide')
                            onSubmitForm(that.container.find('form'));
                        },
                        error: function() { alert('error'); },
                        complete: that.unWait(),
                        beforeInsert: function() {
                            if (typeof that.beforeInsertUpdate != "undefined") {
                                that.beforeInsertUpdate();
                            }
                        },
                        afterInsert: function() {
                            if (typeof that.afterInsertUpdate != "undefined") {
                                that.afterInsertUpdate();
                            }
                        }
                    }
                );
            },
            error: function(jq_xhr, text_status, error_thrown) {
                showError(jq_xhr.responseText);
                that.unWait()
            }
        });
    }
};